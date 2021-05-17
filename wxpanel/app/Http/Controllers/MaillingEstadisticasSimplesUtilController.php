<?php

namespace App\Http\Controllers;


class MaillingEstadisticasSimplesUtilController extends GenericUtilController
{
    public function __construct(MaillingEstadisticasSimplesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}