<?php $pageTitle = 'Вход — SF-AdTech'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= \App\Core\App::baseUrl() ?>/css/app.css">
</head>
<body>

<div class="login-wrap sf-animate">
    <div class="login-box">

        <div class="login-title">SF/ADTECH</div>
        <div class="login-sub">Traffic Tracking System</div>

        <?php if (!empty($error)): ?>
            <div class="sf-alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= \App\Core\App::baseUrl() ?>/auth/login" novalidate>
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

            <div class="mb-3">
                <label class="sf-label" for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="sf-input" required autofocus
                       placeholder="user@example.com">
            </div>

            <div class="mb-4">
                <label class="sf-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="sf-input" required
                       placeholder="••••••••">
            </div>

            <button type="submit" class="sf-btn w-100">ВОЙТИ</button>
        </form>

        <p class="mt-4 text-center" style="font-size:.7rem;color:var(--text-dim);">
            Нет аккаунта?
            <a href="<?= \App\Core\App::baseUrl() ?>/auth/register">Зарегистрироваться</a>
        </p>

        <div style="margin-top:2rem;padding-top:1rem;border-top:1px solid var(--border);
                    font-size:.6rem;letter-spacing:.1em;color:var(--text-dim);text-align:center;">
            SYSTEM v1.0 &nbsp;·&nbsp; <?= date('Y') ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
