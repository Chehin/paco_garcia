<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;


class Dash2UtilController extends GenericUtilController
{
    public function __construct(Dash2Controller $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->itemNameField = 'nombre';

	}
    
    
}
