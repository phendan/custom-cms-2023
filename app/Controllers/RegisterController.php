<?php

namespace App\Controllers;

use App\BaseController;
use App\Models\FormValidation;
use App\Models\Database;
use App\Models\User;
use App\Helpers\Session;
use Exception;
use App\Request;

class RegisterController extends BaseController {
    public function index(Request $request)
    {
        if ($this->user->isLoggedIn()) {
            $this->redirectTo('/');
        }

        if ($request->getMethod() === 'GET') {
            $this->view->render('register/index');
            return;
        }

        // POST
        // Input Validieren
        $formInput = $request->getInput('post');
        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'username' => 'required|min:3|max:64|available:users',
            'email' => 'required|email|available:users',
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
        try {
            $this->user->register(
                $formInput['username'],
                $formInput['email'],
                $formInput['password']
            );
            Session::flash('success', 'Your account has been created. Please sign in.');
            $this->redirectTo('/login');
        } catch (Exception $e) {
            $this->view->render('login/index', [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
