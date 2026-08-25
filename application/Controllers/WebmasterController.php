<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\App;
use App\Models\OfferModel;
use App\Models\SubscriptionModel;
use App\Models\ClickModel;

class WebmasterController extends BaseController
{
    public function dashboard(array $params): void
    {
        $this->requireAuth('webmaster');
        $user  = $_SESSION['user'];
        $model = new SubscriptionModel();
        $subs  = $model->byWebmaster($user['id']);

        $this->view('webmaster/dashboard', [
            'user' => $user,
            'subs' => $subs,
            'csrf' => $this->csrfToken(),
        ]);
    }

    public function offers(array $params): void
    {
        $this->requireAuth('webmaster');
        $user        = $_SESSION['user'];
        $offerModel  = new OfferModel();
        $subModel    = new SubscriptionModel();
        $allOffers   = $offerModel->allActive();
        $mySubs      = $subModel->byWebmaster($user['id']);
        $myOfferIds  = array_column($mySubs, 'offer_id');

        $this->view('webmaster/offers', [
            'user'        => $user,
            'offers'      => $allOffers,
            'myOfferIds'  => $myOfferIds,
            'csrf'        => $this->csrfToken(),
        ]);
    }

    public function subscribe(array $params): void
    {
        $this->requireAuth('webmaster');
        $this->verifyCsrf();

        $offerId = (int)($params['id'] ?? 0);
        $user    = $_SESSION['user'];

        $offerModel = new OfferModel();
        $offer      = $offerModel->findById($offerId);

        if (!$offer || $offer['status'] !== 'active') {
            $this->redirect('/webmaster/offers');
        }

        $subModel = new SubscriptionModel();
        if ($subModel->findByWebmasterAndOffer($user['id'], $offerId)) {
            $this->redirect('/webmaster/dashboard');
        }

        $token     = bin2hex(random_bytes(16));
        $trackLink = App::baseUrl() . '/go/' . $token;
        $subModel->create($user['id'], $offerId, $token);

        $this->redirect('/webmaster/dashboard');
    }

    public function unsubscribe(array $params): void
    {
        $this->requireAuth('webmaster');
        $this->verifyCsrf();

        $offerId  = (int)($params['id'] ?? 0);
        $user     = $_SESSION['user'];
        $subModel = new SubscriptionModel();
        $subModel->delete($user['id'], $offerId);

        $this->redirect('/webmaster/dashboard');
    }

    public function stats(array $params): void
    {
        $this->requireAuth('webmaster');

        $periodRaw = $_GET['period'] ?? 'day';
        $period    = in_array($periodRaw, ['day', 'month', 'year'], true) ? $periodRaw : 'day';
        $offerId = isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : null;
        $user    = $_SESSION['user'];

        $clickModel = new ClickModel();
        $subModel   = new SubscriptionModel();
        $subs       = $subModel->byWebmaster($user['id']);

        if ($offerId) {
            $rows = $clickModel->statsByWebmasterAndOffer($user['id'], $offerId, $period);
        } else {
            $rows = $clickModel->statsByWebmaster($user['id'], $period);
        }

        $this->view('webmaster/stats', [
            'user'     => $user,
            'subs'     => $subs,
            'rows'     => $rows,
            'period'   => $period,
            'offer_id' => $offerId,
            'csrf'     => $this->csrfToken(),
        ]);
    }
}
