<?php

namespace App\Controllers;

use App\BaseController;

class DashboardController extends BaseController {
    public function index()
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirectTo('/login');
        }

        $this->user->find($this->user->getId());

        $posts = $this->user->getPosts();

        $this->view->render('dashboard/index', [
            'posts' => $posts
        ]);
    }
}
