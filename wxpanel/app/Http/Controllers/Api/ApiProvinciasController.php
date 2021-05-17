<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class ApiProvinciasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidosClientes';
        $this->resourceLabel = 'Provincias';
        $this->modelName = 'App\AppCustom\Models\Provincias';
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
                $sortCol = 'id_provincia';
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
                        'provincias.id_provincia',
                        'provincias.id_infomanager',
                        'provincias.provincia as nombre',
                        'provincias.codigo'
                    )
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('provincias.provincia','like',"%{$search}%")
                        ->orWhere('provincias.codigo','like',"%%")
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
            
            $provincias = $request->input('provincias');
            
            $error = array();
            foreach ($provincias as $provincia) {
                
                $provincia = json_decode($provincia);
                
                $item = $modelName::where('id_infomanager','=',$provincia->id)->first();
                
                $data = array(
                    'provincia' => $provincia->nombre,
                    'id_infomanager' => $provincia->id,
                    'codigo' => $provincia->codigo,
                );

                //Validation
                $validator = \Validator::make(
                    $data,
                    [
                        'provincia' => 'required',
                        'id_infomanager' => 'required',
                        'codigo' => 'required'
                    ], 
                    [
                        'provincia.required' => 'El campo Nombre es requerido',
                        'id_infomanager.required' => 'El campo Id es requerido',
                        'codigo.required' => 'El campo Codigo es requerido'
                    ]
                );
                    
                if (!$item) {
                    
                    if (!$validator->fails()) {
                        $resource = new $modelName(
                            [
                                'provincia'         => $data['provincia'],
                                'id_infomanager'    => $data['id_infomanager'],
                                'codigo'            => $data['codigo']
                            ]
                        );

                        if (!$resource->save()) {
                            $aResult['status'] = 1;
                            array_push($error, 'La Provincia ' . $provincia->nombre . ', codigo: ' . $provincia->codigo . ' no se pudo crear');
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = $validator->errors()->all();
                    }
                } else {
                    if (!$validator->fails()) {
                        $item->id_infomanager = $data['id_infomanager'];
                        $item->provincia = $data['provincia'];
                        $item->codigo = $data['codigo'];
                        
                        if (!$item->save()) {
                            $aResult['status'] = 1;
                            array_push($error, 'La Provincia ' . $provincia->nombre . ', codigo: ' . $provincia->codigo . ' no se pudo actualizar');
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
