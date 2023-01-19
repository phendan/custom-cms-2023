<?php

class View {
    public function render(string $view, array $data = [])
    {
        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/partials/header.php");
        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/{$view}.php");
        require_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, "/Views/partials/footer.php");

        exit();
    }
}
