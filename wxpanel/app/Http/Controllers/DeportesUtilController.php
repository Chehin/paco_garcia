<?php
namespace App\Http\Controllers;


class DeportesUtilController extends GenericUtilController
{
    public function __construct(DeportesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->aExtraParams['imageCropW'] = 1602;
		$this->aExtraParams['imageCropH'] = 1069;
		//$this->itemNameField = 'titulo';
	}
}
