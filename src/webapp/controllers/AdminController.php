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

    public function usernameToDoctor()
    {
        $isAdmin = $this->auth->user()->isAdmin();
        if($isAdmin){
            $request = $this->app->request;
            $username = $request->post('User'); // stor eller liten U i user? trengs validering?
            $valname = new UserNamePasswordValidation();
            if(!$valname->validateUserName($username)) {
                $this->app->redirect('/');
                return;
            }
            $usrStr = $request->post('str');
            if($usrStr === $_SESSION['randStr'])
            {
                if($this->userRepository->usernameToDoctor($username) === 1)
                {
                    $this->app->flash('info',"Sucessfully promoted '$username' to doctor");
                    $this->app->redirect('/admin');
                    return;
                }
                    $this->app->flash('info', "'$username' could not be promoted, sorry dude.");
                    $this->app->redirect('/admin');
            }
            else{
                //Report possible CSRF attack
                return $this->app->redirect('/');
            }
                
        }
        else{
           $this->app->redirect('/'); 
        }
     
    }

    public function delete()
    {   
        $isAdmin = $this->auth->user()->isAdmin();
        if($isAdmin){
            $request = $this->app->request;
            $username = $request->post('User');
            $usrStr = $request->post('str');
            if($usrStr === $_SESSION['randStr'])
            {
                $validation = new UserNamePasswordValidation();
                if($validation->validateUserName($username)) {

                        if($username=== $this->auth->getUsername()) {
                            $this->app->flash('info', "Can not delete youself.");
                            $this->app->redirect('/admin');
                            return;
                        }
                        if ($this->userRepository->deleteByUsername($username) === 1) {
                            $this->app->flash('info', "Sucessfully deleted '$username'");
                            $this->app->redirect('/admin');
                            return;
                        }
                }
            }
            else{
                //report possible CSRF attack
                return $this->app->redirect('/');
            }
        }
        else{
           $this->app->redirect('/'); 
        }
        
        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
        $this->app->redirect('/admin');
    }

    public function deletePost()
    {
        $isAdmin = $this->auth->user()->isAdmin();
        if($isAdmin){
            $request = $this->app->request;
            $postId = $request->post('postId');
            $usrStr = $request->post('str');
            $validation = new UserNamePasswordValidation();
            if($usrStr === $_SESSION['randStr'])
            {
                if($validation->validatePostId($postId)) {
                        if ($this->postRepository->deleteByPostid($postId) === 1) {
                            $this->app->flash('info', "Sucessfully deleted '$postId'");
                            $this->app->redirect('/admin');
                            return;
                        }
                    }

            }
            else{
                //report possible CSRF attack
                return $this->app->redirect('/');
            }
         }
         else{
            $this->app->redirect('/');  
         }
            
        $this->app->flash('info', "An error ocurred. Unable to delete post with postid: '$postId'.");
        $this->app->redirect('/admin');
    }

}
