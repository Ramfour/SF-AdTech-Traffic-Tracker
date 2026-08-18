<?php $pageTitle = 'Переходы — SF-AdTech Admin'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<h1 class="h4 mb-3">Переходы <span class="text-muted small">(последние 200)</span></h1>

<div class="table-responsive">
  <table class="table table-sm">
    <thead class="table-light">
      <tr>
        <th>Дата</th><th>Оффер</th><th>Веб-мастер</th><th>Рекламодатель</th>
        <th>Ставка</th><th>Отказ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($clicks as $c): ?>
      <tr class="<?= $c['refused'] ? 'table-danger' : '' ?>">
        <td class="small text-muted"><?= htmlspecialchars(substr($c['clicked_at'], 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
        <td class="small"><?= htmlspecialchars($c['offer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="small"><?= htmlspecialchars($c['webmaster_email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="small"><?= htmlspecialchars($c['advertiser_email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= number_format((float)$c['cost_per_click'], 2) ?> ₽</td>
        <td><?= $c['refused'] ? '<span class="badge bg-danger">Да</span>' : '' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
