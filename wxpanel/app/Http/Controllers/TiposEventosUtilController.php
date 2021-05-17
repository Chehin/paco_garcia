<?php
namespace App\Http\Controllers;


class TiposEventosUtilController extends GenericUtilController
{
    public function __construct(TiposEventosController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
	}
}
