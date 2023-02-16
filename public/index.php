<?php

session_set_cookie_params([ 'httponly' => true, /*'secure' => true*/ ]);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/helpers.php';

$app = new App\App;
