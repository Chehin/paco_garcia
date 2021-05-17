<?php

namespace App\Http\Controllers;

class SliderUtilController extends NewsUtilController
{
	
	public function __construct(SliderController $res) {
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->aExtraParams['imageCropW'] = 1600;
		$this->aExtraParams['imageCropH'] = 600;
	}
}
