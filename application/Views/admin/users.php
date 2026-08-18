<?php $pageTitle = 'Пользователи — SF-AdTech Admin'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/dashboard">Обзор</a></li>
  <li class="nav-item"><a class="nav-link active" href="<?= \App\Core\App::baseUrl() ?>/admin/users">Пользователи</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/offers">Офферы</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/links">Ссылки</a></li>
  <li class="nav-item"><a class="nav-link" href="<?= \App\Core\App::baseUrl() ?>/admin/clicks">Переходы</a></li>
</ul>

<h1 class="h4 mb-3">Пользователи</h1>

<div class="table-responsive">
  <table class="table table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th><th>Email</th><th>Роль</th><th>Статус</th><th>Зарегистрирован</th><th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          <?php if ($u['is_active']): ?>
            <span class="badge bg-success">Активен</span>
          <?php else: ?>
            <span class="badge bg-danger">Заблокирован</span>
          <?php endif; ?>
        </td>
        <td class="text-muted small"><?= htmlspecialchars(substr($u['created_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          <?php if ($u['role'] !== 'admin'): ?>
          <form method="post"
                action="<?= \App\Core\App::baseUrl() ?>/admin/users/<?= (int)$u['id'] ?>/toggle">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="active" value="<?= $u['is_active'] ? '0' : '1' ?>">
            <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
              <?= $u['is_active'] ? 'Заблокировать' : 'Активировать' ?>
            </button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
