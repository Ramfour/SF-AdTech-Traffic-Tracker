<?php $pageTitle = 'Статистика доходов — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/dashboard">Мои подписки</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/webmaster/offers">Все офферы</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/webmaster/stats">Статистика</a>
  </li>
</ul>

<h1 class="h4 mb-3">Статистика доходов</h1>

<form method="get" class="row g-2 mb-4">
  <div class="col-auto">
    <select name="period" class="form-select form-select-sm">
      <option value="day"   <?= $period === 'day'   ? 'selected' : '' ?>>По дням</option>
      <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>По месяцам</option>
      <option value="year"  <?= $period === 'year'  ? 'selected' : '' ?>>По годам</option>
    </select>
  </div>
  <div class="col-auto">
    <select name="offer_id" class="form-select form-select-sm">
      <option value="">Все офферы</option>
      <?php foreach ($subs as $s): ?>
        <option value="<?= (int)$s['offer_id'] ?>"
          <?= (int)$s['offer_id'] === (int)$offer_id ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['offer_name'], ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-auto">
    <button class="btn btn-sm btn-primary" type="submit">Применить</button>
  </div>
</form>

<?php if (empty($rows)): ?>
  <p class="text-muted">Нет данных за выбранный период.</p>
<?php else: ?>
<div class="table-responsive">
  <table class="table table-sm table-striped">
    <thead>
      <tr>
        <th>Период</th>
        <th class="text-end">Переходы</th>
        <th class="text-end">Доход (₽)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['period'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="text-end"><?= (int)$r['clicks'] ?></td>
        <td class="text-end"><?= number_format((float)$r['earnings'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
