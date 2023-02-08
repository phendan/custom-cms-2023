<?php

namespace App;

use App\View;
use App\Models\Database;
use App\Models\User;

class BaseController {
    protected Database $db;
    protected User $user;
    protected View $view;

    public function __construct()
    {
        $this->db = new Database;
        $this->user = new User($this->db);
        $this->view = new View($this->user);
    }

    protected function redirectTo(string $path, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Location: ' . $path);
        exit();
    }
}
