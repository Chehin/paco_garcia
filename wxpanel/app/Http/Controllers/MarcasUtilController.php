<?php
namespace App\Http\Controllers;


class MarcasUtilController extends GenericUtilController
{
    public function __construct(MarcasController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
		$this->aExtraParams['imageCropW'] = 150;
		$this->aExtraParams['imageCropH'] = 120;
	}
}
