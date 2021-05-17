<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\Provincias;


class DireccionesRelatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	//Related notes
    public function index(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();
		
		if ($this->user->hasAccess('pedidosClientes' . '.view')) {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'id';
                $sortDir = 'asc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
            $items = \App\AppCustom\Models\PedidosDirecciones::
                    select(
						'pedidos_direcciones.id',
						'pedidos_direcciones.titulo',
						'pedidos_direcciones.direccion',
						'pedidos_direcciones.numero',
						'pedidos_direcciones.id_provincia',
						'provincias.provincia',
						'pedidos_direcciones.ciudad',
						'pedidos_direcciones.cp',
						'pedidos_direcciones.informacion_adicional',
						'pedidos_direcciones.telefono'
                    )
					->leftJoin('provincias','provincias.id','=','pedidos_direcciones.id_provincia')
					->where('pedidos_direcciones.id_usuario', $request->input('resource_id'))
                    ->orderBy($sortCol, $sortDir)
            ;
            if ($search) {
								
				$items
					->where(function($query) use ($search) {
						$query
							->where('pedidos_direcciones.titulo','=',"%{$search}%")
							->orWhere('pedidos_direcciones.direccion','like',"%{$search}%")
							->orWhere('pedidos_direcciones.telefono','like',"%{$search}%")
							->orWhere('pedidos_direcciones.cp','like',"%{$search}%")
						;
				});

            }

            $items = $items
                ->paginate($pageSize)
            ;
			
            $aItems = $items->toArray();
			
			
            $total = $aItems['total'];
            $aItems = $aItems['data'];

            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess('pedidosClientes.create')) {
            $modelName = $this->modelName;
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'direccion' => 'required',
                    'numero' => 'required',
                    'id_provincia' => 'required',
                    'ciudad' => 'required',
                    'cp' => 'required',
                    'telefono' => 'required',
                    'titulo' => 'required',
                ], 
                [
                    'direccion.required' => 'El campo Dirección es requerido',
                    'numero.required' => 'El campo Número es requerido',
                    'id_provincia.required' => 'El campo Provincia es requerido',
                    'ciudad.required' => 'El campo Ciudad es requerido',
                    'cp.required' => 'El campo Código postal es requerido',
                    'telefono.required' => 'El campo Teléfono es requerido',
                    'titulo.required' => 'El campo Referencia es requerido',
                ]
            );

            if (!$validator->fails()) {
                $resource = new PedidosDirecciones(
                    [
                    	'id_usuario'	=> $request->input('resource_id'),
                        'direccion' 	=> $request->input('direccion'),
                        'numero' 	    => $request->input('numero'),
                        'piso' 	        => $request->input('piso'),
                        'departamento'  => $request->input('departamento'),
                        'id_provincia'  => $request->input('id_provincia'),
                        'ciudad'    	=> $request->input('ciudad'),
                        'cp'    		=> $request->input('cp'),
                        'telefono'    	=> $request->input('telefono'),
                        'titulo'    	=> $request->input('titulo'),
                        'informacion_adicional'    	=> $request->input('informacion_adicional'),
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }  
        
        return response()->json($aResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aResult = Util::getDefaultArrayResult();

        $item = PedidosDirecciones::find($id);

        if ($item) {
            $aResult['data'] = $item->toArray();
            $aResult['data']['id'] = $aResult['data']['id'];
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
        
        
        return response()->json($aResult);
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
		
		if ($this->user->hasAccess('pedidosClientes' . '.update')) {
			
			$item = PedidosDirecciones::find($id);
			
			if ($item) {
				
                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'direccion' => 'required',
                        'numero' => 'required',
                        'id_provincia' => 'required',
                        'ciudad' => 'required',
                        'cp' => 'required',
                        'telefono' => 'required',
                        'titulo' => 'required',
                    ], 
                    [
                        'direccion.required' => 'El campo Dirección es requerido',
                        'numero.required' => 'El campo Número es requerido',
                        'id_provincia.required' => 'El campo Provincia es requerido',
                        'ciudad.required' => 'El campo Ciudad es requerido',
                        'cp.required' => 'El campo Código postal es requerido',
                        'telefono.required' => 'El campo Teléfono es requerido',
                        'titulo.required' => 'El campo Referencia es requerido',
                    ]
                )
                ;   
				
				if (!$validator->fails()) {
                    

                    $item->fill(
                        [
                            'id_usuario'    => $request->input('resource_id'),
                            'direccion'     => $request->input('direccion'),
                            'numero'        => $request->input('numero'),
                            'piso'          => $request->input('piso'),
                            'departamento'  => $request->input('departamento'),
                            'id_provincia'  => $request->input('id_provincia'),
                            'ciudad'        => $request->input('ciudad'),
                            'cp'            => $request->input('cp'),
                            'telefono'      => $request->input('telefono'),
                            'titulo'        => $request->input('titulo'),
                            'informacion_adicional'        => $request->input('informacion_adicional'),
                            
                        ]
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess('pedidosClientes.delete')) {
            $modelName = $this->modelName;
        
            $item = PedidosDirecciones::find($id);

            if ($item) {

                if (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
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
