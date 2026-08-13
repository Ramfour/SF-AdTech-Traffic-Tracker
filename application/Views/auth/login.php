<?php $pageTitle = 'Вход — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-4 text-center">Вход в систему</h1>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= \App\Core\App::baseUrl() ?>/auth/login" novalidate>
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" id="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Войти</button>
        </form>
        <p class="mt-3 text-center small">
          Нет аккаунта? <a href="<?= \App\Core\App::baseUrl() ?>/auth/register">Зарегистрироваться</a>
        </p>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
