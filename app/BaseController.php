<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'View.php';

class BaseController {
    protected View $view;

    public function __construct()
    {
        $this->view = new View;
    }
}
