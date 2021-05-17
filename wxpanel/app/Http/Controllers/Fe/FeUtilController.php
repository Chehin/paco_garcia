<?php
namespace App\Http\Controllers\Fe;

use App\AppCustom\Util;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\NoteLanguage;
use App\AppCustom\Models\Image;
use App\AppCustom\Models\NoteStatistic;
use App\AppCustom\Models\ProductStatistic;
use App\AppCustom\Models\ConfTallesEquivalencias;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\ConfGeneral;
use Carbon\Carbon;

class FeUtilController
{	
	static function getImages($id, $cantidad, $resource) {
	
		$aOItems = Image::select('imagen','imagen_file','epigrafe','id_color')
		->where('resource', $resource)
		->where('resource_id', $id)
		->where('habilitado', 1)
		->orderBy('destacada','desc');
		if($cantidad == 'color'){
			$aOItems = $aOItems->groupBy('id_color');
		}elseif($cantidad != 'all'){
			$aOItems = $aOItems->limit($cantidad);
		}
		$aOItems = $aOItems->get()->toArray();
		
        return $aOItems;
	}
	static function getImagesByColor($id, $cantidad, $resource, $id_color) {
		$aOItems = Image::select('imagen','imagen_file','epigrafe','id_color')
		->where('resource', $resource)
		->where('resource_id', $id)
		/* ->where('id_color',$id_color) */
		->where('habilitado', 1)
		->orderBy('destacada','desc');
		if($cantidad == 'color'){
			$aOItems = $aOItems->groupBy('id_color');
		}elseif($cantidad != 'all'){
			$aOItems = $aOItems->limit($cantidad);
		}
		$aOItems = $aOItems->get()->toArray();
		
        return $aOItems;
	}

	static function getTalle($id){
		$talle = Talles::find($id);
		return $talle;
	}

	static function getLenguage($id, $id_idioma) {
		if($id_idioma>0){
			$lItem1 = NoteLanguage::select('id_nota','titulo','sumario','texto','keyword')
			->where('id_idioma',$id_idioma)
			->where('id_nota',$id)
			->where('habilitado', 1)
			->first();
		}
		$aItems = Note::find($id);
		
		$aItems->titulo = ($id_idioma>0 && $lItem1?$lItem1->titulo:$aItems->titulo);
		$aItems->sumario = ($id_idioma>0 && $lItem1?$lItem1->sumario:$aItems->sumario);
		$aItems->texto = ($id_idioma>0 && $lItem1?$lItem1->texto:$aItems->texto);
		$aItems->keyword = ($id_idioma>0 && $lItem1?$lItem1->keyword:$aItems->keyword);
		
		return $aItems;
	}
    static function newVisitor($id, $title){
		$statistic = NoteStatistic::select('id')->where('id_nota',$id)->first();
		if($statistic){
			$statistic_new = NoteStatistic::find($statistic ->id);
			$statistic_new->visitas++;
		}else{
			$statistic_new = new NoteStatistic;
			$statistic_new->id_nota = $id;
			$statistic_new->titulo = $title;
			$statistic_new->visitas = 1;
		}
		return $statistic_new->save();
	}
	static function newVisitorProduct($id, $title){
		$statistic = ProductStatistic::select('id')->where('id_producto',$id)->first();
		if($statistic){
			$statistic_new = ProductStatistic::find($statistic ->id);
			$statistic_new->visitas++;
		}else{
			$statistic_new = new ProductStatistic;
			$statistic_new->id_producto = $id;
			$statistic_new->titulo = $title;
			$statistic_new->visitas = 1;
		}
		return $statistic_new->save();
	}
	static function getPrecios($id, $id_moneda) {
		$precio = PreciosProductos::
		select('precio_venta','precio_lista','descuento')
		->where('id_producto', $id)
		->where('id_moneda', $id_moneda)
		->first();
		if($precio){
			//si tiene descuento, va sobre el precio de lista
			if($precio->descuento>0 && $precio->precio_lista>0){
				/* $precio->precio_db = ($precio->precio_lista-$precio->descuento);
				$precio->oferta = round(($precio->descuento*100)/$precio->precio_lista); */
				$precio->precio_db = $precio->precio_venta;
				
				$precio_venta = (float)$precio->precio_venta;
                $precio_lista = (float)$precio->precio_lista;
                $porcentaje_max = (float)($precio_venta * 100) / $precio_lista;
				$porcentaje_min = 100 - $porcentaje_max;
					
				$precio->oferta = round($porcentaje_min);
			}else{
				$precio->precio_db = $precio->precio_venta;
			}
			$precio->precio_lista = Util::getPrecioFormat($precio->precio_lista);
			$precio->precio = Util::getPrecioFormat($precio->precio_db);
		}else{
			$precio = false;
		}
		return $precio;
	}
	
	static function getColorTalles($id, $id_color = 0, $id_marca=0, $id_genero=0,$id_rubro=0) {
		$codigo = CodigoStock::
		select(\DB::raw('inv_producto_codigo_stock.id_color, SUM(inv_producto_stock_sucursal.stock) as stock_total, inv_producto_codigo_stock.codigo'))
		->join('inv_producto_stock_sucursal','inv_producto_stock_sucursal.id_codigo_stock','=','inv_producto_codigo_stock.id')
		->where('inv_producto_codigo_stock.id_producto', $id)
		->havingRaw('SUM(inv_producto_stock_sucursal.stock) > 0')
		->orderBy('inv_producto_codigo_stock.id');

		if($id_color){
			$codigo = $codigo->where('inv_producto_codigo_stock.id_color', $id_color);
		}
		$codigo = $codigo->groupBy('inv_producto_codigo_stock.id_color')->get()->toArray();
	
		array_walk($codigo, function(&$val,$key)use($id,$id_marca,$id_genero,$id_rubro){
			
			$aOItems = FeUtilController::getImages($val['id_color'], 'color', 'colores');
			if($aOItems){
				$val['foto'] = $aOItems;
			}
			//talles
			$talles = CodigoStock::
			select('inv_producto_codigo_stock.id_talle','inv_producto_stock_sucursal.stock','inv_producto_codigo_stock.codigo','conf_talles.nombre')
			->leftJoin('conf_talles','conf_talles.id','=','inv_producto_codigo_stock.id_talle')
			->leftJoin('inv_producto_stock_sucursal','inv_producto_stock_sucursal.id_codigo_stock','=','inv_producto_codigo_stock.id')
			/* ->where('inv_producto_codigo_stock.id_color', $val['id_color']) */
			->where('inv_producto_codigo_stock.id_producto', $id)
			->where('conf_talles.habilitado', 1)
			->where('inv_producto_stock_sucursal.stock','>', 0)
			//->groupBy('inv_producto_stock_sucursal.id_codigo_stock')
			->orderBy('conf_talles.nombre','asc')
			->get()->toArray();
			
			if($talles){
				FeUtilController::array_sort_by($talles, 'nombre');
				//buscar equivalencias
				array_walk($talles, function(&$val,$key)use($id_marca,$id_genero,$talles,$id_rubro){

					switch ($id_marca) { //segun US/UK
						case 10: //nike US
							$numeracion = 2;
							break;
						
						case 11: //new Balance US
							$numeracion = 2;
							break;
						
						case 2: //adidas UK
							$numeracion = 1;
							break;
						
						case 13: //salomon UK
							$numeracion = 1;
							break;

						case 31: //crocs US
							$numeracion = 2;
							break;
						
						default:
							$numeracion = 1;
							break;
					}
					$test = Util::getTalleEquivalente($val['nombre'],$id_marca,$id_genero,$numeracion,$id_rubro);
					
					if($test['equivalencia']){						
						$val['nombre'] = $test['equivalencia'];
					}
					
				});

				
				$val['talles'] = $talles;
				//\Log::info(print_r($talles,true));
			}elseif($talles){
				FeUtilController::array_sort_by($talles, 'nombre');
				$val['talles'] = $talles;
			}
		});
		
		return $codigo;
	}


	static function array_sort_by(&$arrIni, $col, $order = SORT_ASC)
	{
		$arrAux = array();
		foreach ($arrIni as $key=> $row)
		{
			$arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
			$arrAux[$key] = strtolower($arrAux[$key]);
		}
		array_multisort($arrAux, $order, $arrIni);
	}

	static function getStockColor($id, $id_color='',$id_talle='') {
		$codigo = CodigoStock::
		select('id_color','id_talle','codigo','stock')
		->where('id_producto', $id)
		->orderBy('id_color');
		if($id_color!=''){
			$codigo = $codigo->where('id_color', $id_color);
		}
		if($id_talle!=''){
			$codigo = $codigo->where('id_talle', $id_talle);
		}
		$codigo = $codigo->get()->toArray();
		return $codigo;
	}

	static function getPrecioEnvioGratis(){
		$precio = ConfGeneral::find(1);
		if($precio->habilitado==1){
			return $precio->valor;
		}else{
			return null;
		}
	}
	static function getDiasRetiroSucursal(){
		$precio = ConfGeneral::find(2);
		if($precio->habilitado==1){
			return $precio->valor;
		}else{
			return null;
		}
	}
	
	static function enviarConfirmEmail($cliente) {
		 
		$params = 'mailConfirmed=yes';
		$params .= '&i='.$cliente->id;
		$params .= '&k=' . \base64_encode($cliente->confirm_token);
		
		$confirmLink = env('FE_URL');
		$confirmLink .= 'mailconfirmed?' . $params;
		$vendedor = \config('appCustom.clientName');
	
		try {
			\Mail::send('email.confirmation', ['cliente' => $cliente, 'confirmLink' => $confirmLink], function($message) use ($cliente, $vendedor){
				$message
					->to($cliente->mail)
					->subject($vendedor . '. Confirmación de Registro');
			});
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return $e->getMessage();
		}
		
		return 1;
		
	}
	
	static function enviarPassForgotEmail($cliente) {
		
		$params = 'passRestore=yes';
		$params .= '&i='.$cliente->id;
		$params .= '&k=' . \base64_encode($cliente->forgot_token);
		
		$confirmLink = env('FE_URL');
		$confirmLink .= 'reset_password?' . $params;
		
		$vendedor = \config('appCustom.clientName');
	
		try {
			\Mail::send('email.passwordForgot', ['user' => $cliente->nombre, 'link' => $confirmLink], function($message) use ($cliente, $vendedor){
				$message
					->to($cliente->mail)
					->subject($vendedor . '. Recuperar contraseña');
			});
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			return $e->getMessage();
		}
		
		return 1;
		
	}
	
	static function postMonth($id_edicion){

		setlocale(LC_TIME, 'es_ES');
		Carbon::setUtf8(true);

		$posts_by_date = Note::
		select(\DB::raw('YEAR(fecha) year, MONTH(fecha) month, MONTHNAME(fecha) month_name, COUNT(*) post_count'), 'fecha')
		->where('editorial_notas.id_edicion', $id_edicion)
		->where('editorial_notas.habilitado', 1)
		->groupBy('year')
		->groupBy('month')
		->orderBy('year', 'desc')
		->orderBy('month', 'desc')
		->get()->toArray();
		array_walk($posts_by_date, function(&$val,$key){
            $fecha = Carbon::parse($val['fecha']);
			$val['month_name'] = $fecha->formatLocalized('%B');

		});

		return response()->json($posts_by_date);
	}

	static function calcularDistancia($lat1, $long1, $lat2, $long2){ 
		//Distancia en kilometros en 1 grado distancia.
		//Distancia en millas nauticas en 1 grado distancia: $mn = 60.098;
		//Distancia en millas en 1 grado distancia: 69.174;
		//Solo aplicable a la tierra, es decir es una constante que cambiaria en la luna, marte... etc.
		$km = 111.302;
		
		//1 Grado = 0.01745329 Radianes    
		$degtorad = 0.01745329;
		
		//1 Radian = 57.29577951 Grados
		$radtodeg = 57.29577951; 
		//La formula que calcula la distancia en grados en una esfera, llamada formula de Harvestine. Para mas informacion hay que mirar en Wikipedia
		//http://es.wikipedia.org/wiki/F%C3%B3rmula_del_Haversine
		$dlong = ($long1 - $long2); 
		$dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad)) + (cos($lat1 * $degtorad) * cos($lat2 * $degtorad) * cos($dlong * $degtorad)); 
		$dd = acos($dvalue) * $radtodeg; 
		return round(($dd * $km), 2);
	}
}
