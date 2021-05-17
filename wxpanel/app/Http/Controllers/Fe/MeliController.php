<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Meli;
use App\AppCustom\Util;
use App\AppCustom\Cart;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\MercadoLibre;
use App\AppCustom\Models\Notificaciones;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosMeli;
use App\AppCustom\Models\Provincias;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Preguntas;
use App\AppCustom\Models\Talles;
use Carbon\Carbon;

class MeliController extends Controller
{
    private $app_id;
    private $app_secret;
    private $access_token;
    private $meli;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->resource = $request->input('edicion');
        $this->filterNote = \config('appCustom.' . $request->input('id_edicion'));

        // Obtengo el access_token, refresh_token, expires
        // Necesito estos datos para verificar si el token esta vencido

        $mercado_libre = MercadoLibre::orderBy('id', 'desc')->first();

        if ($mercado_libre) {
            $this->access_token = $mercado_libre->access_token;
            $this->app_id = config('mercadolibre.app_id');
            $this->app_secret = config('mercadolibre.app_secret');
            $this->meli = new Meli($this->app_id, $this->app_secret, $this->access_token, $mercado_libre->refresh_token);
            // Verifico si el token esta vencidos
            if ($mercado_libre->expires < time()) {
                // Actualizo el token vencido

                $token = $this->meli->refreshAccessToken();

                // Verifico si se renovo correctamente el token
                if ($token['httpCode'] == 200) {
                    if ($token['body']->access_token != '' && $token['body']->refresh_token != '' && $token['body']->expires_in != '') {
                        // Guardo el nuevo token en DB

                        $this->access_token = $token['body']->access_token;
                        $mercado_libre = new MercadoLibre();

                        $mercado_libre->access_token = $token['body']->access_token;
                        $mercado_libre->refresh_token = $token['body']->refresh_token;
                        $mercado_libre->expires = time() + $token['body']->expires_in;

                        $mercado_libre->save();
                    }
                }
            }
        }
    }

    static function getToken()
    {
        return $this->access_token;
    }

    public function setAccessToken(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        $access_token = $request->input('access_token');
        $refresh_token = $request->input('refresh_token');
        $expires = $request->input('expires');

        $mercado_libre = new MercadoLibre();

        $mercado_libre->access_token = $access_token;
        $mercado_libre->refresh_token = $refresh_token;
        $mercado_libre->expires = $expires;

        $mercado_libre->save();

        $aResult['data'] = $access_token;

        return response()->json($aResult);
    }

    public function notificaciones_mercadolibre(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
            $data = $request->input('data');
            \Log::info($data['topic']);
            switch ($data['topic']) {
                case 'questions':
                    $resource = $data['resource'];
                    $pregunta_info = $this->getInfoResource($resource);
                    if ($pregunta_info) {
                        $item_meli = $this->getItem($pregunta_info->item_id);
                        $usuario_info = $this->getInfoResource('/users/' . $pregunta_info->from->id);
                        // GUARDO LA INFORMACION DEL USUARIO QUE HIZO LA PREGUNTA
                        $usuario = PedidosClientes::where('id_usuario_meli', '=', $usuario_info->id)->first();
                        if (!$usuario) {
                            $usuario = new PedidosClientes;
                        }
                        $fecha_nac = substr($usuario_info->registration_date, 0, 10);
                        $usuario->id_usuario_meli = $usuario_info->id;
                        $usuario->nickname_meli = $usuario_info->nickname;
                        $usuario->reputacion_meli = $usuario_info->permalink;
                        $usuario->fecha_nacimiento = $fecha_nac;
                        $usuario->save();
                        if ($pregunta_info->status == 'UNANSWERED') {
                            $pregunta = Preguntas::where('id_pregunta_meli', '=', $pregunta_info->id)->first();
                            if (!$pregunta) {
                                $pregunta = new Preguntas;
                            }
                            $pregunta->id_pregunta_meli = $pregunta_info->id;
                            $pregunta->nickname_meli = $usuario_info->nickname;
                            $pregunta->pregunta_meli = $pregunta_info->text;
                            $pregunta->fecha_pregunta = Carbon::now()->format('Y-m-d H:m:s');
                            $pregunta->id_meli = $pregunta_info->item_id;
                            $pregunta->id_usuario_meli = $pregunta_info->from->id;
                            $pregunta->save();

                            // GUARDO LA NOTIFICACION
                            $texto = "Te preguntaron algo.";
                            $topic = "questions";
                            $this->guardarNotificacion($pregunta_info, $topic, $texto, $item_meli->title);
                        }
                    }
                    break;
                case 'created_orders':
                    $resource = $data['resource'];
                    $pedido_info = $this->getInfoResource($resource);
                    $usuario_info = $this->getInfoResource('/users/' . $pedido_info->buyer->id);
                    $fecha_nac = substr($usuario_info->registration_date, 0, 10);
                    $usuario = PedidosClientes::select('id')->where('id_usuario_meli', '=', $usuario_info->id)->first();
                    if (!$usuario) {
                        $usuario = new PedidosClientes;
                    }
                    $usuario->id_usuario_meli = $usuario_info->id;
                    $usuario->nickname_meli = $usuario_info->nickname;
                    $usuario->reputacion_meli = $usuario_info->permalink;
                    $usuario->fecha_nacimiento = $fecha_nac;

                    \Log::info('pedido_info');
                    \Log::info(json_encode($pedido_info));

                    if (isset($pedido_info->total_amount_with_shipping)) {
                        // Si el pedido fue pagado ML devuelve mas datos sobre el usuario
                        if ($pedido_info->paid_amount >= $pedido_info->total_amount_with_shipping) {

                            $usuario->nombre = isset($pedido_info->buyer->first_name) ? $pedido_info->buyer->first_name : $usuario_info->nickname;
                            $usuario->apellido = isset($pedido_info->buyer->last_name) ? $pedido_info->buyer->last_name : '';
                            $usuario->mail = isset($pedido_info->buyer->email) ? $pedido_info->buyer->email : '';
                            $usuario->dni = isset($pedido_info->buyer->billing_info->doc_number) ? $pedido_info->buyer->billing_info->doc_number : '';
                            $usuario->telefono = (isset($pedido_info->buyer->phone->number) && isset($pedido_info->buyer->phone->area_code)) ? $pedido_info->buyer->phone->area_code . "-" . $pedido_info->buyer->phone->number : '';
                        }
                    }

                    $usuario->save();

                    // GUARDO O ACTUALIZO LOS DATOS DEL PEDIDO
                    $fecha = $pedido_info->date_created;
                    $collection_id = $pedido_info->id;


                    $pedido = PedidosMeli::where('collection_id', $collection_id)->first();
                    \Log::info(json_encode($pedido));
                    //die;
                    if (!$pedido) {
                        \Log::info('no existe el pedido debe crearlo');
                        $pedido = new Pedidos;
                        $pedido->id_pedido = $pedido_info->id;

                            $id_pedido = $pedido->id_pedido;
                            $estado = '';
                            if (isset($pedido_info->payments[0])) {
                                $estado = $pedido_info->payments[0]->status;
                                $pedido->estado = $pedido_info->payments[0]->status;
                                $pedido->detalle_estado = $pedido_info->payments[0]->status_detail;
                                $pedido->precio_venta = $pedido_info->payments[0]->transaction_amount;
                                $pedido->metodo_mercado = $pedido_info->payments[0]->payment_type;
                                $pedido->payment_id = $pedido_info->payments[0]->id;
                                $pedido->fecha_aprobacion = $pedido_info->payments[0]->date_approved;
                                $pedido->fecha_modificacion = $pedido_info->payments[0]->date_last_modified;
                                $pedido->total = $pedido_info->payments[0]->total_paid_amount;
                            } else {
                                $estado = $pedido_info->status;
                                $pedido->estado = $pedido_info->status;
                            }

                            $pedido->id_usuario = $usuario->id;
                            $pedido->metodo_pago = 'Mercado Pago';
                            $pedido->comprado_desde = '1';
                            $pedido->collection_id = $collection_id;
                            $pedido->estado_envio = (isset($pedido_info->shipping->status)) ? $pedido_info->shipping->status : '';

                            if (isset($pedido_info->total_amount_with_shipping)) {
                                if ($pedido_info->paid_amount >= $pedido_info->total_amount_with_shipping) {
                                    if (isset($pedido_info->shipping->id)) { // El pedido tiene envíos
                                        if ($pedido_info->shipping->status == "ready_to_ship") {
                                            $estado_envio_detalle = "PAGADO. Envie su pedido.";
                                            $texto = "Te compraron algo";
                                            $envio = true;
                                            $shipments = $this->getInfoResource('/shipments/' . $pedido_info->shipping->id);
                                            $pedido->tracking_number = $shipments->tracking_number;
                                            $pedido->etiqueta_envio = $shipments->substatus;
                                        } else { // El pedido no tiene ningún envío (retira sucursal)
                                            $estado_envio_detalle = "PAGADO. Puede entregar su artículo.";
                                            $texto = "Te compraron algo";
                                            $envio = false;
                                        }
                                        $pedido->id_envio_meli = $pedido_info->shipping->id;;
                                        $pedido->costo_envio = $pedido_info->shipping->cost;
                                        if (isset($pedido_info->shipping->shipping_option->shipping_method_id)) {
                                            $pedido->id_tipo_envio = $pedido_info->shipping->shipping_option->shipping_method_id;
                                        }
                                        if (isset($pedido_info->shipping->shipping_option->estimated_delivery_time->shipping)) {
                                            $pedido->tiempo_entrega = $pedido_info->shipping->shipping_option->estimated_delivery_time->shipping; // horas
                                        }
                                    } else { // El pedido no tiene ningún envío (retira sucursal)
                                        $estado_envio_detalle = "PAGADO. Puede entregar su artículo.";
                                        $texto = "Te compraron algo.";
                                        $envio = false;
                                    }
                                    // Guardo en la tabla notificaciones
                                    $topic = "orders";
                                    $this->guardarNotificacion($pedido_info, $topic, $texto, $pedido_info->order_items[0]->item->title);
                                } else {
                                    $estado_envio_detalle = "No se ha pagado. No entregar su artículo.";
                                    $texto = "Compra pendiente de pago.";
                                    $topic = "orders";
                                    $envio = false;
                                    $this->guardarNotificacion($pedido_info, $topic, $texto, $pedido_info->order_items[0]->item->title);
                                }
                                $pedido->estado_envio_detalle = $estado_envio_detalle;
                            }



                            $moneda_default = Util::getMonedaDefault();
                            $id_moneda = ($moneda_default ? $moneda_default[0]['id'] : 1);
                            $pedido->id_moneda = $id_moneda;
                          
                          \Log::info(json_encode($pedido));
                            $pedido->save();

                            $order_items = json_decode(json_encode($pedido_info->order_items), true);
                            foreach ($order_items as $pedido_producto) {
                                $producto = Productos::select('id', 'id_marca', 'id_genero', 'id_rubro')->where('id_meli', $pedido_producto['item']['id'])->first();
                                if ($producto) {
                                    $codigo_stock = CodigoStock::where('id_producto', $producto->id);
                                    if ($pedido_producto['item']['seller_custom_field']) {
                                        $codigo_stock = $codigo_stock->where('codigo', '=', $pedido_producto['item']['seller_custom_field']);
                                    }

                                    //obtengo el talle
                                    $variation = $pedido_producto['item']['variation_attributes'];
                                    $key = array_search('SIZE', array_column($variation, 'id'));
                                    if (isset($variation[$key]['value_name'])) {
                                        $nombreTalle = $variation[$key]['value_name'];
                                        switch ($producto->id_marca) { //segun US/UK
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

                                            case 31: //crocs US
                                                $numeracion = 2;
                                                break;

                                            default:
                                                $numeracion = 1;
                                                break;
                                        }
                                        $equi = Util::getTalleEquivalenteInvertida($nombreTalle, $producto->id_marca, $producto->id_genero, $numeracion, $producto->id_rubro);
                                        if ($equi['talle']) {
                                            $nombreTalleEqui = $equi['talle'];
                                        } else {
                                            $nombreTalleEqui = $nombreTalle;
                                        }
                                        $talle = Talles::select('id')->where('nombre', $nombreTalleEqui)->first();
                                        if ($talle) {
                                            $codigo_stock = $codigo_stock->where('id_talle', $talle->id);
                                        }
                                    }

                                    $codigo_stock = $codigo_stock->first();
                                    if (!$codigo_stock && isset($variation[$key]['value_name'])) {
                                        $talle = Talles::select('id')->where('nombre', $variation[$key]['value_name'])->first();
                                        if ($talle) {
                                            $codigo_stock = CodigoStock::where('id_producto', $producto->id)->where('codigo', '=', $pedido_producto['item']['seller_custom_field'])->where('id_talle', $talle->id)->first();
                                        }
                                    }
                                    if ($codigo_stock) {
                                        $ped_prod = PedidosProductos::where('id_pedido', $pedido->id_pedido)
                                            ->where('id_producto', $producto->id)
                                            ->where('id_color', $codigo_stock->id_color)
                                            ->where('id_talle', $codigo_stock->id_talle)
                                            ->first();
                                        if (!$ped_prod) {
                                            $ped_prod = new PedidosProductos;
                                        }
                                        $ped_prod->id_pedido = $pedido->id_pedido;
                                        $ped_prod->id_producto = $producto->id;
                                        $ped_prod->nombre = $pedido_producto['item']['title'];
                                        $ped_prod->precio = $pedido_producto['unit_price'];
                                        $ped_prod->cantidad = $pedido_producto['quantity'];
                                        $ped_prod->id_color = $codigo_stock->id_color;
                                        $ped_prod->id_talle = $codigo_stock->id_talle;
                                        $ped_prod->codigo = $codigo_stock->codigo;
                                        $moneda_default = Util::getMonedaDefault();
                                        $id_moneda = ($moneda_default ? $moneda_default[0]['id'] : 1);
                                        $ped_prod->id_moneda = $id_moneda;

                                      // $ped_prod->save();
                                    }
                                }
                            }

                            if ($estado != "approved") {
                                if (Cart::tieneReserva($pedido->id_pedido)) {
                                    Cart::liberarStock($pedido->id_pedido);
                                }
                            } else {
                                if (!Cart::tieneReserva($pedido->id_pedido)) {
                                    Cart::reservarStock($pedido->id_pedido, 417);
                                }
                            }

                            if (isset($pedido_info->shipping->id)) {
                                // GUARDO LA DIRECCION DE ENVIO
                                if (!$pedido->id_direccion_envio) {
                                    $ped_dire = new PedidosDirecciones;
                                } else {
                                    $ped_dire = PedidosDirecciones::find($pedido->id_direccion_envio);
                                }
                                if (isset($shipments->receiver_address->address_line)) {
                                    $ped_dire->direccion = $shipments->receiver_address->address_line;
                                }
                                if (isset($shipments->receiver_address->city->name)) {
                                    $ped_dire->ciudad = $shipments->receiver_address->city->name;
                                }
                                if (isset($shipments->receiver_address->zip_code)) {
                                    $ped_dire->cp = $shipments->receiver_address->zip_code;
                                }
                                if (isset($shipments->receiver_address->comment)) {
                                    $ped_dire->informacion_adicional = $shipments->receiver_address->comment;
                                }
                                if (isset($shipments->receiver_address->phone)) {
                                    $ped_dire->telefono = $shipments->receiver_address->phone;
                                }
                                $ped_dire->mercadopago = 1;

                                if (isset($shipments->receiver_address->state->name)) {
                                    $provincias = Provincias::select('id')->where('provincia', $shipments->receiver_address->state->name)->first();
                                    if ($provincias) {
                                        $provincia = $provincias->id;
                                    }
                                }

                                $ped_dire->save();
                            }
                    }

                    break;
                case 'payments':
                    $resource = $data['resource'];
                    $payment_info = $this->getInfoResource($resource);
                    $pedido = PedidosMeli::where('payment_id', '=', $payment_info->id)->first();
                    if ($pedido) {
                        $estado = $payment_info->status;
                        if ($estado == "refunded" || $estado == "cancelled" || $estado == "charged_back") {
                            if (Cart::tieneReserva($pedido->id_pedido)) {
                                Cart::liberarStock($pedido->id_pedido);
                            }
                            $pedido->metodo_pago = 'Mercado Pago';
                            $pedido->estado = $payment_info->status;
                            $pedido->metodo_mercado = $payment_info->payment_type;
                            $pedido->detalle_estado = $payment_info->status_detail;
                            $pedido->fecha_aprobacion = $payment_info->date_approved;
                            $pedido->fecha_modificacion = $payment_info->last_modified;
                            $pedido->estado_envio = "to_be_agreed";
                            $pedido->estado_envio_detalle = "No se ha pagado. No entregar su artículo.";
                            $pedido->save();
                        }
                    }
                    break;

                default:
                    # code...
                    break;
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

    // Obtengo la información del recurso, "pedidos", "preguntas", "pagos"
    private function getInfoResource($resource)
    {
        $item = $this->meli->get($resource, ['access_token' => $this->access_token]);
        if ($item['httpCode'] == 200) {
            return $item['body'];
        } else {
            return false;
        }
    }

    // Obtengo los datos del item
    private function getItem($id_meli)
    {
        // Con el id de meli obtengo los datos de la publicación
        $url = "/items/" . $id_meli;
        $item = $this->meli->get($url);
        if ($item['httpCode'] == 200) {
            return $item['body'];
        } else {
            return false;
        }
    }

    private function guardarNotificacion($resource, $topic, $texto, $nombre)
    {
        //$notificaciones = Notificaciones::find($resource->id);
        $notificaciones = Notificaciones::where('id_topic', $resource->id)->first();
        if (!$notificaciones) {
            $notificaciones = new Notificaciones;
            //$notificaciones->id = $resource->id;
        }
        $notificaciones->topic = $topic;
        $notificaciones->texto = $texto;
        $notificaciones->producto = $nombre;
        $notificaciones->fecha_creacion = Carbon::now()->format('Y-m-d H:m:s');
        $notificaciones->id_topic = $resource->id;
        $notificaciones->save();
    }
}
