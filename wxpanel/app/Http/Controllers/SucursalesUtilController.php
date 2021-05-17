<?php

namespace App\Http\Controllers;

class SucursalesUtilController extends NewsUtilController
{
    
    public function __construct(SucursalesController $res) {
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
	}
}
