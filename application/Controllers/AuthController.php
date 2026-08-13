<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function showLogin(array $params): void
    {
        $this->view('auth/login', ['csrf' => $this->csrfToken(), 'error' => null]);
    }

    public function login(array $params): void
    {
        $this->verifyCsrf();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->view('auth/login', ['csrf' => $this->csrfToken(), 'error' => 'Неверный email или пароль']);
            return;
        }

        if (!$user['is_active']) {
            $this->view('auth/login', ['csrf' => $this->csrfToken(), 'error' => 'Аккаунт заблокирован']);
            return;
        }

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        $this->redirect($this->dashboardPath($user['role']));
    }

    public function showRegister(array $params): void
    {
        $this->view('auth/register', ['csrf' => $this->csrfToken(), 'error' => null]);
    }

    public function register(array $params): void
    {
        $this->verifyCsrf();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', ['csrf' => $this->csrfToken(), 'error' => 'Некорректный email']);
            return;
        }
        if (strlen($password) < 6) {
            $this->view('auth/register', ['csrf' => $this->csrfToken(), 'error' => 'Пароль минимум 6 символов']);
            return;
        }
        if (!in_array($role, ['advertiser', 'webmaster'], true)) {
            $this->view('auth/register', ['csrf' => $this->csrfToken(), 'error' => 'Выберите роль']);
            return;
        }

        $model = new UserModel();
        if ($model->findByEmail($email)) {
            $this->view('auth/register', ['csrf' => $this->csrfToken(), 'error' => 'Email уже занят']);
            return;
        }

        $id = $model->create($email, $password, $role);
        $_SESSION['user'] = ['id' => $id, 'email' => $email, 'role' => $role];
        $this->redirect($this->dashboardPath($role));
    }

    public function logout(array $params): void
    {
        session_destroy();
        $this->redirect('/auth/login');
    }

    private function dashboardPath(string $role): string
    {
        return match($role) {
            'advertiser' => '/advertiser/dashboard',
            'webmaster'  => '/webmaster/dashboard',
            'admin'      => '/admin/dashboard',
            default      => '/auth/login',
        };
    }
}
