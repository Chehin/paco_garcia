<?php

namespace App\Http\Controllers;


class MaillingEstadisticasReportUtilController extends GenericUtilController
{
    public function __construct(MaillingEstadisticasReportController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}