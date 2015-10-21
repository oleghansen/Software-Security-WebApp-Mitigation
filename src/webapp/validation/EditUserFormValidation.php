<?php

namespace tdt4237\webapp\validation;

class EditUserFormValidation
{
    private $validationErrors = [];
    
    public function __construct($email, $bio, $age,$fullname,$address,$postcode)
    {
        $this->validate($email, $bio, $age,$fullname,$address,$postcode);
    }
    
    public function isGoodToGo()
    {
        return \count($this->validationErrors) === 0;
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($email, $bio, $age,$fullname,$address,$postcode)
    {
        $this->validateEmail($email);
        $this->validateAge($age);
        $this->validateBio($bio);
        $this->validateFullname($fullname);
        $this->validateAddress($address);
    }
    private function validateFullname($fullname) {
        if(!preg_match('/^[a-zA-Z]{1}[a-zA-Z ]{1,48}$/', $fullname)) {
            $this->validationErrors[] = "Invalid symbols in fullname";
        }
    }
    private function validateAddress($address) {
        if(!preg_match('/^[a-zA-Z0-9]{1}[a-zA-Z0-9 ]{1,68}$/', $address)) {
            $this->validationErrors[] = "Invalid address.";
        }
    }
    private function validatePostcode($postcode) {
        if(!preg_match('/^[0-9]{4}$/', $postcode)) {
            $this->validationErrors[] = "Postcode can not be empty. Invalid symbols.";
        }
    }
    
    private function validateEmail($email)
    {
        if(!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $email)) {
            $this->validationErrors[] = "Invalid email";
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = "Invalid email format on email";
        }
    }
    
    private function validateAge($age)
    {
        if(!preg_match('/^[0-9]{1,3}$/', $age)) {
            $this->validationErrors[] = 'Age is invalid.';
        }
        if (! is_numeric($age) or $age < 0 or $age > 130) {
            $this->validationErrors[] = 'Age must be between 0 and 130.';
        }
    }

    private function validateBio($bio)
    {   
        if(!preg_match('/^[a-zA-Z0-9.]{1}[a-zA-Z0-9. \s]{1,249}$/', $bio)) {
            $this->validationErrors[] = 'Bio contains invalid symbols.';
        }
        if (empty($bio)) {
            $this->validationErrors[] = 'Bio cannot be empty';
        }
    }
}
