<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\RoleController;

class RoleUtilController extends Controller
{
	
	const RESOURCE = RoleController::RESOURCE;
	const RESOURCE_LABEL = RoleController::RESOURCE_LABEL;
	
	public function showMainView(Request $request) {
		
		$aViewData = array(
			'resourceLabel' => self::RESOURCE_LABEL,
			'resource' => self::RESOURCE,
        );
		
		return 
			\View::make('user.' . self::RESOURCE)
				->with('aViewData', $aViewData)
		;
	}

}
