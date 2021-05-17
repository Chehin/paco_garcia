<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\FrontIdiomas;

class IdiomaController extends Controller
{
	public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
		$idioma = FrontIdiomas::
		select('id_idioma','idioma','bandera','default')
		->where('habilitado',1)
		->orderBy('default','desc')
		->get();
		
		$aResult['data'] = $idioma;
        return response()->json($aResult);
    }
}