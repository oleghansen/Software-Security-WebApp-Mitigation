<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\Auth;
use tdt4237\webapp\models\User;
use tdt4237\webapp\validation\UserNamePasswordValidation;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->auth->guest()) {
            $this->app->flash('info', "You must be logged in to view the admin page.");
            $this->app->redirect('/');
        }

        if (! $this->auth->isAdmin()) {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
        }

        session_regenerate_id(True);

        $variables = [
            'users' => $this->userRepository->all(),
            'posts' => $this->postRepository->all()
        ];
        $this->render('admin.twig', $variables);
    }

    public function delete($username)
    {   
        $validation = new UserNamePasswordValidation();
        if($validation->validateUserName($username)) {
            $isAdmin = $this->auth->user()->isAdmin();
            if($isAdmin){
                if ($this->userRepository->deleteByUsername($username) === 1) {
                    $this->app->flash('info', "Sucessfully deleted '$username'");
                    $this->app->redirect('/admin');
                    return;
                }
            }
            else{
               $this->app->redirect('/'); 
            }
        }
        
        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
        $this->app->redirect('/admin');
    }

    public function deletePost($postId)
    {
        $validation = new UserNamePasswordValidation();
        if($validation->validatePostId($postId)) {
            $isAdmin = $this->auth->user()->isAdmin();
            if($isAdmin){
                if ($this->postRepository->deleteByPostid($postId) === 1) {
                    $this->app->flash('info', "Sucessfully deleted '$postId'");
                    $this->app->redirect('/admin');
                    return;
                }
            }
            else{
              $this->app->redirect('/');  
            }
        }
        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
        $this->app->redirect('/admin');
    }

}
