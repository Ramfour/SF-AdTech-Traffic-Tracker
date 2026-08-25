<?php $pageTitle = 'Дашборд администратора — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-4 sf-animate">
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<div style="font-size:.6rem;letter-spacing:.2em;text-transform:uppercase;color:var(--text-dim);margin-bottom:1.25rem;" class="sf-animate sf-animate-d1">
  ▸ system_stats <span style="color:var(--accent)">—</span> realtime overview
</div>

<div class="row g-3 sf-animate sf-animate-d2">
  <div class="col-sm-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-value"><?= (int)$stats['total_clicks'] ?></div>
      <div class="stat-label">Всего кликов</div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-value" style="color:var(--accent)"><?= (int)$stats['valid_clicks'] ?></div>
      <div class="stat-label">Валидных переходов</div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-value red"><?= (int)$stats['refused_clicks'] ?></div>
      <div class="stat-label">Отказов</div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-value blue"><?= number_format((float)$stats['system_income'], 2) ?> ₽</div>
      <div class="stat-label">Доход системы</div>
    </div>
  </div>
</div>

<div class="mt-4 sf-animate sf-animate-d3" style="border-left:2px solid var(--border);padding-left:1rem;">
  <span style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;color:var(--text-dim)">Оборот рекламодателей</span>
  <span style="font-family:var(--font-display);font-size:1.4rem;color:var(--yellow);margin-left:1rem;letter-spacing:.05em">
    <?= number_format((float)$stats['total_revenue'], 2) ?> ₽
  </span>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
