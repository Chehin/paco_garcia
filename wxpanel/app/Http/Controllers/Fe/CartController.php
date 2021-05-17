<?php
	
	namespace App\Http\Controllers\fe;
	
	use Illuminate\Http\Request;
	use App\AppCustom\Util;
	use App\AppCustom\Cart;
	use Illuminate\Pagination\Paginator;
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\Fe\FeUtilController;
	use Carbon\Carbon;
	use App\AppCustom\Models\TipoEnvio;
	use App\AppCustom\Models\PedidosNotificaciones;
	use App\AppCustom\Models\PedidosDirecciones;
	use App\AppCustom\Models\PedidosProductos;	
	use App\AppCustom\Models\PedidosClientes;
	use App\AppCustom\Models\Pedidos;
	use App\AppCustom\Models\Productos;
	use App\AppCustom\Models\Provincias;
	use Andreani\Andreani;
    use Andreani\Requests\CotizarEnvio;
	use MP;
	use TodoPago\Sdk;

use function GuzzleHttp\json_decode;

class CartController extends Controller
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
			$this->id_lista = $request->input('id_lista');
		}
		public function add(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.update') && $this->filterNote) {
				if($this->item){
					$add = Cart::add($this->item);
					
					$carrito = Cart::get($add['id_usuario'], $this->cookie);
					$aResult['data'] = $carrito;
					}else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				}
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function get(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				if(isset($this->id_usuario)){
					$carrito = Cart::get($this->id_usuario, $this->cookie);
					$aResult['data'] = $carrito;
				}else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				} 
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function update(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.update') && $this->filterNote) {
				if($this->item){
			
					$add = Cart::update($this->item, $this->cookie);
					
					$carrito = Cart::get($add['id_usuario'], $this->cookie);
					$aResult['data'] = $carrito;
					}else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				}
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function remove(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.delete') && $this->filterNote) {
				if($this->item){
					$add = Cart::remove($this->item, $this->cookie, $this->id_lista);
					
					$carrito = Cart::get($add['id_usuario'], $this->cookie, $this->id_lista);
					$aResult['data'] = $carrito;
					}else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				}
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function getHistory(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				if($this->id_usuario){
					$carrito = Cart::getHistory($this->id_usuario);
					$aResult['data'] = $carrito;
					}else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				}
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function getDireccionEnvio(Request $request){
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$direccion_envio = PedidosDirecciones::lists('direccion', 'id');
				$aResult['data'] = $direccion_envio;
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}	
			return response()->json($aResult);
		}
		public function getTipoEnvio(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$id = $request->input('id');
			$subtotal = $request->input('subtotal');
			$envioGratis = FeUtilController::getPrecioEnvioGratis();

			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$cp = PedidosDirecciones::find($id);
				$respuesta = array();
				$params = array(
					"dimensions" => "30x30x30,500",
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
						$estimated_delivery = $shipping_speed <= 24 ? (1).' dia' : ceil($shipping_speed / 24).' d&iacute;as'; //from departure, estimated delivery time
						$cost = $shipping_option['cost'];
						$tipo = TipoEnvio::select('id_tipo_envio')->where('id_tipo',$shipping_method_id)->first();
						if($tipo){
							$id_tipo_envio = $tipo->id_tipo_envio;
						}else{
							$tipo_envio = new TipoEnvio;
							$tipo_envio->id_tipo = $shipping_method_id;
							$tipo_envio->nombre = $name;
							$tipo_envio->save();
							$id_tipo_envio = $tipo_envio->id_tipo_envio;
						}
						$data = array(
							'id' => $shipping_method_id,
							'name' => $name.' ('.$estimated_delivery.')',
							'id_tipo_envio' => $id_tipo_envio,
							'cost' => $cost,
							'total' => Util::getPrecioFormat($subtotal+$cost)
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
		
		public function carGetPreference(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$envioGratis = FeUtilController::getPrecioEnvioGratis();
			\Log::info('carGetPreference');
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$item = $this->item;
				$cliente = PedidosClientes::find($this->id_usuario);
				$tamano_paquete = 0;
				$empresa = '';
				if(isset($item['envio']['tipo']['id_tipo_envio'])){
					$tipo_envio = TipoEnvio::find($item['envio']['tipo']['id_tipo_envio']);
					$id_tipo_envio = $tipo_envio->id_tipo;
					if ($tipo_envio->empresa == 'MercadoE') {
						$empresa = 'MercadoE';
					} else {
						$empresa = 'Andreani';
						// Si la empresa de envío es Andreani agrego el costo de envio al precio final
						$item['subtotal']['precio_db'] = (float)$item['subtotal']['precio_db'] + (float)$item['envio']['precio_db'];
					}
				}else{
					$id_tipo_envio = 1;//retiro sucursal
				}
				if(count($item['carrito'])>1){
					$nombre_producto = 'Paco Garcia compra online';
					$foto_prod = '';
					$tamano_paquete = '';
				}else{
					$nombre_producto = $item['carrito'][0]['titulo'];
					if($empresa == 'MercadoE'){
						$tamano_paquete = ($item['carrito'][0]['alto']>70?70:$item['carrito'][0]['alto']).'x'.($item['carrito'][0]['ancho']>70?70:$item['carrito'][0]['ancho']).'x'.($item['carrito'][0]['largo']>70?70:$item['carrito'][0]['largo']).','.($item['carrito'][0]['peso']>25000?25000:$item['carrito'][0]['peso']);
					}
					$foto_prod = '';
                    if (isset($item['carrito'][0]['fotos'])) {
                        $foto_prod = \env("FE_URL").'uploads/be/uploads/'.$item['carrito'][0]['fotos'];
                    }
				}
				if($id_tipo_envio==1){
					$id_tipo_envio = '';
					$foto_prod = '';
				}
				$preferenceData = array(
					"id" => $item['id_pedido'],
					"external_reference" => $item['id_pedido'],
					"items" => array(
						array(
							"title" => $nombre_producto,
							"currency_id" => "ARS",
							"category_id" => "art",
							"quantity" => 1,
							"unit_price" => (float)$item['subtotal']['precio_db'],
							"picture_url" => $foto_prod
						)
					),
					"back_urls" => array(
						"success" => \env("FE_URL")."checkout",
						"failure" => \env("FE_URL")."checkout",
						"pending" => \env("FE_URL")."checkout",
					),
                    "auto_return " => 'approved',
					"notification_url" => \env("FE_URL")."notificaciones"
				);

				\Log::info(json_encode($preferenceData));

				// Si es un pedido de lista de regalo, el cliente puede o no estar logeado
				if ($cliente) {
					$preferenceData["payer"]["name"] = $cliente->nombre;
					$preferenceData["payer"]["surname"] = $cliente->apellido;
					$preferenceData["payer"]["email"] = $cliente->mail;
				}

				//Tiene envio
				if($empresa == 'MercadoE'){
					$preferenceData["shipments"] = array(
						"mode" => "me2",
						"dimensions" => ($tamano_paquete?$tamano_paquete:'70x70x70,25000'),//alto x ancho x largo (centímetros), peso (gramos) //max 70x70x70,25000
						//"local_pickup" => true,//retirar del local del vendedor
						"default_shipping_method" => (int)$id_tipo_envio,
						"zip_code" => $item['envio']['direccion']['cp'],
						"item_price"=> (float)$item['envio']['precio_db'],
						"receiver_address" => array(
							"zip_code" => $item['envio']['direccion']['cp'],
							"street_name" => $item['envio']['direccion']['direccion'],
							"city" => $item['envio']['direccion']['ciudad']
						),
						"free_methods" => array()
					);
					$subtotal = (float)$item['subtotal']['precio_db'];
					if($envioGratis!=null && $envioGratis>=0 && $subtotal>=$envioGratis){
						$preferenceData["shipments"]["free_methods"][0]["id"] = (int)$id_tipo_envio;
					}
				}
					
				\Log::info(json_encode($preferenceData));
				$preference = MP::create_preference($preferenceData);
				\Log::info(json_encode($preference));
				$pedido = Pedidos::find($item['id_pedido']);
				$pedido->id_ped_mercado = $preference['response']['id'];
				$pedido->save();
				
				$aResult['data'] = $preference;
				$aResult['empresa'] = $empresa;
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
	
		public function notificaciones_meli(Request $request){
			\Log::info('notificaciones_meli');
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				\Log::info('pasa');
				$data = $request->input('data');
				$order_null = false;
				if($data["topic"] == 'payment'){
					\Log::info('pasa topic');
					$payment_info = MP::get_payment_info($data['id']);
					\Log::info(json_encode($payment_info));
					if($payment_info["response"]["collection"]["merchant_order_id"]){
						$merchant_order_info = MP::get("/merchant_orders/". $payment_info["response"]["collection"]["merchant_order_id"]);
						$marchant_orden_response = $merchant_order_info["response"];
						\Log::info('marchant_orden_response');
						$asdfasdf= json_encode($marchant_orden_response);
						\Log::info($asdfasdf);
					}else{
						
						$order_null = true;
						$merchant_order_info = $payment_info;
						$marchant_orden_response = $merchant_order_info["response"]['collection'];
						$marchant_orden_response["payments"] = $merchant_order_info["response"];
						if(!isset($marchant_orden_response["shipments"])){
							$marchant_orden_response["shipments"] = array();
						}
					}
				} else if($data["topic"] == 'merchant_order'){
					\Log::info('no pasa merchant_order');
					$merchant_order_info = MP::get("/merchant_orders/". $data['id']);
					$marchant_orden_response = $merchant_order_info["response"];
				}				
				if ($merchant_order_info["status"] == 200) {
					\Log::info('status 200');
					\Log::info(json_encode($marchant_orden_response));
					$fecha_payments = 0;
					$data_payments = 0;
					$paid_amount = 0;
					$costo_envio = 0;
					$payment_id= '';
					foreach ($marchant_orden_response["payments"] as  $payment) {
						if($payment['last_modified']>$fecha_payments){
							$data_payments = $payment;
							$fecha_payments = $payment['last_modified'];
						}
						if ($payment['status'] == 'approved'){
							$paid_amount += $payment['transaction_amount'];
						}		
					}

					$id_pedido = $marchant_orden_response['external_reference'];
					\Log::info($id_pedido);
					$pedido = Pedidos::find($id_pedido);
					\Log::info('pedido1');
					\Log::info(json_encode($pedido));
					if(!$pedido){
						$pedido = new Pedidos;
					}

					if (isset($payment_info['response']['collection']['payment_type'])) {
						$tipo = $payment_info['response']['collection']['payment_type'];	
					}
					$collection_id = $marchant_orden_response['id'];

					if($pedido->payment_id==null || $pedido->payment_id=='' || $pedido->estado=='pending' || $pedido->estado=='rejected'){	
						\Log::info('pasaaa');
							if($data_payments){
								$estado = $data_payments['status'];
								$estado_detalle = $data_payments['status_detail'];
								$precio = $data_payments['transaction_amount'];
								$payment_id = $data_payments['id'];
								$fecha_aprobacion = $data_payments['date_approved'];
								$fecha_modificacion = $data_payments['last_modified'];
								$costo_envio = $data_payments["shipping_cost"];										
								$total = $data_payments["total_paid_amount"];
							}
							if($costo_envio==0){//pidio envio por andreani
								$pedido = Pedidos::find($id_pedido);//busco los valores de costo de envio al momento de la seleccion
								\Log::info(json_encode($pedido));
								\Log::info($id_pedido);
								if($pedido){
								$costo_envio = $pedido->costo_envio;
								}
							}
							$id_envio_meli = '';
							$estado_envio = '';
							$estado_envio = '';
							$tipo_envio = '';
							$tiempo_entrega = '';
							$tipo_envio_andreani= '';
						
							if (!empty($marchant_orden_response["shipments"])) {
								$id_envio_meli = $marchant_orden_response["shipments"][0]['id'];
								$estado_envio = $marchant_orden_response["shipments"][0]["status"];
								$tipo_envio = $marchant_orden_response["shipments"][0]["shipping_option"]["shipping_method_id"];
								$tiempo_entrega = $marchant_orden_response["shipments"][0]["shipping_option"]['speed']['shipping']; //horas
							}else{
								$pedido = Pedidos::find($id_pedido);//busco el tipo de envio al momento de la seleccion
								$tipo_envio= $pedido->id_tipo_envio;
								$tipo_envio_andreani = TipoEnvio::find($tipo_envio);
								if($tipo_envio_andreani){
									PedidosProductos::where('id_pedido', $id_pedido)
									->update(['id_tipo_envio' => $tipo_envio,'id_tipo' => $tipo_envio_andreani->id_tipo, 'precio_envio' => $costo_envio]);//seteo el tipo de envio en productos			
									$estado_envio = 'pending';
								}
							}
							$total_amount = isset($marchant_orden_response["total_amount"])?$marchant_orden_response["total_amount"]:$marchant_orden_response["total_paid_amount"];
							$total = $total_amount;
							//$paid_amount = 0;
							$envio = false;
							$estado_envio_detalle = '';
							


							if($paid_amount >= $total_amount){
								if(count($marchant_orden_response["shipments"]) > 0) { // El merchant_order tiene envíos
									if($marchant_orden_response["shipments"][0]["status"] == "ready_to_ship"){
										$estado_envio_detalle = "PAGADO. Envie su pedido.";
										$envio = true;
									} else {
										$estado_envio_detalle = "PAGADO. Envie su pedido";
									}
								} else { // El merchant_order no tiene ningún envío (retira sucursal
									$estado_envio_detalle = "PAGADO. Puede entregar su articulo.";
									$envio = false;
								}
								Cart::reservarStock($id_pedido);
								// El pedido se ha pagado, envio mail al administrador
								Cart::enviar_mail_compra($id_pedido);
							} else {
								$estado_envio_detalle = "No se ha pagado. No entregar su articulo.";
								$envio = false;
							}
							$moneda_default = Util::getMonedaDefault();
							$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
							$pedido->metodo_pago = 'Mercado Pago';
							\Log::info($collection_id);
							\Log::info($payment_id);
							$pedido->collection_id = $collection_id;
							if (isset($tipo)) {
								$pedido->metodo_mercado = $tipo;	
							}					
							if($data_payments){
								$pedido->estado = $estado;
								$pedido->detalle_estado = $estado_detalle;
								$pedido->precio_venta = $precio;
								$pedido->payment_id = $payment_id;
								$pedido->fecha_aprobacion = $fecha_aprobacion;
								$pedido->fecha_modificacion = $fecha_modificacion;
							}
							if($pedido->id_tipo_envio!=-1){
								$pedido->costo_envio = $costo_envio;
								$pedido->id_tipo_envio = $tipo_envio;
								$total=$costo_envio + $total;
							}
							$pedido->id_envio_meli = $id_envio_meli;
							$pedido->estado_envio = $estado_envio;
							$pedido->estado_envio_detalle = $estado_envio_detalle;
							$pedido->tracking_number = (isset($marchant_orden_response["shipments"][0]["tracking_number"])?$marchant_orden_response["shipments"][0]["tracking_number"]:'');
							$pedido->etiqueta_envio = (isset($marchant_orden_response["shipments"][0]["substatus"])?$marchant_orden_response["shipments"][0]["substatus"]:'');
							if($pedido->id_tipo_envio==-1){
								$total = $total-$pedido->costo_envio;
							}
							$pedido->total = $total;
							$pedido->tiempo_entrega = $tiempo_entrega;
							$pedido->id_moneda = $id_moneda;
							$pedido->save();
							\Log::info($collection_id);
							\Log::info($payment_id);
			
							if ($data_payments["shipping_cost"]==0) {	//No pidio por mercado Envios
								
								$tipo_de_envio = TipoEnvio::select('nombre','empresa')->where('id_tipo',$pedido->id_tipo_envio)->first();
								if(!$tipo_de_envio){
									$tipo_de_envio = TipoEnvio::find($pedido->id_tipo_envio);
								}
								switch ($tipo_de_envio->empresa) {
									case 'Andreani':
										$envio = app('App\Http\Controllers\WebServices\AndreaniController')->altaEnvio($pedido->id_pedido);
										PedidosProductos::where('id_pedido', $pedido->id_pedido)
												->update(['id_tipo_envio' => $pedido->id_tipo_envio,'id_tipo' => $tipo_envio_andreani->id_tipo, 'precio_envio' => $pedido->costo_envio]);//seteo el tipo de envio en productos			
										if($envio['status'] == 0){
											$pedido->alta_envio = 1;
											$pedido->save();
										}
										break;
															
									case 'Mis envios':
										$envio = app('App\Http\Controllers\WebServices\MisEnviosController')->altaEnvio($pedido->id_pedido);
										if($envio['status'] == 0){
											$pedido->alta_envio = 1;
											$pedido->save();
										}
										break;
									
									default:
										$envio = null;
										break;
								}
							}

							if($tipo_envio>1){
								if(!$pedido->id_direccion_envio){
									$ped_dire = new PedidosDirecciones;
								}else{
									$ped_dire = PedidosDirecciones::find($pedido->id_direccion_envio);
								}
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["address_line"])){
									$ped_dire->direccion = $marchant_orden_response["shipments"][0]["receiver_address"]["address_line"];
								}
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["city"]["name"])){
									$ped_dire->ciudad = $marchant_orden_response["shipments"][0]["receiver_address"]["city"]["name"];
								}
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["zip_code"])){
									$ped_dire->cp = $marchant_orden_response["shipments"][0]["receiver_address"]["zip_code"];
								}
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["comment"])){
									$ped_dire->informacion_adicional = $marchant_orden_response["shipments"][0]["receiver_address"]["comment"];
								}
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["phone"])){
									$ped_dire->telefono = $marchant_orden_response["shipments"][0]["receiver_address"]["phone"];
								}
								$ped_dire->mercadopago = 1;
								
								if(isset($marchant_orden_response["shipments"][0]["receiver_address"]["state"]["name"])){
								$provincias = Provincias::select('id')->where('provincia', $marchant_orden_response["shipments"][0]["receiver_address"]["state"]["name"])->first();
									if($provincias){
										$provincia = $provincias->id;
									}
								}
									
								$ped_dire->save();
							}
					}
							
					//Guardar notificacion
					$notificacion = new PedidosNotificaciones();
					$notificacion->id_pedido = $pedido->id_pedido;
					$notificacion->texto = json_encode($data);
					$notificacion->status = isset($estado)? $estado:'';
					$notificacion->more_info = json_encode($marchant_orden_response);
					$notificacion->emisor = 'Mercado Pago';
					$notificacion->save();
				}
				
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
	
		public function cartCheckout(Request $request){
			\Log::info('cartCheckout');
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.update') && $this->filterNote) {
				$id_usuario = $this->id_usuario;
				$id_pedido = $request->input('id_pedido');
				$collection_id = $request->input('collection_id');
				$data = array(
					'id_usuario' => $id_usuario,
					'id_pedido' => $id_pedido,
					'collection_id' => $collection_id
				);
				$checkout = Cart::checkout($data);				
				$aResult['data'] = $checkout;
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}

		public function todoPago(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();
			
			$item = $this->item;
			$id_pedido = $request->input('id_pedido');
			$total = $request->input('total');
			

			$pedido = Pedidos::find($id_pedido);
			
			//común a todas los métodos
			$http_header = array('Authorization'=>\config('appCustom.TP_API_KEY'),
			 'user_agent' => 'PHPSoapClient');
			 
			//creo instancia de la clase TodoPago
			$connector = new Sdk($http_header, "test");

			$operationid = \time();

			$optionsSAR_comercio = array (
				'Security'=>\config('appCustom.TP_KEY'),
				'EncodingMethod'=>'XML',
				'Merchant'=>\config('appCustom.TP_MERCHANT_ID'),
				'URL_OK'=>env('FE_URL')."exito_tp?operationid=".$operationid."&id_pedido=".$id_pedido,
    			'URL_ERROR'=>env('FE_URL')."exito_tp?operationid=".$operationid."&id_pedido=".$id_pedido,
			);

			$optionsSAR_operacion = Cart::todoPago($pedido, $total, $operationid, $item);
			$optionsSAR_operacion['MERCHANT']=\config('appCustom.TP_MERCHANT_ID');
			$rta = $connector->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);

			if ($rta['StatusCode'] == -1) {
				$pedido->RequestKey = $rta['RequestKey'];
				$total = str_replace(".", "", $total);
				$total = str_replace(",", ".", $total);
				$total = number_format($total,2,'.','');
				$pedido->precio_venta = $total;
				$pedido->total = $total;
				$pedido->metodo_mercado = "Todo Pago";
				$pedido->collection_id = $operationid;
				$pedido->id_todopago = $operationid;
				$pedido->save();
			}
			$aResult['data'] = $rta;
			return response()->json($aResult);
		}

		public function validarPagoTP(Request $request)
		{
			$aResult = Util::getDefaultArrayResult();

			$id_pedido = $request->input('id_pedido');
			$operationid = $request->input('operationid');
			$answerKey = $request->input('answerKey');

			$pedido = Pedidos::find($id_pedido);

			if ($pedido) {
				$optionsGAA = array (     
			        'Security'   => \config('appCustom.TP_KEY'),
			        'Merchant'   => \config('appCustom.TP_MERCHANT_ID'),
			        'RequestKey' => $pedido->RequestKey,
			        'AnswerKey'  => $answerKey // *Importante
				);
			}

			//común a todas los métodos
			$http_header = array('Authorization'=>\config('appCustom.TP_API_KEY'));

			//creo instancia de la clase TodoPago
			$connector = new Sdk($http_header, "test");

			$rta2 = $connector->getAuthorizeAnswer($optionsGAA);
			if ($rta2['StatusCode'] != -1) {
				$pedido->id_todopago = $operationid;
				$pedido->estado = "refunded";
				$pedido->metodo_pago = "Todo Pago";
				$pedido->estado_envio_detalle = "No se ha pagado. No entregar su articulo";
				if ($pedido->id_direccion_envio) {
					$pedido->estado_envio = "pending";
				}
				$pedido->save();
			} else {
				$pedido->id_todopago = $operationid;
				$pedido->estado = "approved";
				$pedido->metodo_pago = "Todo Pago";
				if ($pedido->id_direccion_envio) {

					$pedido->estado_envio = "ready_to_ship";
					$tipo_de_envio = TipoEnvio::select('nombre','empresa')->where('id_tipo',$pedido->id_tipo_envio)->first();
					if(!$tipo_de_envio){
						$tipo_de_envio = TipoEnvio::find($pedido->id_tipo_envio);
					}
					switch ($tipo_de_envio->empresa) {
						case 'Andreani':
							$envio =app('App\Http\Controllers\WebServices\AndreaniController')->altaEnvio($pedido->id_pedido);
							PedidosProductos::where('id_pedido', $pedido->id_pedido)
									->update(['id_tipo_envio' => $pedido->id_tipo_envio,'id_tipo' => $tipo_de_envio->id_tipo, 'precio_envio' => $pedido->costo_envio]);//seteo el tipo de envio en productos			
				
							break;
												
						case 'Mis envios':
							$envio = app('App\Http\Controllers\WebServices\MisEnviosController')->altaEnvio($pedido->id_pedido);
							if ($envio['codigo']==200) {
								$pedido->tracking_number = $envio['pieza_id'];
							}
							break;
						
						default:
							$envio = null;
							break;
					}
				}
				$pedido->fecha_aprobacion = Carbon::now()->toDateTimeString();
				$pedido->fecha_modificacion = Carbon::now()->toDateTimeString();
				$pedido->estado_envio_detalle = "PAGADO. Puede entregar su articulo.";
				$pedido->save();

				Cart::reservarStock($pedido->id_pedido);
                // El pedido se ha pagado, envio mail al administrador
                Cart::enviar_mail_compra($pedido->id_pedido);
			}

			$notificacion = new PedidosNotificaciones();
			$notificacion->id_pedido = $pedido->id_pedido;
			$notificacion->texto = json_encode($optionsGAA);
			$notificacion->status = $pedido->estado;
			$notificacion->more_info = json_encode($rta2);
			$notificacion->emisor = 'Todo Pago';
			$notificacion->save();
			$aResult['data'] = $rta2;

			return response()->json($aResult);
		}
		public function estadoPagoPut(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$aResult['data']['status'] = 0;
			$carrito = Cart::get($request['id_usuario'], $this->cookie);
			$id_pedido = $carrito['id_pedido'];
	        $item = Pedidos::find($id_pedido);
	        if ($item) {
				if($request->input('estado_pago')){
					$item->estado = $request->input('estado_pago');
					$item->metodo_pago = $request->input('metodo_pago');
					// Colculo el monto total del pedido
					$total = 0;					
					$productos = PedidosProductos::select('id_pedido_producto','id_producto','precio','id_moneda','cantidad')->where('id_pedido',$id_pedido)->get();
					foreach ($productos as $producto) {
						$productoUp = PedidosProductos::find($producto->id_pedido_producto);
						if($producto->precio){							
							$total = $total + $producto->precio*$producto->cantidad;
						}else{
							$precio_get = FeUtilController::getPrecios($producto->id_producto,$producto->id_moneda);
							$total = $total + $precio_get->precio_db*$producto->cantidad;
							
							$productoUp->precio = $precio_get->precio_db;
						}
						$productoUp->nombre = Productos::find($producto->id_producto)->nombre;
						$productoUp->save();
					}
					$item->total = $total;
					$item->precio_venta = $total;
					$item->save();
					
					Cart::reservarStock($id_pedido);
					Cart::enviar_mail_compra($id_pedido);
				}
				if (!$item->save()) {
	                $aResult['data']['status'] = 1;
	                $aResult['data']['msg'] = \config('appCustom.messages.dbError');
	            }
			}else {
	            $aResult['data']['status'] = 1;
	            $aResult['data']['msg'] = \config('appCustom.messages.itemNotFound');
	        }
			return response()->json($aResult);
		}

		public function email(Request $request){
			$aResult = Util::getDefaultArrayResult();
			
			if ($this->user->hasAccess($this->resource . '.update') && $this->filterNote) {				
				$id_pedido = $request->input('id_pedido');				
								
				$checkout = Cart::enviar_mail_compra($id_pedido);				
				$aResult['data'] = $checkout;
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
	}
