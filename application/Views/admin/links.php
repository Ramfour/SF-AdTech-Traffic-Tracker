<?php $pageTitle = 'Ссылки — SF-AdTech Admin'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<h1 class="h4 mb-3">Выданные ссылки</h1>

<div class="table-responsive">
  <table class="table table-sm">
    <thead class="table-light">
      <tr><th>ID</th><th>Веб-мастер</th><th>Оффер</th><th>Ссылка</th><th>Дата</th></tr>
    </thead>
    <tbody>
      <?php foreach ($links as $l): ?>
      <tr>
        <td><?= (int)$l['id'] ?></td>
        <td class="small"><?= htmlspecialchars($l['webmaster_email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="small"><?= htmlspecialchars($l['offer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><code class="small"><?= htmlspecialchars(\App\Core\App::baseUrl() . '/go/' . $l['track_link'], ENT_QUOTES, 'UTF-8') ?></code></td>
        <td class="small text-muted"><?= htmlspecialchars(substr($l['created_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
