<?php

namespace App\Controllers;

use App\BaseController;
use App\Models\Database;
use App\Models\User;

class LogoutController extends BaseController {
    public function index()
    {
        $db = new Database;
        $user = new User($db);
        $user->logout();
        header('Location: /');
    }
}
