<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\AppCustom\Util;

class ProvinciaController extends BaseController {


        
	public function getProvinciaByPais(Request $request)
	{
		
		$aResult = Util::getDefaultArrayResult();
		
		if ($paisId = $request->input('id_pais')) {
			
			$aResult['data'] = 
				\App\AppCustom\Models\Provincia::where('id_pais', $paisId)
					->orderBy('provincia')
					->get()
				;
			
		} else {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.wrongRequest');
		}
		
		return response()->json($aResult);
	}

		

}
