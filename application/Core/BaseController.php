<?php

declare(strict_types=1);

namespace App\Core;

abstract class BaseController
{
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = ROOT . '/application/Views/' . $template . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: $template");
        }
        require $file;
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . \App\Core\App::baseUrl() . $path);
        exit;
    }

    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function requireAuth(string ...$roles): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('/auth/login');
        }
        if (!empty($roles) && !in_array($_SESSION['user']['role'], $roles, true)) {
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token mismatch');
        }
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
