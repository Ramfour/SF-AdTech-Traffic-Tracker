# TROUBLESHOOTING — SF-AdTech Traffic Tracker

Проблемы, с которыми столкнулись при разработке и деплое проекта, и их решения.

---

## 1. Страница логина: «Неверный email или пароль» сразу после деплоя

**Симптом.** После `psql -f schema.sql` и попытки войти под `admin@sfadtech.local` / `admin123` система выдаёт ошибку аутентификации, хотя пользователь в БД есть.

**Причина.** В `schema.sql` BCrypt-хэш вставлен как строковый литерал. При передаче через `psql -c "..."` в оболочке bash символ `$` в хэше интерпретируется как начало переменной окружения. В результате в поле `password_hash` записывается обрезанная строка, которую `password_verify()` не принимает.

**Пример обрезанного хэша в БД:**
```
y0/yeFss2Zy0SCaA0SQiiktzA1bxUzqQzChW   ← вместо полного $2y$10$...
```

**Решение.** Обновить хэш напрямую через интерактивный psql (внутри него bash-интерполяции нет):

```bash
sudo -u postgres psql -d sfadtech
```

```sql
UPDATE users
SET password_hash = '$2y$12$F9eFC/4z0PIt0Zk2XeQbL.dPeSrniE1ZDiwa82ZJD3WtxEvTBF.ee'
WHERE email = 'admin@sfadtech.local';
\q
```

Либо сгенерировать хэш на сервере и передать через переменную:

```bash
HASH=$(php -r "echo password_hash('admin123', PASSWORD_DEFAULT);")
sudo -u postgres psql -d sfadtech -c "UPDATE users SET password_hash = '$HASH' WHERE email = 'admin@sfadtech.local';"
```

---

## 2. `git pull` падает с «detected dubious ownership»

**Симптом.**
```
fatal: detected dubious ownership in repository at '/var/www/sfadtech'
```

**Причина.** Репозиторий склонирован под `www-data` или другим пользователем, а `git pull` выполняется от `root`. Git отказывается работать с чужим репозиторием по соображениям безопасности (CVE-2022-24765).

**Решение.**
```bash
git config --global --add safe.directory /var/www/sfadtech
sudo git -C /var/www/sfadtech pull origin main
```

---

## 3. `git pull` падает с «local changes would be overwritten»

**Симптом.**
```
error: Your local changes to the following files would be overwritten by merge
```

**Причина.** На сервере есть незакоммиченные изменения в отслеживаемых файлах — например, если кто-то редактировал `config.php` или view-файлы напрямую на сервере, а потом пришло обновление из репозитория.

**Решение А — сбросить изменения** (если они не нужны):
```bash
sudo git -C /var/www/sfadtech checkout -- <file1> <file2>
sudo git -C /var/www/sfadtech pull origin main
```

**Решение Б — отложить через stash** (если изменения нужно сохранить):
```bash
sudo git -C /var/www/sfadtech stash
sudo git -C /var/www/sfadtech pull origin main
sudo git -C /var/www/sfadtech stash pop
```

> `config.php` добавлен в `.gitignore`, поэтому конфиг сервера не конфликтует с репозиторием. Проблема возникает только если напрямую редактировали view-файлы или CSS на сервере.

---

## 4. Nginx отдаёт старый сайт вместо SF-AdTech

**Симптом.** По адресу сервера открывается другой проект (например Image Gallery), хотя nginx перезагружен.

**Причина.** В `/etc/nginx/sites-enabled/` активен другой конфиг с тем же `listen 80` и `server_name`. Nginx обрабатывает их в алфавитном порядке — файл `gallery.local` идёт раньше `sfadtech`.

**Диагностика:**
```bash
sudo nginx -T 2>&1 | grep -E "server_name|listen|root"
ls /etc/nginx/sites-enabled/
```

**Решение.** Отключить конфликтующий конфиг:
```bash
sudo rm /etc/nginx/sites-enabled/gallery.local   # или a2dissite если Apache
sudo nginx -t && sudo systemctl reload nginx
```

Либо заменить содержимое конфликтующего файла на конфиг SF-AdTech.

---

## 5. CSS не применяется — кнопка растянута на весь экран

**Симптом.** После деплоя страница логина выглядит без стилей: кнопка «Войти» растянута на весь экран, шрифты стандартные.

**Причина А — браузерный кеш.** Браузер отдаёт старую версию CSS.  
**Решение:** `Ctrl+Shift+R` или открыть в режиме инкогнито.

**Причина Б — `git pull` не выполнился.** Файлы на сервере старые.  
**Диагностика:**
```bash
head -2 /var/www/sfadtech/public/css/app.css
# Должна быть строка: /* SF-AdTech — Terminal/Hacker Dashboard Theme */
```

**Решение:** выполнить `git pull` (см. проблемы 2–3 выше).

**Причина В — nginx отдаёт CSS из другой директории.**  
**Диагностика:**
```bash
curl -I http://YOUR_SERVER_IP/css/app.css
# Content-Length должен быть ~14000+ байт
```

---

## 6. Apache слушает только 127.0.0.1:8080, порт 80 не работает

**Симптом.** Apache запущен, vhost создан, но `http://IP` не отвечает или отдаёт другой сервис.

**Причина.** В `/etc/apache2/ports.conf` прописан только `Listen 127.0.0.1:8080` — Apache вообще не слушает внешний порт 80.

**Диагностика:**
```bash
cat /etc/apache2/ports.conf
sudo apache2ctl -S 2>&1 | grep "port 80"
```

**Решение.** Если на сервере уже есть nginx — использовать nginx (он слушает 80). Apache на этом сервере настроен как бэкенд для nginx-проксирования на порту 8080. Создавать vhost нужно в nginx, а не в Apache.

---

## 7. PHP-FPM socket: fastcgi_pass на неверный порт

**Симптом.** Nginx возвращает `502 Bad Gateway` при обращении к `.php` файлам.

**Причина.** В nginx-конфиге указан `fastcgi_pass 127.0.0.1:9000`, а PHP-FPM слушает на другом порту (например 9003).

**Диагностика:**
```bash
sudo ss -tlnp | grep php
```

**Решение.** Исправить порт в nginx-конфиге под реальный из вывода `ss`.

---

## 8. HTTP 500 на `/webmaster/stats` и `/advertiser/stats`

**Симптом.**
```
PHP Fatal error: Uncaught TypeError: ClickModel::statsByWebmaster():
Argument #2 ($period) must be of type string, null given
```

**Причина.** Ошибка в тернарном операторе в обоих контроллерах:

```php
// Баг: правая ветка тернарника возвращает $_GET['period'] напрямую,
// который равен null когда параметр не передан в URL
$period = in_array($_GET['period'] ?? 'day', ['day', 'month', 'year'], true)
          ? $_GET['period'] : 'day';
```

`in_array` получает `'day'` (через `??`) и возвращает `true`, но затем тернарник берёт `$_GET['period']` — а он `null`.

**Решение.** Разделить на две строки:

```php
$periodRaw = $_GET['period'] ?? 'day';
$period    = in_array($periodRaw, ['day', 'month', 'year'], true) ? $periodRaw : 'day';
```

Исправлено в `WebmasterController.php` и `AdvertiserController.php`.

---

## 9. `git pull` падает повторно с «local changes» после каждого деплоя

**Симптом.** При каждом `git pull` на сервере возникает конфликт — даже если вы не редактировали файлы вручную.

**Причина.** Git на сервере видит файлы как изменённые из-за разницы окончаний строк (CRLF на Windows → LF на Linux). При клонировании или первом pull файлы записались с LF, но git считает их изменёнными относительно CRLF-версии из репозитория.

**Решение.** Сбросить конкретные файлы и подтянуть:

```bash
sudo git -C /var/www/sfadtech checkout -- <file1> <file2> ...
sudo git -C /var/www/sfadtech pull origin main
```

Либо сбросить все отслеживаемые файлы сразу:

```bash
sudo git -C /var/www/sfadtech checkout -- .
sudo git -C /var/www/sfadtech pull origin main
```

> `config.php` в `.gitignore` — он не будет затронут при `checkout -- .`.
