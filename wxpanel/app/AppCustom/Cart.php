<?php
/**
 * Description of Cart
 *
 */

namespace App\AppCustom;
use App\AppCustom\Models\Sentinel\User;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Image;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\Monedas;
use App\AppCustom\Models\TipoEnvio;
use App\AppCustom\Models\Provincias;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\SucursalesStock;
use App\AppCustom\Models\Note;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Util;
use Andreani\Andreani;
use Andreani\Requests\CotizarEnvio;
use Andreani\Requests\ConfirmarCompra;
use Andreani\Requests\ImpresionDeConstancia;
use App\AppCustom\Models\Talles;

class Cart {
	static function add($data) {
		if(!isset($data['id_color'])){
			$data['id_color'] = 0;
		}
		if(!isset($data['id_talle'])){
			$data['id_talle'] = 0;
		}
		if($data['cookie']!=''){
			$row = 'id_usuario_cookie';
			$id_usuario = $data['cookie'];
		}else{
			$row = 'id_usuario';
			$id_usuario = $data['id_usuario'];
		}
		//busco si el usuario tiene un carrito en proceso
		$pedido = Pedidos::select('id_pedido')
		->where($row,$id_usuario)
		->where('estado','proceso')
		->orderBy('updated_at','desc');
		$pedido = $pedido->first();

		//si no tiene agrego carrito
		if($pedido){
			$id = $pedido->id_pedido;
		}else{
			$pedido_add = new Pedidos([
				$row => $id_usuario,
				'estado' => 'proceso',
				'id_moneda' => $data['id_moneda']
			]);
						
			$pedido_add->save();
			$id = $pedido_add->id_pedido;
		}
		//asocio el producto y color al pedido, si ya existe lo actualizo
		$ped_prod = PedidosProductos::select('id_pedido_producto')
		->where('id_pedido',$id)
		->where('id_producto',$data['id_producto'])
		->where('id_color',$data['id_color'])
		->where('id_talle',$data['id_talle'])
		->first();
		
		$getcodigo = CodigoStock::select('codigo')
		->where('id_producto',$data['id_producto'])
		->first();

		$codigo = '';
		if($getcodigo){
			$codigo = $getcodigo->codigo;
		}/* else{
			$getcodigoProd = Productos::find($data['id_producto']);
			if($getcodigoProd){
				$codigo = $getcodigoProd->modelo;
			}
		} */
		
		if(!isset($data['nombre'])){
			$datoPro=Productos::find($data['id_producto']);
			$data['nombre'] = $datoPro['nombre'];
		}

		$precio = FeUtilController::getPrecios($data['id_producto'],$data['id_moneda']);
		$precio = $precio?$precio->precio_db:0;
		if($ped_prod){
			
			$ped_prod->cantidad = $data['cantidad'];
			$ped_prod->id_color = $data['id_color'];
			$ped_prod->id_talle = $data['id_talle'];
			$ped_prod->codigo = $codigo;
			$ped_prod->id_moneda = $data['id_moneda'];
			$ped_prod->save();
			
			$id_pp = $ped_prod->id_pedido_producto;
		}else{
			$prod_add = new PedidosProductos();
			$prod_add->id_pedido = $id;
			$prod_add->id_producto = $data['id_producto'];
			$prod_add->nombre = $data['nombre'];
			$prod_add->precio = $precio;
			$prod_add->cantidad = $data['cantidad'];
			$prod_add->id_color = $data['id_color'];
			$prod_add->id_talle = $data['id_talle'];
			$prod_add->codigo = $codigo;
			$prod_add->id_moneda = $data['id_moneda'];			
			$prod_add->save();
			$id_pp = $prod_add->id_pedido_producto;
		}
		$return = array(
			'id_pedido' => $id,
			'id_usuario' => $id_usuario
		);
		return $return;
	}
	static function get($id_usuario, $cookie=''){
		if($cookie!=''){
			$row = 'id_usuario_cookie';
			$id_usuario = $cookie;
		}else{
			$row = 'id_usuario';
		}
		$pedido = Pedidos::select('id_pedido','id_tipo_envio','costo_envio','id_direccion_envio')
		->where('estado', 'proceso')
		->where($row, $id_usuario)
		->orderBy('updated_at','desc');

		$pedido = $pedido->first();
		
		if($pedido){
			$carrito = array();
			$precio_temp = 0;
			$ped_prod = PedidosProductos::select('id_pedido_producto','id_moneda','cantidad','id_color','id_talle','id_producto')->where('id_pedido',$pedido->id_pedido)->get();
			foreach($ped_prod as $producto){
				$prod = Productos::find($producto->id_producto);
				if($prod && $prod->habilitado == 1){
					if($producto->id_color){
						$aOItems = FeUtilController::getImagesByColor($producto->id_producto,1, 'productos', $producto->id_color);
					}else{
						$aOItems = FeUtilController::getImages($producto->id_producto,1, 'productos');
					}
					
					if($aOItems){
						array_walk($aOItems, function(&$val,$key)use($producto){
							$coloresStock = FeUtilController::getStockColor($producto->id_producto,'',$producto->id_talle);
							$val['stock'] = $coloresStock[0]['stock'];
						});
					}else{
						$coloresStock = FeUtilController::getStockColor($producto->id_producto, 0);
						$aOItems[0]['stock'] = $coloresStock[0]['stock'];
						$aOItems[0]['imagen_file'] = '';
					}
					//tiene stock para este producto?
					if($aOItems[0]['stock']>=$producto->cantidad){
					if($producto->id_color){
						$dataColor = Colores::find($producto->id_color);
						if($dataColor){
							$producto->color = $dataColor->nombre;
						}							
					}
					if($producto->id_talle){

						//talles
						$talles = FeUtilController::getTalle($producto->id_talle);

						switch ($prod->id_marca) { //segun US/UK
							case 10: //nike US
								$numeracion = 1;
								break;
							
							case 11: //new Balance US
								$numeracion = 1;
								break;
							
							case 2: //adidas UK
								$numeracion = 1;
								break;
							
							case 13: //salomon UK
								$numeracion = 2;
								break;
							
							default:
								$numeracion = 1;
								break;
						}
						
						$dataTalle = Util::getTalleEquivalente($talles->nombre,$prod->id_marca,$prod->id_genero,$numeracion,$prod->id_rubro);
						
						if($dataTalle){
							$producto->talle = $dataTalle['equivalencia'];
						}							
					}
					
					$precio = FeUtilController::getPrecios($producto->id_producto,$producto->id_moneda);
					$moneda = Monedas::find($producto->id_moneda);
					//$stock = (isset($codigo_stock)?$codigo_stock->stock:$prod->stock);
					
					$subtotal = $aOItems[0]['stock']>0?($precio['precio_db']*$producto->cantidad):0;
					$precio_temp = $precio_temp+$subtotal;
					
					$pedido_item = array(
						'id_pedido_producto' => $producto->id_pedido_producto,
						'id_producto' => $producto->id_producto,
						'titulo' => $prod->nombre,
						'largo' => $prod->largo,
						'ancho' => $prod->ancho,
						'alto' => $prod->alto,
						'peso' => $prod->peso,
						'stock' => $aOItems[0]['stock'],
						'talle' => $producto->talle,
						'color' => $producto->color,
						'cantidad' => $producto->cantidad,
						'id_color' => $producto->id_color,
						'precio' => ($aOItems[0]['stock']>0?$precio:0),
						'moneda' => $moneda->simbolo,
						'fotos' => $aOItems,
						'id_pedido' => $pedido->id_pedido,
						'subtotal' => Util::getPrecioFormat($subtotal)
					);
					array_push($carrito,$pedido_item);
					}else{
						//borro el producto del carrito
						$delProd = PedidosProductos::find($producto->id_pedido_producto);
						$delProd->delete();
					}
				}else{
					//borro el producto del carrito
					$delProd = PedidosProductos::find($producto->id_pedido_producto);
					$delProd->delete();
				}
			}						
			
			$response = array(
				'id_pedido' => $pedido->id_pedido,
				'carrito' => $carrito,
				'subtotal' => array(
					'precio' => Util::getPrecioFormat($precio_temp),
					'precio_db' => $precio_temp
				),
				'error' => 0
			);
			$tipo = TipoEnvio::where('id_tipo',$pedido->id_tipo_envio)->first();
			if(!$tipo){
				$tipo = TipoEnvio::where('id_tipo_envio',$pedido->id_tipo_envio)->first();
			}
			if($pedido->id_direccion_envio){
				$direccion = PedidosDirecciones::find($pedido->id_direccion_envio);
				$response['envio'] = array(
					'precio' => $pedido->costo_envio?Util::getPrecioFormat($pedido->costo_envio):'',
					'precio_db' => $pedido->costo_envio?$pedido->costo_envio:'',
					'tipo' => array(
						'nombre' => $tipo->nombre,
						'id_tipo' => $tipo->id_tipo,
						'id_tipo_envio' => $tipo->id_tipo_envio,
						'empresa' => $tipo->empresa
					),
					'direccion' => array(
						'direccion' => $direccion->direccion,
						'ciudad' => $direccion->ciudad,
						'cp' => $direccion->cp
						
					)
				);
				$total_db = $precio_temp+$pedido->costo_envio;
				$total = Util::getPrecioFormat($total_db);
				$response['total'] = array(
					'precio' => $total,
					'precio_db' => $total_db,
				);
			}else{
				if($tipo){
					$response['envio'] = array(
						'precio' => $pedido->costo_envio?Util::getPrecioFormat($pedido->costo_envio):0,
						'precio_db' => $pedido->costo_envio?$pedido->costo_envio:0,
						'tipo' => array(
							'nombre' => $tipo->nombre,
							'id_tipo' => $tipo->id_tipo,
							'id_tipo_envio' => $tipo->id_tipo_envio,
							'empresa' => $tipo->empresa
						)
					);
				}else{
					$response['envio'] = array(
						'precio' => 0,
						'precio_db' => 0,
						'tipo' => array(
							'empresa' => ''
						)
					);
				}
				$response['total'] = array(
					'precio' => Util::getPrecioFormat($precio_temp),
					'precio_db' => $precio_temp,
					'tipo' => array(
						'empresa' => ''
					)
				);
			
			}
		}else{
			$response = array(
				'error' => 1
			);
		}

		return $response;
	}
	static function update($data, $cookie='') {
		if($cookie!=''){
			$row = 'id_usuario_cookie';
			$id_usuario = $data['cookie'];
		}else{
			$row = 'id_usuario';
			$id_usuario = $data['id_usuario'];
		}
		//$id_usuario = $data['id_usuario'];
		$id_pedido_producto = $data['id_pedido_producto'];
		$id_pedido = $data['id_pedido'];
		$cantidad = $data['cantidad'];
		if($cantidad>0){
			//busco que el producto esté en este carrito de este usuario
			$ped_prod = Pedidos::select('pedidos_productos.id_pedido_producto')
			->leftJoin('pedidos_productos', 'pedidos_productos.id_pedido','=','pedidos_pedidos.id_pedido')
			->where('pedidos_pedidos.id_pedido',$id_pedido)
			->where('pedidos_pedidos.'.$row,$id_usuario)
			->where('pedidos_productos.id_pedido_producto', $id_pedido_producto)
			->first();
			if($ped_prod){
				$producto_ped = PedidosProductos::find($id_pedido_producto);
				$producto_ped->cantidad = $cantidad;
				$producto_ped->update();
				$response = array(
					'error' => 0,
					'id_usuario' => $id_usuario
				);
			}else{
				$response = array(
					'error' => 1
				);
			}
		}else{
			$id_tipo_envio = $data['id_tipo_envio'];
			$envio_db = $data['envio_db'];
			$envio_dba = $data['envio_dba'];
			$id_direccion_envio = $data['id_direccion_envio'];
		/* 	$id_direccion_fact = $data['id_direccion_fact']; */
			$id_sucursal = $data['id_sucursal'];
			$fecha_sucursal = $data['fecha_sucursal'];
			/* $cuit = $data['cuit']; */
			$dni = $data['dni'];
			$telefono = $data['telefono'];
			$nombre = $data['nombre'];
            $razon_social = $data['razon_social'];
            $tipo_facturacion = $data['tipo_facturacion'];
		
			$ped_prod = Pedidos::find($data['id_pedido']);

			$ped_prod->id_direccion_envio = $id_direccion_envio;
			if($id_direccion_envio==0){
				$ped_prod->estado_envio = 'en_sucursal';
			}

			$count = count($ped_prod);
						
			if($count>0){
				$ped_prod->id_sucursal = $id_sucursal;
				$ped_prod->fecha_sucursal = $fecha_sucursal;
				$ped_prod->nombre = $nombre;
				$ped_prod->id_direccion_envio = $id_direccion_envio;
				/* $ped_prod->id_direccion_facturacion = $id_direccion_fact; */
				$ped_prod->tipo_facturacion = $tipo_facturacion;
				$ped_prod->id_tipo_envio = $id_tipo_envio;
				$ped_prod->costo_envio = $envio_db;
				$ped_prod->costo_envio_andreani = $envio_dba;
				$ped_prod->dni = $dni;
				$ped_prod->telefono = $telefono;
									
			}else{			
				$ped_prod->id_sucursal = $id_sucursal;
				$ped_prod->fecha_sucursal = $fecha_sucursal;
				$ped_prod->nombre = $nombre;
				$ped_prod->id_direccion_envio = 0;
				/* $ped_prod->id_direccion_facturacion = 0; */
				$ped_prod->id_tipo_envio = 0;
				$ped_prod->costo_envio = 0;
				$ped_prod->costo_envio_andreani = 0;
				$ped_prod->dni = '';
				$ped_prod->telefono = '';
			}
			
			$ped_prod->save();
			$response = array(
				'error' => 0,
				'id_usuario' => $id_usuario
			);
		}
		return $response;
	}
	static function remove($data, $cookie='') {
		if($cookie!=''){
			$row = 'id_usuario_cookie';
			$id_usuario = $data['cookie'];
		}else{
			$row = 'id_usuario';
			$id_usuario = $data['id_usuario'];
		}
		//$id_usuario = $data['id_usuario'];
		$id_pedido_producto = $data['id_pedido_producto'];
		$pedido = PedidosProductos::find($id_pedido_producto);
		$id_pedido = $pedido->id_pedido;
		
		//busco que el producto esté en este carrito de este usuario
		$ped_prod = Pedidos::select('pedidos_productos.id_pedido_producto')
		->leftJoin('pedidos_productos', 'pedidos_productos.id_pedido','=','pedidos_pedidos.id_pedido')
		->where('pedidos_pedidos.id_pedido',$id_pedido)
		->where('pedidos_pedidos.'.$row,$id_usuario)
		->where('pedidos_productos.id_pedido_producto', $id_pedido_producto);

		$ped_prod = $ped_prod->first();

		if($ped_prod){
			$producto_ped = PedidosProductos::find($id_pedido_producto);
			$producto_ped->delete();

			$update_pedido = Pedidos::where('id_pedido', $id_pedido)
			->update([
				'id_tipo_envio' => null, 
				'id_direccion_facturacion' => null, 
				'id_direccion_envio' => null, 
				'costo_envio' => 0
			]);
			$response = array(
				'error' => 0,
				'id_usuario' => $id_usuario
			);
		}else{
			$response = array(
				'error' => 1
			);
		}
		return $response;
	}
	static function cartLogin($id_cookie, $id_usuario){
		$pedido = Pedidos::select('id_pedido')
		->where('id_usuario_cookie',$id_cookie)
		->first();
		if($pedido){
			$pedido_cookie = $pedido->id_pedido;
			//busco si el usuario tiene un pedido creado
			$pedido_usuario = Pedidos::select('id_pedido')
			->where('id_usuario',$id_usuario)
			->where('estado', 'proceso')
			->first();
			if($pedido_usuario){
				//busco que no haya productos para duplicados
				$ped_prod_cookie = PedidosProductos::select('id_pedido_producto','id_producto','id_color')
				->where('id_pedido',$pedido_cookie)
				->get();
				foreach($ped_prod_cookie as $pc){
					$ped_prod = PedidosProductos::select('id_pedido_producto')
					->where('id_pedido',$pedido_usuario->id_pedido)
					->where('id_producto',$pc->id_producto)
					->where('id_color',$pc->id_color)
					->first();
					if($ped_prod) { 
						$ped_prod->delete(); 
					}
					$ped_c = PedidosProductos::find($pc->id_pedido_producto);
					$ped_c->id_pedido = $pedido_usuario->id_pedido;
					$ped_c->save();
				}
				
				//borro el pedido cookie
				$pedido->delete();
			}else{
				$pedido->id_usuario_cookie = '';
				$pedido->id_usuario = $id_usuario;
				$pedido->save();
			}
			
		}
	}
	static function getHistory($id_usuario){
		$array_pedidos = array();
		$row = 'id_usuario';
		$pedidos = Pedidos::select('id_pedido','created_at','metodo_pago','precio_venta','estado','estado_envio','costo_envio','total')
		->where('estado','!=', 'proceso')
		->where($row, $id_usuario)
		->orderBy('updated_at','desc')
		->get();
		
		if($pedidos){
			foreach($pedidos as $pedido){
				$carrito = array();
				$total_pedido = 0;
				$precio_temp = 0;
				$ped_prod = PedidosProductos::select('id_pedido_producto','id_producto','id_moneda','cantidad','nombre','precio')->where('id_pedido',$pedido->id_pedido)->get();
				foreach($ped_prod as $producto){
					$moneda = Monedas::find($producto->id_moneda);
					if($producto->precio){
						$precio = $producto->precio;
						$subtotal = $producto->precio*$producto->cantidad;
					}else{
						$precio_get = FeUtilController::getPrecios($producto->id_producto,$producto->id_moneda);
						$precio = $precio_get->precio;
						$subtotal = $precio_get->precio_db*$producto->cantidad;
					}
					$precio_temp = $precio_temp+$subtotal;
					
					$pedido_item = array(
						'id_pedido_producto' => $producto->id_pedido_producto,
						'titulo' => $producto->nombre,
						'cantidad' => $producto->cantidad,
						'precio' => Util::getPrecioFormat($precio),
						'moneda' => $moneda->simbolo,
						'subtotal' => Util::getPrecioFormat($subtotal)
					);
					
					array_push($carrito,$pedido_item);
				}
				$total_pedido = $precio_temp + $pedido->costo_envio;
				$response = array(
					'id_pedido' => $pedido->id_pedido,
					'fecha' => $pedido->created_at->format('d/m/Y'),
					'metodo_pago' => $pedido->metodo_pago,
					'moneda' => $moneda->simbolo,
					'estado' => Util::estadoPedido($pedido->estado),
					'estado_paquete' => Util::estadoEnvio($pedido->estado_envio),
					'carrito' => $carrito,
					'envio' => array(
						'precio' => Util::getPrecioFormat($pedido->costo_envio),
						'precio_db' => $pedido->costo_envio,
						'moneda' => $moneda->simbolo
					),
					'subtotal' => array(
						'precio' => Util::getPrecioFormat($precio_temp),
						'precio_db' => $precio_temp,
						'moneda' => $moneda->simbolo
					),
					'total' => array(
						'precio' => Util::getPrecioFormat($total_pedido),
						'precio_db' => $total_pedido,
						'moneda' => $moneda->simbolo
					),
					'error' => 0
				);
				array_push($array_pedidos, $response);
			}
		}else{
			$array_pedidos = array(
				'error' => 1
			);
		}
		return $array_pedidos;
	}
	static function get_pedido($id_pedido){
		$pedido = Pedidos::select('id_pedido','id_tipo_envio','costo_envio','id_direccion_envio')
		->where('id_pedido', $id_pedido)
		->orderBy('updated_at','desc')
		->first();
		
		if($pedido){
			$carrito = array();
			$precio_temp = 0;
			$ped_prod = PedidosProductos::select('id_pedido_producto','id_moneda','cantidad','id_color', 'id_talle','id_producto')->where('id_pedido',$pedido->id_pedido)->get();
			foreach($ped_prod as $producto){
				unset($codigo_stock);
				/* if($producto->id_color){ */
					$color = FeUtilController::getImages($producto->id_color,1, 'colores');
					$codigo_stock = CodigoStock::select('id as id_codigo_stock', 'codigo','id_color','stock')
					->where('id_producto',$producto->id_producto);
					/* ->where('id_color' , $producto->id_color); */
					if($producto->id_talle > 0){
						$codigo_stock = $codigo_stock->where('id_talle' , $producto->id_talle);
					}
					$codigo_stock = $codigo_stock->first();
				/* } */
				$precio = FeUtilController::getPrecios($producto->id_producto,$producto->id_moneda);
				$producto_foto = FeUtilController::getImages($producto->id_producto,2, 'productos');
				$prod = Productos::find($producto->id_producto);
				$moneda = Monedas::find($producto->id_moneda);
				
				if($prod){	
					$stock = (isset($codigo_stock)?$codigo_stock->stock:$prod->stock);	

				$subtotal = $stock>0?($precio['precio_db']*$producto->cantidad):0;
				$precio_temp = $precio_temp+$subtotal;
				
				$pedido_item = array(
					'id_pedido_producto' => $producto->id_pedido_producto,
					'id_producto' => $producto->id_producto,
					'titulo' => $prod->nombre,
					'largo' => $prod->largo,
					'ancho' => $prod->ancho,
					'alto' => $prod->alto,
					'peso' => $prod->peso,
					'stock' => $stock,
					'cantidad' => $producto->cantidad,
					'id_codigo_stock' => (isset($codigo_stock)?$codigo_stock->id_codigo_stock:0),
					'codigo' => (isset($codigo_stock)?$codigo_stock->codigo:$prod->codigo),
					'color' => (isset($color)?$color:''),
					'id_color' => $producto->id_color,
					'id_talle' => $producto->id_talle,
					'precio' => ($stock>0?$precio:0),
					'moneda' => $moneda->simbolo,
					'fotos' => $producto_foto,
					'id_pedido' => $pedido->id_pedido,
					'subtotal' => Util::getPrecioFormat($subtotal)
				);
				
				array_push($carrito,$pedido_item);
			   }
			}
			
			
			$response = array(
				'id_pedido' => $pedido->id_pedido,
				'carrito' => $carrito,
				'subtotal' => array(
					'precio' => Util::getPrecioFormat($precio_temp),
					'precio_db' => $precio_temp
				),
				'error' => 0
			);
			
			if($pedido->id_direccion_envio){
				$tipo = TipoEnvio::where('id_tipo',$pedido->id_tipo_envio)->first();
				$direccion = PedidosDirecciones::find($pedido->id_direccion_envio);
				if($tipo && $direccion){
					$response['envio'] = array(
						'precio' => $pedido->costo_envio?Util::getPrecioFormat($pedido->costo_envio):'',
						'precio_db' => $pedido->costo_envio?$pedido->costo_envio:'',
						'tipo' => array(
							'nombre' => $tipo->nombre,
							'id_tipo' => $tipo->id_tipo,
							'id_tipo_envio' => $tipo->id_tipo_envio
						),
						'direccion' => array(
							'direccion' => $direccion->direccion,
							'ciudad' => $direccion->ciudad,
							'cp' => $direccion->cp
							
							)
					);
				}
				$total_db = $precio_temp+$pedido->costo_envio;
				$total = Util::getPrecioFormat($total_db);
				$response['total'] = array(
					'precio' => $total,
					'precio_db' => $total_db,
				);
			}else{
				$response['envio'] = array(
					'precio' => 0,
					'precio_db' => 0,
				);
				$response['total'] = array(
					'precio' => Util::getPrecioFormat($precio_temp),
					'precio_db' => $precio_temp
				);
			
			}
		}else{
			$response = array(
				'error' => 1
			);
		}
		return $response;
	}
	static function checkout($data){
		\Log::info('checkout');
		$id_usuario = $data['id_usuario'];
		$id_pedido = $data['id_pedido'];
		$collection_id = $data['collection_id'];
		$pedido = Pedidos::find($id_pedido);
		if($pedido){
			if($pedido->payment_id == $collection_id && $pedido->id_usuario == $id_usuario){
				$carrito = Cart::get_pedido($id_pedido);
				if($pedido->precio_producto_up==0){
					//recorro los productos para actualizar
					foreach($carrito['carrito'] as $productos){
						$ped_prod = PedidosProductos::find($productos['id_pedido_producto']);
						if($ped_prod){
							$ped_prod->precio = $productos['precio']['precio_db'];
							$ped_prod->nombre = $productos['titulo'];
							$ped_prod->save();
						}
					}
					$pedido->precio_producto_up = 1;
					$pedido->save();
					
					Cart::reservarStock($id_pedido, $pedido->id_sucursal);
				}
				$estado_color='';
				$estado_ico='';
				if($pedido->estado=='pending' || $pedido->estado=='in_process' || $pedido->estado=='in_mediation'){
					$estado_color = 'info';
					$estado_ico = 'exclamation';
				}elseif($pedido->estado=='refunded' || $pedido->estado=='cancelled' || $pedido->estado=='rejected'){
					$estado_color = 'danger';
					$estado_ico = 'times';
				}else{
					$estado_color = 'success';
					$estado_ico = 'check';
				}
				$datos = array(
					'error' => 0,
					'estado' => Util::estadoPedido($pedido->estado),
					'estado_detalle' => Util::estadoPedidoDetalle($pedido->detalle_estado),
					'estado_color' => $estado_color,
					'estado_ico' => $estado_ico,
                    'carrito' => Cart::get_pedido($id_pedido)
				);
				$array_pedidos = $datos;
				Cart::enviar_mail_compra($id_pedido);
			}else{
				$array_pedidos = array(
					'error' => 1,
					'msg' => 'Pedido no encontrado'
				);
			}
		}else{
			$array_pedidos = array(
				'error' => 1,
				'msg' => 'Pedido no encontrado'
			);
		}
		return $array_pedidos;
	}
	static function sucursal_default(){
		$sucursal = Note::select('id_nota as id','titulo','sumario')
		->where('id_edicion',\config('appCustom.MOD_SUCURSALES_FILTER'))
		->where('destacado',1)
		->first();
		return $sucursal;
	}
	static function reservarStock($id_pedido, $sucursal = 0){
		if($sucursal==0){
			$sucursal_default = Cart::sucursal_default();
			$id_sucursal = $sucursal_default?$sucursal_default['id']:0;
		}else{
			$id_sucursal = $sucursal;
		}
		//tengo q restar la cantidad al stock de los productos de este pedido
		$stock_reserva= Pedidos::find($id_pedido);
		if($stock_reserva->estado != 'proceso'){
			$stock_reserva->stock_reserva = 1;
			$stock_reserva->save();
			
			$stock_reserva_up = PedidosProductos::
			where('id_pedido', $id_pedido)
			->update(['stock_reserva' => 1]);
			
			$pedido = PedidosProductos::where('id_pedido', $id_pedido)->get();
			
			foreach($pedido as $producto){
				if($producto->id_talle >= 0){
					$pro_cod_stock = CodigoStock::select('id')
					->where('id_producto', $producto->id_producto)
					->where('id_talle', $producto->id_talle)
					->where('codigo', $producto->codigo)
					->first();
			
					if($pro_cod_stock){
						$cod_stock = CodigoStock::find($pro_cod_stock->id);
						if($cod_stock){
							$cod_stock->stock = ($cod_stock->stock-$producto->cantidad);
							$cod_stock->save();
						}
						//restar el stock de la sucursal
						if($id_sucursal>0){
							$stock_reserva_sucursal = SucursalesStock::
							where('id_codigo_stock', $pro_cod_stock->id)
							->where('id_sucursal', $id_sucursal)
							->decrement('stock', $producto->cantidad);
						}
					}
				}else{
					$producto_stock = Productos::find($producto->id_producto);
					$producto_stock->stock = ($producto_stock->stock-$producto->cantidad);
					$producto_stock->save();
				}
			}
			
		}
	}
	static function liberarStock($id_pedido){
		//tengo q sumar la cantidad al stock de los productos de este pedido
		$stock_reserva= Pedidos::find($id_pedido);
		$stock_reserva->stock_reserva = 0;
		$stock_reserva->save();
		
		$stock_reserva_up = PedidosProductos::where('id_pedido', $id_pedido)
		->update(['stock_reserva' => 0]);
		
		$pedido = PedidosProductos::where('id_pedido', $id_pedido)->get();
		
		foreach($pedido as $producto){
			if($producto->id_talle >= 0){
				$pro_cod_stock = CodigoStock::select('id')
				->where('id_producto', $producto->id_producto)
				->where('id_talle', $producto->id_talle)
				->where('codigo', $producto->codigo)
				->first();
				if($pro_cod_stock){
					$cod_stock = CodigoStock::find($pro_cod_stock->id);
					if($cod_stock){
						$cod_stock->stock = ($cod_stock->stock+$producto->cantidad);
						$cod_stock->save();
					}
					//sumar el stock de la sucursal
					$id_sucursal = $stock_reserva->id_sucursal;
					if($id_sucursal>0){
						$stock_reserva_sucursal = SucursalesStock::
						where('id_codigo_stock', $pro_cod_stock->id)
						->where('id_sucursal', $id_sucursal)
						->increment('stock', $producto->cantidad);
					}
				}
			}else{
				$producto_stock = Productos::find($producto->id_producto);
				$producto_stock->stock = ($producto_stock->stock+$producto->cantidad);
				$producto_stock->save();
			}
		}
	}
	static function tieneReserva($id_pedido){
		$pedido = Pedidos::select('stock_reserva')->where('stock_reserva','>',0)->where('id_pedido','=',$id_pedido)->first();
		if ($pedido) {
			return true;
		} else {
			return false;
		}
	}

	static function altaEnvio($id_pedido)
	{
		$andreani_datos = array(
			'cliente' => \config('appCustom.ANDREANI_CLIENTE'), 
			'usuario' => \config('appCustom.ANDREANI_USUARIO'),
			'pass' => \config('appCustom.ANDREANI_PASS'),
			'ambiente' => \config('appCustom.ANDREANI_AMBIENTE')
		);
		\Log::info(print_r($andreani_datos,true));
		$envio = 1;
		$pedido = Pedidos::find($id_pedido);
	
		if ($pedido->id_direccion_envio) {
			$direccion = PedidosDirecciones::find($pedido->id_direccion_envio);
			$provincia = Provincias::select('provincia')->find($direccion->id_provincia);
			$productos = PedidosProductos::where('id_pedido',$pedido->id_pedido)->get();
			$usuario = PedidosClientes::find($pedido->id_usuario);
			$nombreyApellido = $usuario->apellido . ', ' . $usuario->nombre;			
			
			foreach ($productos as $producto) {
				if (empty($producto->id_pedido_andreani)) {				
					$inv_producto = Productos::select('alto', 'ancho', 'largo', 'peso')->find($producto->id_producto);
					$volumen = 0;
					$peso = 0;
					if ($inv_producto) {
						$volumen = $inv_producto->alto*$inv_producto->largo*$inv_producto->ancho;
						$peso = $inv_producto->peso;
					}						
					$precio = PreciosProductos::select('precio_venta')
												->where('id_producto','=',$producto->id_producto)
												->where('id_moneda','=',$producto->id_moneda)
												->first();
					if ($producto->id_tipo_envio > 0) {
						$tipo_envio = TipoEnvio::find($producto->id_tipo_envio);
						//$tipo_envio = TipoEnvio::where('id_tipo',$producto->id_tipo_envio)->first();
						if ($tipo_envio) {
							if ($tipo_envio->empresa == 'Andreani') {							
								// Obtengo los datos para dar de alta el Envío							
								$cotizar = new CotizarEnvio();
							    $cotizar->setCodigoDeCliente($andreani_datos['cliente']);
							    $cotizar->setNumeroDeContrato($tipo_envio->id_tipo);
							    $cotizar->setCodigoPostal($direccion->cp);
							    $cotizar->setPeso($peso);
							    $cotizar->setVolumen($volumen);
							    $cotizar->setValorDeclarado($precio->precio_venta);
							    $andreani = new Andreani($andreani_datos['usuario'],$andreani_datos['pass'],$andreani_datos['ambiente']);
					    		$response = $andreani->call($cotizar);
								
					    		// Doy de alta el envio
					    		if ($response->isValid()) {				    			
					    			$respuesta = $response->getMessage();
					    			// return $respuesta;
					    			$comprarEnvio = new ConfirmarCompra();
					    			$comprarEnvio->setDatosDestino($provincia->provincia, $direccion->ciudad,$direccion->cp,$direccion->direccion, $direccion->numero, $direccion->piso, $direccion->departamento, 70, null); // 70 es el codigo de sucursal para Tucumán
					    			$comprarEnvio->setDatosDestinatario($nombreyApellido, null, 'DNI', $usuario->dni , $usuario->mail, $direccion->telefono, $direccion->telefono);
					    			$comprarEnvio->setDatosTransaccion($tipo_envio->id_tipo, null, $producto->precio_envio, null);
									$comprarEnvio->setCategoriaDistancia($respuesta->CotizarEnvioResult->CategoriaDistanciaId);
                					$comprarEnvio->setCategoriaFacturacion(null);
                					$comprarEnvio->setCategoriaPeso($respuesta->CotizarEnvioResult->CategoriaPesoId);
                					$comprarEnvio->setPeso($peso);
                					$comprarEnvio->setDetalleProductosEntrega(null);
                					$comprarEnvio->setDetalleProductosRetiro(null);
                					$comprarEnvio->setVolumen($volumen);
                					$comprarEnvio->setValorDeclarado($precio->precio_venta);
									//$respuesta->CotizarEnvioResult->CategoriaDistanciaId,null, null, $respuesta->CotizarEnvioResult->CategoriaPesoId, $peso, null, null, $volumen, $precio->precio_venta
								
									$response1 = $andreani->call($comprarEnvio);
									\Log::info(print_r($response1,true));						
									// Si se hizo la compra del envío guardo el id de transacción
									if ($response1->isValid()) {
										$respuesta1 = $response1->getMessage();
										$nroAndreani = $respuesta1->ConfirmarCompraResult->NumeroAndreani;
										$producto->id_pedido_andreani = $nroAndreani;									
										$producto->save();
									}else{
										\Log::error('Error transacción Andreani');
									}
					    		}
							}
						}
					}
				}			    		
			}

			// Obtengo los datos de impresión			
			foreach ($productos as $producto) {
				if ($producto->id_tipo_envio > 0) {
					$tipo_envio = TipoEnvio::find($producto->id_tipo_envio);
					//$tipo_envio = TipoEnvio::where('id_tipo',$producto->id_tipo_envio)->first();
					if ($tipo_envio) {
						if ($tipo_envio->empresa == 'Andreani') {
							if (isset($andreani)) {
								unset($andreani);	
							}				
							if (empty($producto->impresion_etiqueta)) {
								$constancia = new ImpresionDeConstancia();
								$constancia->setNumeroDeEnvio($producto->id_pedido_andreani);
								
								$andreani = new Andreani($andreani_datos['usuario'],$andreani_datos['pass'],$andreani_datos['ambiente']);
								$response = $andreani->call($constancia);
												
								if ($response->isValid()) {
									$respuesta = $response->getMessage();
									$producto->impresion_etiqueta = $respuesta->ImprimirConstanciaResult->ResultadoImprimirConstancia->PdfLinkFile;
									$producto->save();
								}
								
							}
						}
					}
				}
			}
		} else {
			$envio = 0;
		}
		return $envio;
	}

	static function todoPago($pedido, $total, $operationid, $item)
	{
		$total = str_replace(".", "", $total);
		$total = str_replace(",", ".", $total);
		$data_total = number_format($total,2,'.',''); // Formateo número
		$data_calle = 'Ella con otro';
		$data_postal = '4000';
		$data_telefono = '3814677891';
		$data_pais = 'AR';
		$data_ciudad = 'Tucuman';
		$data_estado = 'T'; // Por defecto Tucuman
		$data_email = "ella@gmail.com";
		$data_first_name="ella";
		$data_last_name="conotro";
		$data_id_cliente="";

		// Obtengo los datos del comprador
		$cliente = PedidosClientes::find($pedido->id_usuario);
		if ($cliente) {
			$data_first_name = $cliente->nombre;
			$data_last_name = $cliente->apellido;
			$data_email = $cliente->mail;
			$data_id_cliente = $cliente->id;
		}
		// Obtengo los datos de envio del comprador
		if ($pedido->id_direccion_facturacion) {
			$direccion = PedidosDirecciones::find($pedido->id_direccion_facturacion);
			if ($direccion) {
				$data_calle = $direccion->direccion;
				$data_postal = $direccion->cp;
				$data_telefono = $direccion->telefono;
				$data_telefono = str_replace("-", "", $data_telefono);
				$provincia = Provincias::find($direccion->id_provincia);
				if ($provincia) {
					$data_ciudad = $provincia->provincia;
					$data_estado = $provincia->cod_todopago;
				}
			}
		} else {
			if ($pedido->id_direccion_envio) {
				$direccion = PedidosDirecciones::find($pedido->id_direccion_envio);
				if ($direccion) {
					$data_calle = $direccion->direccion;
					$data_postal = $direccion->cp;
					$data_telefono = $direccion->telefono;
					$data_telefono = str_replace("-", "", $data_telefono);
					$provincia = Provincias::find($direccion->id_provincia);
					if ($provincia) {
						$data_ciudad = $provincia->provincia;
						$data_estado = $provincia->cod_todopago;
					}
				}
			} else {
				if ($cliente) {
					$direccion = PedidosDirecciones::where('id_usuario','=',$cliente->id)->where('habilitado','=',1)->first();
					if ($direccion) {					
						$data_calle = $direccion->direccion;
						$data_postal = $direccion->cp;
						$data_telefono = $direccion->telefono;
						$data_telefono = str_replace("-", "", $data_telefono);
						$provincia = Provincias::find($direccion->id_provincia);
						if ($provincia) {
							$data_ciudad = $provincia->provincia;
							$data_estado = $provincia->cod_todopago;
						}
					}
				}
			}
		}
		
		$data_nombre_producto = ' compra online';
		
		return [
			'MERCHANT'=>"1074302",    // IMPORTANTE, VER EL ISSUE #13
			'OPERATIONID'=>$operationid,
			'CURRENCYCODE'=>32,
			'AMOUNT'=>$data_total,
			'CSPTCURRENCY'=> "ARS",
			'CSPTGRANDTOTALAMOUNT'=> number_format($data_total,2,'.',''),
			'MININSTALLMENTS' => 1,
			'MAXINSTALLMENTS' => 8,
			'CSBTIPADDRESS'=> \Request::ip(),
			'CSBTCUSTOMERID'=> $data_id_cliente,
			'CSBTEMAIL'=> $data_email,
			'CSSTEMAIL'=> $data_email,
			'CSBTFIRSTNAME'=> $data_first_name,
			'CSSTFIRSTNAME'=> $data_first_name,      
			'CSBTLASTNAME'=> $data_last_name,
			'CSSTLASTNAME'=> $data_last_name,
			'CSBTCOUNTRY'=> $data_pais,
			'CSSTCOUNTRY'=> $data_pais,
			'CSBTCITY'=> $data_ciudad,
			'CSSTCITY'=> $data_ciudad,
			'CSBTPHONENUMBER'=> $data_telefono,     
			'CSSTPHONENUMBER'=> $data_telefono,     
			'CSBTPOSTALCODE'=> $data_postal,
			'CSSTPOSTALCODE'=> $data_postal,
			'CSBTSTATE'=> $data_estado,
			'CSSTSTATE'=> $data_estado,
			'CSBTSTREET1'=> $data_calle,
			'CSSTSTREET1'=> $data_calle,
			'CSITPRODUCTCODE'=> "default",
			'CSITPRODUCTDESCRIPTION'=> $data_nombre_producto,     
			'CSITPRODUCTNAME'=> $data_nombre_producto,     
			'CSITPRODUCTSKU'=> hash('crc32',$data_nombre_producto),
			'CSITTOTALAMOUNT'=> number_format($data_total,2,'.',''),
			'CSITQUANTITY'=> $item,
			'CSITUNITPRICE'=> number_format($data_total,2,'.','')
		];
	}

	static function enviar_mail_compra($id_pedido){	
		$mail_ventas = \config('appCustom.clientVentas');
		$mail_ventasCCO = ['sabrinam.cuevas23@gmail.com','webpacogarciasa@gmail.com','luis.frias@pacogarcia.com.ar'];
		$pedido = Pedidos::find($id_pedido);
		$total = 0;
		if($pedido){
		
			//datos de la sucursal
			$sucursal=Note::find($pedido->id_sucursal);

			if(!$pedido->mail_enviado){
				$nTransaccion = $pedido->collection_id;
				$pedidoUsuario = PedidosClientes::find($pedido->id_usuario);
				if($pedido->id_moneda){
					$moneda = Monedas::find($pedido->id_moneda);
					$moneda = $moneda->simbolo;
					$id_moneda = $pedido->id_moneda;
				}else{
					$moneda = Monedas::select('simbolo','id')->orderBy('principal','desc')->first();
					$moneda = $moneda->simbolo;
					$id_moneda = $moneda->id;
				}
				if($pedidoUsuario){
					$mail_comprador= $pedidoUsuario->mail;
					$url_sitio = \env("FE_URL");
					$destino_img = \env("URL_BASE_UPLOADS");
					$costo_envio = 0;
					
					$productos = PedidosProductos::where('id_pedido', $id_pedido)->get();
					
					if($pedido->estado=='pending' || $pedido->estado=='in_process' || $pedido->estado=='in_mediation'){
						$estado_color = '#5bc0de';
					}elseif($pedido->estado=='refunded' || $pedido->estado=='cancelled' || $pedido->estado=='rejected'){
						$estado_color = '#dc3545';
					}else{
						$estado_color = '#28a745';
					}
					if($pedido->estado == 'cash_on_delivery' || $pedido->estado == 'payment_in_branch'){
						$estado_pedido = "Su compra se registro en el sistema!";
						if($pedido->estado == 'cash_on_delivery'){
							$detalle_pedido = "Los productos serán enviado a la direccion seleccionada, debe abonar el pedido en el domicilio";
						}else{
							$detalle_pedido = "Debe pasar por la sucursal de puerto online para abonar y retirar los productos";
						}
					}else{
						$estado_pedido = Util::estadoPedido($pedido->estado);
						$detalle_pedido = Util::estadoPedidoDetalle($pedido->detalle_estado);
					}
					$cuerpo_mail = '';
					$cabecera_mail = "<div>";
						$cabecera_mail.="<div style='width:100%;border-bottom:1px solid #ccc;'>";
						
						$cabecera_comprador = "<h1 style='font-family: Arial;line-height: 38px;font-size: 20px;color: ".$estado_color.";font-weight: normal;'>". $estado_pedido ."</h1>";
						$cabecera_comprador.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin-bottom:0;'>". $detalle_pedido ."</p>";
						
						$cabecera_vendedor = "<h1 style='font-family: Arial;line-height: 38px;font-size: 20px;color: #008B2E;font-weight: normal;'>Pedido realizado!</h1>";
						$cabecera_vendedor.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin-bottom:0;'>Comprador: ".$pedidoUsuario->nombre." ".$pedidoUsuario->apellido."</p>";
						$cabecera_vendedor.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;'>Mail comprador: ".$pedidoUsuario->mail."</p>";
						
						$cabecera_vendedor.="<h1 style='font-family: Arial;line-height: 38px;font-size: 20px;color: ".$estado_color.";font-weight: normal;'>".Util::estadoPedido($pedido->estado)."</h1>";
						$cabecera_vendedor.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin-bottom:0;'>".Util::estadoPedidoDetalle($pedido->detalle_estado)."</p>";
						$subtotal = 0;
						foreach($productos as $producto){
							$producto_data = Productos::find($producto->id_producto);
							if($producto_data){
								if($producto->precio){
									$precio_u_db = $producto->precio;
									$precio_u = Util::getPrecioFormat($producto->precio);
								}else{
									$precio_u = FeUtilController::getPrecios($producto->id_producto,$id_moneda);
									$precio_u_db = $producto->precio_db;
									$precio_u = $precio_u->precio;
								}
								$subtotal = $subtotal+($precio_u_db*$producto->cantidad);
								$foto_producto = Image::select('imagen_file')
								->where('resource', 'productos')
								->where('resource_id', $producto->id_producto)
								//->where('id_color', $producto->id_color)
								->where('habilitado', 1)
								->orderBy('destacada','desc')
								->first();
								if($foto_producto){
									$imagen_prod=$destino_img."th_".$foto_producto->imagen_file;
								}else{
									$imagen_prod=$url_sitio.'images/img_default/th_producto.jpg';
								}
								$cuerpo_mail.="<table>";
									$cuerpo_mail.="<tr>";								
										$cuerpo_mail.="<td>";
											$cuerpo_mail.="<div style='width:90px;float:left;margin-right:10px;'><img src='".$imagen_prod."' width='90' style='width: 90px;'/></div>";
										$cuerpo_mail.="</td>";
										$cuerpo_mail.="<td>";
											$cuerpo_mail.="<div>";
												$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin:5px 0'>".$producto_data->nombre."</p>";
												$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin:5px 0'>C&oacute;digo: ".$producto->codigo."</p>";
												$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin:5px 0'>Cantidad: ".$producto->cantidad."</p>";
												if($producto->id_color>0){
													$color = Colores::find($producto->id_color);
													if($color){
														$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin:5px 0'>".$color->nombre."</p>";
													}
												}
						
												if($producto->id_talle>0){
													$talle = Talles::find($producto->id_talle);

													switch ($producto_data->id_marca) { //segun US/UK
														case 10: //nike US
															$numeracion = 1;
															break;
														
														case 11: //new Balance US
															$numeracion = 1;
															break;
														
														case 2: //adidas UK
															$numeracion = 1;
															break;
														
														case 13: //salomon UK
															$numeracion = 2;
															break;
														
														default:
															$numeracion = 1;
															break;
													}
													if($talle->nombre && $producto_data->id_marca && $producto_data->id_genero && $numeracion && $producto_data->id_rubro){
														$talleEq = Util::getTalleEquivalente($talle->nombre,$producto_data->id_marca,$producto_data->id_genero,$numeracion,$producto_data->id_rubro);
														if($talleEq){
															$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;margin:5px 0'> Talle:".$talleEq->equivalencia."</p>";
														}
													}
												}

												$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #B22C00;line-height: 1.4;margin:5px 0;'>".$moneda.$precio_u." c/u</p>";
											$cuerpo_mail.="</div>";
										$cuerpo_mail.="</td>";									
									$cuerpo_mail.="</tr>";								
								$cuerpo_mail.="</table>";
							}
						}
						
						$cuerpo_mail.="<p><br/></p>";
						if($pedido->id_tipo_envio){
							//$tipo = TipoEnvio::find($pedido->id_tipo_envio);
							$tipo = TipoEnvio::where('id_tipo',$pedido->id_tipo_envio)->first();
							$costo_envio = $pedido->costo_envio;
							if($tipo){						
								$cuerpo_mail.="<p style='font-family: Arial;color: #666;line-height: 1.4;font-weight:bold;font-size: 16px;text-align:right;'></p>";
								if($pedido->id_tipo_envio!=3){
									$nombre_envio = $tipo->nombre.($tipo->empresa?' '.$tipo->empresa:'');
									$cuerpo_mail.="<p style='font-family: Arial;color: #666;line-height: 1.4;font-weight:bold;font-size: 16px;text-align:right;'>Envio ".$nombre_envio.": <span>".$moneda.Util::getPrecioFormat($costo_envio)."</span></p>";
								}else{
									if($sucursal){
										$cuerpo_mail.="<p style='font-family: Arial;color: #666;line-height: 1.4;font-weight:bold;font-size: 16px;text-align:right;'>".$tipo->nombre." ".$sucursal->titulo." ".$sucursal->ciudad."(<a href='https://www.google.com.ar/search?q=paco+garcia&npsic=0&rflfq=1&rlha=0&rllag=-26829320,-65205043,400&tbm=lcl&ved=2ahUKEwjrtfnHyZDeAhWBIpAKHfB1AA4QtgN6BAgBEAU&tbs=lrf:!2m1!1e2!2m1!1e3!3sIAE,lf:1,lf_ui:10&rldoc=1#rlfi=hd:;si:;mv:!3m12!1m3!1d5785.63744998688!2d-65.236004!3d-26.82746945!2m3!1f0!2f0!3f0!3m2!1i424!2i78!4f13.1;tbs:lrf:!2m1!1e2!2m1!1e3!3sIAE,lf:1,lf_ui:10' target='_blank'> Ver ubicación</a>)</span></p>";
									}
								}
							}
						}
						if($pedido->precio_venta){
							$total = $pedido->precio_venta;
							$total = Util::getPrecioFormat($total);
						}else{
							$total = $subtotal+$costo_envio;
							$total = Util::getPrecioFormat($total);
						}
						$cuerpo_mail.="<p style='font-family: Arial;font-size: 14px;color: #666;line-height: 1.4;font-weight:bold;font-size: 19px;text-align:right;'>TOTAL: <span style='color:#008B2E'>".$moneda.$total."</span></p>";
						if($nTransaccion){
							$cuerpo_mail.="<p style='font-family: Arial;font-size: 9px;color: #666;line-height: 1.4;font-weight:bold;font-size: 19px;text-align:right;'>Num. de Transacci&oacute;n: <span style='color:#008B2E'>".$nTransaccion."</span></p>";
						}
						
					$cuerpo_mail.="</div>";
					
					//mail comprador
					$cuerpo_mail_comprador = array(
						'data1' => $cabecera_mail,
						'data2' => $cabecera_comprador,
						'data3' => $cuerpo_mail
					);


					if(\Mail::send('email.compra', $cuerpo_mail_comprador, function($message)use($mail_comprador){
						$message->to($mail_comprador)->subject('Detalle de compra - '.\config('appCustom.clientName'));
					})){
						$pedido->mail_enviado = 1;
						$pedido->save();
					};
					
					
					//mail vendedor
					$cuerpo_mail_vendedor = array(
						'data1' => $cabecera_mail,
						'data2' => $cabecera_vendedor,
						'data3' => $cuerpo_mail
					);

					if($pedido->id_tipo_envio!=3){
						\Mail::send('email.compra', $cuerpo_mail_vendedor, function($message)use($mail_ventas,$mail_ventasCCO){
							$message->to($mail_ventas)
							->bcc($mail_ventasCCO)
							->subject('Detalle de compra - '.\config('appCustom.clientName'));
						});
					}else{
						/* if($sucursal->email!=''){
							\Mail::send('email.compra', $cuerpo_mail_vendedor, function($message)use($mail_ventas,$mail_ventasCCO,$sucursal){
								$message->to($mail_ventas)
								->cc($sucursal->email)
								->bcc($mail_ventasCCO)
								->subject('Detalle de compra - '.\config('appCustom.clientName'));
							});
						}else{ */
							\Mail::send('email.compra', $cuerpo_mail_vendedor, function($message)use($mail_ventas,$mail_ventasCCO){
								$message->to($mail_ventas)
								->bcc($mail_ventasCCO)
								->subject('Detalle de compra - '.\config('appCustom.clientName'));
							});
						//}
						
					}
				}
			}
		}
	}
}