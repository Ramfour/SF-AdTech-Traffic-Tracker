<?php $pageTitle = '404 — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>
<div class="text-center py-5">
  <h1 class="display-1 text-muted">404</h1>
  <p class="lead">Страница не найдена</p>
  <a href="<?= \App\Core\App::baseUrl() ?>/" class="btn btn-primary">На главную</a>
</div>
<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
