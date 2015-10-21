<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;


class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $fullname, $address, $postcode)
    {
        return $this->validate($username, $password, $fullname, $address, $postcode);
    }
    
    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $password, $fullname, $address, $postcode)
    {
        if(!preg_match('/^[a-zA-Z0-9 ]{2,20}$/', $username)) {
            $this->validationErrors[] = 'Username is invalid';
        }
        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,50}$/', $password)) {
            $this->validationErrors[] = 'Invalid password';
        }
        if(!preg_match('/^[a-zA-Z ]{2,50}$/', $fullname)) {
            $this->validationErrors[] = "Please write in your full name. No symbols!";
        }
        if(!preg_match('/^[a-zA-Z0-9 ]{2,70}$/', $address)) {
            $this->validationErrors[] = "Please write in your address. No symbols!";
        }
        if(!preg_match('/^[0-9]{4}$/', $postcode)) {
             $this->validationErrors[] = "Please write in your post code. Four digits!";
        }
       
        if(strlen($username) < 2)
        {   
            $this->validationErrors[] = 'Username must be between 2 and 20 characters long';
        }
        else if(strlen($username) > 20)
        {
            $this->validationErrors[] = 'Username must be between 2 and 20 characters long';
        }
        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }

        if(empty($fullname)) {
            $this->validationErrors[] = "Please write in your full name";
        }

        if(empty($address)) {
            $this->validationErrors[] = "Please write in your address";
        }

        if(empty($postcode)) {
            $this->validationErrors[] = "Please write in your post code";
        }

        if (strlen($postcode) != "4") {
            $this->validationErrors[] = "Post code must be exactly four digits";
        }

    }
}
