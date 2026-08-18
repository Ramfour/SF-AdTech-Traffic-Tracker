<?php $pageTitle = 'Офферы — SF-AdTech Admin'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<h1 class="h4 mb-3">Все офферы</h1>

<div class="table-responsive">
  <table class="table table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th><th>Название</th><th>Рекламодатель</th>
        <th>Ставка</th><th>Подписчики</th><th>Статус</th><th>Создан</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($offers as $o): ?>
      <tr>
        <td><?= (int)$o['id'] ?></td>
        <td><?= htmlspecialchars($o['name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="text-muted small"><?= htmlspecialchars($o['advertiser_email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= number_format((float)$o['cost_per_click'], 2) ?> ₽</td>
        <td><?= (int)$o['subscribers'] ?></td>
        <td>
          <?php if ($o['status'] === 'active'): ?>
            <span class="badge bg-success">Активен</span>
          <?php else: ?>
            <span class="badge bg-secondary">Неактивен</span>
          <?php endif; ?>
        </td>
        <td class="text-muted small"><?= htmlspecialchars(substr($o['created_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
