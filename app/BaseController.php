<?php

namespace App;

use App\View;

class BaseController {
    protected View $view;

    public function __construct()
    {
        $this->view = new View;
    }
}
