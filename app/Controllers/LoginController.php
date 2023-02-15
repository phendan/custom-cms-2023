<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Models\FormValidation;
use Exception;
use App\Request;

class LoginController extends BaseController {
    public function index(Request $request)
    {
        if ($this->user->isLoggedIn()) {
            $this->redirectTo('/');
        }

        // if ($request->getMethod() === 'GET') {
        if (!$request->expectsJson()) {
            $this->view->render('login/index');
            return;
        }

        // POST
        // Input Validieren
        $formInput = $request->getInput('json');
        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'username' => 'required|min:3|max:64',
            'password' => 'required|min:6',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->json(422, [
                'errors' => $validation->getErrors()
            ]);
        }

        // User Einloggen
        try {
            $this->user->login($formInput['username'], $formInput['password']);
            Session::flash('success', 'You have been successfully signed in.');
            $this->view->json(200);
        } catch (Exception $e) {
            $this->view->json(422, [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
