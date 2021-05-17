<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;

class EmpresaSisController extends Controller
{
    use ResourceTraitController {
        create as protected createTrait;
        edit as protected editTrait;
    }
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'empresaSis';
        $this->resourceLabel = 'La empresa';
        $this->modelName = \App\AppCustom\Models\EmpresaSis::class;
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
                        'name',
                        'name_org',
                        'habilitado'
                    )
					->where('id',$this->id_company)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('name','like',"%{$search}%")
                        ->orWhere('name_org','like',"%{$search}%")
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
            $this->putImgCnt($aItems);       
            
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $aResult = Util::getDefaultArrayResult();
		
		$modelName = $this->modelName;
        
		$item = $modelName::where('id', $this->id_company)->first();
        
        if ($item) {

            $aViewData = [
                'mode'  => 'edit',
                'item' => $item,
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
				'viewPrefix' => $this->viewPrefix,
				
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
        
            $item = $modelName::where('id', $this->id_company)->first();

            if ($item) {

                $validator = \Validator::make(
                    $request->all(), 
                     [
						'name' => "required",
						'name_org' => "required",
						'cuit' => 'required',
					], 
					[
						'name.required' => 'El campo Nombre es requerido',
						'name_org.required' => 'El campo Razón Social es requerido',
						'cuit.required' => 'El campo CUIT es requerido',
					]
                )
                ;

                if (!$validator->fails()) {
                    $item->fill(
                        [
							'name' => $request->input('name'),
							'name_org' => $request->input('name_org'),
							'email' => $request->input('email'),
							'telephone' => $request->input('telephone'),
							'cuit' => $request->input('cuit'),
							'iva' => $request->input('iva'),
							'domicilio' => $request->input('domicilio'),
							'web' => $request->input('web'),
							'facebook' => $request->input('facebook'),
							'twitter' => $request->input('twitter'),
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
	
	protected function commonCustomViewData()
    { 
		
                
    }

}
