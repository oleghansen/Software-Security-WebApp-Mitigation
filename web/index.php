<?php
error_reporting(E_ALL); // endret verdi fra E_ALL til 0
ini_set('display_errors', 'on');

if (! extension_loaded('openssl')) {
    die('You must enable the openssl extension.');
}

session_set_cookie_params(0, $httponly=True);
session_cache_limiter(false);
session_start();

//session_regenerate_id(True);

if (preg_match('/\.(?:png|jpg|jpeg|gif|txt|css|js)$/', $_SERVER["REQUEST_URI"]))
    return false; // serve the requested resource as-is.
else {
    $app = require __DIR__ . '/../src/app.php';
    $app->run();
}
