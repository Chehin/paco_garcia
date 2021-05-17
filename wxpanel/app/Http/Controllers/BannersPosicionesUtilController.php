<?php
namespace App\Http\Controllers;


class BannersPosicionesUtilController extends GenericUtilController
{
    public function __construct(BannersPosicionesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
	}
}