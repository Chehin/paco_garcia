<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;

class NoteController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		//if ($request->user()->can('ckeckCustomPrivs', static::RESOURCE . '.view')) {
            
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
                Note::
					select(
					'editorial_notas.id_nota as id', 
					'editorial_notas.id_edicion',
					'editorial_notas.antetitulo',
					'editorial_notas.sumario',
					'editorial_notas.titulo',
					'editorial_notas.texto',
					'editorial_notas.habilitado',
					'editorial_notas.updated_at',
					'editorial_notas.destacado',
					'b.titulo as edicion'
					)
					->join("editorial_ediciones as b", "editorial_notas.id_edicion","=", "b.id_edicion")
					->where('editorial_notas.habilitado', 1)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $aOItems->where('editorial_notas.titulo','like',"%{$search}%")
                    ->orWhere('b.titulo','like',"%{$search}%")
                ;
            }

            $aOItems = $aOItems
                ->paginate($pageSize)
            ;

            $aItems = $aOItems->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
				$val['texto']	= Util::truncateString(\strip_tags($val['texto']));
			});
			
            $total = $aItems['total'];
            $aItems = $aItems['data'];

            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        /*} else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }*/

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
