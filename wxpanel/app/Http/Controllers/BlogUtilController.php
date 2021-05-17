<?php

namespace App\Http\Controllers;

class BlogUtilController extends NewsUtilController
{
    
    public function __construct(BlogController $res) {
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->aExtraParams['imageCropW'] = 750;
		$this->aExtraParams['imageCropH'] = 380;
	}
}
