<?php

namespace App\Traits\RouteGuards;

trait UserOnly {
    public function __construct()
    {
        parent::__construct();

        if (!$this->user->isLoggedIn()) {
            $this->redirectTo('/login');
        }
    }
}
