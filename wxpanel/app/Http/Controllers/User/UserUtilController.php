<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\User\UserController;
use App\AppCustom\Models\Sentinel\User;
use Sentinel;

class UserUtilController extends BaseController
{
	public function showMainView() {
		
		$aViewData = array(
            'resourceLabel' => UserController::RESOURCE_LABEL,
			'resource' => UserController::RESOURCE,
        );
		
		return 
			\View::make('user.user')
				->with('aViewData', $aViewData)
		;
	}
	
    static function generateApiToken() {
        
        do {
            $tokenKey = \Hash::make(\str_random(50));
        } while (User::where("api_token", "=", $tokenKey)->first() instanceof User);
        
        return $tokenKey;
        
    }
    
    static function clearApiTokenCurrentUser() {
		
		if (Sentinel::check()) {
			$user = User::find(Sentinel::getUser()->id);
			$user->api_token = '';
			$user->save();
		}
    }
	
	public static function permissionLabel($permission) {
		
		list(,$permissionOriginal) = explode('.', $permission);
		
		switch($permissionOriginal) {
			case 'view':
				return 'Ver';
			case 'create':
				return 'Crear';
			case 'update':
				return 'Modificar';
			case 'delete':
				return 'Eliminar';
		}
		
	}
	
}
