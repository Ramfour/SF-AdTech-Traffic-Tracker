<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\OfferModel;
use App\Models\ClickModel;

class AdvertiserController extends BaseController
{
    public function dashboard(array $params): void
    {
        $this->requireAuth('advertiser');
        $user   = $_SESSION['user'];
        $model  = new OfferModel();
        $offers = $model->byAdvertiser($user['id']);

        $this->view('advertiser/dashboard', [
            'user'   => $user,
            'offers' => $offers,
            'csrf'   => $this->csrfToken(),
        ]);
    }

    public function createOffer(array $params): void
    {
        $this->requireAuth('advertiser');
        $this->view('advertiser/create_offer', [
            'csrf'  => $this->csrfToken(),
            'error' => null,
        ]);
    }

    public function storeOffer(array $params): void
    {
        $this->requireAuth('advertiser');
        $this->verifyCsrf();

        $name      = $this->sanitize($_POST['name'] ?? '');
        $cpc       = (float)($_POST['cost_per_click'] ?? 0);
        $url       = trim($_POST['target_url'] ?? '');
        $topics    = $this->sanitize($_POST['topics'] ?? '');

        if (!$name || $cpc <= 0 || !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->view('advertiser/create_offer', [
                'csrf'  => $this->csrfToken(),
                'error' => 'Заполните все поля корректно',
            ]);
            return;
        }

        $model = new OfferModel();
        $model->create($_SESSION['user']['id'], $name, $cpc, $url, $topics);
        $this->redirect('/advertiser/dashboard');
    }

    public function deactivateOffer(array $params): void
    {
        $this->requireAuth('advertiser');
        $this->verifyCsrf();

        $offerId = (int)($params['id'] ?? 0);
        $model   = new OfferModel();
        $offer   = $model->findById($offerId);

        if (!$offer || (int)$offer['advertiser_id'] !== $_SESSION['user']['id']) {
            $this->redirect('/advertiser/dashboard');
        }

        $model->setStatus($offerId, 'inactive');
        $this->redirect('/advertiser/dashboard');
    }

    public function activateOffer(array $params): void
    {
        $this->requireAuth('advertiser');
        $this->verifyCsrf();

        $offerId = (int)($params['id'] ?? 0);
        $model   = new OfferModel();
        $offer   = $model->findById($offerId);

        if (!$offer || (int)$offer['advertiser_id'] !== $_SESSION['user']['id']) {
            $this->redirect('/advertiser/dashboard');
        }

        $model->setStatus($offerId, 'active');
        $this->redirect('/advertiser/dashboard');
    }

    public function stats(array $params): void
    {
        $this->requireAuth('advertiser');

        $period  = in_array($_GET['period'] ?? 'day', ['day', 'month', 'year'], true)
                   ? $_GET['period'] : 'day';
        $offerId = isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : null;
        $user    = $_SESSION['user'];

        $clickModel  = new ClickModel();
        $offerModel  = new OfferModel();
        $offers      = $offerModel->byAdvertiser($user['id']);

        if ($offerId) {
            $rows = $clickModel->statsByAdvertiserAndOffer($user['id'], $offerId, $period);
        } else {
            $rows = $clickModel->statsByAdvertiser($user['id'], $period);
        }

        $this->view('advertiser/stats', [
            'user'     => $user,
            'offers'   => $offers,
            'rows'     => $rows,
            'period'   => $period,
            'offer_id' => $offerId,
            'csrf'     => $this->csrfToken(),
        ]);
    }

    // AJAX: изменить статус оффера drag-and-drop
    public function updateStatus(array $params): void
    {
        $this->requireAuth('advertiser');

        $body    = json_decode(file_get_contents('php://input'), true) ?? [];
        $token   = $body['_csrf'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $this->json(['error' => 'CSRF'], 403);
        }

        $offerId = (int)($body['offer_id'] ?? 0);
        $status  = in_array($body['status'] ?? '', ['active', 'inactive'], true)
                   ? $body['status'] : null;

        if (!$offerId || !$status) {
            $this->json(['error' => 'Bad request'], 400);
        }

        $model = new OfferModel();
        $offer = $model->findById($offerId);
        if (!$offer || (int)$offer['advertiser_id'] !== $_SESSION['user']['id']) {
            $this->json(['error' => 'Forbidden'], 403);
        }

        $model->setStatus($offerId, $status);
        $this->json(['ok' => true]);
    }
}
