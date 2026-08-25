<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SF-AdTech', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= \App\Core\App::baseUrl() ?>/css/app.css">
    <noscript><style>.js-only{display:none}</style></noscript>
</head>
<body>

<nav class="sf-nav navbar mb-0">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="<?= \App\Core\App::baseUrl() ?>/">
            SF<span style="color:#fff;opacity:.3">/</span>ADTECH
        </a>
        <?php if (!empty($_SESSION['user'])): ?>
        <div class="d-flex align-items-center gap-3">
            <span class="nav-user">
                <span style="color:var(--accent)">▸</span>
                <?= htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8') ?>
                <span style="color:var(--text-dim);margin-left:.4rem">[<?= htmlspecialchars($_SESSION['user']['role'], ENT_QUOTES, 'UTF-8') ?>]</span>
            </span>
            <a href="<?= \App\Core\App::baseUrl() ?>/auth/logout" class="btn-logout">EXIT</a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container sf-page">
