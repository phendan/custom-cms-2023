<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\Post;
use App\Helpers\Session;
use App\Models\FormValidation;

class CommentController extends BaseController {
    public function create(Request $request) {
        if (!isset($request->getInput('get')['postId'])) {
            $this->redirectTo('/');
        }

        if (!$this->user->isLoggedIn()) {
            Session::flash('error', 'You must be signed in to comment');
            $this->redirectTo('/login');
        }

        $postId = $request->getInput('get')['postId'];

        $post = new Post($this->db);

        if (!$post->find($postId)) {
            Session::flash('error', 'The post you were trying to comment on does not exist.');
            $this->redirectTo('/');
        }

        $formInput = $request->getInput();

        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'body' => 'required|min:25'
        ]);

        if ($validation->fails()) {
            Session::flash('commentErrors', $validation->getErrors());
            $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
        }

        $post->addComment($this->user->getId(), $formInput['body']);
        Session::flash('success', 'Your comment was successfully added.');
        $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
    }
}
