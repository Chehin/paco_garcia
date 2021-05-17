<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\Monedas;


class PreciosRelatedController extends Controller
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
		
		if ($this->user->hasAccess('productos' . '.view')) {
            
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
            $items = PreciosProductos::
                    select(
						'inv_precios.id',
						'inv_precios.precio_venta',
                        'inv_precios.precio_lista',
                        'inv_precios.precio_meli',
                        'inv_precios.descuento',
                        'conf_monedas.nombre as moneda',
                        'conf_monedas.simbolo'
                    )
                    ->join('conf_monedas','conf_monedas.id','=','inv_precios.id_moneda')
					->where('inv_precios.id_producto', $request->input('resource_id'))
                    ->orderBy($sortCol, $sortDir)
            ;
            if ($search) {
								
				$items
					->where(function($query) use ($search) {
						$query
							->where('inv_precios.precio_venta','=',"%{$search}%")
							->orWhere('inv_precios.precio_lista','like',"%{$search}%")
							->orWhere('conf_monedas.nombre','like',"%{$search}%")
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
        
        if ($this->user->hasAccess('productos.create')) {
            $modelPrecio = 'App\AppCustom\Models\PreciosProductos';
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'id_moneda' => 'required',
                    'precio_venta' => 'required',
                ], 
                [
                    'moneda.required' => 'El campo Moneda es requerido',
                    'precio_venta.required' => 'El campo Precio Venta es requerido',
                ]
            );

            $validator->after(function($validator) use ($modelPrecio, $request) {
                if (!$modelPrecio::where('id_moneda',$request->id_moneda)->where('id_producto',$request->resource_id)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El precio para la moneda seleccionada ya fue cargado');
                }
            });

            if (!$validator->fails()) {
                /* Calculo el descuento*/
                $precio_venta = (float)$request->input('precio_venta');
                $precio_lista = (float)$request->input('precio_lista');
                $porcentaje_max = (float)($precio_venta * 100) / $precio_lista;
                $porcentaje_min = 100 - $porcentaje_max;

                $resource = new PreciosProductos(
                    [
                    	'id_producto'	=> $request->input('resource_id'),
                        'precio_venta' 	=> $request->input('precio_venta'),
                        'precio_lista'  => $request->input('precio_lista'),
                        'precio_meli'  => $request->input('precio_meli'),
                        'descuento'     => $porcentaje_min,
                        'id_moneda'     => $request->input('id_moneda'),
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

    public function storeImportKernel($request)
    {
        $aResult = Util::getDefaultArrayResult();
        
            $modelPrecio = 'App\AppCustom\Models\PreciosProductos';
            //Validation
            $validator = \Validator::make(
                $request, 
                [
                    'id_moneda' => 'required',
                    'precio_venta' => 'required',
                ], 
                [
                    'moneda.required' => 'El campo Moneda es requerido',
                    'precio_venta.required' => 'El campo Precio Venta es requerido',
                ]
            );

            $validator->after(function($validator) use ($modelPrecio, $request) {
                if (!$modelPrecio::where('id_moneda',$request['id_moneda'])->where('id_producto',$request['resource_id'])->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El precio para la moneda seleccionada ya fue cargado');
                }
            });

            if (!$validator->fails()) {
                /* Calculo el descuento*/
                $precio_venta = (float)$request['precio_venta'];
                $precio_lista = (float)$request['precio_lista'];
                $porcentaje_max = (float)($precio_venta * 100) / $precio_lista;
                $porcentaje_min = 100 - $porcentaje_max;

                $resource = new PreciosProductos(
                    [
                    	'id_producto'	=> $request['resource_id'],
                        'precio_venta' 	=> $request['precio_venta'],
                        'precio_lista'  => $request['precio_lista'],
                        'precio_meli'  => $request['precio_meli'],
                        'descuento'     => $porcentaje_min,
                        'id_moneda'     => $request['id_moneda'],
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

        $item = PreciosProductos::find($id);

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
        $modelPrecio = 'App\AppCustom\Models\PreciosProductos';
		
		if ($this->user->hasAccess('productos' . '.update')) {
			
			$item = PreciosProductos::find($id);
			
			if ($item) {
				
                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'id_moneda' => 'required',
                        'precio_venta' => 'required',
                    ], 
                    [
                        'moneda.required' => 'El campo Moneda es requerido',
                        'precio_venta.required' => 'El campo Precio Venta es requerido',
                    ]
                )
                ;  
                if ($item->id_moneda != $request->id_moneda) {
                    $validator->after(function($validator) use ($modelPrecio, $request) {
                        if (!$modelPrecio::where('id_moneda',$request->id_moneda)->get()->isEmpty()) {
                            $validator->errors()->add('field', 'El precio para la moneda seleccionada ya fue cargado');
                        }
                    });
                }
				
				if (!$validator->fails()) {
                    /* Calculo el descuento*/
                    $precio_venta = (float)$request->input('precio_venta');
                    $precio_lista = (float)$request->input('precio_lista');
                    $porcentaje_max = (float)($precio_venta * 100) / $precio_lista;
                    $porcentaje_min = 100 - $porcentaje_max;
        
                    $item->fill(
                        [
                            'id_producto'   => $request->input('resource_id'),
                            'precio_venta'  => $request->input('precio_venta'),
                            'precio_lista'  => $request->input('precio_lista'),
                            'precio_meli'  => $request->input('precio_meli'),
                            'descuento'     => $porcentaje_min,
                            'id_moneda'     => $request->input('id_moneda'),
                            
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
        
        if ($this->user->hasAccess('productos.delete')) {
            $modelName = $this->modelName;
        
            $item = PreciosProductos::find($id);

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

    public function editInLine(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        $id = $request->input('id');
        $aData = $request->input('data');
        $item = PreciosProductos::find($id);
        if ($item) {
            $item->precio_venta = $aData['precio_venta'];
            $item->precio_lista = $aData['precio_lista'];
            $item->precio_meli = $aData['precio_meli'];
            $item->descuento = $aData['descuento'];
            if (!$item->save()) {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.dbError');
            }
        }
        return response()->json($aResult);
    }
		
}
