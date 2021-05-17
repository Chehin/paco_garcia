<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\Note;
use App\AppCustom\Util;
use App\AppCustom\Models\EtiquetasNotas;

class BlogController extends NewsController
{
	
	public function __construct(Request $request)
    {
		Controller::__construct($request);
		
        $this->resource = 'blog';
		$this->resourceLabel = 'Blog';
		$this->filterNote = \config('appCustom.MOD_BLOG_FILTER');
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
					
                    $item->slider_texto = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable3')) {
					
                    $item->slider_mobile = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }

                //Validation
                $validator = \Validator::make(
                    $request->all(), 
                    array(
                    'fecha' => 'required',
                    'titulo' => 'required',
                    'sumario' => 'required',
                    'texto' => 'required',
                    ), 
                    array(
                        'fecha.required' => 'La fecha es requerido',
                        'titulo.required' => 'El título es requerido',
                        'sumario.required' => 'El direccion es requerido',
                        'texto.required' => 'El texto es requerido',
                    )
                );

                if (!$validator->fails()) {
                    $item->fill(
                        array(
                            'fecha' => ($request->input('fecha')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha')) : null,
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
							'icono' => $request->input('icono')
                        )
                    )
                    ;

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    // Relaciono el producto con las etiquetas
                    $aAllEtiquetas = EtiquetasNotas::get();
                    if (!$aAllEtiquetas->isEmpty()) {
                        $aOpt = $request->input('etiquetasBlogIds');
                        if($aOpt){
                            array_walk($aOpt, function($value) use ($item){
                                $etiqueta = EtiquetasNotas::where('id',$value)->first();
                                if($etiqueta) {
                                    $etiqueta->blog()->attach($item);
                                }
                            });
                        }
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
