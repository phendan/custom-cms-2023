<?php

namespace App\Controllers;

use App\BaseController;

class HomeController extends BaseController {
    public function index()
    {
        if ($this->user->isLoggedIn()) {
            $this->user->find($this->user->getId());
        }

        $this->view->render('home/index');
    }
}
