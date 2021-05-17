<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\NoteLanguage;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use Sentinel;

class NewsNoteLanguageController extends Controller
{
	
	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(NewsController $res)
    {
        $this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
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
                $sortCol = 1;
                $sortDir = 'asc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $aOItems = 
                NoteLanguage::
                    select(
                        'editorial_notas_idioma.id',
                        'editorial_notas_idioma.id_idioma',
                        'editorial_notas_idioma.id_nota',
                        'b.idioma',
                        'editorial_notas_idioma.titulo',
						'editorial_notas_idioma.sumario',
						'editorial_notas_idioma.keyword',
						'editorial_notas_idioma.texto',
						'editorial_notas_idioma.habilitado',
                        'editorial_notas_idioma.updated_at'
                    )
                    ->where('editorial_notas_idioma.id_nota', $request->input('id'))
                    ->join('front_idiomas as b', 'b.id_idioma', '=', 'editorial_notas_idioma.id_idioma')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
				
				$aOItems->where(function($query) use ($search){
					$query
						->where('editorial_notas_idioma.titulo','like',"%{$search}%")
						->where('editorial_notas_idioma.sumario','like',"%{$search}%")
						->orWhere('b.idioma','like',"%{$search}%")
					;
				});
                
            }

            $aOItems = $aOItems
                ->paginate($pageSize)
            ;

            $aItems = $aOItems->toArray();
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
        //
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
                [
					'id_nota' => 'required',
					'id_idioma' => 'required|integer|min:1|unique:editorial_notas_idioma,id_idioma,NULL,id_nota,id_nota,'.$request->input('id_nota'),
					'titulo' => 'required',
					'texto' => 'required',
                ], 
                [
                    'id_nota.required' => 'ID nota incorrecto',
                    'id_idioma.unique' => 'La idioma ingresado ya existe para esta nota',
                    'id_idioma.min' => 'El idioma no es válido',
                    'titulo.required' => 'El título es obligatorio',
					'texto.required' => 'El texto es obligatorio',
                ]
            );
			
			$validator->after(function($validator) use ($request) {
				if (!empty($request->input('texto')) && empty(\trim(\strip_tags($request->input('texto'))))) {
					$validator->errors()->add('texto', 'El campo Texto no puede contener sólo etiquetas HTML o espacios en blanco');
				}
			});

            if (!$validator->fails()) {
                $resource = new NoteLanguage(
					[
						'id_idioma' => $request->input('id_idioma'),
						'id_nota' => $request->input('id_nota'),
						'titulo' => $request->input('titulo'),
						'sumario' => $request->input('sumario'),
						'keyword' => $request->input('keyword'),
						'texto' => $request->input('texto'),
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
        
        $item = NoteLanguage::find($id);
                
        if ($item) {
            $aResult['data'] = $item->toArray();
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
        
            $item = NoteLanguage::find($id);

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

                //Validation
                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'id_nota' => 'required',
                        'id_idioma' => 'required|integer|min:1|unique:editorial_notas_idioma,id_idioma,'.$item->id.',id,id_nota,'.$request->input('id_nota'),
                        'titulo' => 'required',
						'texto' => 'required',
                    ], 
                    [
						'id_nota.required' => 'ID nota incorrecto',
						'id_idioma.unique' => 'La idioma ingresado ya existe para esta nota',
						'id_idioma.min' => 'El idioma no es válido',
						'titulo.required' => 'El título es obligatorio',
						'texto.required' => 'El texto es obligatorio',
					]
                );
				
				$validator->after(function($validator) use ($request) {
					if (!empty($request->input('texto')) && empty(\trim(\strip_tags($request->input('texto'))))) {
						$validator->errors()->add('texto', 'El campo Texto no puede contener sólo etiquetas HTML o espacios en blanco');
					}
				});

                if (!$validator->fails()) {
                    $item->fill(
                        [
							'id_idioma' => $request->input('id_idioma'),
							'titulo' => $request->input('titulo'),
							'sumario' => $request->input('sumario'),
							'keyword' => $request->input('keyword'),
							'texto' => $request->input('texto'),
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
        
            $item = NoteLanguage::find($id);

            if ($item) {
                if (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
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
