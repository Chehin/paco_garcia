<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;

class NotasController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
        $this->fotos = ($request->input('fotos')?$request->input('fotos'):'all');
		$this->id_idioma = $request->input('idioma');
		
		$this->orden = $request->input('orden');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');
		$this->limit = $request->input('limit');
		$this->id_seccion = $request->input('id_seccion');
		$this->id_rel = $request->input('id_rel');
		$this->destacado = $request->input('destacado');
		
		$this->id = $request->input('id');
    }
	public function listado(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['nota'] = array();
			$aResult['data']['total'] = array();

			$limit = $this->limit;
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $currentPage = ($offset / $pageSize) + 1;
			$sort = $this->orden;
			$rand = false;
			if($sort=='rand'){
				$rand = true;
			}else{
				$sortDir = 'ASC';
				$sortCol = 'orden';
			}
			
			Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
			if($this->id_rel){
				$NotaRel = Note::find($this->id_rel);
				$aResult['data']['notaRel'] = array(
					'id' => $NotaRel->id_nota,
					'titulo' => $NotaRel->titulo
				);
				
				$aItems = Note::
				 select('editorial_notas.id_nota as id', 'editorial_notas.id_edicion','editorial_notas.antetitulo','editorial_notas.sumario','editorial_notas.titulo','editorial_notas.texto','editorial_notas.habilitado','editorial_notas.updated_at','editorial_notas.destacado','editorial_notas.icono','editorial_notas.ciudad','editorial_notas.icono')
				 ->join("editorial_relacion_notas as a","a.id_secundaria","=","editorial_notas.id_nota")
				 ->where('a.id_principal', $this->id_rel)
				 ->where('editorial_notas.habilitado', 1);
			}else{ 
			
				$aItems = Note::
				select('id_nota as id', 'id_edicion','antetitulo','sumario','titulo','texto','habilitado','updated_at','destacado','icono','ciudad', 'icono')
				->where('habilitado', 1)
				->where('id_seccion', $this->id_seccion)
				->where('id_edicion',$this->filterNote);
		
				$seccion = Util::getCategorie($this->id_seccion);
				$aResult['data']['seccion'] = $seccion;
			}
			
			if($this->destacado){
				$aItems = $aItems->where('editorial_notas.destacado',$this->destacado);
			}
			if($limit){
				$aItems = $aItems->limit($limit);
			}
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
			}
			$aItems = $aItems->paginate($pageSize);
			
			foreach ($aItems as $item) {
				if($this->fotos){
					$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
				}else{
					$aOItems = '';
				}
				//idioma texto
				if($this->id_idioma>0){
					$lItem = FeUtilController::getLenguage($item->id, $this->id_idioma);
				}

				$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
				$data = array(
					'id' => $item->id,
					'id_edicion' => $item->id_edicion,
					'titulo' => ($this->id_idioma>0 && $lItem?$lItem->titulo:$item->titulo),
					'sumario' => ($this->id_idioma>0 && $lItem?$lItem->sumario:$item->sumario),
					'ciudad' => ($this->id_idioma>0 && $lItem?$lItem->ciudad:$item->ciudad),
					'icono' => $item->icono,
					'updated_at' => $fecha,
					'fotos' => $aOItems
				);
				array_push($aResult['data']['nota'],$data);
			}
			$aResult['data']['total'] = $aItems->total();
			return response()->json($aResult);
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
	}
	public function nota(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();        
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
            $aItems = Note::
			select('id_nota')
			->where('id_edicion',$this->filterNote)
			->where('habilitado',1)
			->where('id_nota',$this->id)
			->first();
						
			//imagenes
			if($this->fotos){
				$aOItems = FeUtilController::getImages($aItems->id_nota,$this->fotos, $this->resource);
			}else{
				$aOItems = '';
			}
			//idioma texto
			$aItems = FeUtilController::getLenguage($aItems->id_nota, $this->id_idioma);
			
			$seccion = Util::getCategorie($aItems['id_seccion']);
			
			$aItems['seccion'] = $seccion?$seccion->seccion:'';

			//registro visita a la nota
			FeUtilController::newVisitor($aItems->id_nota, $aItems->titulo);
			
			$data = array(
				'nota' => $aItems,
				'fotos' => $aOItems
			);
			$aResult['data'] = $data;
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

}
