<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Generos;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\SubSubRubrosGeneroMarca;

class SubRubrosController extends Controller
{
    use ResourceTraitController {
        create as protected createTrait;
        edit as protected editTrait;
        destroy as protected destroyTrait;
    }
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
         
        parent::__construct($request);
        
        $this->resource = 'subRubros';
        $this->resourceLabel = 'Sub Rubros';
        $this->modelName = 'App\AppCustom\Models\SubRubros';
        $this->SubSubRubrosGeneroMarca= 'App\AppCustom\Models\SubSubRubrosGeneroMarca';
        $this->viewPrefix = 'productos.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.view')) {
            
            $modelName = $this->modelName;
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'id';
                $sortDir = 'desc';
            } else {
                            
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            $search1 = \trim($request->input('sSearch_0'));
            

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        'inv_subrubros.id',
                        'inv_subrubros.nombre',
                        'inv_subrubros.habilitado',
                        'inv_rubros.nombre as rubro',
                        'inv_subrubros.orden',
                        'inv_subrubros.destacado',
                        'inv_subrubros.descripcion'
                    )
                    ->join('inv_rubros','inv_rubros.id','=','inv_subrubros.id_rubro')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('inv_subrubros.nombre','like',"%{$search}%")
                    ;
                });
            }
            if ($search1) {
				$items->where(function($query) use ($search1){
					$query
						->where('inv_subrubros.id_rubro',$search1)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        $this->aCustomViewData['aGeneros'] = Generos::all();
        $this->aCustomViewData['aMarcas'] = Marcas::all(); 
        return $this->createTrait();
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
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'nombre' => 'required',
                    'id_rubro' => 'required',
                ], 
                [
                    'nombre.required' => 'El campo Nombre es requerido',
                    'id_rubro.required' => 'El campo Rubro es requerido',
                ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                if (!$modelName::where('nombre',$request->nombre)->where('id_rubro',$request->id_rubro)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El campo Nombre ya existe');
                }
            });

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'        => $request->input('nombre'),
                        'orden'         => $request->input('orden'),
                        'id_rubro'      => $request->input('id_rubro'),
                        'descripcion'   => $request->input('descripcion'),
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                $id = $modelName::all()->last()->id;

                if ($resource->save()) {
					$aItemsStore = json_decode($request->input('itemsStore'),true);
					if (isset($aItemsStore[0])) {
						foreach ($aItemsStore[0] as $aIs) {
                            $genero = Generos::find($aIs['id_genero']);
                            if(isset($aIs['imagen_base'])){
                                $fileName = \time();
                                $fileName .= '_' . \base64_encode($aIs['imagen']);
                                $fileName .= '.jpg';
                                Util::uploadBase64File(
                                    \config('appCustom.UPLOADS_BE'),
                                    $fileName, 
                                    $aIs['imagen_base'],
                                    0.5
                                );
                            }else{
                                $fileName = $aIs['imagen'];
                            }
                            $resource->equivalencias()->attach($genero,['inv_subsubrubros_id'=>$id,'conf_marcas_id' => $aIs['id_marca'], 'conf_generos_id' => $aIs['id_genero'],  'imagen' => $fileName ]);
                        }
                    }
                }else{
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        $this->aCustomViewData['aMarcas'] = Marcas::all(); 
        $this->aCustomViewData['aGeneros'] = Generos::all();
        
        $SubRubrosGeneroMarca = SubSubRubrosGeneroMarca::
        leftJoin('conf_marcas','conf_marcas.id','=','inv_subrubros_genero_marca.conf_marcas_id')
        ->leftJoin('conf_generos','conf_generos.id','=','inv_subrubros_genero_marca.conf_generos_id')
        ->select('conf_marcas.nombre as marca','conf_marcas.id as id_marca','inv_subrubros_genero_marca.imagen as imagen','conf_generos.genero as genero','conf_generos.id as id_genero')
        ->where('inv_subrubros_genero_marca.inv_subsubrubros_id', $id)
        ->get();
        foreach($SubRubrosGeneroMarca as $data){
            $data['link'] = \config('appCustom.PATH_UPLOADS') . $data['imagen_file'];
        }
        $this->aCustomViewData['equivalenciasSubrubros'] = $SubRubrosGeneroMarca;

        return $this->editTrait($id);

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
            
            $modelName = $this->modelName;
            $item = $modelName::find($id);

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
                //Just enable/disable resource? 
                if ('yes' === $request->input('justEnable1')) {
                    $item->destacado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }
                
                

                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'nombre' => 'required',
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                    ]
                )
                ;
                
                if ($item->nombre != $request->nombre) {
                    $validator->after(function($validator) use ($modelName, $request) {
                        if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                            $validator->errors()->add('field', 'El campo Nombre ya existe');
                        }
                    });
                }
                
                

                if (!$validator->fails()) {
                    $item->fill(
                        [
                            'nombre'        => $request->input('nombre'),
                            'orden'         => $request->input('orden'),
                            'id_rubro'      => $request->input('id_rubro'),
                            'descripcion'   => $request->input('descripcion'),
                            
                        ]
                    )
                    ;

                    // if (!$item->save()) {
                    //     $aResult['status'] = 1;
                    //     $aResult['msg'] = \config('appCustom.messages.dbError');
                    // }

                    if ($item->save()) {
                        $item->equivalencias()->detach();
                        $aItemsStore = json_decode($request->input('itemsStore'),true);
						if (isset($aItemsStore[0])) {
							foreach ($aItemsStore[0] as $aIs) {
                                $genero = Generos::find($aIs['id_genero']);
                                if(isset($aIs['imagen_base'])){
                                    $fileName = \time();
                                    $fileName .= '_' . \base64_encode($aIs['imagen']);
                                    $fileName .= '.jpg';
                                    
                                    Util::uploadBase64File(
                                        \config('appCustom.UPLOADS_BE'),
                                        $fileName, 
                                        $aIs['imagen_base'],
                                        0.5
                                    )
                                    ;
                                    // \Log::info("as ".$fileName);
                                }else{
                                    $fileName = $aIs['imagen'];
                                }
                                // \Log::info($fileName);
                                $item->equivalencias()->attach($genero,['inv_subsubrubros_id'=>$id,'conf_marcas_id' => $aIs['id_marca'], 'conf_generos_id' => $aIs['id_genero'],  'imagen' => $fileName ]);


							}
                        }
                    }else{
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
