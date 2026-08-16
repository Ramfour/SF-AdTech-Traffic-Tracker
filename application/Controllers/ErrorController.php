<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

class ErrorController extends BaseController
{
    public function notFound(array $params): void
    {
        http_response_code(404);
        $this->view('errors/404');
    }
}
