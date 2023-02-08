<?php

namespace App\Controllers;

class NotFoundController {
    public function index()
    {
        http_response_code(404);
        echo 'not found';
    }
}
