<?php

namespace App\Http\Controllers;

class BlogNoteLanguageController extends NewsNoteLanguageController
{
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(BlogController $res)
    {
        $this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
    }
    
}
