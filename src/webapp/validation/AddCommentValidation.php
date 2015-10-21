<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Post;

class AddCommentValidation {

	
	private $validationErrors = [];

	public function __construct($author, $content) {
        return $this->validate($author,  $content);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }

    public function validate($author,$content) {


    	if($author == null || !preg_match('/^[a-zA-Z0-9]{1}[a-zA-Z0-9 ]{1,19}$/', $author)) {
             $this->validationErrors[] = "Author invalid";
        }
        
        if($content == null || !preg_match('/^[a-zA-Z0-9.]{1}[a-zA-Z0-9.? \s]{1,249}$/', $content)) {
            $this->validationErrors[] = "Text invalid";
        }

        return $this->validationErrors;
    }

}