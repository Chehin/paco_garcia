<?php
namespace App\Http\Controllers;

class MarketingEmpresasUtilController extends GenericUtilController
{
    public function __construct(MarketingEmpresasController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
}
