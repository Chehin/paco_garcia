<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;

use Carbon\Carbon;

class RelacionadoController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->fil_edicion = $request->input('fil_edicion');
		$this->forzar = $request->input('forzar');
		$this->orden = $request->input('orden');
		$this->id_relacion = $request->input('id_relacion');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');		
        $this->fotos = $request->input('fotos');
		$this->id_idioma = $request->input('idioma');
    }
	public function relacionado(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['nota'] = array();
			$aResult['data']['total'] = array();
			$aResult['data']['padre'] = array();
			
			$padre = Note::find($this->id_relacion);
			//idioma texto
			$padre = FeUtilController::getLenguage($padre->id_nota, $this->id_idioma);
						
			$aResult['data']['padre'] = $padre;
			
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $currentPage = ($offset / $pageSize) + 1;
			$sort = $this->orden;
			$rand = false;
			if($sort=='rand'){
				$rand = true;
			}else{
				$sortDir = $sort['dir'];
				$sortCol = $sort['col'];
			}
			
			Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
			
			$aItems = Note::
			 select('editorial_notas.id_nota')
			 ->join("editorial_relacion_notas as a","a.id_secundaria","=","editorial_notas.id_nota")
			 ->where('a.id_principal', $this->id_relacion)
			 ->where('editorial_notas.habilitado', 1);
			
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
			}
			if($this->fil_edicion){
				$aItems = $aItems->where('editorial_notas.id_edicion', $this->filterNote);
			}
			$aItems = $aItems->paginate($pageSize);
			if($aItems->total()==0 && $this->forzar){
				$aItems = Note::
				select('id_nota')
				->where('habilitado', 1)
				->where('id_edicion',$this->filterNote)
				->where('id_seccion','!=',2)
				->where('id_nota', '!=', $this->id_relacion)
				->inRandomOrder()
				->paginate($pageSize);
			}
			foreach ($aItems as $item) {
				if($this->fotos){
					$aOItems = FeUtilController::getImages($item->id_nota,$this->fotos, $this->resource);
				}else{
					$aOItems = '';
				}
				//idioma texto
				$item = FeUtilController::getLenguage($item->id_nota, $this->id_idioma);
				
				$data = array(
					'id' => $item->id_nota,
					'titulo' => $item->titulo,
					'sumario' => $item->sumario,
					'id_edicion' => $item->id_edicion,
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
}
