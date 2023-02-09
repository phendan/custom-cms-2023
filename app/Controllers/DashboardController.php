<?php

namespace App\Controllers;

use App\BaseController;
use App\Traits\RouteGuards\UserOnly;

class DashboardController extends BaseController {
    use UserOnly;

    public function index()
    {
        $this->user->find($this->user->getId());

        $posts = $this->user->getPosts();

        $this->view->render('dashboard/index', [
            'posts' => $posts
        ]);
    }

    public function settings()
    {
        //
    }
}
