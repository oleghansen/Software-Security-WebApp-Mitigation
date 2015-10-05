<?php

namespace tdt4237\webapp\models;

class User
{

    protected $userId  = null;
    protected $username;
    protected $fullname;
    protected $address;
    protected $postcode;
    protected $hash;
    protected $salt;
    protected $email   = null;
    protected $bio     = 'Bio is empty.';
    protected $age;
    protected $bankcard;
    protected $isAdmin = 0;
    protected $creditcard = null;

    function __construct($username, $hash, $salt, $fullname, $address, $postcode)
    {
        $this->username = $username;
        $this->hash = $hash;
        $this->salt = $salt;
        $this->fullname = $fullname;
        $this->address = $address;
        $this->postcode = $postcode;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPostcode() {
        return $this->postcode;

    }

    public function setPostcode($postcode) {
        $this->postcode = $postcode;

    }

    public function isAdmin()
    {
        return $this->isAdmin === '1';
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setHash($hash) {
        $this->hash = $hash;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function setCredticard($credticard) {
        $this->$credticard = $credticard;
        return $this;
    }

    public function getCreditcard() {
        return $this->creditcard;
    }

}
