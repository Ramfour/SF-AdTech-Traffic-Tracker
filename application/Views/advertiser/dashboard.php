<?php $pageTitle = 'Дашборд рекламодателя — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">Мои офферы</h1>
  <a href="<?= \App\Core\App::baseUrl() ?>/advertiser/offers/create" class="btn btn-primary">+ Новый оффер</a>
</div>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/advertiser/dashboard">Офферы</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/advertiser/stats">Статистика</a>
  </li>
</ul>

<!-- Drag-and-drop доски -->
<div class="row" id="kanban">
  <!-- Активные -->
  <div class="col-md-6">
    <h5 class="mb-2 text-success">Активные</h5>
    <div class="kanban-col border rounded p-2 bg-light min-vh-25"
         data-status="active"
         id="col-active">
      <?php foreach ($offers as $o): ?>
        <?php if ($o['status'] === 'active'): ?>
          <?php include __DIR__ . '/_offer_card.php'; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Неактивные -->
  <div class="col-md-6">
    <h5 class="mb-2 text-secondary">Неактивные</h5>
    <div class="kanban-col border rounded p-2 bg-white min-vh-25"
         data-status="inactive"
         id="col-inactive">
      <?php foreach ($offers as $o): ?>
        <?php if ($o['status'] === 'inactive'): ?>
          <?php include __DIR__ . '/_offer_card.php'; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
const BASE = <?= json_encode(\App\Core\App::baseUrl()) ?>;
const CSRF = <?= json_encode($csrf) ?>;
</script>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
