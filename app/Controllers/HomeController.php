<?php

namespace App\Controllers;

use App\BaseController;
use App\Models\Database;
use App\Models\User;

class HomeController extends BaseController {
    public function index()
    {
        if ($this->user->isLoggedIn()) {
            $this->user->find($this->user->getId());
        }

        $this->view->render('home/index');
    }
}
