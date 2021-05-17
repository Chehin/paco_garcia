<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Preguntas;
use App\Http\Requests;

class PreguntasRelatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	//Related notes
    public function index(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();
		
		if ($this->user->hasAccess('productos' . '.view')) {
            
            $producto = Productos::find($request->input('resource_id'));

            if ($producto) {            
	            $pageSize = $request->input('iDisplayLength', 10);
	            $offset = $request->input('iDisplayStart');
	            $currentPage = ($offset / $pageSize) + 1;

	            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
	                $sortCol = 'id';
	                $sortDir = 'asc';
	            } else {
	                $sortDir = $request->input('sSortDir_0');
	            }

	            //Search filter
	            $search = \trim($request->input('sSearch'));

	            Paginator::currentPageResolver(function() use ($currentPage) {
	                return $currentPage;
	            });
	            $items = Preguntas::
	                    select(
							'inv_preguntas.id',
							'inv_preguntas.nickname_meli',
	                        'inv_preguntas.pregunta_meli',
	                        'inv_preguntas.fecha_pregunta',
	                        'inv_preguntas.estado'
	                    )
						->where('inv_preguntas.id_meli', $producto->id_meli)
	                    ->orderBy($sortCol, $sortDir)
	            ;
	            if ($search) {
									
					$items
						->where(function($query) use ($search) {
							$query
								->where('inv_preguntas.nickname_meli','=',"%{$search}%")
								->orWhere('inv_preguntas.pregunta_meli','like',"%{$search}%")
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
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
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

        $item = Preguntas::find($id);

        if ($item) {
            $aResult['data'] = $item->toArray();
            $aResult['data']['id'] = $aResult['data']['id'];
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
                
        return response()->json($aResult);
    }
}
