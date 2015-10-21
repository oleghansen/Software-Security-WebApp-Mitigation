<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\repository\UserRepository;

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
    	$totalSecondsWait = 1;
    	
        $request = $this->app->request;
        $user    = $request->post('user');
        $pass    = $request->post('pass');
         	
        // Time Attack 1
        if (stristr(PHP_OS, 'LINUX')) {
        	$oldTime = system('date +%s%N');
        } else {
        	$oldTime = microtime(True);
        }

        if ($this->auth->checkCredentials($user, $pass)) {
            $rand = rand(40, 55);
            $randString = $this->generateRandomString($rand);
            $_SESSION['randStr'] = $randString;
            $_SESSION['user'] = $user;
            $this->app->flash('info', "You are now successfully logged in as $user.");
            $this->app->redirect('/');
            return;
        }
        
        // Time Attack 2
        if (stristr(PHP_OS, 'LINUX')) {
        	time_nanosleep(0, $oldTime - system('date +%s%N')
        			+ $totalSecondsWait * 1000000000);
        } else {
        	usleep($oldTime - microtime(True) 
        			+ $totalSecondsWait * 1000000);
        }
        
        $this->app->flashNow('error', 'Incorrect user/pass combination.');
        $this->render('login.twig', []);
    }
    
    public function generateRandomString($length) {
       return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
   }
}
