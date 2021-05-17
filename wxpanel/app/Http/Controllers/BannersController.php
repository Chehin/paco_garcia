<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\BannersTipos;
use App\AppCustom\Models\BannersPosiciones;
use App\AppCustom\Models\BannersClientes;
use App\AppCustom\Models\Etiquetas;

class BannersController extends Controller
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
        
        $this->resource = 'banners';
        $this->resourceLabel = 'Banners';
        $this->modelName = 'App\AppCustom\Models\Banners';
        $this->viewPrefix = 'banners.';
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
                        'banners_banners.id',
                        'banners_tipos.nombre as tipo',
                        \DB::raw('CONCAT(banners_clientes.apellido, ", ", banners_clientes.nombre) as cliente'),
                        'banners_posiciones.nombre as posicion',
                        'banners_banners.nombre',
                        'banners_banners.inicio',
                        'banners_banners.fin',
                        'banners_banners.impresiones',
                        'banners_banners.clicks',
                        'banners_banners.habilitado'
                    )
                    ->join('banners_tipos','banners_tipos.id','=','banners_banners.id_tipo')
                    ->join('banners_clientes','banners_clientes.id','=','banners_banners.id_cliente')
                    ->join('banners_posiciones','banners_posiciones.id','=','banners_banners.id_posicion')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('banners_banners.nombre','like',"%{$search}%")
                        ->orWhere('banners_tipos.nombre','like',"%{$search}%")
                        ->orWhere('banners_clientes.nombre','like',"%{$search}%")
                        ->orWhere('banners_clientes.apellido','like',"%{$search}%")
                        ->orWhere('banners_posiciones.nombre','like',"%{$search}%")
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
        $this->aCustomViewData['aTipos'] = BannersTipos::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        $this->aCustomViewData['aClientes'] = BannersClientes::select(\DB::raw('CONCAT(apellido, ", ", nombre) as nombre'),'id')->where('habilitado','=','1')->lists('nombre','id');
        $this->aCustomViewData['aPosiciones'] = BannersPosiciones::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        
        $this->aCustomViewData['aEtiquetas'] = Etiquetas::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
        
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
                    'id_tipo' => 'required',
                    'id_cliente' => 'required',
                    'id_posicion' => 'required',
                ], 
                [
                    'nombre.required' => 'El campo Nombre es requerido',
                    'id_tipo.required' => 'El campo Tipo es requerido',
                    'id_cliente.required' => 'El campo Cliente es requerido',
                    'id_posicion.required' => 'El campo Posición es requerido',
                ]
            );

            if (!$validator->fails()) {

                if (!empty($request->input('files'))) {
                    $file = $request->input('files');
                    $sourceDir = sys_get_temp_dir() . '/';
                    if (file_exists($sourceDir . $file)) {
                        if (!copy($sourceDir . $file, \config('appCustom.UPLOADS_BANNERS') . $file)) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.subirError');
                        }
                    }
                }

                $resource = new $modelName(
                    [
                        'nombre'        => $request->input('nombre'),
                        'id_tipo'       => $request->input('id_tipo'),
                        'id_cliente'    => $request->input('id_cliente'),
                        'id_posicion'   => $request->input('id_posicion'),
                        'id_etiqueta'   => $request->input('id_etiqueta'),
                        'link'          => $request->input('link'),
                        'target'        => $request->input('target'),
                        'anchopopup'    => $request->input('anchopopup'),
                        'altopopup'     => $request->input('altopopup'),
                        'anchoflash'    => $request->input('anchoflash'),
                        'altoflash'     => $request->input('altoflash'),
                        'texto'         => $request->input('texto'),
                        'banners'       => $request->input('files'),
                        'inicio'        => ($request->input('inicio')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('inicio')) : null,
                        'fin'           => ($request->input('fin')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fin')) : null,
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
        $aResult = Util::getDefaultArrayResult();
        
        $modelName = $this->modelName;
        
        $item = $modelName::find($id);
        
        if ($item) {
            if (!empty($item->banners)) {
                $nameCoded = explode('_', $item->banners);
                $item->bannersDecoded = base64_decode($nameCoded[1]);    
            }

            $this->aCustomViewData['aTipos'] = BannersTipos::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
            $this->aCustomViewData['aClientes'] = BannersClientes::select(\DB::raw('CONCAT(apellido, ", ", nombre) as nombre'),'id')->where('habilitado','=','1')->lists('nombre','id');
            $this->aCustomViewData['aPosiciones'] = BannersPosiciones::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');

            $this->aCustomViewData['aEtiquetas'] = Etiquetas::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');

            $aViewData = [
                'mode'  => 'edit',
                'item' => $item,
                'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
                
                'aCustomViewData' => (isset($this->aCustomViewData) ? $this->aCustomViewData : null),
            ];

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "Edit")
                ->with('aViewData', $aViewData)
                ->render()
            ;
            
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
                        'nombre' => 'required',
                        'id_tipo' => 'required',
                        'id_cliente' => 'required',
                        'id_posicion' => 'required',
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'id_tipo.required' => 'El campo Tipo es requerido',
                        'id_cliente.required' => 'El campo Cliente es requerido',
                        'id_posicion.required' => 'El campo Posición es requerido',
                    ]
                )
                ;                            
                

                if (!$validator->fails()) {

                    if (!empty($request->input('filesDeleted'))) {
                        $sourceDir = \config('appCustom.UPLOADS_BANNERS');
                        $fileName = $request->input('filesDeleted');
                        if (file_exists($sourceDir . $fileName)) {
                            unlink($sourceDir . $fileName);
                        }
                        $item->banners = '';
                    }
                    $aResult['files'] = $request->input('filesDeleted');

                    if (!empty($request->input('files'))) {
                        $file = $request->input('files');
                        $sourceDir = sys_get_temp_dir() . '/';
                        if (file_exists($sourceDir . $file)) {
                            if (!copy($sourceDir . $file, \config('appCustom.UPLOADS_BANNERS') . $file)) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.subirError');
                            }
                            $item->banners = $request->input('files');
                        }
                    }
                    $aResult['files'] = $request->input('files');                    

                    $item->fill(
                        [
                            'nombre'        => $request->input('nombre'),
                            'id_tipo'       => $request->input('id_tipo'),
                            'id_cliente'    => $request->input('id_cliente'),
                            'id_posicion'   => $request->input('id_posicion'),
                            'id_etiqueta'   => $request->input('id_etiqueta'),
                            'link'          => $request->input('link'),
                            'target'        => $request->input('target'),
                            'anchopopup'    => $request->input('anchopopup'),
                            'altopopup'     => $request->input('altopopup'),
                            'anchoflash'    => $request->input('anchoflash'),
                            'altoflash'     => $request->input('altoflash'),
                            'texto'         => $request->input('texto'),
                            'inicio'        => ($request->input('inicio')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('inicio')) : null,
                            'fin'           => ($request->input('fin')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fin')) : null,
                            
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

    function upload(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        $file = $request->file('file');
        $fileName = time();
        $fileName .= '_' . base64_encode($file->getClientOriginalName());
        $file->move(sys_get_temp_dir(),$fileName);
        return $fileName;
    }
}
