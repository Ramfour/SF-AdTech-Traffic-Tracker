<?php $pageTitle = 'Регистрация — SF-AdTech'; ?>
<?php require ROOT . '/application/Views/layouts/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-4 text-center">Регистрация</h1>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= \App\Core\App::baseUrl() ?>/auth/register" novalidate>
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Пароль <span class="text-muted small">(min 6 символов)</span></label>
            <input type="password" id="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="mb-4">
            <label class="form-label">Роль</label>
            <div class="d-flex gap-4">
              <div class="form-check">
                <input type="radio" id="r_adv" name="role" value="advertiser" class="form-check-input" required>
                <label for="r_adv" class="form-check-label">Рекламодатель</label>
              </div>
              <div class="form-check">
                <input type="radio" id="r_wm" name="role" value="webmaster" class="form-check-input">
                <label for="r_wm" class="form-check-label">Веб-мастер</label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100">Зарегистрироваться</button>
        </form>
        <p class="mt-3 text-center small">
          Уже есть аккаунт? <a href="<?= \App\Core\App::baseUrl() ?>/auth/login">Войти</a>
        </p>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/application/Views/layouts/footer.php'; ?>
