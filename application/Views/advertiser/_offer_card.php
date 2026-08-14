<div class="offer-card card mb-2 shadow-sm"
     draggable="true"
     data-id="<?= (int)$o['id'] ?>"
     data-status="<?= htmlspecialchars($o['status'], ENT_QUOTES, 'UTF-8') ?>">
  <div class="card-body py-2 px-3">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <strong><?= htmlspecialchars($o['name'], ENT_QUOTES, 'UTF-8') ?></strong>
        <div class="text-muted small mt-1">
          <?= number_format((float)$o['cost_per_click'], 2) ?> ₽/клик &bull;
          Подписчиков: <?= (int)$o['subscribers'] ?>
        </div>
        <div class="text-muted small text-truncate" style="max-width:220px">
          <?= htmlspecialchars($o['target_url'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php if ($o['topics']): ?>
          <div class="small text-info"><?= htmlspecialchars($o['topics'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
      </div>
      <div class="d-flex flex-column gap-1 ms-2">
        <?php if ($o['status'] === 'active'): ?>
        <form method="post"
              action="<?= \App\Core\App::baseUrl() ?>/advertiser/offers/<?= (int)$o['id'] ?>/deactivate">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
          <button class="btn btn-sm btn-outline-warning" type="submit">Откл.</button>
        </form>
        <?php else: ?>
        <form method="post"
              action="<?= \App\Core\App::baseUrl() ?>/advertiser/offers/<?= (int)$o['id'] ?>/activate">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
          <button class="btn btn-sm btn-outline-success" type="submit">Вкл.</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
