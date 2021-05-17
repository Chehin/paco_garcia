<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\MktPaises;

class MarketingEmpresasController extends Controller
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
        
        $this->resource = 'marketingEmpresas';
        $this->resourceLabel = 'Empresas';
        $this->modelName = 'App\AppCustom\Models\MktEmpresas';
        $this->viewPrefix = 'marketing.';
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
                        'mkt_empresas.id',
                        'mkt_empresas.email',
                        'mkt_empresas.razon_social',
                        'mkt_empresas.dominio',
                        'mkt_empresas.direccion',
                        'mkt_empresas.telefono',
                        'mkt_paises.pais',
                        'mkt_provincias.provincia',
                        'mkt_empresas.ciudad',
                        'mkt_empresas.habilitado'
                    )
                    ->join('mkt_paises','mkt_paises.id','=','mkt_empresas.id_pais')
                    ->leftjoin('mkt_provincias','mkt_provincias.id','=','mkt_empresas.id_provincia')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
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
        $this->aCustomViewData['aPaises'] = MktPaises::select('id','pais')->lists('pais','id');
        $this->aCustomViewData['aProvincias'] = array('' => 'Seleccionar provincia');
        
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
                    'email'         => 'required|email|unique:mkt_empresas,email',
                    'razon_social'  => 'required',
                    'id_pais'       => 'required'
                ], 
                [
                    'email.required'        => 'El campo Email es requerido',
                    'email.email'           => 'Debe ingresar una dirección de correo válida',
                    'email.unique'          => 'El email ingresado ya existe',
                    'razon_social.required' => 'El campo Razón Social es requerido',
                    'id_pais.required'      => 'El campo País es requerido'
                ]
            );                       

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'email'         => $request->input('email'),
                        'razon_social'  => $request->input('razon_social'),
                        'dominio'       => $request->input('dominio'),
                        'direccion'     => $request->input('direccion'),
                        'telefono'      => $request->input('telefono'),
                        'id_pais'       => $request->input('id_pais'),
                        'id_provincia'  => $request->input('id_provincia'),
                        'ciudad'        => $request->input('ciudad')
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
        
        $this->aCustomViewData['aPaises'] = MktPaises::select('id','pais')->lists('pais','id');
        $this->aCustomViewData['aPersonasAssigned'] = $modelName::find($id)->personas()->get();
				
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
                
                //Just enable/disable resource? Habilitado
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
                        'email'         => 'required|email|unique:mkt_empresas,email,'.$item->id,
                        'razon_social'  => 'required',
                        'id_pais'       => 'required'
                    ], 
                    [
                        'email.required'        => 'El campo Email es requerido',
                        'email.email'           => 'Debe ingresar una dirección de correo válida',
                        'email.unique'          => 'El email ingresado ya existe',
                        'razon_social.required' => 'El campo Nombre es requerido',
                        'id_pais.required'      => 'El campo País es requerido'
                    ]
                );

                if (!$validator->fails()) {
                    $item->fill(
                        [
                            'email'         => $request->input('email'),
                            'razon_social'  => $request->input('razon_social'),
                            'dominio'       => $request->input('dominio'),
                            'direccion'     => $request->input('direccion'),
                            'telefono'      => $request->input('telefono'),
                            'id_pais'       => $request->input('id_pais'),
                            'id_provincia'  => $request->input('id_provincia'),
                            'ciudad'        => $request->input('ciudad')                            
                        ]
                    );

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
