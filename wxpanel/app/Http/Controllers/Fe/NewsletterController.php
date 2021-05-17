<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Note;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->email = $request->input('email');
    }

	public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.create')) {
        
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                array(
                    'email' => 'required|unique:editorial_notas,email',
                ), 
                array(
                    'email.required' => 'El Email es obligatorio',
                    'email.unique' => 'El Email ingresado ya existe',
                )
            );

            if (!$validator->fails()) {
                $resource = new Note(
                    array(
                        'id_edicion' => $this->filterNote,
                        'email' => $request->input('email'),
                    )
                )
                ;

                if (!$resource->save()) {
                    $aResult['data']['status'] = 'danger';
                    $aResult['data']['msg'] = \config('appCustom.messages.dbError');
                } else {
                    $aResult['data']['status'] = 'success';
                    $aResult['data']['msg'] = 'Suscripcion realizada con exito, muchas gracias.';
                }

            } else {
                $aResult['data']['status'] = 'danger';
                $aResult['data']['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['data']['status'] = 'danger';
            $aResult['data']['msg'] = \config('appCustom.messages.unauthorized');
        }  
        return response()->json($aResult);
    }
}