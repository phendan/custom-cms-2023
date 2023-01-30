<?php

namespace App;

use App\Router;

class App {
    public function __construct()
    {
        // $this->autoloadClasses();

        $router = new Router;

        $requestedController = $router->getRequestedController();
        $requestedMethod = $router->getRequestedMethod();
        $params = $router->getParams();

        $controller = new $requestedController;

        $request = new Request($params);
        $controller->{$requestedMethod}($request);
    }

    // private function autoloadClasses()
    // {
    //     spl_autoload_register(function ($namespace) {
    //         $projectNamespace = 'App\\';
    //         $className = str_replace($projectNamespace, '', $namespace);
    //         $filePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    //         if (file_exists($filePath)) {
    //             require_once $filePath;
    //         }
    //     });
    // }
}
