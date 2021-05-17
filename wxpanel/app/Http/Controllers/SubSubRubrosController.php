<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use Illuminate\Pagination\Paginator;

class SubSubRubrosController extends Controller
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
        
        $this->resource = 'subsubRubros';
        $this->resourceLabel = 'Sub Sub Rubros';
        $this->modelName = 'App\AppCustom\Models\SubSubRubros';
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
            

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        'inv_subsubrubros.id',
                        'inv_subsubrubros.nombre',
                        'inv_subsubrubros.habilitado',
                        'inv_rubros.nombre as rubro',
                        'inv_subrubros.nombre as subrubro',
                        'inv_subsubrubros.orden',
                        'inv_subsubrubros.descripcion'
                    )
                    ->join('inv_subrubros','inv_subrubros.id','=','inv_subsubrubros.id_subrubro')
                    ->join('inv_rubros','inv_rubros.id','=','inv_subrubros.id_rubro')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('inv_subsubrubros.nombre','like',"%{$search}%")
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
        $this->aCustomViewData['aSubRubros'] = array();
        
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
                    'id_subrubro' => 'required',
                ], 
                [
                    'nombre.required' => 'El campo Nombre es requerido',
                    'id_rubro.required' => 'El campo Rubro es requerido',
                    'id_subrubro.required' => 'El campo SubRubro es requerido',
                ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                if (!$modelName::where('nombre',$request->nombre)->where('id_subrubro',$request->id_subrubro)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El campo Nombre ya existe');
                }
            });

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'        => $request->input('nombre'),
                        'orden'         => $request->input('orden'),
                        'id_rubro'      => $request->input('id_rubro'),
                        'id_subrubro'      => $request->input('id_subrubro'),
                        'descripcion'   => $request->input('descripcion'),
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$modelName = $this->modelName;
	$id_rubro = $modelName::find($id);
        $this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        $this->aCustomViewData['aSubRubros'] = SubRubros::select('id','nombre')->where('habilitado','=','1')->where('id_rubro','=',$id_rubro->id_rubro)->lists('nombre','id');

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
                            'id_subrubro'      => $request->input('id_subrubro'),
                            'descripcion'   => $request->input('descripcion'),
                            
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

}
