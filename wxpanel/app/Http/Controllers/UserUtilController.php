<?php
namespace App\Http\Controllers;

use App\AppCustom\Util;

class UserUtilController extends Controller
{
    
	
	
	public function showMainViewImage() {
	
		$aResult = Util::getDefaultArrayResult();
        
		$aViewData['resource'] = 'userImg';
		$aViewData['resourceLabel'] = 'Imagen de perfil';


		$aResult['html'] = 
			\View::make('user.userImage')
				->with('aViewData', $aViewData)
				->render()
			;
            
        
        
        return response()->json($aResult);
	}
	
	
    
    
}
