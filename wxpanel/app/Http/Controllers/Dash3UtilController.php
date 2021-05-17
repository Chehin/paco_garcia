<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;


class Dash3UtilController extends GenericUtilController
{
    public function __construct(Dash3Controller $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->itemNameField = 'nombre';

	}
    
    
}
