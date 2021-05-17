<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Mailling;
use App\AppCustom\Models\TemplatesAutomaticos;
use Illuminate\Pagination\Paginator;

class MaillingTiposController extends Controller
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
        
        $this->resource = 'maillingTipos';
        $this->resourceLabel = 'Mails Automaticos';
        $this->modelName = 'App\AppCustom\Models\Mailling';
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
            

            //Search filter 2 
            $search2 = \trim($request->input('sSearch_0'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        'mailling.id',
                        'mailling.templates_id_templates',                       
                        'mailling.nombre',
                        'mailling.asunto',
                        'mailling.habilitado',
                        'templates_automaticos.nombre as nombreTemplate'                        
                    )
                    ->join('templates_automaticos','templates_automaticos.id','=','mailling.templates_id_templates')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('mailling.nombre','like',"%{$search}%")
                    ;
                });
            }
            
            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();
                            
            
            $total = $aItems['total'];
            $aItems = $aItems['data'];
            
            //Cuento la cantidad de Imagenes por rubro
            //$this->putImgCnt($aItems);       
            
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

    public function create()
    {
        $this->aCustomViewData['aTemplate'] = TemplatesAutomaticos::all();
        
        return $this->createTrait();
    }

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
                    'templates_id_templates' => 'required'
                ], 
                [
                    'nombre.required' => 'El campo Nombre es requerido',
                    'templates_id_templates.required' => 'El template es requerido',
                ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El campo Nombre ya existe');
                }
            });

            if (!$validator->fails()) {
              if($request->input('templates_id_templates')){
                
                $texto=TemplatesAutomaticos::where('id','=',$request->input('templates_id_templates'))
                                            ->first();

                foreach ($request->input('templates_id_templates') as $t) {
                $resource = new $modelName(
                    [
                        'templates_id_templates'  => $t,
                        'nombre'                  => $request->input('nombre'),
                        'asunto'                  => $request->input('asunto'),
                        'texto'                   => $texto->template,
                        
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
               }
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
        $this->aCustomViewData['aTemplates'] = TemplatesAutomaticos::all();
        $this->aCustomViewData['aTemplate'] = Mailling::select(
                                                        'mailling.id',
                                                        'mailling.templates_id_templates',                       
                                                        'mailling.nombre',
                                                        'mailling.asunto',
                                                        'mailling.habilitado',
                                                        'mailling.texto',
                                                        'templates_automaticos.nombre as nombreTemplate' 
                                                        )                                               
                                                ->join('templates_automaticos','templates_automaticos.id','=','mailling.templates_id_templates')
                                                ->where('mailling.habilitado','=','1')
                                                ->where('mailling.id','=',$id)
                                                ->get();
        
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
                            
                            'nombre' => $request->input('nombre'),
                            'texto' => $request->input('content'),
                            
                            
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
