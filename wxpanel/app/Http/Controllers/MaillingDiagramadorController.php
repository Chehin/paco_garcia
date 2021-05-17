<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\TemplatesEditables;
use App\AppCustom\Models\MaillingCampanias;
use App\AppCustom\Models\Campaign;
use App\AppCustom\Models\CampaignListas;
use App\AppCustom\Models\MktListas;
use Illuminate\Pagination\Paginator;
use DB;

class MaillingDiagramadorController extends Controller
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
        
        $this->resource = 'maillingDiagramador';
        $this->resourceLabel = 'Campañas';
        $this->modelName = 'App\AppCustom\Models\Campaign';
        $this->viewPrefix = 'mailling.';
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
                $modelName::select(DB::raw('id, nombre,habilitado,DATE_FORMAT(created_at, "%d-%m-%Y") as fecha,DATE_FORMAT(fechaenvio, "%d-%m-%Y %H:%i:%s") as fechaenvio'))
                    ->orderBy($sortCol, $sortDir)
            ;
            
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
        $this->aCustomViewData['template'] = TemplatesEditables::select('id','nombre')->lists('nombre','id');
        $this->aCustomViewData['lista'] = MktListas::select('id','nombre')->where('habilitado', '=', '1')->lists('nombre','id');
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
                        'templates_id_templates' => 'required',
                        'asunto' => 'required',
                        'remitente' => 'required',
                        'fecha' => 'required',
                        'hora' => 'required'
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'templates_id_templates.required' => 'El campo Tipo  es requerido',
                        'asunto.required' => 'El campo Asunto es requerido',
                        'remitente.required' => 'El remitente es requerido',
                        'fecha.required' => 'El campo Fecha es requerido',
                        'hora.required' => 'El campo Hora es requerido',
                    ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El nombre elegido ya existe');
                }
            });

            if (!$validator->fails()) {

                $resource = new $modelName(
                    [
                        'nombre'       => $request->input('nombre'),
                        'fecha'        => ($request->input('fecha')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha')) : null,
                        'hora'         => $request->input('hora')
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                $idCampaign=Campaign::all()->last();
                $conta=0;
                if($request->input('lista')){
                    foreach ($request->input('lista') as $l) {
                            $conta=$conta + Util::contador($l);
                            $resourceListas = new CampaignListas(
                                [
                                    'id_campaign'     => $idCampaign->id,
                                    'id_lista'        => $l
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    }
                }
						
				//se cambia el charset para los emojis
				\DB::statement("SET NAMES 'utf8mb4'");

                $resourceMailling = new MaillingCampanias(
                    [
                        'templates_id_templates' => $request->input('templates_id_templates'),
                        'id_campania'   => $idCampaign->id,
                        'nombre'        => $request->input('nombre'),
                        'remitente'     => $request->input('remitente'),
                        'asunto'        => $request->input('asunto'),
                        'texto'         => $request->input('content'),
                        'enviados'      => $conta
                    ]
                )
                ;

                if (!$resourceMailling->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
				
				//se vuelve a utf8
				\DB::statement("SET NAMES 'utf8'");

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
        $this->aCustomViewData['template'] = TemplatesEditables::select('id','nombre')->lists('nombre','id');
        $this->aCustomViewData['lista'] = MktListas::select('id','nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aLista'] = Util::lista($id);
		//se cambia el charset para los emojis
		\DB::statement("SET NAMES 'utf8mb4'");
        $this->aCustomViewData['aData'] = MaillingCampanias::where('id_campania','=',$id)->first();
		//se vuelve a utf8
		\DB::statement("SET NAMES 'utf8'");
        
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
                        'nombre' => 'required',
                        'templates_id_templates' => 'required',
                        'asunto' => 'required',
                        'remitente' => 'required',
                        'fecha' => 'required',
                        'hora' => 'required'
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'templates_id_templates.required' => 'El campo Tipo  es requerido',
                        'asunto.required' => 'El campo Asunto es requerido',
                        'remitente.required' => 'El remitente es requerido',
                        'fecha.required' => 'El campo Fecha es requerido',
                        'hora.required' => 'El campo Hora es requerido',
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
                            'nombre'       => $request->input('nombre'),
                            'fecha'        => ($request->input('fecha')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha')) : null,
                            'hora'         => $request->input('hora')
                            
                        ]
                    )
                    ;

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                
                $conta=0;
                if($request->input('lista')){
                    CampaignListas::where('id_campaign','=',$id)->delete();
                    foreach ($request->input('lista') as $l) {
                        $conta=$conta + Util::contador($l);
                        $resourceListas = new CampaignListas(
                                [
                                    'id_campaign'     => $id,
                                    'id_lista'        => $l
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    }                  
                }
				
				//se cambia el charset para los emojis
				\DB::statement("SET NAMES 'utf8mb4'");

                $itemMailling=[
                        'templates_id_templates' => $request->input('templates_id_templates'),
                        'id_campania'   => $id,
                        'nombre'        => $request->input('nombre'),
                        'asunto'        => $request->input('asunto'),
                        'remitente'     => $request->input('remitente'),
                        'texto'         => $request->input('content'),
                        'enviados'      => $conta
                ];

                MaillingCampanias::where('id_campania','=',$id)->update($itemMailling);


                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = $validator->errors()->all();
                }
				
				//se vuelve a utf8
				\DB::statement("SET NAMES 'utf8'");

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
