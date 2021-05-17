<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\TemplatesEditables;
use App\AppCustom\Models\MaillingTesting;
use App\AppCustom\Models\CampaignTesting;
use App\AppCustom\Models\CampaignTestingLista;
use App\AppCustom\Models\MktListas;
use Illuminate\Pagination\Paginator;
use DB;

class MaillingCampaniasController extends Controller
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
        
        $this->resource = 'maillingCampanias';
        $this->resourceLabel = 'Campañas';
        $this->modelName = 'App\AppCustom\Models\CampaignTesting';
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
                        'templates_id_templatesA' => 'required',
                        'asuntoA' => 'required',
                        'remitentea' => 'required',
                        'fechaA' => 'required',
                        'horaA' => 'required'
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'templates_id_templatesA.required' => 'El campo Tipo  es requerido',
                        'asuntoA.required' => 'El campo Asunto es requerido',
                        'remitentea.required' => 'El remitente es requerido',
                        'fechaA.required' => 'El campo Fecha es requerido',
                        'horaA.required' => 'El campo Hora es requerido',
                    ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El nombre elegido ya existe');
                }
            });

            if (!$validator->fails()) {
            
                //save campaña A
                $resource = new $modelName(
                    [
                        'nombre'       => $request->input('nombre')
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                $idCampaign=CampaignTesting::all()->last();
				
				//se cambia el charset para los emojis
				\DB::statement("SET NAMES 'utf8mb4'");

                $resourceMaillingA = new MaillingTesting(
                    [
                        'templates_id_templates' => $request->input('templates_id_templatesA'),
                        'id_campania'   => $idCampaign->id,
                        'id_ab'         => 'a',
                        'asunto'        => $request->input('asuntoA'),
                        'remitente'     => $request->input('remitentea'),
                        'texto'         => $request->input('contentA'),
                        'fecha'        => ($request->input('fechaA')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fechaA')) : null,
                        'hora'         => $request->input('horaA')
                    ]
                )
                ;
                
                if (!$resourceMaillingA->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
				
				

                $idMaillingA=MaillingTesting::all()->last();
                $conta=0;
                if($request->input('listaA')){
                    foreach ($request->input('listaA') as $l) {
                            $conta=$conta + Util::contador($l);
                            $resourceListas = new CampaignTestingLista(
                                [
                                    'id_campaign'     => $idCampaign->id,
                                    'id_lista'        => $l,
                                    'id_mailling'     => $idMaillingA->id,
                                    'id_ab'           => 'a'
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    }
                }

                $itemMaillingA=[
                        'enviados' => $conta
                ];

                MaillingTesting::where('id','=', $idMaillingA->id)->update($itemMaillingA);
                //save campaña B
               $resourceMaillingB = new MaillingTesting(
                    [
                        'templates_id_templates' => $request->input('templates_id_templatesB'),
                        'id_campania'   => $idCampaign->id,
                        'id_ab'         => 'b',
                        'asunto'        => $request->input('asuntoB'),
                        'remitente'     => $request->input('remitenteb'),
                        'texto'         => $request->input('contentB'),
                        'fecha'        => ($request->input('fechaB')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fechaB')) : null,
                        'hora'         => $request->input('horaB')
                    ]
                )
                ;

                if (!$resourceMaillingB->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
				
				//se vuelve a utf8
				\DB::statement("SET NAMES 'utf8'");

                $idMaillingB=MaillingTesting::all()->last();

                $contb=0;
                if($request->input('listaB')){
                    foreach ($request->input('listaB') as $l) {
                            $contb=$contb + Util::contador($l);
                            $resourceListas = new CampaignTestingLista(
                                [
                                    'id_campaign'     => $idCampaign->id,
                                    'id_lista'        => $l,
                                    'id_mailling'     => $idMaillingB->id,
                                    'id_ab'           => 'b'
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    }
                }  
            
                $itemMaillingB=[
                        'enviados' => $contb
                ];

                MaillingTesting::where('id','=', $idMaillingB->id)->update($itemMaillingB);

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
		//se cambia el charset para los emojis
		\DB::statement("SET NAMES 'utf8mb4'");
        $this->aCustomViewData['template'] = TemplatesEditables::select('id','nombre')->lists('nombre','id');
        $this->aCustomViewData['lista'] = MktListas::select('id','nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aDataA'] = MaillingTesting::where('id_campania','=',$id)->first();
        $this->aCustomViewData['aListaA'] = Util::listaAB($id,$this->aCustomViewData['aDataA']->id);
        $this->aCustomViewData['aDataB'] = MaillingTesting::where('id_campania','=',$id)->orderBy('id','desc')->first();
        $this->aCustomViewData['aListaB'] = Util::listaAB($id,$this->aCustomViewData['aDataB']->id);
		//se vuelve a utf8
		//\DB::statement("SET NAMES 'utf8'");
		
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
                        'templates_id_templatesA' => 'required',
                        'asuntoA' => 'required',
                        'remitentea' => 'required',
                        'fechaA' => 'required',
                        'horaA' => 'required'
                    ], 
                    [
                        'nombre.required' => 'El campo Nombre es requerido',
                        'templates_id_templatesA.required' => 'El campo Tipo  es requerido',
                        'asuntoA.required' => 'El campo Asunto es requerido',
                        'remitentea' => 'El remitente es requerido',
                        'fechaA.required' => 'El campo Fecha es requerido',
                        'horaA.required' => 'El campo Hora es requerido',
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
                            'nombre'       => $request->input('nombre')                            
                        ]
                    )
                    ;

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
					
				//se cambia el charset para los emojis
				\DB::statement("SET NAMES 'utf8mb4'");
                    
            //update campaña A
                $itemMailling=[
                        'templates_id_templates' => $request->input('templates_id_templatesA'),
                        'id_campania'   => $id,
                        'asunto'        => $request->input('asuntoA'),
                        'remitente'     => $request->input('remitentea'),
                        'texto'         => $request->input('contentA'),
                        'fecha'         => ($request->input('fechaA')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fechaA')) : null,
                        'hora'          => $request->input('horaA')
                ];

                MaillingTesting::where('id','=',$request->input('idA'))->update($itemMailling);
                
                $conta=0;
                if($request->input('listaA')){                   
                    CampaignTestingLista::where('id_mailling','=',$request->input('idA'))->delete();
                    foreach ($request->input('listaA') as $l) {
                        $conta=$conta + Util::contador($l);
                        $resourceListas = new CampaignTestingLista(
                                [
                                    'id_campaign'     => $id,
                                    'id_lista'        => $l,
                                    'id_mailling'     => $request->input('idA'),
                                    'id_ab'           => 'a'
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    } 
                    
                    $itemMaillingA=[
                        'enviados' => $conta
                    ];
                    MaillingTesting::where('id_campania','=', $id)->where('id_ab','=', 'a')->update($itemMaillingA);
                }

            // update campaña B    

             $itemMailling=[
                        'templates_id_templates' => $request->input('templates_id_templatesB'),
                        'id_campania'   => $id,
                        'asunto'        => $request->input('asuntoB'),
                        'remitente'     => $request->input('remitenteb'),
                        'texto'         => $request->input('contentB'),
                        'fecha'         => ($request->input('fechaB')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fechaB')) : null,
                        'hora'          => $request->input('horaB')
                ];

                MaillingTesting::where('id','=',$request->input('idB'))->update($itemMailling);
                
                $contb=0;
                if($request->input('listaB')){                    
                    CampaignTestingLista::where('id_mailling','=',$request->input('idB'))->delete();
                    foreach ($request->input('listaB') as $l) {
                        $contb=$contb + Util::contador($l);
                        $resourceListas = new CampaignTestingLista(
                                [
                                    'id_campaign'     => $id,
                                    'id_lista'        => $l,
                                    'id_mailling'     => $request->input('idB'),
                                    'id_ab'           => 'b'
                                ]
                            )
                            ;
                            if (!$resourceListas->save()) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = \config('appCustom.messages.dbError');
                            }
                    }  
                    
                    $itemMaillingB=[
                        'enviados' => $contb
                    ];

                    MaillingTesting::where('id_campania','=', $id)->where('id_ab','=', 'b')->update($itemMaillingB);
                }
				
				//se vuelve a utf8
				\DB::statement("SET NAMES 'utf8'");
//***********************

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
