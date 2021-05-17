<?php
namespace App\Http\Controllers;


class EtiquetasBlogUtilController extends GenericUtilController
{
    public function __construct(EtiquetasBlogController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->aExtraParams['imageCropW'] = 1024;
		$this->aExtraParams['imageCropH'] = 386;
		//$this->itemNameField = 'titulo';
	}
}
