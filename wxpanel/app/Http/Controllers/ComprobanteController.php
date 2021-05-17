<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;

class ComprobanteController extends Controller
{
    use ResourceTraitController {
        create as protected createTrait;
        edit as protected editTrait;
    }
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'comprobante';
        $this->resourceLabel = 'Comprobantes';
        $this->modelName = \App\AppCustom\Models\Comprobante::class;
        $this->viewPrefix = 'configuracion.';
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
                $modelName::ByCompany($this->id_company,'comprobantes')
					->select(
                        'comprobantes.id',
                        'tipo_comprobante_clases.nombre as clase',
                        'tipo_comprobante_letras.nombre as letra',
                        'tipo_comprobante.id_tipo_comprobante',
                        'comprobantes.punto_venta',
                        'comprobantes.autoimpresion',
                        'comprobantes.habilitado',
                        'comprobantes.descripcion'
                    )
					->leftJoin('tipo_comprobante_clases','tipo_comprobante_clases.id','=','comprobantes.id_clase')
                    ->leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                    ->leftJoin('tipo_comprobante','tipo_comprobante.id_tipo_comprobante','=','comprobantes.id_tipo_comprobante')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('tipo_comprobante_clases.nombre','like',"%{$search}%")
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
	
	public function create()
    { 
		
		$this->commonCustomViewData();
		
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
                    'id_clase' => 'required|exists:tipo_comprobante_clases,id',
                    'id_letra' => 'required|exists:tipo_comprobante_letras,id',
                    'id_tipo_comprobante' => 'required|exists:tipo_comprobante,id_tipo_comprobante',
                    'punto_venta' => 'required',
                    'domicilio_fiscal' => 'required'
                ], 
                [
                    'id_clase.required' => 'El campo Clase es requerido',
                    'id_clase.exists' => 'El campo Clase no existe',
                    'id_letra.required' => 'El campo Letra es requerido',
                    'id_letra.exists' => 'El campo Letra no existe',
                    'id_tipo_comprobante.required' => 'El campo Tipo comprobante es requerido',
                    'id_tipo_comprobante.exists' => 'El campo Tipo comprobante no existe',
					'punto_venta.required' => 'El campo Punto de Venta es requerido',
					'domicilio_fiscal.required' => 'El campo Domicilio Fiscal es requerido'
                ]
            );
			
			$validator->after(function($validator) use ($request){
				if (!$validator->errors()->has('id_categoria')) {
					if (1!=$request->id_categoria && '' === $request->fiscal) {
						$validator->errors()->add('field', 'Para esta Categoría debe elegir el campo Fiscal');
					}
				}
			});
			
			

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
						'id_company' => $this->id_company,
						'id_clase' => $request->input('id_clase'),
                        'id_letra' => $request->input('id_letra'),
                        'id_tipo_comprobante' => $request->input('id_tipo_comprobante'),
						'fiscal' =>  1,
						'punto_venta' => $request->input('punto_venta'),
						'domicilio_fiscal' => $request->input('domicilio_fiscal'),
                        'autoimpresion' => 1,
                        'descripcion' => $request->input('descripcion'),
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
	
	public function edit($id)
    { 
		
		
		$this->commonCustomViewData();
		
		
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
						'id_clase' => 'required|exists:tipo_comprobante_clases,id',
                        'id_letra' => 'required|exists:tipo_comprobante_letras,id',
                        'id_tipo_comprobante' => 'required|exists:tipo_comprobante,id_tipo_comprobante',
						'punto_venta' => 'required',
						'domicilio_fiscal' => 'required'
					], 
					[
						'id_clase.required' => 'El campo Clase es requerido',
						'id_clase.exists' => 'El campo Clase no existe',
						'id_letra.required' => 'El campo Letra es requerido',
                        'id_letra.exists' => 'El campo Letra no existe',
                        'id_tipo_comprobante.required' => 'El campo Tipo Comprobante es requerido',
						'id_tipo_comprobante.exists' => 'El campo Tipo Comprobante no existe',
						'punto_venta.required' => 'El campo Punto de Venta es requerido',
						'domicilio_fiscal.required' => 'El campo Domicilio Fiscal es requerido'
					]
                )
                ;
				
				$validator->after(function($validator) use ($request){
					if (!$validator->errors()->has('id_categoria')) {
						if (1!=$request->id_categoria && '' === $request->fiscal) {
							$validator->errors()->add('field', 'Para esta Categoría debe elegir el campo Fiscal');
						}
					}
				});

                if (!$validator->fails()) {
                    $item->fill(
                        [
							'id_clase' => $request->input('id_clase'),
                            'id_letra' => $request->input('id_letra'),
                            'id_tipo_comprobante' => $request->input('id_tipo_comprobante'),
							'fiscal' => 1,
							'punto_venta' => $request->input('punto_venta'),
							'domicilio_fiscal' => $request->input('domicilio_fiscal'),
                            'autoimpresion' => 1,
                            'descripcion' => $request->input('descripcion'),
                            
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
	
	protected function commonCustomViewData()
    { 
		
		$this->aCustomViewData['tipoComprobanteClases'] = 
			\App\AppCustom\Models\TipoComprobanteClase::orderBy('nombre','desc')
		;
		
		$this->aCustomViewData['tipoComprobanteLetras'] = 
			\App\AppCustom\Models\TipoComprobanteLetra::orderBy('nombre','desc')
        ;
        
        $this->aCustomViewData['tipoComprobante'] = 
			\App\AppCustom\Models\TipoComprobante::orderBy('tipo_comprobante','desc')
		;
                
    }

}
