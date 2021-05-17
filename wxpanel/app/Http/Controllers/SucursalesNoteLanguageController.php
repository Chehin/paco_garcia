<?php

namespace App\Http\Controllers;

class SucursalesNoteLanguageController extends NewsNoteLanguageController
{
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(SucursalesController $res)
    {
        $this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
    }
    
}
