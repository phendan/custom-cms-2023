<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Request;
use Exception;
use App\Models\{FormValidation, FileValidation, Post};

class PostController extends BaseController {
    // Detail Page
    public function index(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('error', 'The page you were trying to access does not exist.');
            $this->redirectTo('/');
        }

        $id = $request->getInput('page')[0];

        $post = new Post($this->db);

        if (!$post->find($id)) {
            Session::flash('error', 'This post could not be found.');
            $this->redirectTo('/');
        }

        $this->view->render('posts/index', [
            'commentErrors' => Session::flash('commentErrors'),
            'post' => $post
        ]);
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

        $fileInput = $request->getInput('file');
        $fileValidation = new FileValidation($fileInput);

        $fileValidation->setRules([
            'image' => 'type:image|maxsize:2097152'
        ]);

        $fileValidation->validate();

        if ($formValidation->fails() || $fileValidation->fails()) {
            $this->view->render('posts/create', [
                'errors' => array_merge(
                    $formValidation->getErrors(),
                    $fileValidation->getErrors()
                )
            ]);
        }

        try {
            $post = new Post($this->db);

            $image = isset($fileInput['image']) && $fileInput['image']['size'] > 0 ? $fileInput['image'] : null;

            $post->create(
                $this->user->getId(),
                $formInput['title'],
                $formInput['body'],
                $image
            );

            Session::flash('success', 'Your post has been created');
            $this->redirectTo('/dashboard');
        } catch (Exception $e) {
            //
        }
    }

    public function delete(Request $request)
    {
        $getInput = $request->getInput('get');

        if (!isset($getInput['csrfToken']) || $getInput['csrfToken'] !== Session::get('csrfToken')) {
            Session::flash('error', 'This request did not seem intentional.');
            $this->redirectTo('/dashboard');
        }

        if (!isset($request->getInput('page')[0])) {
            Session::flash('error', 'The page you were trying to access does not exist.');
            $this->redirectTo('/dashboard');
        }

        $id = $request->getInput('page')[0];

        $post = new Post($this->db);

        if (!$post->find($id)) {
            Session::flash('error', 'This post has already been deleted');
            $this->redirectTo('/dashboard');
        }

        if (!$this->user->isLoggedIn() || $this->user->getId() !== $post->getUserId()) {
            Session::flash('error', 'You do not have permission to delete this post.');
            $this->redirectTo('/dashboard');
        }

        if (!$post->delete()) {
            Session::flash('error', 'Something went wrong.');
            $this->redirectTo('/dashboard');
        }

        Session::flash('success', 'The post was successfully deleted');
        $this->redirectTo('/dashboard');
    }

    public function edit(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('error', 'You must access this page via a link.');
            $this->redirectTo('/dashboard');
        }

        $id = $request->getInput('page')[0];
        $post = new Post($this->db);

        if (!$post->find($id)) {
            Session::flash('error', 'This post does not exist');
            $this->redirectTo('/dashboard', 404);
        }

        if (!$this->user->isLoggedIn() || $this->user->getId() !== $post->getUserId()) {
            Session::flash('error', 'You do not have permission to edit this post.');
            $this->redirectTo('/login');
        }

        if ($request->getMethod() !== 'POST') {
            $this->view->render('/posts/edit', [
                'post' => $post
            ]);
        }

        $formInput = $request->getInput();

        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:100'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->render('posts/edit', [
                'post' => $post,
                'errors' => $validation->getErrors()
            ], 422);
        }

        if (!$post->edit($formInput['title'], $formInput['body'])) {
            // Session::flash('error', 'Something went wrong while trying to update your post.');
            $this->view->render('posts/edit', [
                'errors' => [
                    'root' => ['Something went wrong while trying to update your post.']
                ],
                'post' => $post
            ]);
        }

        Session::flash('success', 'The post has been successfully updated.');
        $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
    }

    public function like(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('error', 'You must access this page via a link.');
            $this->redirectTo('/dashboard');
        }

        $id = $request->getInput('page')[0];

        $post = new Post($this->db);

        if (!$post->find($id)) {
            Session::flash('error', 'This post does not exist');
            $this->redirectTo('/dashboard', 404);
        }

        if (!$this->user->isLoggedIn()) {
            Session::flash('error', 'You must be signed in to like this post.');
            $this->redirectTo('/login');
        }

        if (!$post->like($this->user->getId())) {
            Session::flash('error', "You've already liked this post");
        }

        $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
    }

    public function dislike(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('error', 'You must access this page via a link.');
            $this->redirectTo('/dashboard');
        }

        $id = $request->getInput('page')[0];

        $post = new Post($this->db);

        if (!$post->find($id)) {
            Session::flash('error', 'This post does not exist');
            $this->redirectTo('/dashboard', 404);
        }

        if (!$this->user->isLoggedIn()) {
            Session::flash('error', 'You must be signed in to like this post.');
            $this->redirectTo('/login');
        }

        $post->dislike($this->user->getId());
        $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
    }
}
