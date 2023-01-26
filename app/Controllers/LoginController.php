<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Models\FormValidation;
use App\Models\Database;
use App\Models\User;
use Exception;
use App\Request;

class LoginController extends BaseController {
    public function index(Request $request)
    {
        if ($this->user->isLoggedIn()) {
            $this->redirectTo('/');
        }

        if ($request->getMethod() === 'GET') {
            $this->view->render('login/index');
            return;
        }

        // POST
        // Input Validieren
        $formInput = $request->getInput('post');
        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'username' => 'required|min:3|max:64',
            'password' => 'required|min:6',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->render('login/index', [
                'errors' => $validation->getErrors()
            ]);
        }

        // User Einloggen
        try {
            $this->user->login($formInput['username'], $formInput['password']);
            Session::flash('success', 'You have been successfully signed in.');
            $this->redirectTo('/dashboard');
        } catch (Exception $e) {
            $this->view->render('login/index', [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
