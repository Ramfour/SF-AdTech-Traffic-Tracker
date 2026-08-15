<?php $pageTitle = 'Дашборд веб-мастера — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/webmaster/dashboard">Мои подписки</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/offers">Все офферы</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/stats">Статистика</a>
  </li>
</ul>

<h1 class="h4 mb-3">Мои подписки</h1>

<?php if (empty($subs)): ?>
  <p class="text-muted">Вы ещё не подписаны ни на один оффер.
    <a href="<?= \App\Core\App::baseUrl() ?>/webmaster/offers">Посмотреть офферы</a>
  </p>
<?php else: ?>
<div class="table-responsive">
  <table class="table table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>Оффер</th>
        <th>Ставка (₽/клик)</th>
        <th>Темы</th>
        <th>Статус</th>
        <th>Ссылка трекинга</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($subs as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['offer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= number_format((float)$s['cost_per_click'], 2) ?></td>
        <td><span class="text-muted small"><?= htmlspecialchars($s['topics'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></td>
        <td>
          <?php if ($s['offer_status'] === 'active'): ?>
            <span class="badge bg-success">Активен</span>
          <?php else: ?>
            <span class="badge bg-secondary">Неактивен</span>
          <?php endif; ?>
        </td>
        <td>
          <code class="user-select-all small">
            <?= htmlspecialchars(\App\Core\App::baseUrl() . '/go/' . $s['track_link'], ENT_QUOTES, 'UTF-8') ?>
          </code>
        </td>
        <td>
          <form method="post"
                action="<?= \App\Core\App::baseUrl() ?>/webmaster/offers/<?= (int)$s['offer_id'] ?>/unsubscribe">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Отписаться от оффера?')">Отписаться</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
