<?php

namespace App\Controllers;

use App\BaseController;
use App\Models\FormValidation;
use App\Models\Database;
use App\Models\User;
use Exception;

class RegisterController extends BaseController {
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view->render('register/index');
            return;
        }

        // POST
        // Input Validieren
        $formInput = $_POST;
        $validation = new FormValidation($formInput);

        $validation->setRules([
            'username' => 'required|min:3|max:64',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'passwordAgain' => 'required|matches:password'
        ]);

        $validation->setMessages([
            'passwordAgain.matches' => "You didn't repeat the password correctly."
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->render('register/index', [
                'errors' => $validation->getErrors()
            ]);
        }

        // User Einloggen
        $db = new Database;
        $user = new User($db);
        try {
            $user->register(
                $formInput['username'],
                $formInput['email'],
                $formInput['password']
            );
            header('Location: /');
        } catch (Exception $e) {
            $this->view->render('login/index', [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
