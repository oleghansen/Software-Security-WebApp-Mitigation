<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    public function __construct()
    {
    }

    public static function make($plaintext, $salt)
    {
        return hash('sha512', $plaintext . $salt);
    }

    public function check($plaintext, $hash, $salt)
    {
        return $this->make($plaintext, $salt) === $hash;
    }

    public function generateSalt()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 45);
    }

}
