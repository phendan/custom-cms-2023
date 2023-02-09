<?php

namespace App\Controllers;

use App\BaseController;

class LogoutController extends BaseController {
    public function index()
    {
        $this->user->logout();
        $this->redirectTo('/login');
    }
}
