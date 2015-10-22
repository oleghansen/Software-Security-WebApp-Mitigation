<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Post;
use tdt4237\webapp\models\User;
use tdt4237\webapp\controllers\UserController;
use tdt4237\webapp\models\Comment;
use tdt4237\webapp\validation\PostValidation;
use tdt4237\webapp\validation\AddCommentValidation;
use tdt4237\webapp\validation\UserNamePasswordValidation;

class PostController extends Controller {

    public function __construct() {
        parent::__construct();
    }


    public function index() {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        } elseif ($this->auth->isDoctor()) {
            $posts = $this->postRepository->doctorPost();
            $posts->sortByDate();
            $this->render('posts.twig', ['posts' => $posts]);
        } else {
            $posts = $this->postRepository->all();

            if ($posts != null) {
                $posts->sortByDate();
                $this->render('posts.twig', ['posts' => $posts]);
            } else {
                $this->render('posts.twig', ['posts' => $posts]);
            }

        }

        session_regenerate_id(True);
    }

    public function show($postId) {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        } elseif ($this->auth->isDoctor()  && $post->getDoctorPost() != 1) {
            $this->app->redirect("/posts");

        } else {
            $validation = new UserNamePasswordValidation();
            if ($validation->validatePostId($postId)) {
                $post = $this->postRepository->find($postId);
                $comments = $this->commentRepository->findByPostId($postId);
                $request = $this->app->request;
                $message = $request->get('msg');
                $variables = [];

                if ($message) {
                    $variables['msg'] = $message;

                }

                $this->render('showpost.twig', [
                    'post' => $post,
                    'comments' => $comments,
                    'flash' => $variables
                ]);
            }

        }

    }

    public function addComment($postId) {


        if ($this->auth->isDoctor()) {

            $content = $this->app->request->post("text");
            $usrStr = $this->app->request->post("str");
            if ($usrStr === $_SESSION['randStr']) {
                $valpostId = new UserNamePasswordValidation();
                if ($valpostId->validatePostId($postId)) {

                    $validation = new AddCommentValidation($_SESSION['user'], $content);
                    $post = $this->postRepository->find($postId);
                    $comments = $this->commentRepository->findByPostId($postId);

                    if ($post->getDoctorPost() == 1 && $validation->isGoodToGo()) {
                        $comment = new Comment();
                        $comment->setAuthor($_SESSION['user']." [DOCTOR]");
                        $comment->setText(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
                        $comment->setDate(date("dmY"));
                        $comment->setPost($postId);
                        $this->commentRepository->save($comment);

                        if ($post->getDoctorAnswered() != 1) {
                            $post->setDoctorAnswered(1);

                            $this->postRepository->updatePost($post);

                            $userDoctor = $this->userRepository->findByUser($_SESSION['user']);
                            $userPoster = $this->userRepository->findByUser($post->getAuthor());

                            $userDoctor->addBalance(7);
                            $userPoster->addBalance(-10);

                            $this->userRepository->saveExistingUser($userDoctor);
                            $this->userRepository->saveExistingUser($userPoster);
                        }
                        $this->app->redirect('/posts/' . $postId);
                    }
                }
            }

        } elseif (!$this->auth->guest()) {

            $content = $this->app->request->post("text");
            $usrStr = $this->app->request->post("str");
            if ($usrStr === $_SESSION['randStr']) {
                $valpostId = new UserNamePasswordValidation();
                if ($valpostId->validatePostId($postId)) {

                    $validation = new AddCommentValidation($_SESSION['user'], $content);
                    $post = $this->postRepository->find($postId);
                    $comments = $this->commentRepository->findByPostId($postId);

                    if ($validation->isGoodToGo()) {
                        $comment = new Comment();
                        $comment->setAuthor($_SESSION['user']);
                        $comment->setText(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
                        $comment->setDate(date("dmY"));
                        $comment->setPost($postId);
                        $this->commentRepository->save($comment);
                        $this->app->redirect('/posts/' . $postId);
                    } else {
                        $errors = join("<br>\n", $validation->getValidationErrors());
                        $this->app->flashNow('error', $errors);
                        $this->render('showpost.twig', ['post' => $post, 'comments' => $comments, 'error' => $errors]);
                    }
                }
            } else {
                //report possible CSRF attack
                $this->app->redirect('/');
            }

        } else {
            $this->app->redirect('/login');
            $this->app->flash('info', 'you must log in to do that');
        }
    }


    public function showNewPostForm() {

        if ($this->auth->check()) {
            $this->render('createpost.twig', ['user' => $this->auth->user()]);
        } else {

            $this->app->flash('error', "You need to be logged in to create a post");
            $this->app->redirect("/");
        }

    }

    public function create() {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to create a post");
            $this->app->redirect("/login");
        } else {
            $request = $this->app->request;

            $usrStr = $request->post('str');
            if ($usrStr === $_SESSION['randStr']) {
                $title = $request->post('title');
                $content = $request->post('content');

                $author = $this->auth->getUsername();
                $date = date("dmY");

                $validation = new PostValidation($author, $title, $content);
                if ($validation->isGoodToGo()) {
                    $post = new Post();
                    $post->setAuthor(htmlspecialchars("$author", ENT_QUOTES, 'UTF-8'));
                    $post->setTitle(htmlspecialchars("$title", ENT_QUOTES, 'UTF-8'));
                    $post->setContent(htmlspecialchars("$content", ENT_QUOTES, 'UTF-8'));
                    $post->setDate($date);

                    if ($this->auth->hasBankcard() && isset($_POST['showtodoctor'])) {
                        $post->setDoctorPost(1);
                    } else {
                        $post->setDoctorPost(0);
                    }

                    $savedPost = $this->postRepository->save($post);
                    $this->app->redirect('/posts/' . $savedPost . '?msg="Post succesfully posted');
                }
            } else {
                //report possible CSRF attack
                return $this->app->redirect('/');

            }
            $this->app->flash('error', join('<br>', $validation->getValidationErrors()));
            $this->app->redirect("/posts/new");
            // RENDER HERE
        }
    }
}

