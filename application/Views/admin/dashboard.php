<?php $pageTitle = 'Дашборд администратора — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<h1 class="h4 mb-4">Статистика системы</h1>

<div class="row g-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fs-2 fw-bold"><?= (int)$stats['total_clicks'] ?></div>
        <div class="text-muted small">Всего кликов</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fs-2 fw-bold text-success"><?= (int)$stats['valid_clicks'] ?></div>
        <div class="text-muted small">Валидных переходов</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fs-2 fw-bold text-danger"><?= (int)$stats['refused_clicks'] ?></div>
        <div class="text-muted small">Отказов</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fs-2 fw-bold text-primary"><?= number_format((float)$stats['system_income'], 2) ?> ₽</div>
        <div class="text-muted small">Доход системы</div>
      </div>
    </div>
  </div>
</div>

<div class="mt-4">
  <p class="text-muted">Общий оборот рекламодателей: <strong><?= number_format((float)$stats['total_revenue'], 2) ?> ₽</strong></p>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
