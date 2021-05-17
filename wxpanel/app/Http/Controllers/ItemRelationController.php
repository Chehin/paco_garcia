<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\ItemRelated;
use DB;

class ItemRelationController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            //$currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 1;
                $sortDir = 'asc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            //Search filter seccion
			$search1 = \trim($request->input('sSearch_0'));
			
			if($search1=='' || $search1=='Notas'){
				$items0 = 
					Note::
						select(
						'editorial_notas.id_nota as id', 
						'editorial_notas.titulo',
						DB::raw('\'Notas\' as seccion'),
						DB::raw('\'news\' as resource')
						)
				;
			}
			if($search1=='' || $search1=='Etiquetas'){
				$items1 = 
					Etiquetas::
						select(
						'id', 
						'nombre as titulo',
						DB::raw('\'Etiquetas\' as seccion'),
						DB::raw('\'etiquetas\' as resource')
						)
				;
			}
			if($search1=='' || $search1=='Productos'){
				$items = 
					Productos::
						select(
						'id',
						'nombre as titulo',
						DB::raw('\'Productos\' as seccion'),
						DB::raw('\'productos\' as resource')
						)
				;
			}
			if($search1!=''){
				if(isset($items0)){
					$items = $items0->get();
				}elseif(isset($items1)){
					$items = $items1->get();
				}else{
					$items = $items->get();
				}
			}else{
				$items = $items
				->union($items0)
				->union($items1)
				->get();
			}

			$aItems = $items->toArray();
			
			if ($search) {
				$aItems = Util::inArrayGetAll($aItems, 'titulo', $search);
			}
			
			
			$itemsForCurrentPage = array_slice($aItems, $offset, $pageSize, true);

			$tot = count($aItems);
			
			$aResult['data'] = array_values($itemsForCurrentPage); //parche para datatables :(
            $aResult['recordsTotal'] = $tot;
            $aResult['recordsFiltered'] = $tot;
			

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
		
		$aParams = NoteRelatedUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.wrongRequest');
			
			return response()->json($aResult);
		}
		
        
		if ($this->user->hasAccess($aParams['resource'] . '.update')) {
			
			$item = Note::find($id);
			
			if ($item) {
				
				$alredyRelated = 
					ItemRelated::where('parent_id', $id)
						->where('parent_resource', $request->input('parent_resource'))
						->where('related_id', $request->input('related_id'))
						->where('related_resource', $request->input('related_resource'))
				;
				
				if (0 == $alredyRelated->count()) {
				
					$itemRelated = 
						new ItemRelated(
							[
								'parent_id' => $id,
								'parent_resource' => $request->input('parent_resource'),
								'related_id' => $request->input('related_id'),
								'related_resource' => $request->input('related_resource')
							]
						)
						;
					if (!$itemRelated->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Ya se ha relacionado este elemento';
				}
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
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
		
		$aParams = NoteRelatedUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.wrongRequest');
			
			return response()->json($aResult);
		}
		
        
		if ($this->user->hasAccess($aParams['resource'] . '.delete')) {
			
			$item = ItemRelated::find($id);
			
			if ($item) {
					
				if (!$item->delete()) {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.dbError');
				}
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}  else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		
		return response()->json($aResult);
    }
	
	
	public function itemsRelated(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();
		
		$aParams = NoteRelatedUtilController::getParameters($request);
		
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.wrongRequest');
			
			return response()->json($aResult);
		}
		
		
		if ($this->user->hasAccess($aParams['resource'] . '.view')) {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            //$currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 1;
                $sortDir = 'asc';
            } else {
				$sortCol = 'editorial_notas.' . $sortCol;
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

			
			$items = 
				ItemRelated::where('parent_id', $request->input('parent_id'))
					->where('parent_resource', $request->input('parent_resource'))
					->orderBy('related_resource')
					->get()
			;
			
			$aData = [];			
			foreach ($items	as $item) {
				switch ($item->related_resource) {
					case 'news':
						$data = Note::where('id_nota', $item->related_id)
						->select('titulo',DB::raw('"Notas" as seccion'))
						->get()
						->toArray();
						if($data){
							array_push(
								$aData,
								array_merge(
									['id' => $item->id],
									$data[0]
								)
							);
						}

						break;
					case 'etiquetas':
						$data = Etiquetas::where('id', $item->related_id)
						->select('nombre as titulo',DB::raw('"Etiquetas" as seccion'))
						->get()
						->toArray();
						if($data){
							array_push(
								$aData,
								array_merge(
									['id' => $item->id],
									$data[0]
								)
							);
						}
						break;
					case 'productos':
						$data = Productos::where('id', $item->related_id)
						->select('nombre as titulo',DB::raw('"Productos" as seccion'))
						->get()
						->toArray();
						if($data){
							array_push(
								$aData,
								array_merge(
									['id' => $item->id],
									$data[0]
								)
							);
						}
					break;
				}
			}
			
			if ($search) {
				$aData = Util::inArrayGetAll($aData, 'titulo', $search);
			}
            

            $itemsForCurrentPage = array_slice($aData, $offset, $pageSize, true);

			$tot = count($items);
			
			$aResult['data'] = array_values($itemsForCurrentPage); //parche para datatables :&
            $aResult['recordsTotal'] = $tot;
            $aResult['recordsFiltered'] = $tot;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
		
    }
	
}
