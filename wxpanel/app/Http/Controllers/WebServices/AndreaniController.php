<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\Http\Controllers\WebServices\GoogleMapsController;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\Provincias;
use App\AppCustom\Models\PedidosNotificaciones;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\TipoEnvio;
use Andreani\Andreani;
use Andreani\Requests\CotizarEnvio;
use Andreani\Requests\ConfirmarCompra;
use Andreani\Requests\ImpresionDeConstancia;

class AndreaniController extends Controller
{
    //Consulta API
    public function apiAndreani($method, $url, $body = null){
        $url_base = \config('appCustom.ANDREANI_URL');
        $url_api = $url_base.$url;
        switch ($method) {
            case 'GET':
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url_api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                curl_close($ch);

                break;

            case 'POST':

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                curl_setopt($curl, CURLOPT_URL, $url_api);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($curl);
                curl_close($curl);

                break;
            
            default:
                $result = 'ERROR! Metodo inexistente';
                break;
        }
        
        return $result;
    }
    
    //GET obtiene las sucursales
    public function getSucursales(){
		$aResult = Util::getDefaultArrayResult();
        
        $result = $this->apiAndreani('GET', 'v1/sucursales');
        if ($result) {
            $aResult['data'] = json_decode($result, true);
        }else{
            $aResult['status'] = 1;
            $aResult['msg'] = 'Error Andreani';
		}
		
		return $aResult;
    }

    //encuetra la sucursal mas cercana
    public function obtenerSucursalDestino($id_direccion, $id_pedido){

		$aResult = Util::getDefaultArrayResult();

        //obtengo Latitud y Longitud de la direccion
		$geoCoordenadas = GoogleMapsController::geoCodificar($id_direccion, $id_pedido);
		if($geoCoordenadas['status'] == 0){
			$geoCoordenadas = $geoCoordenadas['data'];
			$lat = $geoCoordenadas['results'][0]['geometry']['location']['lat'];
			$long = $geoCoordenadas['results'][0]['geometry']['location']['lng'];
		
			//Busco la sucursal mas cercana por coordenadas
			$sucursalesAndreani = $this->getSucursales();
			if($sucursalesAndreani['status'] == 0){
				$sucursalesAndreani = $sucursalesAndreani['data'];
				$sucursalCercana = array();

				$distanciaMinima = null;
				foreach ($sucursalesAndreani as $sucursal) {
					
					if (isset($sucursal['geocoordenadas'])) {
						$latSucursal = $sucursal['geocoordenadas']['latitud'];
						$longSucursal = $sucursal['geocoordenadas']['longitud'];

						$distancia = FeUtilController::calcularDistancia($lat, $long, $latSucursal, $longSucursal);

						if ($distancia<$distanciaMinima || $distanciaMinima==null){
							$sucursalCercana = $sucursal;
							$distanciaMinima = $distancia;
						}
					}            
				}
				$aResult['data'] = $sucursalCercana;
			}else{
				$aResult['status'] = 1;
            	$aResult['msg'] = 'Error Andreani Sucursales';
			}
		}else{
			$aResult['status'] = 1;
            $aResult['msg'] = 'Direccion No encontrada';
		}

        return $aResult;
    }

    //Alta de Envio
    public function altaEnvio($id_pedido, $id_sucursal=0)
	{
		\Log::info('alta andreani');
		$aResult = Util::getDefaultArrayResult();

		$andreani_datos = array(
			'cliente' => \config('appCustom.ANDREANI_CLIENTE'), 
			'usuario' => \config('appCustom.ANDREANI_USUARIO'),
			'pass' => \config('appCustom.ANDREANI_PASS'),
			'ambiente' => \config('appCustom.ANDREANI_AMBIENTE')
		);
		$pedido = Pedidos::find($id_pedido);
	
		if ($pedido->id_direccion_envio) {
			$direccion = PedidosDirecciones::find($pedido->id_direccion_envio);
			$provincia = Provincias::select('provincia')->find($direccion->id_provincia);
			$productos = PedidosProductos::where('id_pedido',$pedido->id_pedido)->get();
			$usuario = PedidosClientes::find($pedido->id_usuario);

			$nombreyApellidoUsuario = $usuario->apellido . ', ' . $usuario->nombre;
			$nombreyApellidoRetira = $pedido->nombre;

			$dni = $pedido->dni;

			if(!$dni){				
				$dni = $usuario->dni;
			}
			
			//Sucursal de destino
			$idSucursalDestino = 0;
			if($id_sucursal){
				$idSucursalDestino = $id_sucursal;
			}else{
				$suc = $this->obtenerSucursalDestino($pedido->id_direccion_envio, $pedido->id_pedido);
				if($suc['status'] == 0){
					$idSucursalDestino = $suc['data']['id'];
				}
			}
			if($idSucursalDestino != 0){
				
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
									
									//preparo la consulta
									$data_envio = array(
										'nro_contrato' => $tipo_envio->id_tipo,
										'codigo_postal' => $direccion->cp,
										'peso' => $peso,
										'volumen' => $volumen,
										'valor_declarado' => $precio->precio_venta
									);
									
									$response = $this->getPrecioEnvio($data_envio);
									
									// Doy de alta el envio
									if ($response->isValid()) {				    			
										$respuesta = $response->getMessage();
										
										// return $respuesta;
										$comprarEnvio = new ConfirmarCompra();
										$comprarEnvio->setDatosDestino($provincia->provincia, $direccion->ciudad,$direccion->cp,$direccion->direccion, $direccion->numero, $direccion->piso, $direccion->departamento, $idSucursalDestino, null);
										$comprarEnvio->setDatosDestinatario($nombreyApellidoRetira, $nombreyApellidoUsuario, 'DNI', $dni , $usuario->mail, $pedido->telefono, $direccion->telefono);
										$comprarEnvio->setDatosTransaccion($tipo_envio->id_tipo, null, $producto->precio_envio, null);
										$comprarEnvio->setCategoriaDistancia($respuesta->CotizarEnvioResult->CategoriaDistanciaId);
										$comprarEnvio->setCategoriaFacturacion(null);
										$comprarEnvio->setCategoriaPeso($respuesta->CotizarEnvioResult->CategoriaPesoId);
										$comprarEnvio->setPeso($peso);
										$comprarEnvio->setDetalleProductosEntrega(null);
										$comprarEnvio->setDetalleProductosRetiro(null);
										$comprarEnvio->setVolumen($volumen);
										$comprarEnvio->setValorDeclarado($precio->precio_venta);

										$andreani = new Andreani($andreani_datos['usuario'],$andreani_datos['pass'],$andreani_datos['ambiente']);
										//$respuesta->CotizarEnvioResult->CategoriaDistanciaId,null, null, $respuesta->CotizarEnvioResult->CategoriaPesoId, $peso, null, null, $volumen, $precio->precio_venta
										\Log::info(print_r($comprarEnvio,true));
										$response1 = $andreani->call($comprarEnvio);
										\Log::info(print_r($response1,true));

										$notificacion = new PedidosNotificaciones();
										$notificacion->id_pedido = $pedido->id_pedido;
										$notificacion->texto = 'Alta Envio';
										$notificacion->status = '';
										$notificacion->more_info = json_encode($response1);
										$notificacion->emisor = 'Andreani';
										$notificacion->save();
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
			}else{
				$aResult['status'] = 1;
            	$aResult['msg'] = 'Sucursal no encontrada';
			}
		} else {
			$aResult['status'] = 1;
            $aResult['msg'] = 'Direccion invalida';
		}

		return $aResult;
	}

	//Imprimir Etiquetas
    public function imprimir_etiquetas($id_pedido)
	{
		\Log::info('Imprimir etiquetas andreani');
		$aResult = Util::getDefaultArrayResult();

		$andreani_datos = array(
			'cliente' => \config('appCustom.ANDREANI_CLIENTE'), 
			'usuario' => \config('appCustom.ANDREANI_USUARIO'),
			'pass' => \config('appCustom.ANDREANI_PASS'),
			'ambiente' => \config('appCustom.ANDREANI_AMBIENTE')
		);
		$pedido = Pedidos::find($id_pedido);
	
		if ($pedido) {
			$productos = PedidosProductos::where('id_pedido',$pedido->id_pedido)->get();

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
			$aResult['status'] = 1;
            $aResult['msg'] = 'Pedido no encontrado';
		}

		return $aResult;
	}

	//GET consulta el costo de un envio
	public function getPrecioEnvio($datos){

		$andreani_datos = array(
			'cliente'	=> \config('appCustom.ANDREANI_CLIENTE'), 
			'usuario' 	=> \config('appCustom.ANDREANI_USUARIO'),
			'pass' 		=> \config('appCustom.ANDREANI_PASS'),
			'ambiente' 	=> \config('appCustom.ANDREANI_AMBIENTE')
		);

		// Los siguientes datos son de prueba, para la implementación en un entorno productivo deberán reemplazarse por los verdaderos					    
		$cotizar = new CotizarEnvio();
		$cotizar->setCodigoDeCliente($andreani_datos['cliente']);
		$cotizar->setNumeroDeContrato($datos['nro_contrato']);
		$cotizar->setCodigoPostal($datos['codigo_postal']);
		$cotizar->setPeso($datos['peso']);
		$cotizar->setVolumen($datos['volumen']);
		$cotizar->setValorDeclarado($datos['valor_declarado']);

		$andreani = new Andreani($andreani_datos['usuario'],$andreani_datos['pass'],$andreani_datos['ambiente']);
		$response = $andreani->call($cotizar);

		return $response;
	}
}