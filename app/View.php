<?php

namespace App;

use App\Helpers\Session;
use App\Models\User;

class View {
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render(string $view, array $data = [])
    {
        $user = $this->user;
        $session = Session::class;

        extract($data);

        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/partials/header.php");
        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/{$view}.php");
        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/partials/footer.php");

        exit();
    }
}
