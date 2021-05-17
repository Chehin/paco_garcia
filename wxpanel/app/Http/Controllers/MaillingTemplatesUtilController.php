<?php

namespace App\Http\Controllers;


class MaillingTemplatesUtilController extends GenericUtilController
{
    public function __construct(MaillingTemplatesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}