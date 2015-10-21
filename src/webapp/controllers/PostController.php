<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Post;
use tdt4237\webapp\controllers\UserController;
use tdt4237\webapp\models\Comment;
use tdt4237\webapp\validation\PostValidation;

class PostController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        }
        elseif($this->auth->isDoctor()) {
            $posts = $this->postRepository->doctorPost();
            $posts->sortByDate();
            $this->render('posts.twig', ['posts' => $posts]);
        }
        else{
            $posts = $this->postRepository->all();
            $posts->sortByDate();
            $this->render('posts.twig', ['posts' => $posts]);
        }
    }

    public function show($postId)
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        }
        else{
            $post = $this->postRepository->find($postId);
            $comments = $this->commentRepository->findByPostId($postId);
            $request = $this->app->request;
            $message = $request->get('msg');
            $variables = [];

            if($message) {
                $variables['msg'] = $message;

            }

            $this->render('showpost.twig', [
                'post' => $post,
                'comments' => $comments,
                'flash' => $variables
            ]);
        }

    }

    public function addComment($postId)
    {

        if(!$this->auth->guest()) {

            $comment = new Comment();
            $comment->setAuthor($_SESSION['user']);
            $comment->setText(htmlspecialchars($this->app->request->post("text"), ENT_QUOTES, 'UTF-8'));
            $comment->setDate(date("dmY"));
            $comment->setPost($postId);
            $this->commentRepository->save($comment);
            $this->app->redirect('/posts/' . $postId);
        }
        else {
            $this->app->redirect('/login');
            $this->app->flash('info', 'you must log in to do that');
        }

    }

    public function showNewPostForm()
    {

        if ($this->auth->check()) {
            $this->render('createpost.twig');
        } else {

            $this->app->flash('error', "You need to be logged in to create a post");
            $this->app->redirect("/");
        }

    }

    public function create()
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to create a post");
            $this->app->redirect("/login");
        } else {
            $request = $this->app->request;
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
                $savedPost = $this->postRepository->save($post);
                $this->app->redirect('/posts/' . $savedPost . '?msg="Post succesfully posted');
            }
            $this->app->flash('error', join('<br>', $validation->getValidationErrors()));
            $this->app->redirect("/posts/new");
            // RENDER HERE
        }
    }
}

