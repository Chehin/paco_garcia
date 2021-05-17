<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\RolePcController;

class RolePcUtilController extends Controller
{
	
	const RESOURCE = RolePcController::RESOURCE;
	const RESOURCE_LABEL = RolePcController::RESOURCE_LABEL;
	
	public function showMainView(Request $request) {
		
		$aViewData = array(
			'resourceLabel' => self::RESOURCE_LABEL,
			'resource' => self::RESOURCE,
        );
		
		return 
			\View::make('userPc.' . self::RESOURCE)
				->with('aViewData', $aViewData)
		;
	}

}
