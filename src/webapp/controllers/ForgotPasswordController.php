<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 30.08.2015
 * Time: 00:07
 */

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\validation\UserNamePasswordValidation;

class ForgotPasswordController extends Controller {

    public function __construct() {
        parent::__construct();
    }


    function forgotPassword() {
        $this->render('forgotPassword.twig', []);
    }

    function submitName() {
        $username = $this->app->request->post('username');
        $validation = new UserNamePasswordValidation();
        if($username != "" && $validation->validateUserName($username)) {
            $this->app->redirect('/forgot/' . $username);
        }
        else {
            $this->render('forgotPassword.twig');
            $this->app->flash("error", "Please input a username");
        }

    }

    function confirmForm($username) {
        $validation = new UserNamePasswordValidation();
        if($username != "" && $validation->validateUserName($username)) {
            $user = $this->userRepository->findByUser($username);
            $this->render('forgotPasswordConfirm.twig', ['user' => $user]);
        }
        else {
            $this->app->flashNow("error", "Please write in a username");
        }
    }

    function confirm() {
        $this->app->flash('success', 'Thank you! The password was sent to your email');
        // $sendmail

        $this->app->redirect('/login');
    }

    function deny() {

    }





} 