<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\Note;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;

class NewsletterController extends NewsController
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
                
        
        $this->resource = 'newsletter';
        $this->resourceLabel = 'Newsletter';
        $this->filterNote = \config('appCustom.MOD_NEWSLETTER_FILTER');
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

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $aOItems = 
                Note::
                    select(
                        'id_nota as id',
                        'email',
                        'habilitado'
                        
                    )
                    ->where('id_edicion', $this->filterNote)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $aOItems->where(function($query) use ($search){
                    $query
                        ->where('email','like',"%{$search}%")
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
        $aResult = Util::getDefaultArrayResult();

        $aViewData = array(
            'mode' => 'add',
            'resource' => $this->resource,
            'resourceLabel' => $this->resourceLabel,
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
                    'email' => 'required|unique:editorial_notas,email',
                ), 
                array(
                    'email.required' => 'El Email es obligatorio',
                    'email.unique' => 'El Email ingresado ya existe',
                )
            );

            if (!$validator->fails()) {
                $resource = new Note(
                    array(
                        'id_edicion' => $this->filterNote,
                        'email' => $request->input('email'),
                    )
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
        
        $item = 
            Note::select('id_nota as id', 'editorial_notas.email')
                ->where('id_nota', $id)
                ->first()
            ;
        
        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'aItem' => $item->toArray(),
                'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
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

                //Validation
                $validator = \Validator::make(
                    $request->all(), 
                    array(
                    'email' => 'required',
                    ), 
                    array(
                        'email.required' => 'El Email es obligatorio',
                    )
                );

                if (!$validator->fails()) {
                    $item->fill(
                        array(
                            'email' => $request->input('email'),
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
