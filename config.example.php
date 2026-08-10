<?php

// Параметры подключения к PostgreSQL
// Скопируйте этот файл в config.php и заполните своими значениями

return [
    'db' => [
        'host'     => 'localhost',
        'port'     => '5432',
        'dbname'   => 'sfadtech',
        'user'     => 'postgres',
        'password' => 'secret',
    ],
    'app' => [
        'base_url'   => 'http://localhost:8080',
        'commission' => 0.20,   // 20% комиссия системы
    ],
];
