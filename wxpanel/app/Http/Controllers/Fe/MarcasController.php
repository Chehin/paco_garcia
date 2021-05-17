<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Marcas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;

class MarcasController extends Controller
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
            $aItems = Marcas::
			select('id','nombre')
			->where('habilitado',1)			
			->orderBy('nombre','asc');
			if ($this->destacado == 1) {
				$aItems = $aItems->where('destacado', $this->destacado);
			}
			$aItems = $aItems->get();
			foreach ($aItems as $item) {
				//imagenes
				if($this->fotos){
					$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
				}else{
					$aOItems = '';
				}
				//idioma texto
				if($this->id_idioma>0){
					$lItem = FeUtilController::getLenguage($item->id, $this->id_idioma);
				}
				$data = array(
					'id' => $item->id,
					'nombre' => ($this->id_idioma>0 && $lItem?$lItem->titulo:$item->nombre),
					'fotos' => $aOItems
				);
				array_push($aResult['data'],$data);
			}
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
	}

}