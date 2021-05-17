<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\Etiquetas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\ItemRelated;
use App\AppCustom\Models\ProductosEtiquetas;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Image;
use DB;

class SliderController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->id_idioma = $request->input('idioma');
    }
	public function slider(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$array_slider = array(); 
			//slider news
			$slider_slider = Note::select('id_nota','titulo','antetitulo','sumario','slider_texto')
			->where('id_edicion',$this->filterNote)
			->where('habilitado',1)
			->orderBy('orden','asc')
			->get();
			foreach($slider_slider as $item){
				$aOItems = FeUtilController::getImages($item->id_nota,1, 'slider');
				if($aOItems){
					$set_array = array(
						'id' => $item->id_nota,
						'titulo' => $item->titulo,
						'antetitulo' => $item->antetitulo,
						'sumario' => $item->sumario,
						'foto' => $aOItems,
						'slider_texto' => $item->slider_texto
					);
					array_push($array_slider, $set_array);
				}
			}			
			$aResult['data'] = $array_slider;
			return response()->json($aResult);
		}
	}

	public function destacadosSlider(Request $request) {
		$aResult = Util::getDefaultArrayResult();

		$idNota = $request->input('id_nota');
		$idMoneda = $request->input('id_moneda');
		$page = $request->input('page');
		$pageSize = $request->input('iDisplayLength');
		$offset = $request->input('iDisplayStart');
		
		$slider = Note::find($idNota);

		$items = 
			ItemRelated::
				where('parent_id', $idNota)
				->orderBy('related_resource')
				->get()

		;
		$prod_ids = array();
		
		$aData = [
			'slider' => $slider,
			'news' => [],
			'productos' => []
		];			
		foreach ($items	as $item) {
			$producto = null;
			switch ($item->related_resource) {
				case 'news':
					$nota = Note::find($item->related_id);
					if($nota){
						$data = array(
							'id' => $item->related_id,
							'titulo' => $nota->titulo,
							'sumario' => $nota->sumario,
							'resource' => 'news'
						);
					
					array_push(
						$aData['news'],
						array_merge(
							$data,
							['fotos' => 
								Image::where('resource','like','news')
									->where('resource_id', $item->related_id)
									->get()
									->toArray()
							]
							
						)
					);
				}
					break;
				case 'etiquetas':
					
					$prods = 
					ProductosEtiquetas::where('id_etiqueta',$item->related_id)
						->select('id_producto')
						->get()
					;
					
					foreach ($prods as &$prod) {
						if (!Util::in_array($prod_ids, 'id', $prod->id_producto)) {
							$habilitado  = Productos::select('id')->where('id', $prod->id_producto)->where('habilitado', 1)->first();
							if($habilitado){
								array_push($prod_ids, array('id' => $prod->id_producto));
							}
						}
					}

					break;
				case 'productos':
					if (!Util::in_array($prod_ids, 'id', $item->related_id)) {
						$habilitado  = Productos::select('id')->where('id', $item->related_id)->where('habilitado', 1)->first();
						if($habilitado){
							array_push($prod_ids, array('id' => $item->related_id));
						}
					}
				break;
			}
		}
		$itemsForCurrentPage = array_slice($prod_ids, $offset, $pageSize, true);

		foreach($itemsForCurrentPage as $producto){
			$request->request->add([
				'idProducto' => $producto['id'], 
				'id_edicion' => 'companyDefaultId', 
				'edicion' => 'productos',
				'iDisplayLength' => 1,
				'iDisplayStart' => 0,
				'orden' => ['col' =>  'id','dir' =>  'asc'],
			]);	
			$getproducto = app('App\Http\Controllers\Fe\ProductosController')->listado($request);
			$data = json_decode($getproducto->getContent(),true);
			if(isset($data['data']['productos'][0])){
				$producto = $data['data']['productos'][0];
				array_push($aData['productos'],$producto);
			}
		}
		$aResult['data'] = $aData;
		$aResult['data']['total'] = count($prod_ids);
		
		return response()->json($aResult);

	}
}
