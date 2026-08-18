<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;
use App\Models\OfferModel;
use App\Models\SubscriptionModel;
use App\Models\ClickModel;

class AdminController extends BaseController
{
    public function dashboard(array $params): void
    {
        $this->requireAuth('admin');

        $clickModel = new ClickModel();
        $stats      = $clickModel->systemStats();

        $this->view('admin/dashboard', [
            'user'  => $_SESSION['user'],
            'stats' => $stats,
            'csrf'  => $this->csrfToken(),
        ]);
    }

    public function users(array $params): void
    {
        $this->requireAuth('admin');
        $model = new UserModel();
        $this->view('admin/users', [
            'user'  => $_SESSION['user'],
            'users' => $model->all(),
            'csrf'  => $this->csrfToken(),
        ]);
    }

    public function toggleUser(array $params): void
    {
        $this->requireAuth('admin');
        $this->verifyCsrf();

        $id     = (int)($params['id'] ?? 0);
        $active = ($_POST['active'] ?? '0') === '1';
        $model  = new UserModel();
        $model->setActive($id, $active);
        $this->redirect('/admin/users');
    }

    public function offers(array $params): void
    {
        $this->requireAuth('admin');
        $model = new OfferModel();
        $this->view('admin/offers', [
            'user'   => $_SESSION['user'],
            'offers' => $model->all(),
            'csrf'   => $this->csrfToken(),
        ]);
    }

    public function clicks(array $params): void
    {
        $this->requireAuth('admin');
        $model = new ClickModel();
        $this->view('admin/clicks', [
            'user'   => $_SESSION['user'],
            'clicks' => $model->allClicks(),
            'csrf'   => $this->csrfToken(),
        ]);
    }

    public function links(array $params): void
    {
        $this->requireAuth('admin');
        $model = new SubscriptionModel();
        $this->view('admin/links', [
            'user'  => $_SESSION['user'],
            'links' => $model->all(),
            'csrf'  => $this->csrfToken(),
        ]);
    }
}
