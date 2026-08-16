<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\App;
use App\Models\SubscriptionModel;
use App\Models\ClickModel;

class TrackController extends BaseController
{
    public function go(array $params): void
    {
        $token    = $params['token'] ?? '';
        $subModel = new SubscriptionModel();
        $sub      = $subModel->findByTrackLink($token);

        $ip        = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

        if (!$sub || $sub['offer_status'] !== 'active') {
            // Фиксируем отказ, если подписка найдена
            if ($sub) {
                $clickModel = new ClickModel();
                $clickModel->record(
                    (int)$sub['id'],
                    (int)$sub['offer_id'],
                    (int)$sub['webmaster_id'],
                    (int)$sub['advertiser_id'],
                    (float)$sub['cost_per_click'],
                    App::commission(),
                    $ip,
                    $userAgent,
                    true
                );
            }
            http_response_code(404);
            $ctrl = new ErrorController();
            $ctrl->notFound([]);
            return;
        }

        $clickModel = new ClickModel();
        $clickModel->record(
            (int)$sub['id'],
            (int)$sub['offer_id'],
            (int)$sub['webmaster_id'],
            (int)$sub['advertiser_id'],
            (float)$sub['cost_per_click'],
            App::commission(),
            $ip,
            $userAgent,
            false
        );

        header('Location: ' . $sub['target_url'], true, 302);
        exit;
    }
}
