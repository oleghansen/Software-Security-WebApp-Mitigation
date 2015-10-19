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


    	if(!preg_match('/^[a-zA-Z0-9]{2,20}/$', $author)) {
             $this->validationErrors[] = "Author invalid";
        }
        if ($author == null) {
            $this->validationErrors[] = "Author needed";

        }
        if(!preg_match('/^[a-zA-Z0-9]{1,250}/$', $content)) {
            $this->validationErrors[] = "Text invalid";
        }
        if ($content == null) {
            $this->validationErrors[] = "Text needed";
        }

    }

}