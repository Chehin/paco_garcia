<?php

namespace App\Http\Controllers;


class MaillingEstadisticasUtilController extends GenericUtilController
{
    public function __construct(MaillingEstadisticasController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}