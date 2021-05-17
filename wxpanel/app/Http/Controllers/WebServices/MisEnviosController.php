<?php

namespace App\Http\Controllers\WebServices;

use App\AppCustom\Api;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\MisEnvios;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosNotificaciones;
use App\AppCustom\Models\Localidades;
use App\AppCustom\Models\Provincias;
use App\AppCustom\ClientCustom;
use GuzzleHttp\Client;
use App\Http\Requests;
use DateTime as GlobalDateTime;
use Faker\Provider\ka_GE\DateTime;

//use function GuzzleHttp\json_decode;

class MisEnviosController extends Controller
{
    //Consulta API
    public function apiMisEnvios($method, $url, $body = null){

        $url_base = 'http://clientes.sispo.com.ar/api/';
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

    //GET consulta el costo de un envio
    public function getPrecioEnvio($envio){

        $token = $this->obtenerToken();
        $envio['access_token'] = $token;
        $params = http_build_query($envio);

        $result = $this->apiMisEnvios('GET', 'shipnow?'.$params);

        if ($result) {
            $array_response = json_decode($result, true);
        }else{
            $array_response = array(
                'codigo' => 404,
                'mensaje' => 'Peticion Fallida'
            );
        }
        return $array_response;
    }

    //GET obtiene las sucursales
    public function getTiposDocumentos(){
        
        $result = $this->apiMisEnvios('GET', 'tiposdocumentos');

        if ($result) {
            $array_response = json_decode($result, true);
                
            if (isset($array_response["codigo"])) {
                if ($array_response["codigo"] == 200) {
                    return $array_response["data"];
                }else{
                    return 'ERROR!';
                }
            }
        }
    }

    //GET obtiene las sucursales
    public function getSucursales(){
        $aResult = Util::getDefaultArrayResult();
	
        $result = $this->apiMisEnvios('GET', 'sucursales');

        if ($result) {
            $array_response = json_decode($result, true);
                
            if (isset($array_response["codigo"])) {
                if ($array_response["codigo"] == 200) {
                    
                    $aResult['data'] = $array_response["data"];
                }else{
                    $aResult['status'] = 1;
		            $aResult['msg'] = 'Error Mis Envios';
                }
            }else{
                $aResult['status'] = 1;
                $aResult['msg'] = 'Error de Conexion';
            }
        }else{
            $aResult['status'] = 1;
            $aResult['msg'] = 'Error!';
        }
	
	    return $aResult;
    }

    //POST obtiene un token
    public function getAccessToken(){
        $data = array(
            'api-key' => \config('appCustom.MIS_ENVIOS_API_KEY'),
            'secret-key' => \config('appCustom.MIS_ENVIOS_SECRET_KEY')
        );

        $result = $this->apiMisEnvios('POST', 'tokens', $data);

        if ($result) {
            $array_response = json_decode($result, true);
                
            if (isset($array_response["codigo"])) {
                if ($array_response["codigo"] == 200) {
                    
                    $this->guardarNuevoToken($array_response["access_token"]);
                    return $array_response["access_token"];
                }else{
                    return 'ERROR!';
                }
            }
        }
    }

    //Guarda un Nuevo token con vencimiento de 4hs    
    public function guardarNuevoToken($access){
        
        MisEnvios::truncate();
        
        $vencimiento = new GlobalDateTime();
        $vencimiento->modify('+4 Hours');
        $expires = $vencimiento->format('Y-m-d H:i:00');
        $token = new MisEnvios();
        $token->access_token = $access;
        $token->expires = $expires;

        $token->save();
    }

    //Obtiene un token, si esta vencido pide otro
    public function obtenerToken(){
        $fecha_actual = date('Y-m-d H:i:00');
        $tokens = MisEnvios::get();
        $miToken = "";

        foreach($tokens as $token){
            if($token->expires>$fecha_actual){
                $miToken = $token->access_token;
            }
        }

        if($miToken==''){
            $miToken = $this->getAccessToken();
        }

        return $miToken;
    }

    //Realiza el alta del envio
    public function altaEnvio($id_pedido, $id_sucursal=0){
        \Log::info('alta MisEnvios');
        $aResult = Util::getDefaultArrayResult();
        
        $pedido = Pedidos::where('id_pedido', '=' , $id_pedido)->first();

        if($pedido){
            if(!$pedido->tracking_number){
                $address = PedidosDirecciones::find($pedido->id_direccion_envio);
            
                $productos = PedidosProductos::leftJoin('inv_productos','inv_productos.id','=','pedidos_productos.id_producto')->where('id_pedido','=',$id_pedido)->get();
                $cliente = PedidosClientes::find($pedido->id_usuario);
                
                $alto = $ancho = $largo = $peso = 0;
                    
                foreach ($productos as $producto) {
                    $alto = $alto + $producto['alto']*$producto['cantidad'];
                    $ancho = $ancho + $producto['ancho']*$producto['cantidad'];
                    $largo = $largo + $producto['largo']*$producto['cantidad'];
                    $peso = $peso + $producto['peso']*$producto['cantidad'];
                }

                //Convertimos a kg y redondeamos peso hacia arriba
                $peso = $peso / 1000;
                $peso = round($peso, 2, PHP_ROUND_HALF_UP);

                //No Puede pesar menos de 1kg
                if ($peso < 1) {
                    $peso = 1;
                }

                //paquete
                $dimensiones = $alto.'x'.$ancho.'x'.$largo;
                $descripcion = 'Compra Online '. env('SITE_NAME'); //Descripcion del paquete fija
                $bultos = '1';  //cantidad de bultos fija
                $codigo = \config('appCustom.shortName') . $pedido->id_pedido.'-'.$pedido->id_usuario;

                //cliente
                $nombres = $cliente->nombre;
                $apellidos = $cliente->apellido;
                $documento = $pedido->dni;
                $telefono = $pedido->telefono;
                $mail = \config('appCustom.clientVentas');
                
                //direccion
                $cp = $address->cp;
                $calle = $address->direccion;
                $numero = $address->numero;
                $piso = $address->piso;
                $dpto = $address->departamento;
                $referencia_domicilio = $address->informacion_adicional;
                //$localidad = Localidades::find($address->id_localidad);
                //$localidadName = $localidad->nombre;
                $localidadName = $address->ciudad;
                $provincia = Provincias::find($address->id_provincia);
                $provinciaName = $provincia->provincia;

                //TIPOS DE DOCUMENTOS, DE MOMENTO SOLO MANEJAMOS DNI
                //$tipoDocumento = $this->getTiposDocumentos();

                $tipoDocumento = 'DNI';
                $token = $this->obtenerToken();

                //Dias de entrega No reuqerido
                $dias_entrega = '1';

                //Sucursales MIS ENVIOS
                $sucursal_origen = '4'; //ID sucursal TUCUMAN
                $sucursal_destino = '4';
                if($id_sucursal){
                    $sucursal_destino = $id_sucursal;
                }

                $params = [
                    'access_token' => $token,
                    'sucursal_origen'=> $sucursal_origen,
                    'codigo_externo'=> $codigo,
                    'descripcion_paquete'=> $descripcion,
                    'dimensiones'=> $dimensiones,
                    'peso'=> $peso,
                    'bultos'=> $bultos,
                    'dias_entrega'=> $dias_entrega,
                    'nombres'=> $nombres,
                    'apellidos'=> $apellidos,
                    'tipo_documento'=> $tipoDocumento,
                    'documento'=> $documento,
                    'telefono'=> $telefono,
                    'mail'=> $mail,
                    'calle'=> $calle,
                    'numero'=> $numero,
                    'piso'=> $piso,
                    'depto'=> $dpto,
                    'referencia_domicilio'=> $referencia_domicilio,
                    'codigo_postal'=> $cp,
                    'localidad_ciudad'=> $localidadName,
                    'provincia'=> $provinciaName,
                    'sucursal_destino'=> $sucursal_destino
                ];

                //HACE EL ENVIO
                $response = $this->apiMisEnvios('POST', 'envios', $params);
                \Log::info($response);

                //Guardo una Notificacion
                $notificacion = new PedidosNotificaciones();
                $notificacion->id_pedido = $pedido->id_pedido;
                $notificacion->texto = 'Alta Envio';
                $notificacion->status = '';
                $notificacion->more_info = json_encode($response);
                $notificacion->emisor = 'Mis envios';
                $notificacion->save();

                $response = json_decode($response, true);
                if ($response['codigo']==200) {
                    $pedido->tracking_number = $response['codigo_externo'];
                    $pedido->save();
                    
                    $aResult['data'] = $response;
                }else{
                    $aResult['status'] = 1;
                    $aResult['msg'] = 'Error en Mis envios';
                }
            }else{
                $aResult['status'] = 2;
            	$aResult['msg'] = 'Este pedido ya fue creado en Mis envios';
            }
        }else{
            $aResult['status'] = 1;
            $aResult['msg'] = 'Pedido no encontrado';
        }

        return $aResult;
    }
}
