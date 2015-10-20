<?php

namespace tdt4237\webapp\validation;

class UserNamePasswordValidation {

	private $validationErrors = [];

    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
    public function validateUserName($username) {

        if(!preg_match('/^[a-zA-Z0-9 ]{2,20}$/', $username)) {
            $this->validationErrors[] = 'Username is invalid';
            return false;
        }
        return true;
    }

    public function validatePassword($password) { //rediger for login

        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,50}$/', $password)) {
            $this->validationErrors[] = 'Invalid password';
            return false;
        }
        return true;
    }

    public function validateLoginPassword($password) {
    	if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{1,50}/', $password)) {
    		$this->validationErrors[]="Invalid password";
    		return false;
    	}
    	return true;
    }

    public function validatePostId($postid) {
    	if(!preg_match('/^[0-9]{1,3}$/', $postid)) {
    		$this->validationErrors[] = "Invalid id";
    		return false;
    	}
    	return true;
    }

}