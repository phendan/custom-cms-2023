<?php

namespace App\Controllers;

use App\BaseController;
use App\Models\Database;
use App\Models\User;

class HomeController extends BaseController {
    public function index()
    {
        $db = new Database;
        $user = new User($db);

        if ($user->isLoggedIn()) {
            $user->find($user->getId());
        }

        $this->view->render('home/index', [
            'user' => $user
        ]);
    }
}
