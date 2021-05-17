<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ImportarClientesUtilController extends GenericUtilController
{
    	
	public function __construct(ImportarClientesController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->viewPrefix = $res->viewPrefix;
	}
	
	 public function showMainView(Request $request) {

		if ($this->user->hasAccess($this->resource . '.view')) {
			$aViewData = array();
			$aViewData['resource'] = $this->resource;
			$aViewData['resourceLabel'] = $this->resourceLabel;
					
			return 
				\View::make($this->viewPrefix . '.' . $this->resource)
					->with('aViewData', $aViewData)
				;
		} else {
				return \View::make('errors.unauthorized');
		}
	}
	 

    
    
}
