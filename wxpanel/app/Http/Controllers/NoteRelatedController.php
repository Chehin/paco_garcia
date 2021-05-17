<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\NoteRelated;
use Sentinel;


class NoteRelatedController extends Controller
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
		
		$aParams = NoteRelatedUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
		
		
		if ($this->user->hasAccess($aParams['resource'] . '.view')) {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 1;
                $sortDir = 'asc';
            } else {
				$sortCol = 'editorial_notas.' . $sortCol;
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
                        'editorial_notas.id_nota as id',
                        'editorial_notas.titulo',
						'editorial_notas.texto',
						'updated_at',
						'a.id_principal',
						'a.id_secundaria',
						'b.titulo as edicion'
                    )
					->join("editorial_relacion_notas as a","a.id_secundaria","=","editorial_notas.id_nota")
					->join("editorial_ediciones as b", "editorial_notas.id_edicion","=", "b.id_edicion")
					->where('a.id_principal', $request->input('id'))
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
				$aOItems
                    ->where(function($query) use ($search) {
                        $query
                            ->where('editorial_notas.titulo','like',"%{$search}%")
							->orWhere('b.titulo','like',"%{$search}%")
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
        //
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
		
		$aParams = NoteRelatedUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
		
		list($primaryId, $noteId) = explode('_', $id);
		
        
		if ($this->user->hasAccess($aParams['resource'] . '.update')) {
			
			$item = Note::find($primaryId);
			
			if ($item) {
				
				$alredyRelated = 
					NoteRelated::where('id_principal', $primaryId)
						->where('id_secundaria', $noteId)
				;
				
				if (0 == $alredyRelated->count()) {
				
					$noteRelated = 
						new NoteRelated(
							[
								'id_principal' => $primaryId,
								'id_secundaria' => $noteId,
							]
						)
						;
					if (!$noteRelated->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Ya se ha relacionado esta nota';
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
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
		
		list($primaryId, $noteId) = explode('_', $id);
		
        
		if ($this->user->hasAccess($aParams['resource'] . '.update')) {
			
			$item = Note::find($primaryId);
			
			if ($item) {
				
				$alredyRelated = 
					NoteRelated::where('id_principal', $primaryId)
						->where('id_secundaria', $noteId)
						
				;
				
				if ($alredyRelated->count() > 0) {
					
					if (!$alredyRelated->delete()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Las notas no están relacionadas';
				}
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}
		
		return response()->json($aResult);
    }
}
