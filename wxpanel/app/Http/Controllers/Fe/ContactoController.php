<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;

class ContactoController extends Controller
{
	public function index(Request $request)
    {
        
        $aResult = Util::getDefaultArrayResult();
        return response()->json($aResult);
    }
 
    public function send(Request $request)
    {
        
        $aResult = Util::getDefaultArrayResult();
        $asunto = 'Nuevo contacto';
		$array_data = array(
            'nombre' => $request->input('nombre'),
            'asunto' => $request->input('asunto'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'mensaje' => $request->input('mensaje'),
        );

        if (\Mail::send('email.contacto', $array_data, function ($message)use($asunto) {
            $message->to('sabrina.cuevas@webexport.com.ar')->subject($asunto.' - Paco Garcia');
        })) {
            $aResult['data']['status'] = 'success';
            $aResult['data']['msg'] = 'CONTACTO_EXITO';//lang
        } else {
            $aResult['data']['status'] = 'danger';
            $aResult['data']['msg'] = 'CONTACTO_ERROR';//lang
        }

        return response()->json($aResult);
    }

   

}