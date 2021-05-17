<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\Note;
use App\AppCustom\Util;


class SucursalesController extends NewsController
{
	
	public function __construct(Request $request)
    {
		Controller::__construct($request);
		
        $this->resource = 'sucursales';
		$this->resourceLabel = 'Sucursales';
		$this->filterNote = \config('appCustom.MOD_SUCURSALES_FILTER');
    }
   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
        
            $item = Note::find($id);

            if ($item) {
                //Just enable/disable resource?
                if ('yes' === $request->input('justEnable')) {
                    $item->habilitado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
						
					}

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable1')) {
					//puede haber un solo destacado
					Note::where('id_edicion', $this->filterNote)->update(['destacado' => 0]);
					
                    $item->destacado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable2')) {
                    $item->sucursalEnvio = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				/* //Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable3')) {
					
                    $item->slider_mobile = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                } */

                //Validation
                $validator = \Validator::make(
                    $request->all(), 
                    array(
                    'titulo' => 'required',
                    'antetitulo' => 'required',
                    'sumario' => 'required',
                    //'texto' => 'required',
                    ), 
                    array(
                        'titulo.required' => 'El título es requerido',
                        'antetitulo.required' => 'El Código de sucursal es requerido',
                        'sumario.required' => 'El direccion es requerido',
                        //'texto.required' => 'El texto es requerido',
                    )
                );

                if (!$validator->fails()) {
                    $item->fill(
                        array(
                            'titulo' => $request->input('titulo'),
							'antetitulo' => $request->input('antetitulo'),
                            'sumario' => $request->input('sumario'),
                            'texto' => $request->input('texto'),
                            'id_seccion' => $request->input('id_seccion'),
							'id_subseccion' => $request->input('id_subseccion'),
                            'ciudad' => $request->input('ciudad'),
                            'pais' => $request->input('pais'),
                            'keyword' => $request->input('keyword'),
                            'orden' => $request->input('orden'),
							'id_video' => $request->input('video_id'),
                            'icono' => $request->input('icono'),
                            'email' => $request->input('email')
                        )
                    )
                    ;

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = $validator->errors()->all();
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }
    
}
