<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private static array $config = [];

    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        return self::$config[$key] ?? $default;
    }

    public static function baseUrl(): string
    {
        return rtrim(self::$config['base_url'] ?? '', '/');
    }

    public static function commission(): float
    {
        return (float)(self::$config['commission'] ?? 0.20);
    }
}
