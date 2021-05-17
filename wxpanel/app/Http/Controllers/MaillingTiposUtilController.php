<?php

namespace App\Http\Controllers;
use App\AppCustom\Util;
use Illuminate\Http\Request;


class MaillingTiposUtilController extends GenericUtilController
{
    public function __construct(MaillingTiposController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}

	public function getTemplates(Request $request) { 

		$aViewData = Util::Templates($request->id);                  

		return response()->json($aViewData);
		  
	}
}