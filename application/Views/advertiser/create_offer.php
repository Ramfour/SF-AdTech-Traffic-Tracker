<?php $pageTitle = 'Новый оффер — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <h1 class="h4 mb-4">Создать оффер</h1>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <form method="post" action="<?= \App\Core\App::baseUrl() ?>/advertiser/offers/create" novalidate>
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
          <div class="mb-3">
            <label for="name" class="form-label">Название оффера</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="cpc" class="form-label">Стоимость перехода (₽)</label>
            <input type="number" id="cpc" name="cost_per_click" class="form-control"
                   min="0.01" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="url" class="form-label">Целевой URL</label>
            <input type="url" id="url" name="target_url" class="form-control"
                   placeholder="https://example.com/product" required>
          </div>
          <div class="mb-4">
            <label for="topics" class="form-label">Темы сайта <span class="text-muted small">(через запятую)</span></label>
            <input type="text" id="topics" name="topics" class="form-control"
                   placeholder="новости, технологии, бизнес">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="<?= \App\Core\App::baseUrl() ?>/advertiser/dashboard" class="btn btn-outline-secondary">Отмена</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
