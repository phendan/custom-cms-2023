<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'BaseController.php';

class LoginController extends BaseController {
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view->render('login/index');
        }

        // POST
        // Input Validieren
        $validation = new FormValidation();

        // User Einloggen
        $user = new User();
        $user->login();
    }
}
