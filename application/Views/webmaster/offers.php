<?php $pageTitle = 'Доступные офферы — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/dashboard">Мои подписки</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/webmaster/offers">Все офферы</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/stats">Статистика</a>
  </li>
</ul>

<h1 class="h4 mb-3">Доступные офферы</h1>

<?php if (empty($offers)): ?>
  <p class="text-muted">Активных офферов пока нет.</p>
<?php else: ?>
<div class="table-responsive">
  <table class="table table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>Оффер</th>
        <th>Рекламодатель</th>
        <th>Ставка (₽/клик)</th>
        <th>Темы</th>
        <th>Подписчиков</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($offers as $o): ?>
      <tr>
        <td><?= htmlspecialchars($o['name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="text-muted small"><?= htmlspecialchars($o['advertiser_email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= number_format((float)$o['cost_per_click'], 2) ?></td>
        <td class="text-muted small"><?= htmlspecialchars($o['topics'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int)$o['subscribers'] ?></td>
        <td>
          <?php if (in_array((int)$o['id'], array_map('intval', $myOfferIds))): ?>
            <span class="badge bg-success">Подписан</span>
          <?php else: ?>
            <form method="post"
                  action="<?= \App\Core\App::baseUrl() ?>/webmaster/offers/<?= (int)$o['id'] ?>/subscribe">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
              <button type="submit" class="btn btn-sm btn-primary">Подписаться</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
