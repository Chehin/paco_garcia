<?php
namespace App\Http\Controllers;


class Banners2PosicionesUtilController extends GenericUtilController
{
    public function __construct(Banners2PosicionesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
	}
}