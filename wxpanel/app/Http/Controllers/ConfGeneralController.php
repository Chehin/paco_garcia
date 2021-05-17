<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;

class ConfGeneralController extends Controller
{
    use ResourceTraitController;
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'general';
        $this->resourceLabel = 'Configuración General';
        $this->modelName = 'App\AppCustom\Models\ConfGeneral';
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
                $modelName::select(
                        'id',
                        'tipo',
                        'valor',
                        'habilitado'
                    )
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('tipo','like',"%{$search}%")
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
        //
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
                        'valor' => 'required',
                    ], 
                    [
                        'valor.required' => 'El campo Valor es requerido',
                    ]
                )
                ;
                
                

                if (!$validator->fails()) {
                    $item->fill(
                        [
                            'valor'    => $request->input('valor')
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
