<?php
	
namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Cart;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\Http\Controllers\WebServices\MisEnviosController;
use Carbon\Carbon;
use App\AppCustom\Models\TipoEnvio;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\Provincias;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\SucursalesStock;
use Andreani\Andreani;
use Andreani\Requests\CotizarEnvio;
use MP;
use TodoPago\Sdk;

class EnvioController extends Controller
{
	public function __construct(Request $request)
	{
		parent::__construct($request);
		
		$this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->id_idioma = $request->input('idioma');
		
		$this->id_usuario = $request->input('id_usuario');
		$this->cookie = $request->input('cookie');
		$this->item = $request->input('item');
	}
	public function getDistancia($lat1, $long1, $lat2, $long2){ 
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
	
	public function getDireccionEnvio(Request $request){
		$aResult = Util::getDefaultArrayResult();
		if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$id_usuario = $request->input('id_usuario');
			$direccion_envio = PedidosDirecciones::select(\DB::raw('CONCAT(direccion, " ", numero) as direccion'),'id')->where('id_usuario','=',$id_usuario)->lists('direccion', 'id');
			$dni = PedidosClientes::select('dni')->where('id',$id_usuario)->first();
			$aResult['data'] = $direccion_envio;
			$aResult['dni'] = $dni->dni;
			}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}	
		return response()->json($aResult);
	}

	public function getTipoEnvio(Request $request){
		$aResult = Util::getDefaultArrayResult();
		//tipos de envios
		$delivery = false;
		$mercado_envio = false;
		$andreani = true;
		$mis_envios = true;
		$envio_acordar = false;
		$envioGratis = FeUtilController::getPrecioEnvioGratis();
		/////////////

		$respuesta = array();
		$prod_oferta = false;
		$id = $request->input('id'); // ID dirección seleccionada por el cliente
		$pedidos = $request->input('pedido'); // los datos del pedidos, productos a comprar
		$subtotal = (int)$request->input('subtotal'); // el costo de la operación
		if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$cp = PedidosDirecciones::find($id); // Obtengo los datos de la dirección 			
			$alto = $ancho = $largo = $peso = 0;
			foreach ($pedidos as $pedido) {
				$cantidad = (int)$pedido['cantidad'];
				$item = Productos::find($pedido['id_producto']);
				$alto = $alto + ($item->alto*$cantidad);
				$ancho = $ancho + ($item->ancho*$cantidad);
				$largo = $largo + ($item->largo*$cantidad);
				$peso = $peso + ($item->peso*$cantidad);
				/*if($item->oferta == 1){
					$prod_oferta = true;
				}*/
				if($item->alto == 0 || $item->ancho == 0 || $item->largo == 0 || $item->peso == 0){
					$mercado_envio = false;
					$andreani = false;
				}
			}
			if($alto > 70 || $ancho > 70 || $largo > 70 || $peso > 25000){
				//$mercado_envio = false;
				$alto= 70;
				$ancho= 70;
				$largo= 70;
				$peso= 25000;
			}			
			if($delivery){
				$prov = Provincias::find($cp->id_provincia);
				//delivery
				$map_address = $cp->direccion. ' '.$cp->numero.' '.$cp->ciudad.' '.$prov->provincia.' Argentina';
				$url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDPGUWUeNkS7HfMXZO33taHOci4nYrsYXQ&sensor=false&address=".urlencode($map_address);
				$lat_long = get_object_vars(json_decode(file_get_contents($url)));
				
				//latitud y longitud sucursal 
				$lat1 = '-26.829728';
				$long1 = '-65.2335332';
				if(isset($lat_long['results'][0]->geometry->location->lat) && isset($lat_long['results'][0]->geometry->location->lng)){
					$lat2 = $lat_long['results'][0]->geometry->location->lat;
					$long2 = $lat_long['results'][0]->geometry->location->lng;
					
					$distancia = $this->getDistancia($lat1, $long1, $lat2, $long2);
					if($distancia<=20){						
						if(($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis) || $prod_oferta){
							$cost = 0;
						}else{
							$cost = 250;
						}
						$data = array(
							'id' => -1,
							//'name' => 'Delivery a domicilio Paco Garcia - $'.$cost,
							'name' => 'Delivery a domicilio Paco Garcia',
							'id_tipo_envio' => -1,
							'cost' => $cost,
							'total' => Util::getPrecioFormat($subtotal),
							'empresa' => \config('appCustom.clientName')
						);
						array_push($respuesta,$data);
					}
					
				}
			}
			if($mercado_envio){
				$tamano_paquete = $alto."x".$ancho."x".$largo.",".$peso;
				// Verfico que el articulo no supere las medidas estandar para Mercado Envíos
				$params = array(
					"dimensions" => $tamano_paquete,
					"zip_code" => $cp->cp,
					"item_price"=>$subtotal
				);
				if($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis){
					$params['free_method'] = '73328';
				}
				$response = MP::get('/shipping_options',$params);
				if($response){
					$shipping_options = $response['response']['options'];
					foreach($shipping_options as $shipping_option) {
						$shipping_method_id = $shipping_option['shipping_method_id'];
						$name = $shipping_option['name'];
						$shipping_speed = $shipping_option['estimated_delivery_time']['shipping'];
						$estimated_delivery = $shipping_speed < 24 ? (1).' dia' : ceil($shipping_speed / 24).' d&iacute;as'; //from departure, estimated delivery time
						$cost = $shipping_option['cost'];
						$tipo = TipoEnvio::select('id_tipo_envio','id_tipo')->where('id_tipo',$shipping_method_id)->first();
						if($tipo){
							$id_tipo_envio = $tipo->id_tipo_envio;
							$codigo_tipo_envio = $tipo->id_tipo;
						}else{
							$tipo_envio = new TipoEnvio;
							$tipo_envio->id_tipo = $shipping_method_id;
							$tipo_envio->nombre = $name;
							$tipo_envio->empresa = 'MercadoE';
							$tipo_envio->save();
							$id_tipo_envio = $tipo_envio->id_tipo_envio;
							$codigo_tipo_envio = $tipo->id_tipo;
						}
						$data = array(
							'id' => $shipping_method_id,
							//'name' => $name.' - Mercado Envíos - $'.$cost,
							'name' => $name.' - Mercado Envíos',
							'id_tipo_envio' => $id_tipo_envio,
							'tipo_envio' => $codigo_tipo_envio,
							'cost' => $cost,
							'total' => Util::getPrecioFormat($subtotal),
							'empresa' => 'Mercado Envíos'
						);
						array_push($respuesta,$data);
					}
				}
			}

			if($andreani){
				$volumen = $alto*$ancho*$largo;
				$tipo_envio = TipoEnvio::select('id_tipo_envio','id_tipo','nombre')->where('empresa','=','Andreani')->get();
				
				if ($tipo_envio) {
					foreach ($tipo_envio as $tipo) {

						//preparo la consulta
						$data_envio = array(
							'nro_contrato' => $tipo->id_tipo,
							'codigo_postal' => $cp->cp,
							'peso' => $peso,
							'volumen' => $volumen,
							'valor_declarado' => $subtotal
						);

						$response = app('App\Http\Controllers\WebServices\AndreaniController')->getPrecioEnvio($data_envio);
						if($response->isValid()){
							$mensaje = $response->getMessage();
							$tarifa = ($envioGratis < $subtotal)? 0 : $mensaje->CotizarEnvioResult->Tarifa;

							$data = array(
								'id' => $tipo->id_tipo,
								//'name' => $tipo->nombre.' - $'. $tarifa,
								'name' => $tipo->nombre,
								'id_tipo_envio' => $tipo->id_tipo_envio,
								'tipo_envio' => $tipo->id_tipo,
								'cost' => $tarifa,
								'cost_andreani' => $mensaje->CotizarEnvioResult->Tarifa, //para el caso de costo de envio $0 en andreani
								'total' => Util::getPrecioFormat($subtotal),
								'respuesta' => $mensaje,
								'empresa' => 'Andreani'
							);
							array_push($respuesta,$data);
						}
					}
				}
			}

			if($mis_envios){

				$tipo_envio = TipoEnvio::select()->where('empresa','Mis envios')->first();
				if($tipo_envio){
					
					//Peso de g a Kg
					$peso = $peso / 1000;
					if ($peso<1) {
						$peso = 1;
					}

					$prov = Provincias::find($cp->id_provincia);

					$data_envio = array(
						"alto" => $alto,
						"ancho" => $ancho,
						"profundidad" => $largo,
						"peso" => $peso,
						"destino" => $prov->provincia
					);
					
					$consulta = app('App\Http\Controllers\WebServices\MisEnviosController')->getPrecioEnvio($data_envio);

					if($consulta){
						if ($consulta['codigo'] == 200) {

							$precio = $consulta['precio'];

							if($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis){
								$precio = 0;
							}
								
							$data = array(
								'id' => $tipo_envio->id_tipo,
								'name' => $tipo_envio->nombre.' - $'. $precio,
								'id_tipo_envio' => $tipo_envio->id_tipo_envio,
								'tipo_envio' => $tipo_envio->id_tipo,
								'cost' => $precio,
								'total' => Util::getPrecioFormat($subtotal),
								'empresa' => 'Mis Envios'
							);
							array_push($respuesta, $data);
						}
					}
				}
			}

			if(count($respuesta)==0){
				if($envio_acordar){
					$data = array(
						'id' => -2,
						'name' => 'Entrega a acordar con '.\config('appCustom.clientName'),
						'total' => Util::getPrecioFormat($subtotal),
						'empresa' => \config('appCustom.clientName')
					);
					array_push($respuesta,$data);
				}else{
					$data = array(
						'id' => 0,
						'name' => 'No hay formas de envio disponibles para su direccion',
						'empresa' => \config('appCustom.clientName')
					);
					array_push($respuesta,$data);
				}
			}
			$aResult['data'] = $respuesta;
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}	
		return response()->json($aResult);
	}

	public function setTipoEnvio(Request $request)
	{
		$aResult = Util::getDefaultArrayResult();
		$pedidos = $request->input('pedido'); // los datos del pedidos, productos a comprar
		$id_producto = $request->input('id_producto');
		$id_tipo_envio = $request->input('id_tipo_envio');
		$precio_envio_db = $request->input('precio_envio_db');
		if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$total_envio = 0;
			foreach ($pedidos as $pedido) {
				if ($pedido['id_producto'] == $id_producto) {
					$tipo_envio = TipoEnvio::find($id_tipo_envio);
					$pedidoProducto = PedidosProductos::find($pedido['id_pedido_producto']);
					$pedidoProducto->id_tipo_envio = $id_tipo_envio;
					$pedidoProducto->id_tipo = $tipo_envio->id_tipo;
					$pedidoProducto->precio_envio = $precio_envio_db;
					$pedidoProducto->nombre_envio = $tipo_envio->nombre;
					$pedidoProducto->empresa_envio = $tipo_envio->empresa;
					$pedidoProducto->save();
					$total_envio = $total_envio + $precio_envio_db;
				} else {
					$pedidoProducto = PedidosProductos::find($pedido['id_pedido_producto']);
					$total_envio = $total_envio + $pedidoProducto->precio_envio;
				}
			}
			$aResult['data'] = $total_envio;
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
	public function array_value_recursive($key, array $arr){
		$val = array();
		array_walk_recursive($arr, function($v, $k) use($key, &$val){
			if($k == $key) array_push($val, $v);
		});
		return count($val) > 1 ? $val : array_pop($val);
	}

	public function getSucursalEnvio(Request $request){
		$aResult = Util::getDefaultArrayResult();
		if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$id_pedido = $request->input('id_pedido');
			$pedido = Cart::get_pedido($id_pedido);
			//traer sucursales que tengan todos esos productos, si no que muestre la fecha en la q estará en esa sucursal según la configuración general
			$sucursales = Note::select('id_nota as id','titulo','sumario', 'ciudad')
			->where('id_edicion',\config('appCustom.MOD_SUCURSALES_FILTER'))
			->where('habilitado',1)
			->where('sucursalEnvio',1)
			->orderBy('destacado','desc')
			->orderBy('orden','asc')
			->get()->toArray();
			//$aResult['data'] = $pedido['carrito'];
			
			$carrito =$pedido['carrito'];
			array_walk($sucursales, function(&$val,$key)use($carrito){
				$stock_sucursal = true;
				//busco en que sucursales hay stock de todo el carrito
				foreach($carrito as $idc){
					$data = SucursalesStock::select('stock')
					->where('id_sucursal', $val['id'])
					->where('stock', '>=', $idc['cantidad'])
					->where('id_codigo_stock', $idc['id_codigo_stock'])
					->first();
					if(!$data){
						$stock_sucursal = false;
					}
				}
				if($stock_sucursal){
					$val['disponible'] = 1;
					$val['name'] = $val['sumario'].', '.$val['ciudad'].' - Disponible en sucursal';
					$val['fecha'] = array(
						'fecha' => Carbon::now()->format('Y/m/d h:i:s'),
						'dia' => Carbon::now()->format('d/m/Y'),
						'hora' => Carbon::now()->format('h')
					);
				}else{
					$val['disponible'] = 0;
					$dias_retiro = FeUtilController::getDiasRetiroSucursal();
					if($dias_retiro){
						$val['fecha'] = array(
							'fecha' => Carbon::now()->addDays($dias_retiro)->format('Y/m/d h:i:s'),
							'dia' => Carbon::now()->addDays($dias_retiro)->format('d/m/Y'),
							'hora' => Carbon::now()->addDays($dias_retiro)->format('h')
						);
						$val['name'] = $val['sumario'].' - Disponible a partir del '.$val['fecha']['dia'].' a las '.$val['fecha']['hora'].'hs.';
					}else{
						$val = array();
					}
				}
				$val['cost'] = 0;
			});
			$aResult['data'] = $sucursales;
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}	
		return response()->json($aResult);
	}

	public function consultaCostoEnvio(Request $request){
		$aResult = Util::getDefaultArrayResult();
		$respuesta = array();
		
		//tipos de envios
		$mercado_envio = false;
		$andreani = true;
		$mis_envios = true;
		$envio_acordar = false;
		$envioGratis = FeUtilController::getPrecioEnvioGratis();
		/////////////

		
		$id = $request->input('id');
		$codigo_postal = $request->input('codigo_postal');
		
		$precio = PreciosProductos::where('id_producto','=',$id)->where('id_moneda','=',1)->first();
		$subtotal = $precio->precio_venta;
		
		if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$cp = $request->input('codigo_postal');
			$alto = $ancho = $largo = $peso = 0;
			
			$item = Productos::find($id);
			$alto = $item->alto;
			$ancho = $item->ancho;
			$largo = $item->largo;
			$peso = $item->peso;
			if($item->oferta == 1){
				$prod_oferta = true;
			}
			if($item->alto == 0 || $item->ancho == 0 || $item->largo == 0 || $item->peso == 0){
				$mercado_envio = false;
				$andreani = false;
			}
			
			if($alto > 70 || $ancho > 70 || $largo > 70 || $peso > 25000){
				$mercado_envio = false;
			}
			if($mercado_envio){
				$tamano_paquete = $alto."x".$ancho."x".$largo.",".$peso;
				// Verfico que el articulo no supere las medidas estandar para Mercado Envíos
				$params = array(
					"dimensions" => $tamano_paquete,
					"zip_code" => $cp,
					"item_price"=>$subtotal
				);
				if(($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis)){
					$params['free_method'] = '73328';
				}
				$response = MP::get('/shipping_options',$params);
				if($response){
					$shipping_options = $response['response']['options'];
					foreach($shipping_options as $shipping_option) {
						$shipping_method_id = $shipping_option['shipping_method_id'];
						$name = $shipping_option['name'];
						$shipping_speed = $shipping_option['estimated_delivery_time']['shipping'];
						$estimated_delivery = $shipping_speed < 24 ? (1).' dia' : ceil($shipping_speed / 24).' d&iacute;as'; //from departure, estimated delivery time
						$cost = $shipping_option['cost'];
						$tipo = TipoEnvio::select('id_tipo_envio','id_tipo')->where('id_tipo',$shipping_method_id)->first();
						if($tipo){
							$id_tipo_envio = $tipo->id_tipo_envio;
							$codigo_tipo_envio = $tipo->id_tipo;
						}else{
							$tipo_envio = new TipoEnvio;
							$tipo_envio->id_tipo = $shipping_method_id;
							$tipo_envio->nombre = $name;
							$tipo_envio->empresa = 'MercadoE';
							$tipo_envio->save();
							$id_tipo_envio = $tipo_envio->id_tipo_envio;
							$codigo_tipo_envio = $tipo->id_tipo;
						}
						$data = array(
							'id' => $shipping_method_id,
							'name' => $name,
							'id_tipo_envio' => $id_tipo_envio,
							'cost' => $cost,
							'total' => Util::getPrecioFormat($subtotal),
							'empresa' => 'Mercado Envíos'
						);
						array_push($respuesta,$data);
					}
				}
			}
			if($andreani){

				$volumen = $alto*$ancho*$largo;
				$tipo_envio = TipoEnvio::select('id_tipo_envio','id_tipo','nombre')->where('empresa','=','Andreani')->get();

				if ($tipo_envio) {
					foreach ($tipo_envio as $tipo) {
						//preparo la consulta
						$data_envio = array(
							'nro_contrato' => $tipo->id_tipo,
							'codigo_postal' => $cp,
							'peso' => $peso,
							'volumen' => $volumen,
							'valor_declarado' => $subtotal
						);

						$response = app('App\Http\Controllers\WebServices\AndreaniController')->getPrecioEnvio($data_envio);
						
						if($response->isValid()){
							$mensaje = $response->getMessage();

							$data = array(
								'id' => $tipo->id_tipo,
								'name' => $tipo->nombre,
								'id_tipo_envio' => $tipo->id_tipo_envio,
								'cost' => ($envioGratis < $subtotal)? 0 : $mensaje->CotizarEnvioResult->Tarifa,
								'total' => Util::getPrecioFormat($subtotal),
								'respuesta' => $mensaje,
								'empresa' => 'Andreani'
							);
							array_push($respuesta,$data);
						}
					}
				}
			}
			if($mis_envios){

				$tipo_envio = TipoEnvio::select()->where('empresa','Mis envios')->first();
				if($tipo_envio){

					//Peso de g a Kg
					$peso = $peso / 1000;
					if ($peso<1) {
						$peso = 1;
					}

					$prov = Provincias::where('codigo_postal',$cp)->first();

					if($prov){
						//Preparo la consulta
						$data_envio = array(
							"alto" => $alto,
							"ancho" => $ancho,
							"profundidad" => $largo,
							"peso" => $peso,
							"destino" => $prov->provincia
						);
						
						//Hago la consulta
						$consulta = app('App\Http\Controllers\WebServices\MisEnviosController')->getPrecioEnvio($data_envio);

						if($consulta){
							if ($consulta['codigo'] == 200) {

								$precio = $consulta['precio'];
								
								if($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis){
									$precio = 0;
								}
									
								$data = array(
									'id' => $tipo_envio->id_tipo,
									'name' => $tipo_envio->nombre.' - '.$prov->provincia.' - $'. $precio,
									'id_tipo_envio' => $tipo_envio->id_tipo_envio,
									'tipo_envio' => $tipo_envio->id_tipo,
									'cost' => $precio,
									'total' => Util::getPrecioFormat($subtotal),
									'empresa' => 'Mis Envios'
								);
								array_push($respuesta, $data);
							}
						}
					}
				}
			}
			$aResult['data'] = $respuesta;
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}	
		return response()->json($aResult);			
	}

	public function andreani(Request $request)
	{
		\Log::info('pasa andreani');
		$aResult = Util::getDefaultArrayResult();
		$envio = app('App\Http\Controllers\WebServices\AndreaniController')->altaEnvio($request['id_pedido']);
		$aResult['data'] = $envio;
		return response()->json($aResult);	
	}
}
