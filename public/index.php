<?php

declare(strict_types=1);

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

$config = require ROOT . '/config.php';

// PDO singleton
\App\Core\Database::init($config['db']);

// App config
\App\Core\App::setConfig($config['app']);

// Session
session_start();

// Router
$router = new \App\Core\Router();
require ROOT . '/application/routes.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
