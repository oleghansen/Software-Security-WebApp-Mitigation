<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    static $salt = "1234";


    public function __construct()
    {
    }

    public static function make($plaintext)
    {
        return password_hash($plaintext, PASSWORD_BCRYPT);

    }

    public function check($plaintext, $hash)
    {
        return password_verify($plaintext, $hash);
    }

}
