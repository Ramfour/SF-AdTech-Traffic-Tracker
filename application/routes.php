<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdvertiserController;
use App\Controllers\WebmasterController;
use App\Controllers\AdminController;
use App\Controllers\TrackController;

// Главная — редирект на логин
$router->get('/', [AuthController::class, 'showLogin']);

// Аутентификация
$router->get('/auth/login',    [AuthController::class, 'showLogin']);
$router->post('/auth/login',   [AuthController::class, 'login']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register',[AuthController::class, 'register']);
$router->get('/auth/logout',   [AuthController::class, 'logout']);

// Рекламодатель
$router->get('/advertiser/dashboard',        [AdvertiserController::class, 'dashboard']);
$router->get('/advertiser/offers/create',    [AdvertiserController::class, 'createOffer']);
$router->post('/advertiser/offers/create',   [AdvertiserController::class, 'storeOffer']);
$router->post('/advertiser/offers/{id}/deactivate', [AdvertiserController::class, 'deactivateOffer']);
$router->post('/advertiser/offers/{id}/activate',   [AdvertiserController::class, 'activateOffer']);
$router->get('/advertiser/stats',            [AdvertiserController::class, 'stats']);
$router->post('/advertiser/offers/status',   [AdvertiserController::class, 'updateStatus']); // AJAX drag-drop

// Веб-мастер
$router->get('/webmaster/dashboard',           [WebmasterController::class, 'dashboard']);
$router->get('/webmaster/offers',              [WebmasterController::class, 'offers']);
$router->post('/webmaster/offers/{id}/subscribe',   [WebmasterController::class, 'subscribe']);
$router->post('/webmaster/offers/{id}/unsubscribe', [WebmasterController::class, 'unsubscribe']);
$router->get('/webmaster/stats',               [WebmasterController::class, 'stats']);

// Трекер переходов
$router->get('/go/{token}', [TrackController::class, 'go']);

// Администратор
$router->get('/admin/dashboard',       [AdminController::class, 'dashboard']);
$router->get('/admin/users',           [AdminController::class, 'users']);
$router->post('/admin/users/{id}/toggle', [AdminController::class, 'toggleUser']);
$router->get('/admin/offers',          [AdminController::class, 'offers']);
$router->get('/admin/clicks',          [AdminController::class, 'clicks']);
$router->get('/admin/links',           [AdminController::class, 'links']);
