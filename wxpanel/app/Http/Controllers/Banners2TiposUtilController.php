<?php
namespace App\Http\Controllers;


class Banners2TiposUtilController extends GenericUtilController
{
    public function __construct(Banners2TiposController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
	}
}