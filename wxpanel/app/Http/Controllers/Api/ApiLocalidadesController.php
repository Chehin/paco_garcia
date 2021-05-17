<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class ApiLocalidadesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidosClientes';
        $this->resourceLabel = 'Localidades';
        $this->modelName = 'App\AppCustom\Models\Localidades';
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
            
            $pageSize = $request->input('iDisplayLength', 1000);
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
                        'localidad.id',
                        'localidad.id_infomanager',
                        'localidad.codigo',
                        'localidad.nombre',
                        'localidad.cod_provincia'
                    )
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
                        ->orWhere('codigo','like',"%%")
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
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;
            
            $localidades = $request->input('localidades');
            
            $error = array();
            foreach ($localidades as $localidad) {
                
                $localidad = json_decode($localidad);
                
                $item = $modelName::where('id_infomanager','=',$localidad->id)->first();
                
                $data = array(
                    'nombre' => $localidad->nombre,
                    'id_infomanager' => $localidad->id,
                    'codigo' => $localidad->codigo,
                    'cod_provincia' => $localidad->cod_provincia,
                );

                //Validation
                $validator = \Validator::make(
                    $data,
                    [
                        'nombre' => 'required',
                        'id_infomanager' => 'required',
                        'codigo' => 'required',
                        'cod_provincia' => 'required'
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'id_infomanager.required' => 'El campo Id es requerido',
                        'codigo.required' => 'El campo Codigo es requerido',
                        'cod_provincia.required' => 'El campo Codigo provincia es requerido'
                    ]
                );
                    
                if (!$item) {
                    
                    if (!$validator->fails()) {
                        $resource = new $modelName(
                            [
                                'nombre'            => $data['nombre'],
                                'id_infomanager'    => $data['id_infomanager'],
                                'codigo'            => $data['codigo'],
                                'cod_provincia'     => $data['cod_provincia']
                            ]
                        );

                        if (!$resource->save()) {
                            $aResult['status'] = 1;
                            array_push($error, 'La localidad ' . $localidad->nombre . ', codigo: ' . $localidad->codigo . ' no se pudo crear');
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = $validator->errors()->all();
                    }
                } else {
                    if (!$validator->fails()) {
                        $item->id_infomanager = $data['id_infomanager'];
                        $item->nombre = $data['nombre'];
                        $item->cod_provincia = $data['cod_provincia'];
                        $item->codigo = $data['codigo'];
                        
                        if (!$item->save()) {
                            $aResult['status'] = 1;
                            array_push($error, 'La localidad ' . $localidad->nombre . ', codigo: ' . $localidad->codigo . ' no se pudo actualizar');
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = $validator->errors()->all();
                    }
                }
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }        
        return response()->json($aResult);
    }        
}
