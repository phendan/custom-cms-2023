<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Request;
use App\Models\FormValidation;
use Exception;
use App\Models\Post;

class PostController extends BaseController {
    // Detail Page
    public function index()
    {
        //
    }


    public function create(Request $request)
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirectTo('/');
        }

        if ($request->getMethod() === 'GET') {
            $this->view->render('posts/create');
        }

        $formInput = $request->getInput();

        $formValidation = new FormValidation($formInput, $this->db);

        $formValidation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:100'
        ]);

        $formValidation->validate();

        if ($formValidation->fails()) {
            $this->view->render('posts/create', [
                'errors' => $formValidation->getErrors()
            ]);
        }

        try {
            $post = new Post($this->db);
            $post->create($this->user->getId(), $formInput['title'], $formInput['body']);
            Session::flash('success', 'Your post has been created');
            $this->redirectTo('/dashboard');
        } catch (Exception $e) {
            //
        }
    }
}
