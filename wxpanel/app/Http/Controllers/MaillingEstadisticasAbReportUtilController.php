<?php

namespace App\Http\Controllers;


class MaillingEstadisticasAbReportUtilController extends GenericUtilController
{
    public function __construct(MaillingEstadisticasAbReportController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}