<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SF-AdTech', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= \App\Core\App::baseUrl() ?>/css/app.css">
    <noscript><style>.js-only{display:none}</style></noscript>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= \App\Core\App::baseUrl() ?>/">SF-AdTech</a>
        <?php if (!empty($_SESSION['user'])): ?>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white small"><?= htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8') ?></span>
            <a href="<?= \App\Core\App::baseUrl() ?>/auth/logout" class="btn btn-outline-light btn-sm">Выйти</a>
        </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container pb-5">
