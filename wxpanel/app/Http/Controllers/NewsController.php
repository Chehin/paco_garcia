<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\EtiquetasNotas;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Category;

class NewsController extends Controller
{ 

	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
		
		parent::__construct($request);
				
		
        $this->resource = 'news';
		$this->resourceLabel = 'Contenidos';
		$this->modelName = 'App\AppCustom\Models\Note';
		$this->filterNote = \config('appCustom.MOD_NEWS_FILTER');
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
			//Search seccion 
            $search0 = \trim($request->input('sSearch_0'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $aOItems = 
                Note::
                    select(
                        'id_nota as id',
                        'id_seccion',
                        'icono',
                        'titulo',
                        'antetitulo',
						'sumario',
						'texto',
                        'orden',
                        'slider_texto',
                        'slider_mobile',
                        'destacado',
                        'sucursalEnvio',
                        'habilitado'
                        
                    )
                    ->where('id_edicion', $this->filterNote)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $aOItems->where(function($query) use ($search){
					$query
						->where('titulo','like',"%{$search}%")
						->orWhere('sumario','like',"%{$search}%")
					;
				});
            }
			
            if ($search0) {
                $aOItems->where(function($query) use ($search0){
					$query
						->where('id_seccion','like',"%{$search0}%")
					;
				});
            }

            $aOItems = $aOItems
                ->paginate($pageSize)
            ;

            $aItems = $aOItems->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
				$val['sumario']	= Util::truncateString(\strip_tags($val['sumario']));
                $val['seccion']	= ($val['id_seccion']?Category::find($val['id_seccion'])->seccion:'');
			});
			
            $total = $aItems['total'];
            $aItems = $aItems['data'];
			
			$this->putImgInfo($aItems);
			$this->putRelationCnt($aItems);

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
        $aResult = Util::getDefaultArrayResult();

        $aViewData = array(
            'mode' => 'add',
			'resource' => $this->resource,
            'resourceLabel' => $this->resourceLabel,
			'aCategories' => Util::getCategories()->lists('seccion', 'id_seccion')
        );
        
        

        $aResult['html'] = \View::make($this->viewPrefix . $this->resource.".".$this->resource."Edit")
            ->with('aViewData', $aViewData)
            ->render()
        ;

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
        
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                array(
                    'titulo' => 'required',
                    //'sumario' => 'required',
                    //'texto' => 'required',
                ), 
                array(
                    'titulo.required' => 'El título es requerido',
                    //'sumario.required' => 'El sumario es requerido',
                    //'texto.required' => 'El texto es requerido',
                )
            );

            if (!$validator->fails()) {
                $resource = new Note(
                    array(
                        'id_edicion' => $this->filterNote,
                        'titulo' => $request->input('titulo'),
                        'antetitulo' => $request->input('antetitulo'),
                        'sumario' => $request->input('sumario'),
                        'texto' => $request->input('texto'),
                        'keyword' => $request->input('keyword'),
                        'orden' => $request->input('orden'),
						'icono' => $request->input('icono'),
						'id_video' => $request->input('video_id'),
						'id_seccion' => $request->input('id_seccion'),
						'id_subseccion' => $request->input('id_subseccion'),
						'ciudad' => $request->input('ciudad'),
                        'pais' => $request->input('pais'),
                        'email' => $request->input('email')                            
                            
							
							
                    )
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                // Relaciono el notas con las etiquetas
                $aAllEtiquetas = EtiquetasNotas::get();
                if (!$aAllEtiquetas->isEmpty()) {
                    $aOpt = $request->input('etiquetasBlogIds');
                
					if($aOpt){
						array_walk($aOpt, function($value) use ($resource){
                            $etiqueta = EtiquetasNotas::where('id',$value)->first();
							if($etiqueta) {
								$etiqueta->blog()->attach($resource);
							}
						});
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        
        $item = 
            Note::select('id_nota as id', 'editorial_notas.*')
                ->where('id_nota', $id)
                ->first()
            ;
        
        $etiquetas = Note::find($id)->etiquetas()->get();
        $etiquetasBlog = Note::find($id)->etiquetasBlog()->get();

        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'aItem' => $item->toArray(),
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
                'etiquetas'  => $etiquetas,
                'etiquetasBlog'  => $etiquetasBlog,
				'aCategories' => Util::getCategories()->lists('seccion', 'id_seccion')
            );

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
        
            $item = Note::find($id);

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
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable1')) {
					
                    $item->destacado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable2')) {
					
                    $item->slider_texto = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable3')) {
					
                    $item->slider_mobile = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }

                //Validation
                $validator = \Validator::make(
                    $request->all(), 
                    array(
                    'titulo' => 'required',
                    //'sumario' => 'required',
                    //'texto' => 'required',
                    ), 
                    array(
                        'titulo.required' => 'El título es requerido',
                        //'sumario.required' => 'El sumario es requerido',
                        //'texto.required' => 'El texto es requerido',
                    )
                );

                if (!$validator->fails()) {
                    $item->fill(
                        array(
                            'titulo' => $request->input('titulo'),
							'antetitulo' => $request->input('antetitulo'),
                            'sumario' => $request->input('sumario'),
                            'texto' => $request->input('texto'),
                            'id_seccion' => $request->input('id_seccion'),
							'id_subseccion' => $request->input('id_subseccion'),
                            'ciudad' => $request->input('ciudad'),
                            'pais' => $request->input('pais'),
                            'keyword' => $request->input('keyword'),
                            'orden' => $request->input('orden'),
							'id_video' => $request->input('video_id'),
							'icono' => $request->input('icono')
                        )
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.delete')) {
        
            $item = Note::find($id);

            if ($item) {
                if (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                // Borro las relaciones de las Etiquetas 
                $aAllEtiquetas = EtiquetasNotas::get();
                if (!$aAllEtiquetas->isEmpty()) {
                    
                    foreach ($aAllEtiquetas as $etiqueta) {
                        $etiqueta->blog()->detach($item);
                    }
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
