<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\MktPaises;
use App\AppCustom\Models\MktEmpresas;
use App\AppCustom\Models\MktListas;
use App\AppCustom\Models\MktTelefonos;

class MarketingPersonasController extends Controller
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
        
        $this->resource = 'marketingPersonas';
        $this->resourceLabel = 'Personas';
        $this->modelName = 'App\AppCustom\Models\PedidosClientes';
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
                        'pedidos_usuarios.id',
                        'pedidos_usuarios.mail as email',
                        \DB::raw('CONCAT(nombre, " ", apellido) as contacto'),
                        'pedidos_usuarios.nombre',
                        'pedidos_usuarios.apellido',
                        'mkt_paises.pais',
                        'mkt_provincias.provincia',
                        'pedidos_usuarios.ciudad',
                        'pedidos_usuarios.habilitado',
                        'pedidos_usuarios.telefono',
                        'pedidos_usuarios.ciudad',
                        'pedidos_usuarios.created_at'
                    )
                    ->leftJoin('mkt_paises','mkt_paises.id','=','pedidos_usuarios.id_pais')
                    ->leftjoin('mkt_provincias','mkt_provincias.id','=','pedidos_usuarios.id_provincia')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
                        ->orWhere('mail','like',"%{$search}%")
                    ;
                });
            }
            
            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();
                
//            array_walk($aItems['data'], function(&$val,$key){
//                $i = 0;
//				$telefonos = MktTelefonos::where('id_persona','=',$val['id'])->get();
//                $len = count($telefonos);
//                $val['telefono'] = '';
//                foreach ($telefonos as $telefono) {
//                    if ($i == $len - 1) {
//                        $val['telefono'] .= $telefono->numero;
//                    } else {
//                        $val['telefono'] .= $telefono->numero.' - ';
//                    }
//                    $i++;
//                }
//			});
            
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
		
		$this->aCustomViewData['aTipoTelefono'] = Util::getEnumValues('mkt_personas_telefonos','tipo_telefono');

        
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
                    'email'     => 'required|email|unique:pedidos_usuarios,mail',
                    'nombre'    => 'required',
                    'apellido'  => 'required',
                    'id_pais'   => 'required'
                ], 
                [
                    'email.required'    => 'El campo Email es requerido',
                    'email.email'       => 'Debe ingresar una dirección de correo válida',
                    'email.unique'       => 'El email ingresado ya existe',
                    'nombre.required'   => 'El campo Nombre es requerido',
                    'apellido.required' => 'El campo Apellido es requerido',
                    'id_pais.required'  => 'El campo País es requerido'
                ]
            );                       

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'mail'         => $request->input('email'),
                        'nombre'        => $request->input('nombre'),
                        'apellido'      => $request->input('apellido'),
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
                } else {
                    // Relaciono la persona con las empresas seleccionadas
                    $aAllEmpresas = MktEmpresas::get();
                    if (!$aAllEmpresas->isEmpty()) {
                        $aOpt = $request->input('empresasIds');
                        if($aOpt){
                            array_walk($aOpt, function($value) use ($resource){
                                $empresa = MktEmpresas::where('id',$value)->first();
                                if($empresa) {
                                    $empresa->personas()->attach($resource);
                                }
                            });
                        }
                    }
                    
                    // Relaciono la persona con las listas seleccionadas
                    $aAllListas = MktListas::get();
                    if (!$aAllListas->isEmpty()) {
                        $aOpt = $request->input('listasIds');
                        if($aOpt){
                            array_walk($aOpt, function($value) use ($resource){
                                $lista = MktListas::where('id',$value)->first();
                                if($lista) {
                                    $lista->personas()->attach($resource);
                                }
                            });
                        }
                    }
                    
                    // Relaciono el teléfono con la persona
                    $aOptC = json_decode($request->input('telefonoAsig'), true);
                    if(!$aOptC){ $aOptC = array(); }else{ $aOptC = $aOptC[0]; }
                    array_walk($aOptC, function($value) use ($resource){
                        $telefono = new MktTelefonos([
                            'id_persona' => $resource->id,
                            'tipo_telefono' => $value['tipo_telefono'],
                            'numero' => $value['numero']
                        ]);
                        $telefono->save();
                    });
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
        $this->aCustomViewData['aEmpresasAssigned'] = $modelName::find($id)->empresas()->get();
        $this->aCustomViewData['aListasAssigned'] = $modelName::find($id)->listas()->get();
        $this->aCustomViewData['aTipoTelefono'] = Util::getEnumValues('mkt_personas_telefonos','tipo_telefono');
		
        $aTelefonos = MktTelefonos::where('id_persona','=',$id)->get();
        $this->aCustomViewData['aTelefonosAssigned'] = $aTelefonos;
		
		$this->aCustomViewData['aProvincias'] = array('' => 'Seleccionar provincia');

        
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
                        'email'     => 'required|email|unique:pedidos_usuarios,mail,'.$item->id,
                        'nombre'    => 'required',
                        'apellido'  => 'required',
                        'id_pais'   => 'required'
                    ], 
                    [
                        'email.required'    => 'El campo Email es requerido',
                        'email.email'       => 'Debe ingresar una dirección de correo válida',
                        'email.unique'       => 'El email ingresado ya existe',
                        'nombre.required'   => 'El campo Nombre es requerido',
                        'apellido.required' => 'El campo Apellido es requerido',
                        'id_pais.required'  => 'El campo País es requerido'
                    ]
                );
                
                $validator->after(function($validator) use ($id, $request) {
					$aSc = json_decode($request->input('telefonoAsig'), true);

					if (isset($aSc[0])) {
						foreach ($aSc[0] as $aItem) {
							$item = 
								MktTelefonos::where('numero', $aItem['numero'])
									->where('id_persona','==',$id)
									->first()
							;

							if ($item) {
								$validator->errors()->add('field', 'El número '.$aItem['número'].' ya existe');
								break;
							}
						}
					}
				});

                if (!$validator->fails()) {
                    $item->fill(
                        [
                            'mail'         => $request->input('email'),
                            'nombre'        => $request->input('nombre'),
                            'apellido'      => $request->input('apellido'),
                            'telefono'      => $request->input('telefono'),
                            'id_pais'       => $request->input('id_pais'),
                            'id_provincia'  => $request->input('id_provincia'),
                            'ciudad'        => $request->input('ciudad')                            
                        ]
                    );

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    } else {
                        // Relaciono la persona con las empresas seleccionadas
                        $aEmpresas = $item->empresas()->get(); // Busco las empresas que ya fueron relacionadas
                        if (!$aEmpresas->isEmpty()) {
                            foreach ($aEmpresas as $empresa) {
                                $empresa->personas()->detach($item);
                            }
                        }
                        $aOpt = $request->input('empresasIds');
                        if(!$aOpt){ $aOpt = array(); }
                        array_walk($aOpt, function($value) use ($item){
                            $empresa = MktEmpresas::where('id',$value)->first();
                            if ($empresa) {
                                $empresa->personas()->attach($item);
                            }
                        });
                        
                        // Relaciono la persona con las empresas seleccionadas
                        $aListas = $item->listas()->get(); // Busco las empresas que ya fueron relacionadas
                        if (!$aListas->isEmpty()) {
                            foreach ($aListas as $lista) {
                                $lista->personas()->detach($item);
                            }
                        }
                        $aOpt = $request->input('listasIds');
                        if(!$aOpt){ $aOpt = array(); }
                        array_walk($aOpt, function($value) use ($item){
                            $lista = MktListas::where('id',$value)->first();
                            if ($lista) {
                                $lista->personas()->attach($item);
                            }
                        });
                        
                        // Relaciono el teléfono con la persona
                        $aTelefonos = MktTelefonos::where('id_persona','=',$id)->get();
                        foreach ($aTelefonos as $telefono) {
                            $telefono->delete();
                        }
                        $aOptC = json_decode($request->input('telefonoAsig'), true);
                        if(!$aOptC){ $aOptC = array(); }else{ $aOptC = $aOptC[0]; }
                        array_walk($aOptC, function($value) use ($item){
                            $telefono = new MktTelefonos([
                                'id_persona' => $item->id,
                                'tipo_telefono' => $value['tipo_telefono'],
                                'numero' => $value['numero']
                            ]);
                            $telefono->save();
                        });
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
