<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Post;

class PostValidation {

    private $validationErrors = [];

    public function __construct($author, $title, $content) {
        return $this->validate($author, $title, $content);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }

    public function validate($author, $title, $content)
    {
        
        if($title == null || !preg_match('/^[a-zA-Z0-9]{1,20}/$', $title)) {
            $this->validationErrors[] = "Title invalid";
        }
        if($author == null || !preg_match('/^[a-zA-Z0-9]{2,20}/$', $author)) {
             $this->validationErrors[] = "Author invalid";
        }
        if($content == null || !preg_match('/^[a-zA-Z0-9]{1,250}/$', $content)) {
            $this->validationErrors[] = "Text invalid";
        }


        return $this->validationErrors;
    }


}
