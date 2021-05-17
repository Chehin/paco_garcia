<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\EtiquetasRubros;
use App\AppCustom\Models\ProductosEtiquetas;
use App\AppCustom\Models\ProductosDeportes;
use App\AppCustom\Models\Deportes;
use App\AppCustom\Models\ProductStatistic;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\SubSubRubrosGeneroMarca;

use Carbon\Carbon;


class ProductosController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		
		$this->fotos = ($request->input('fotos')?$request->input('fotos'):'all');		
		$this->orden = $request->input('orden');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');
		$this->limit = $request->input('limit');
		$this->id_relacion = $request->input('id_relacion');
		$this->forzar = $request->input('forzar');
		$this->id_moneda = $request->input('id_moneda');
		$this->filtros = $request->input('filtros');
		$this->tag = $request->input('tag');
		$this->id_deporte = $request->input('id_deporte');
		$this->search = $request->input('search');
		$this->id_marca = $request->input('id_marca');
		$this->mas_vistos = $request->input('mas_vistos');
		$this->idProducto = $request->input('idProducto');
		$this->precio = $request->input('precio');
    }
	public function rubros(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();        
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$rubros = Util::getRubros('array', true);
			foreach($rubros as $rubro){
				$subrubros = Util::getSubRubros($rubro['id'],'array', true);
				$array_subrubro = array();
				foreach($subrubros as $subrubro){
					$data1 = array(
						'id' => $subrubro['id'],
						'text' => $subrubro['text'],
						'cantidad' => $subrubro['cantidad'],
						'subsubrubros' => Util::getSubSubRubros($subrubro['id'],'array', true)
					);
					array_push($array_subrubro,$data1);
				}
				$data = array(
					'id' => $rubro['id'],
					'text' => $rubro['text'],
					'cantidad' => $rubro['cantidad'],
					'subrubros' => $array_subrubro
				);
				array_push($aResult['data'],$data);
			}
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }
	public function listado(Request $request)
    {


        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['productos'] = array();
			$aResult['data']['total'] = array();
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $limit = $this->limit;
			$currentPage = ($offset / $pageSize) + 1;
			$sort = $this->orden;
			$rand = false;
			if($sort=='rand'){
				$rand = true;
			}else{
				$sortDir = 'ASC';
				$sortCol = 'inv_productos.orden';
			}
			Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
			$etiqueta_array = array();
			$aResult['data']['etiqueta'] = null;
			$aResult['data']['deporte'] = null;
			$aResult['data']['marca'] = null;
			if($this->tag || isset($this->id_deporte)){
				$etiqueta = Etiquetas::find($this->tag);
				if($etiqueta){
					//foto header etiqueta
					$aOItemsEtiqueta = FeUtilController::getImages($this->tag,'all', 'etiquetas');

					$etiqueta_array['id'] = $etiqueta->id;
					$etiqueta_array['nombre'] = $etiqueta->nombre;
					$etiqueta_array['header'] = $aOItemsEtiqueta;
					$aResult['data']['etiqueta'] = $etiqueta_array;
				}
				if($this->tag && $this->id_deporte){
					$aItems = ProductosEtiquetas::
					select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
					->leftJoin('inv_productos','inv_productos.id','=','inv_productos_etiquetas.id_producto')
					->leftJoin('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
					->where('inv_productos.habilitado',1)
					->where('inv_productos_etiquetas.id_etiqueta',$this->tag)
					->where('inv_productos_deportes.id_deporte',$this->id_deporte);
				}elseif($this->tag){
					$aItems = ProductosEtiquetas::
					select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
					->leftJoin('inv_productos','inv_productos.id','=','inv_productos_etiquetas.id_producto')
					->where('inv_productos.habilitado',1)
					->where('inv_productos_etiquetas.id_etiqueta',$this->tag);
				}else{
					$aItems = ProductosDeportes::
					select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
					->leftJoin('inv_productos','inv_productos.id','=','inv_productos_deportes.id_producto')
					->where('inv_productos.habilitado',1)
					->where('inv_productos_deportes.id_deporte',$this->id_deporte);
				}
			}elseif($this->mas_vistos){
				$aItems = ProductStatistic::
				select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
				->leftJoin('inv_productos','inv_productos.id','=','inv_productos_estadisticas.id_producto')
				->where('inv_productos.habilitado',1)
				->orderBy('inv_productos_estadisticas.visitas','desc');
			}else{
				$aItems = Productos::
				select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
				->where('inv_productos.habilitado', 1);
			}
			if ($this->idProducto) {
				$aItems->where('inv_productos.id', $this->idProducto)
						->where('inv_productos.habilitado',1);
			}
			
			if($this->filtros){
				$filtros = $this->filtros;
				foreach($filtros as $clave => $valor){
					$clave = "inv_productos.$clave";
					$aItems = $aItems->where($clave,$valor);
				}
			}
			if($this->search){
				$search = $this->search;
				// $aItems = CodigoStock::leftJoin('inv_productos','inv_productos.id','=','inv_producto_codigo_stock.id_producto')
				// 		->where('inv_productos.habilitado','=',1)
				// 		->where('inv_productos.nombre','like',"%{$search}%")
                //         ->orWhere('inv_productos.sumario','like',"%{$search}%")
				// 		->orWhere('inv_productos.texto','like',"%{$search}%")
				// 		->orWhere('inv_producto_codigo_stock.codigo','=',$search)
				// 		->groupBy('inv_productos.id')
				// 	;
				
				$aItems = $aItems ->distinct()
				
				->leftJoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
				->leftJoin('inv_etiquetas','inv_etiquetas.id','=','inv_productos_etiquetas.id_etiqueta')
				
				->leftJoin('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
				->leftJoin('inv_deportes','inv_deportes.id','=','inv_productos_deportes.id_deporte')
				
				->leftJoin('inv_rubros','inv_rubros.id','=','inv_productos.id_rubro') 
				->leftJoin('inv_subrubros','inv_subrubros.id','=','inv_productos.id_subrubro') 
				->leftJoin('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')
				->where(function ($query) use ($search){
					
					$query->where('inv_productos.nombre','like',"%{$search}%")
					->orWhere('inv_productos.sumario','like',"%{$search}%")
					->orWhere('inv_productos.descripcion','like',"%{$search}%")
					->orWhere('inv_productos.texto','like',"%{$search}%")

					->orwhere('inv_deportes.nombre','like',"%{$search}%")
					->orwhere('conf_marcas.nombre','like',"%{$search}%")
					->orwhere('inv_rubros.nombre','like',"%{$search}%")
					->orwhere('inv_subrubros.nombre','like',"%{$search}%")
					->orwhere('inv_etiquetas.nombre','like',"%{$search}%");

				})
				// ->groupBy('inv_productos.id')
				;
			}
			if($this->id_marca){
				$aItems = $aItems->where('inv_productos.id_marca',$this->id_marca);
			}
			if($this->precio){
				$rangos=explode('-',$this->precio);
				$aItems = Productos::
				select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.id_subsubrubro','inv_productos.oferta')
				->join('inv_precios','inv_precios.id_producto','=','inv_productos.id')
				->where('inv_productos.habilitado',1)
				->whereBetween('inv_precios.precio_venta', [$rangos[0],$rangos[1]]);
			}
			if($limit){
				$aItems = $aItems->limit($limit);
			}
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
				$aItems = $aItems->orderBy('inv_productos.id', $sortDir);
			}
			$aItems = $aItems->paginate($pageSize);
			
			//categorias
			$aResult['data']['categoria'] = array();
			if(isset($filtros['id_rubro']) || isset($filtros['id_subrubro'])){
				if(!isset($filtros['id_rubro'])){
					$getsubrubro = SubRubros::find($filtros['id_subrubro']);
					$filtros['id_rubro'] = $getsubrubro->id_rubro;
				}
				$rubro = Rubros::find($filtros['id_rubro']);
				if($rubro){
					$aOItemsRubros = FeUtilController::getImages($rubro->id,'all', 'rubros');
					$aResult['data']['categoria']['rubro'] = array(
						'id' => $rubro->id,
						'rubro' => $rubro->nombre,
						'header' => $aOItemsRubros
					);
				}
			}
			if(isset($filtros['id_subrubro'])){
				$subrubro = SubRubros::find($filtros['id_subrubro']);
				if($subrubro){
					$aResult['data']['categoria']['subrubro'] = array(
						'id' => $subrubro->id,
						'subrubro' => $subrubro->nombre,
					);
				}
			}
			if(isset($filtros['id_subsubrubro'])){
				$subsubrubro = SubSubRubros::find($filtros['id_subsubrubro']);
				if($subsubrubro){
					$aResult['data']['categoria']['subsubrubro'] = array(
						'id' => $subrubro->id,
						'subsubrubro' => $subsubrubro->nombre
					);
				}
			}
			if($this->id_deporte){
				$deporte = Deportes::find($this->id_deporte);
				if($deporte){
					//foto header etiqueta
					$aResult['data']['deporte'] = array(
						'id' => $deporte->id,
						'nombre' => $deporte->nombre
					);
				}
			}
			if($this->id_marca){
				$marca = Marcas::find($this->id_marca);
				if($marca){
					//foto header etiqueta
					$aResult['data']['marca'] = array(
						'id' => $marca->id,
						'nombre' => $marca->nombre
					);
				}
			}
			
			$coloresStock = array();
			foreach ($aItems as $item) {
				if($this->fotos){
					$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
					if($aOItems){
						array_walk($aOItems, function(&$val,$key)use($item){
							$coloresStock = FeUtilController::getStockColor($item->id, $val['id_color']);
							$val['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
							$val['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
						});
					}else{
						$coloresStock = FeUtilController::getStockColor($item->id, 0);
						$aOItems[0]['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
						$aOItems[0]['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
						$aOItems[0]['id_color'] = 0;
					}
				}else{
					$aOItems = '';
				}
				//precio
				$precio = FeUtilController::getPrecios($item->id,$this->id_moneda);
				
				$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
				//rubro y subrubro
				$rubro = array();
				if(isset(Rubros::find($item->id_rubro)->nombre)){
					$rubro = array(
						'id' => $item->id_rubro,
						'rubro' => Rubros::find($item->id_rubro)->nombre
					);
				}
				
				$subrubro = array();

				if(isset(SubRubros::find($item->id_subrubro)->nombre)){
					if($item->id_subrubro){
						$subrubro = array(
							'id' => $item->id_subrubro,
							'subrubro' => SubRubros::find($item->id_subrubro)->nombre
						);
					}
				}
				if($item->id_subsubrubro){
					$subsubrubro = array(
						'id' => $item->id_subsubrubro,
						'subsubrubro' => SubSubRubros::find($item->id_subsubrubro)->nombre
					);
				}
				$data = array(
					'id' => $item->id,
					'titulo' => $item->nombre,
					'sumario' => $item->sumario,
					'categoria' => array(
						'rubro' => $rubro,
						'subrubro' => $subrubro,
						'subsubrubro' => isset($subsubrubro)?$subsubrubro:''
					),
					'fotos' => $aOItems,
					'precios' => $precio,
					'oferta' => $item->oferta,
					'updated_at' => $fecha
				);
				array_push($aResult['data']['productos'],$data);
			}
			$aResult['data']['total'] = $aItems->total();
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}

	public function producto(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$id = $request->input('id');
		
            $aItems = Productos::
			where('habilitado',1)
			->where('id', $id)
			->first();

			if($aItems){
			$coloresStock = FeUtilController::getColorTalles($aItems->id,0,$aItems->id_marca,$aItems->id_genero,$aItems->id_rubro);
			$aOItems = array();
			//imagenes
			if($coloresStock){
				$aOItems = FeUtilController::getImagesByColor($aItems->id, 99, $this->resource, $coloresStock[0]['id_color']);
			}else{
				$aOItems = FeUtilController::getImagesByColor($aItems->id, 99, $this->resource, 0);
				$coloresStock = array();
				$coloresStock[0] = array(
					'codigo' => '',
					'id_color' => 0,
					'stock_total' => 0 //se agrega para este caso de paco que no tienen stock por color
				);
			}

			//rubro y subrubro
			$rubro = array();
			if($aItems->id_rubro){
				$rubro = array(
					'id' => $aItems->id_rubro,
					'rubro' => Rubros::find($aItems->id_rubro)->nombre
				);
			}
			
			$subrubro = array();
			if($aItems->id_subrubro){
				$subrubro = array(
					'id' => $aItems->id_subrubro,
					'subrubro' => SubRubros::find($aItems->id_subrubro)->nombre
				);
			}
			if($aItems->id_subsubrubro){
				$subsubrubro = array(
					'id' => $aItems->id_subsubrubro,
					'subsubrubro' => SubSubRubros::find($aItems->id_subsubrubro)->nombre
				);
			}else{
				$subsubrubro = array();
			}
			
			

			//precio
			$precio = FeUtilController::getPrecios($aItems->id,$this->id_moneda);
			
			//marca
			$marca = Marcas::find($aItems->id_marca);
			$aItems->marca = ($marca?$marca->nombre:'');
			
			//origen
			$origen = Pais::find($aItems->id_origen);
			$aItems->origen = ($origen?$origen->pais:'');

			//registro visita al producto
			FeUtilController::newVisitorProduct($aItems->id, $aItems->nombre);

			$etiquetas = Productos::find($id)->etiquetas()->get();
			
			//tabla inv_subrubros_genero_marca
			$subRubroGeneroMarca = SubSubRubrosGeneroMarca::select('conf_marcas.nombre as marca','conf_generos.genero as genero','inv_subrubros_genero_marca.imagen as imagen','inv_subrubros.nombre as subrubro')
			->join('conf_marcas','conf_marcas.id','=','inv_subrubros_genero_marca.conf_marcas_id')
			->join('conf_generos','conf_generos.id','=','inv_subrubros_genero_marca.conf_generos_id')
			->join('inv_subrubros','inv_subrubros.id','=','inv_subrubros_genero_marca.inv_subsubrubros_id')
			->where('inv_subrubros_genero_marca.inv_subsubrubros_id','=',$aItems->id_subrubro)
			->where('inv_subrubros_genero_marca.conf_marcas_id','=',$aItems->id_marca)
			->get();

			$data = array(
				'producto' => $aItems,
				'categoria' => array(
					'rubro' => $rubro,
					'subrubro' => $subrubro,
					'subsubrubro' => $subsubrubro
				),
				'etiquetas' => $etiquetas,
				'precios' => $precio,
				'stockColor' => $coloresStock,
				'fotos' => $aOItems,
				'subrubrogeneromarca'=>$subRubroGeneroMarca
			);
			$aResult['data'] = $data;
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = 'Producto no encontrado';
			}
        } else {
			$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
        return response()->json($aResult);
	}
	
	public function relacionados(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['productos'] = array();
			$aResult['data']['total'] = array();
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $limit = $this->limit;
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
			
			$aItems = Productos::
			select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro', 'inv_productos.oferta')
			->join("inv_productos_relacion as a","a.id_secundaria","=","inv_productos.id")
			->where("a.id_principal", $this->id_relacion)
			->where('inv_productos.habilitado', 1)
			->where('inv_productos.id','!=',$this->id_relacion);
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
			}
			$aItems = $aItems->paginate($pageSize);
			if($aItems->total() > 0){
				foreach ($aItems as $item) {
					if($this->fotos){
						$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
						if($aOItems){
							array_walk($aOItems, function(&$val,$key)use($item){
								$coloresStock = FeUtilController::getStockColor($item->id, $val['id_color']);
								$val['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
								$val['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							});
						}else{
							$coloresStock = FeUtilController::getStockColor($item->id, 0);
							$aOItems[0]['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
							$aOItems[0]['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							$aOItems[0]['id_color'] = 0;
						}
					}else{
						$aOItems = '';
					}
					//precio
					$precio = FeUtilController::getPrecios($item->id,$this->id_moneda);
					
					$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
					//rubro y subrubro
					$rubro = array();
					if(isset(Rubros::find($item->id_rubro)->nombre)){
						$rubro = array(
							'id' => $item->id_rubro,
							'rubro' => Rubros::find($item->id_rubro)->nombre
						);
					}

					$subrubro = array();
					if($item->id_subrubro){
						$subrubro = array(
							'id' => $item->id_subrubro,
							'subrubro' => SubRubros::find($item->id_subrubro)->nombre
						);
					}
					$data = array(
						'id' => $item->id,
						'titulo' => $item->nombre,
						'sumario' => $item->sumario,
						'categoria' => array(
							'rubro' => $rubro,
							'subrubro' => $subrubro
						),
						'fotos' => $aOItems,
						'precios' => $precio,
						'updated_at' => $fecha,
						'oferta' => $item->oferta
					);
					array_push($aResult['data']['productos'],$data);
				}
				$aResult['data']['total'] = $aItems->total();
			}else{
				$aItems = array();
			
				// *********************************************************************
				//obtengo los productos del mismo rubro					
				
				$aItems = Productos::
				select('id', 'nombre', 'id_rubro', 'id_subrubro','oferta')
				->where('habilitado', 1)
				->where('id_rubro',Productos::find($this->id_relacion)->id_rubro)
				->where('id', '!=',$this->id_relacion);
				if($limit){
					$aItems = $aItems->limit($limit);
				}
				if($rand){
					$aItems = $aItems->inRandomOrder();
				}else{
					$aItems = $aItems->orderBy($sortCol, $sortDir);
				}
				$aItems = $aItems->paginate($limit);
				foreach ($aItems as $item) {
					if($this->fotos){
						$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
						if($aOItems){
							array_walk($aOItems, function(&$val,$key)use($item){
								$coloresStock = FeUtilController::getStockColor($item->id, $val['id_color']);
								$val['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
								$val['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							});
						}else{
							$coloresStock = FeUtilController::getStockColor($item->id, 0);
							$aOItems[0]['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
							$aOItems[0]['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							$aOItems[0]['id_color'] = 0;
						}
					}else{
						$aOItems = '';
					}
					//precio
					$precio = FeUtilController::getPrecios($item->id,$this->id_moneda);
					
					$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
					//rubro y subrubro
					$rubro = array();
					if(isset(Rubros::find($item->id_rubro)->nombre)){
						$rubro = array(
							'id' => $item->id_rubro,
							'rubro' => Rubros::find($item->id_rubro)->nombre
						);
					}

					$subrubro = array();
					if(isset(SubRubros::find($item->id_subrubro)->nombre)){
						if($item->id_subrubro){
							$subrubro = array(
								'id' => $item->id_subrubro,
								'subrubro' => SubRubros::find($item->id_subrubro)->nombre
							);
						}
					}
 
					$data = array(
						'id' => $item->id,
						'titulo' => $item->nombre,
						'sumario' => $item->sumario,
						'categoria' => array(
							'rubro' => $rubro,
							'subrubro' => $subrubro
						),
						'fotos' => $aOItems,
						'precios' => $precio,
						'updated_at' => $fecha,
						'oferta' => $item->oferta
					);
					array_push($aResult['data']['productos'],$data);
				}
				$aResult['data']['total'] = $aItems->total();
			}
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}

	public function relacionadosColor(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['productos'] = array();
			$aResult['data']['total'] = array();
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $limit = $this->limit;
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
			
			$aItems = Productos::
			select('inv_productos.id', 'inv_productos.nombre', 'inv_productos.id_rubro', 'inv_productos.id_subrubro')
			->join("inv_productos_color_relacion as a","a.id_secundaria","=","inv_productos.id")
			->where("a.id_principal", $this->id_relacion)
			->where('inv_productos.habilitado', 1)
			->where('inv_productos.id','!=',$this->id_relacion);
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
			}
			$aItems = $aItems->paginate($pageSize);
			if($aItems->total() > 0){
				foreach ($aItems as $item) {
					if($this->fotos){
						$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
						if($aOItems){
							array_walk($aOItems, function(&$val,$key)use($item){
								$coloresStock = FeUtilController::getStockColor($item->id, $val['id_color']);
								$val['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
								$val['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							});
						}else{
							$coloresStock = FeUtilController::getStockColor($item->id, 0);
							$aOItems[0]['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
							$aOItems[0]['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
							$aOItems[0]['id_color'] = 0;
						}
					}else{
						$aOItems = '';
					}
					//precio
					$precio = FeUtilController::getPrecios($item->id,$this->id_moneda);
					
					$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
					
					//rubro y subrubro
					$rubro = array();
					if($item->id_rubro){
						$rubro = array(
							'id' => $item->id_rubro,
							'rubro' => Rubros::find($item->id_rubro)->nombre
						);
					}

					$subrubro = array();
					if($item->id_subrubro){
						$subrubro = array(
							'id' => $item->id_subrubro,
							'subrubro' => SubRubros::find($item->id_subrubro)->nombre
						);
					}

					$data = array(
						'id' => $item->id,
						'titulo' => $item->nombre,
						'sumario' => $item->sumario,
						'categoria' => array(
							'rubro' => $rubro,
							'subrubro' => $subrubro
						),
						'fotos' => $aOItems,
						'precios' => $precio,
						'updated_at' => $fecha
					);
					array_push($aResult['data']['productos'],$data);
				}
				$aResult['data']['total'] = $aItems->total();
			}else{
				$aItems = array();				
			}
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}

	public function menu(Request $request)
	{
		$data = array();
		//etiquetas menu
		$etiquetas = Etiquetas::
		select('id','nombre', 'color')
		->where('habilitado', 1)
		->where('menu', 1)
		->orderBy('orden','asc')
		->get()->toArray();
		array_walk($etiquetas, function(&$val,$key){
			$etiqueta_rubro = EtiquetasRubros::
			select('inv_rubros.id','inv_rubros.nombre')
			->leftJoin('inv_rubros','inv_rubros.id','=','inv_etiquetas_rubros.id_rubro')
			->where('inv_etiquetas_rubros.id_etiqueta', $val['id'])
			->where('inv_rubros.habilitado', 1)
			->orderBy('inv_rubros.orden')
			->get()->toArray();
			array_walk($etiqueta_rubro, function(&$val1,$key1){
				$subrubros = SubRubros::
				select('id', 'nombre')
				->where('id_rubro', $val1['id'])
				->where('habilitado', 1)
				->where('destacado', 1)
				->orderBy('orden')
				->get()->toArray();
				$val1['subrubros'] = $subrubros;
			});
			if($etiqueta_rubro){
				$val['rubros'] = $etiqueta_rubro;
			}
		});
		if($etiquetas){
			$data['etiquetas'] = $etiquetas;
		}
		//etiquetas destacadas
		$etiquetas_d = Etiquetas::
		select('id','nombre', 'color')
		->where('habilitado', 1)
		->where('slider', 1)
		->orderBy('orden','asc')
		->get()->toArray();
		if($etiquetas_d){
			$data['etiquetas_destacadas'] = $etiquetas_d;
		}

		//deportes
		$deportes = Deportes::select('id','nombre')->where('habilitado',1)->where('menu', 1)->get()->toArray();
		if($deportes){
			$data['deportes'] = $deportes;
		}
		//marcas
		$marcas = Marcas::select('id','nombre')->where('habilitado',1)->where('destacado', 1)->get()->toArray();
		if($marcas){
			$data['marcas'] = $marcas;
		}

		//productos en oferta
		$moneda_default = Util::getMonedaDefault();
		$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
		$array_send = array(
            'fotos' => 2,
            'id_moneda' => $id_moneda,
            'orden' => 'rand',
            'iDisplayLength' => 4, //registros por pagina
            'iDisplayStart' => 0 //registro inicial (dinamico)
		);
		$array_send['filtros']['oferta'] = 1;
		$request->request->add($array_send);
		$aResult = app('App\Http\Controllers\Fe\ProductosController')->listado($request);
        $aResult = json_decode($aResult->getContent(),true);
		$data['ofertas'] = $aResult['data']['productos'];
		

		$aResult['data'] = $data;
		return response()->json($aResult);
	}

	public function cambiarColor(Request $request){
		$aResult = Util::getDefaultArrayResult();

		$id = $request->input('id_producto');

		$aItems = Productos::
		where('habilitado',1)
		->where('id', $id)
		->first();
		if($aItems){
			$id_color = $request->input('id_color');
			$id_marca = $request->input('id_marca');
			$id_genero = $request->input('id_genero');
		
			//taer fotos, [talles, codigos y stock]
			$coloresStock = FeUtilController::getColorTalles($id,$id_color,$id_marca,$id_genero,$aItems->id_rubro);
			$aOItems = FeUtilController::getImagesByColor($id, 'all', $this->resource, $id_color);
			$aResult['data'] = array(
				'fotos' => $aOItems,
				'talles' => $coloresStock
			);
		}
		return response()->json($aResult);
	}

	public function filtroproductos(Request $request){
		$aResult = Util::getDefaultArrayResult();        
		$aResult['data']['productos'] = array();
		$aResult['data']['firts_page'] =null;
		$aResult['data']['last_page'] = null;
		$aResult['data']['next_url'] = null;
		$aResult['data']['perpage'] = null;
		$aResult['data']['total'] = null;

		$categorias = $request->input('categorias')?explode(",",$request->input('categorias')):null;
		$rubros = $request->input('rubros')?explode(",",$request->input('rubros')):null;
		$deportes = $request->input('deportes')?explode(",",$request->input('deportes')):null;
		$marcas = $request->input('marcas')?explode(",",$request->input('marcas')):null;
		$precios = $request->input('precios')?explode(",",$request->input('precios')):null;
		$sortList = $request->input('sortlist')?$request->input('sortlist'):'';
		$pageSize = (int)env('REGISTROS_PAGINA');

		$currentPage = $request->input('page')?$request->input('page'):1;
		Paginator::currentPageResolver(function() use ($currentPage) {
			return $currentPage;
		});
		//consulta productos
		$aItems = Productos::select(
				'inv_productos.id as id', 
				'inv_productos.nombre as producto', 
				'inv_productos.id_rubro', 
				'inv_productos.id_subrubro', 
				'inv_productos.id_subsubrubro',
				'inv_productos.oferta'
		)->distinct();

		// generos
		if($categorias){
			$aItems 
			->join('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
			->join('inv_etiquetas','inv_etiquetas.id','=','inv_productos_etiquetas.id_etiqueta')
			->whereIn('inv_etiquetas.nombre',$categorias);

		}
		if($rubros){
			$aItems->join('inv_subrubros','inv_subrubros.id','=','inv_productos.id_subrubro')
			->whereIn('inv_subrubros.nombre', $rubros);
		}
		if($deportes){
			$aItems
			->join('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
			->join('inv_deportes','inv_deportes.id','=','inv_productos_deportes.id_deporte')
			->whereIn('inv_deportes.nombre',$deportes);
		}
		if($marcas){
			$aItems
			->join('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')
			->whereIn('conf_marcas.nombre',$marcas);
		}
		if($precios[0]){
			$rangos=explode('-',$precios[0]);
			$aItems  
			->join('inv_precios','inv_precios.id_producto','=','inv_productos.id')
			->whereBetween('inv_precios.precio_venta', [ $rangos[0],$rangos[1] ]);
		}
		$aItems
		->where('inv_productos.habilitado', 1);

		if($sortList){
			switch($sortList){
				case 'nombre':
					$sort = 'inv_productos.nombre';
					$dir = 'asc';
				break;
				case 'menorPrecio':
					$sort = 'inv_productos.nombre';
					$dir = 'asc';
				break;
				case 'mayorPrecio':
					$sort = 'inv_productos.nombre';
					$dir = 'desc';
				break;
				case 'destacados':
					$sort = 'inv_productos.destacado';
					$dir = 'desc';
				break;
				case 'ofertas':
					$sort = 'inv_productos.oferta';
					$dir = 'desc';
				break;
				default:
					$sort = 'inv_productos.nombre';
					$dir = 'asc';
			}
			$aItems->orderby($sort,$dir);
		}
		$aItems = $aItems->paginate($pageSize);
		
		$aResult['data']['firts_page'] = $aItems->currentPage();
		$aResult['data']['last_page'] = $aItems->lastPage();
		$aResult['data']['next_url'] = $aItems->nextPageUrl();
		$aResult['data']['perpage'] = $aItems->perPage();
		$aResult['data']['total'] = $aItems->total();

		$coloresStock = array();
		foreach ($aItems as $item) {
			if($this->fotos){
				$aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
				if($aOItems){
					array_walk($aOItems, function(&$val,$key)use($item){
						$coloresStock = FeUtilController::getStockColor($item->id, $val['id_color']);
						$val['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
						$val['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
					});
				}else{
					$coloresStock = FeUtilController::getStockColor($item->id, 0);
					$aOItems[0]['stock'] = isset($coloresStock[0]['stock'])?$coloresStock[0]['stock']:0;
					$aOItems[0]['id_talle'] = isset($coloresStock[0]['id_talle'])?$coloresStock[0]['id_talle']:0;
					$aOItems[0]['id_color'] = 0;
				}
			}else{
				$aOItems = '';
			}
			
			//precio
			$precio = FeUtilController::getPrecios($item->id,1);
			
			$fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
			//rubro y subrubro
			$rubro = array();
			if(isset(Rubros::find($item->id_rubro)->nombre)){
				$rubro = array(
					'id' => $item->id_rubro,
					'rubro' => Rubros::find($item->id_rubro)->nombre
				);
			}
			
			$subrubro = array();

			if(isset(SubRubros::find($item->id_subrubro)->nombre)){
				if($item->id_subrubro){
					$subrubro = array(
						'id' => $item->id_subrubro,
						'subrubro' => SubRubros::find($item->id_subrubro)->nombre
					);
				}
			}
			if($item->id_subsubrubro){
				$subsubrubro = array(
					'id' => $item->id_subsubrubro,
					'subsubrubro' => SubSubRubros::find($item->id_subsubrubro)->nombre
				);
			}
			
			$data = array(
				'id' => $item->id,
				'titulo' => $item->producto,
				'titulo_slug' => str_slug($item->producto),
				'categoria' => array(
					'rubro' => $rubro,
					'subrubro' => $subrubro,
					'subsubrubro' => isset($subsubrubro)?$subsubrubro:''
				),
				'fotos' => $aOItems,
				'precios' => $precio,
				'oferta' => $item->oferta,
				'moneda_default' => env('MONEDA_DEFAULT')?env('MONEDA_DEFAULT'):'$',
				'updated_at' => $fecha
			);
			array_push($aResult['data']['productos'],$data);
		}

		return response()->json($aResult);
	
	}

	public function filtros(Request $request){
		$aResult = Util::getDefaultArrayResult();        
		$marcas = array();
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$rubros = $this->rubros($request);
			$aResult['data']['rubros'] = json_decode(json_encode($rubros->getData()),true);

			$marcas = Util::getMarcas('array', true);
			$aResult['data']['marcas'] = $marcas;

			$etiquetas = Util::getEtiquetas('array');
			$aResult['data']['etiquetas'] = $etiquetas;

			$deportes = Util::getDeportes('array', true);
			$aResult['data']['deportes'] = $deportes;

			$precios = Util::getRangoPrecios('array');
			$aResult['data']['precios'] = $precios;

		}else{
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}

	


	/* public function autocomplete(Request $request){
		$aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$data=Productos::select('inv_productos.id','inv_productos.nombre', 'img.imagen_file','inv_precios.precio_venta')
			->join('img','img.resource_id','=','inv_productos.id')
			->join('inv_precios','inv_precios.id_producto','=','inv_productos.id')
			->where('img.resource','=','productos')
			->where('inv_productos.nombre', 'LIKE', '%'.$request['q'].'%')
			->groupBy('inv_productos.id')
            ->take(10)
			->get();
			
		} else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		
		$aResult['data'] = $data;
        return $data;
	} */

	public function search(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$array_send = array(
				"fotos" => 1,
				"id_moneda" => 1,
				"orden" => array(
					"col" => "inv_productos.orden",
					"dir" => "ASC"
				),
				"iDisplayLength" => 10,
				"iDisplayStart" => 0,
				"search" => $this->search
			);
			$request->request->add($array_send);
			$aResult = app('App\Http\Controllers\Fe\ProductosController')->listado($request);
			$aResult = json_decode($aResult->getContent(),true);
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
}
