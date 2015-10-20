<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\repository\UserRepository;
use tdt4237\webapp\validation\UserNamePasswordValidation;
class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->auth->check()) {
            $username = $this->auth->user()->getUsername();
            $this->app->flash('info', 'You are already logged in as ' . $username);
            $this->app->redirect('/');
            return;
        }

        $this->render('login.twig', []);
    }

    public function login()
    {
        $request = $this->app->request;
        $user    = $request->post('user');
        $pass    = $request->post('pass');
        $validation = new UserNamePasswordValidation();
        if($validation->validateUserName($user) && $validation->validateLoginPassword($pass)) {
            if ($this->auth->checkCredentials($user, $pass)) {
                $rand = rand(40, 55);
                $randString = $this->generateRandomString($rand);
                $_SESSION['randStr'] = $randString;
                $_SESSION['user'] = $user;
                $this->app->flash('info', "You are now successfully logged in as $user.");
                $this->app->redirect('/');
                return;
            }
        }
        
        
        $this->app->flashNow('error', 'Incorrect user/pass combination.');
        $this->render('login.twig', []);
    }
    
    public function generateRandomString($length) {
       return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
   }
}
