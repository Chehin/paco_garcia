<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class pagoController extends Controller
{
    
    public function todoPago(Request $request, Api $api){
           
            $id_pedido = $request['id_pedido'];
            $total = $request['total'];
            
            //
            $array_de = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'id_usuario' => $_SESSION["id_user"]
            );

            $data =Util::aResult();

            try {
                $post = http_build_query($array_de);
                $data  = $api->client->resJson('GET', 'cartGet?'.$post);
                if ($data ['status'] == 0){
                    $carrito=$data['data'];
                    $_SESSION['carrito']=$carrito;
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
            //
        
			$array_send = array(
				'id_pedido' => $id_pedido,
				'total' => $total,
				'item' => count($carrito['carrito'])
			);
            $dataT = Util::aResult();

            try {
                $postT = http_build_query($array_send);
                $dataT = $api->client->resJson('GET','todoPago?'.$postT);
                if ($dataT['status'] == 0){
                    $todo_pago=$dataT['data'];
                }            
                $this->view_ready($api);
                if ($todo_pago['StatusCode'] != -1) {
                    if ($todo_pago['StatusCode'] == 98001) {
                        return redirect(env('URL_BASE').'?error_tp?status='.$todo_pago['StatusCode']);
                    }
                } else {
                    return redirect($todo_pago['URL_Request']);
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
    }

    public function exito_tp(Request $request, Api $api){
           
            $pageTitle = env('SITE_NAME') . " - Checkout Todo Pago";
            $this->view_ready($api);
            $operationid = strip_tags($request['operationid']);
			$answerKey = strip_tags($request['Answer']);
			$id_pedido = $request['id_pedido'];
			$array_send = array(
				'id_pedido' => $id_pedido,
				'operationid' => $operationid,
				'answerKey' => $answerKey
            );
            
            $data=Util::aResult();

            try {
                $post = http_build_query($array_send);
                $data = $api->client->resJson('GET', 'validarPagoTP?'.$post);
                if ($data['status'] == 0){
                    $todo_pago=$data['data'];
                } 

                if ($todo_pago['StatusCode'] != -1){
                    $icon = "fa-times";
                    $estado_color = "danger";
                    $estado = "OPERACION :".htmlspecialchars($operationid)." rechazada!";
                    if ($todo_pago['StatusCode'] == 98001) {
                        $estado_detalle = "Para operar con TodoPago debe agregar una dirección";
                    } else {
                        $estado_detalle = $todo_pago['StatusMessage'];
                    }
                }else{
                    $icon = "fa-check";
                    $estado_color = "success";
                    $estado = "Compra realizada!";
                    $estado_detalle = "OPERACION :".htmlspecialchars($operationid) . " " . $todo_pago['StatusMessage'];
                    // Si el pago fue aprobado elimino datos de carrito
                    //
                    $array_de = array(
                        'id_edicion' => 'MOD_PEDIDOS_FILTER',
                        'edicion' => 'pedidos',
                        'id_usuario' => $_SESSION["id_user"]
                    );

                    $dataT=Util::aResult();

                    try {
                        $postT = http_build_query($array_de);
                        $dataT = $api->client->resJson('GET', 'cartGet?'.$postT);
                        if ($dataT['status'] == 0){
                            $carrito=$dataT['data'];
                            $_SESSION['carrito']=$carrito;
                        }
                    } catch (RequestException $e) {
                        Log::error(Psr7\str($e->getRequest()));
                        if ($e->hasResponse()) {
                            Log::error($e->getMessage());
                        }
                    }                    
                    //
                
                }

                return view('cart.exito_tp', ['pageTitle'=>$pageTitle])->with('icon', $icon)
                                                                   ->with('estado_color', $estado_color)
                                                                   ->with('estado', $estado)
                                                                   ->with('estado_detalle', $estado_detalle);
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        
    }

    public function notificaciones(Request $request, Api $api){
       
        \Log::info('notifi');
        if (!isset($request["id"], $request["topic"]) || !ctype_digit($request["id"])) {
            http_response_code(400);
            return;
        }else{
            $data = array(
                'id' => $_GET["id"],
                'topic' => $_GET["topic"]
            );
            $array_data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
			    'data' => $data,
            );

            $notificaciones_meli=Util::aResult();

            try {
                $postT = http_build_query($array_data);
                $notificaciones_meli = $api->client->resJson('GET', 'notificaciones_meli?'.$postT);
                \Log::info(print_r($notificaciones_meli,true));
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
    public function notificaciones_meli(Request $request, Api $api){
        \Log::info('notificaciones_meli');
        header("HTTP/1.1 200 OK");
        $notifications=file_get_contents("php://input");
        $notifications=json_decode($notifications);
        $data = array(
            'resource' => $notifications->resource,
            'topic' => $notifications->topic
        );
        $array_data = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'data' => $data,
        );
        try {
            $postT = http_build_query($array_data);
            $notificaciones_meli = $api->client->resJson('GET', 'notificaciones_mercadolibre?'.$postT);
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }           
    }

    public function checkout(Request $request, Api $api){
        \Log::debug('sesion: '.print_r($_SESSION,true));
        $pageTitle = env('SITE_NAME') . 'Checkout';
        $this->view_ready($api);
        //si el pago no es rechazado
        //eliminar carrito de session
        if($request['collection_id'] && $request['external_reference'] && $request['collection_id']!='null'){		
            $array_data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'id_usuario' => $_SESSION["id_user"],
                'id_pedido' => $request['external_reference'],
                'collection_id' => $request['collection_id'],
            );			

             $checkout=Util::aResult();

            try {
                $post = http_build_query($array_data);
                $checkout = $api->client->resJson('GET','cartCheckout?'.$post);
               
                if($checkout['status']>0){
                    $estado = 'Hubo un problema inesperado';
                    $estado_detalle = 'Por favor vuelva a realizar el pedido';
                }else{
                    if($checkout['data']['error']>0){
                        $estado = $checkout['data']['msg'];
                        $estado_detalle = 'Por favor vuelva a realizar el pedido';
                        $estado_color = 'danger';
                        $estado_ico = 'times';
                    }else{
                        $estado = $checkout['data']['estado'];
                        $estado_detalle = $checkout['data']['estado_detalle'];
                        $estado_color = $checkout['data']['estado_color'];
                        $estado_ico = $checkout['data']['estado_ico'];
                        $carritoPago = $checkout['data']['carrito'];
                    }
                    
                    //datos del pedido
                    $array_de = array(
                        'id_edicion' => 'MOD_PEDIDOS_FILTER',
                        'edicion' => 'pedidos',
                        'id_usuario' => $_SESSION["id_user"]
                    );

                    $data=Util::aResult();

                    try {
                        $post = http_build_query($array_de);
                        $data = $api->client->resJson('GET', 'cartGet?'.$post);
                        $carrito=$data['data'];
                        $_SESSION['carrito']=$carrito;
                    } catch (RequestException $e) {
                        Log::error(Psr7\str($e->getRequest()));
                        if ($e->hasResponse()) {
                            Log::error($e->getMessage());
                        }
                    }
                }

                return view('cart.checkout', ['carritoPago'=>$carritoPago,'pageTitle'=>$pageTitle])->with('estado_ico', $estado_ico)
                                                                    ->with('estado_color', $estado_color)
                                                                    ->with('estado', $estado)
                                                                    ->with('estado_detalle', $estado_detalle);
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }

        }elseif ($request['opcion'] && isset($_SESSION['carrito']['carrito'][0])) {
            if ($request['opcion'] == 1) {
                $estado_pago = 'payment_in_branch';
                $metodo_pago = 'Pago en sucursal';
            } elseif ($request['opcion'] == 2) {
                $estado_pago = 'cash_on_delivery';
                $metodo_pago = 'Contrareembolso';
            } elseif ($request['opcion'] == 3) {
                
                $estado_pago = 'payment_in_branch';
                $metodo_pago = 'Tarjeta de Crédito';

                $array_data = array(
                    'id_usuario' => $_SESSION["id_user"],
                    'id_pedido' => $_SESSION['carrito']['id_pedido'],
                    'nombre' => $request['nombre'],
                    'apellido' => $request['apellido'],
                    'dni' => $request['dni'],
                    'email' => $request['email'],
                    'telefono' => $request['telefono'],
                    'numTarjeta' => $request['tarjeta'],
                    'venc' => $request['vencimiento'],
                    'cvc' => $request['cvc']                   
                );     
                
                try {
                    $post = http_build_query($array_data);
                    $data = $api->client->resJson('GET', 'cartTarjeta?'.$post);
                } catch (RequestException $e) {
                    Log::error(Psr7\str($e->getRequest()));
                    if ($e->hasResponse()) {
                        Log::error($e->getMessage());
                    }
                }
            

            }else{
                return redirect('cart');
            }
            $array_data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'id_usuario' => $_SESSION["id_user"],
                'estado_pago' => $estado_pago,
                'metodo_pago' => $metodo_pago,
            );
            $checkout=Util::aResult();

            try {
                $post = http_build_query($array_data);
                $checkout = $api->client->resJson('GET','estadoPagoPut?'.$post);
               
                if($checkout['status']==0){
                    if ($request['opcion'] == 1 || $request['opcion'] == 3) {
                        $estado_detalle = "Debe pasar por la sucursal de Paco Garcia para abonar y retirar los productos";
                        $mapa = true;                        
                    }else{
                        $estado_detalle = "Los productos serán enviados a la direccion seleccionada, debe abonar el pedido en el domicilio";
                    }
                    $estado_ico = "check";
					$estado_color = "success";
                    $estado = "Su compra se registro en el sistema!";
                    //datos del pedido
                    $array_de = array(
                        'id_edicion' => 'MOD_PEDIDOS_FILTER',
                        'edicion' => 'pedidos',
                        'id_usuario' => $_SESSION["id_user"]
                    );
                    $data=Util::aResult();
                    try {
                        $post = http_build_query($array_de);
                        $data = $api->client->resJson('GET', 'cartGet?'.$post);
                        $carrito=$data['data'];
                        $_SESSION['carrito']=$carrito;
                    } catch (RequestException $e) {
                        Log::error(Psr7\str($e->getRequest()));
                        if ($e->hasResponse()) {
                            Log::error($e->getMessage());
                        }
                        return redirect('cart');
                    }
                }else{
                    return redirect('cart');
                }
                $this->view_ready($api);
                return view('cart.checkout', ['pageTitle'=>$pageTitle])
                ->with('estado_ico', $estado_ico)
                ->with('estado_color', $estado_color)
                ->with('estado', $estado)
                ->with('mapa', $mapa)
                ->with('estado_detalle', $estado_detalle);
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        }else{
            return redirect('cart');
        }





    }

    public function enviar_mail(Request $request, Api $api){//solo para emergencias
        $array_data = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_pedido' => 30321,
        );

        try {
            $postT = http_build_query($array_data);
            $email = $api->client->resJson('GET', 'email?'.$postT);            
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }           
    }

}