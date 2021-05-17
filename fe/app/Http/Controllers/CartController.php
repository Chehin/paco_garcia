<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class CartController extends Controller
{

    public function update_cart(Request $request, Api $api)
    { 
       $id_cookie = '';
        //si no esta logeado le asigno un id
        if(!isset($_SESSION['id_user'])){
            $_SESSION['id_user']=0;
        }
        if(!isset($_COOKIE["id_usuario_".env('APP_NAME')])){
            $id_usuario_cookie = md5(uniqid(rand(), true));
            setcookie("id_usuario_".env('APP_NAME'), $id_usuario_cookie , time()+(60*60*24*365));
        }
        $id_cookie = isset($id_usuario_cookie)?$id_usuario_cookie:$_COOKIE["id_usuario_".env('APP_NAME')];
        if($_SESSION['id_user']>0){
            $id_cookie = '';
        }
        $id = $request['id'];
        $id_color = $request['id_color'];
        $id_talle = $request['id_talle'];
        $method = $request['method'];
        $nombre = $request['nombre'];

        if($method=='add'){
            $data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
				'edicion' => 'pedidos',
                'id_usuario' => $_SESSION['id_user'],
                'cookie' => $id_cookie,
                'id_producto' => $id,
                'cantidad' => $request['cantidad'],
                'id_color' => $id_color,
                'id_talle' => $id_talle,
                'nombre' => $nombre,
                'id_moneda' => (int)env('ID_MONEDA')
            );
                
             //
             $array_data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'item' => $data,
                'cookie' => $data['cookie']
            );
            
            $dataC=Util::aResult();

            try {
                $postC = http_build_query($array_data);
                $dataC = $api->client->resJson('GET', 'cartAdd?'.$postC);
                if ($dataC['status'] == 0){
                        $carrito=$dataC['data'];
                        $_SESSION['carrito']=$carrito;
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        }elseif($method=='remove'){
            $id_pedido_producto=$request['id'];
            $data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
				'edicion' => 'pedidos',
                'id_usuario' => $_SESSION['id_user'],
                'cookie' => $id_cookie,
                'id_pedido_producto' => $id_pedido_producto
            );
            
            //
            
            $array_data = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'item' => $data,
                'cookie' => $data['cookie']
            );

            $dataC=Util::aResult();
        
            try {
                $postC = http_build_query($array_data);
               
                $dataC = $api->client->resJson('GET', 'cartRemove?'.$postC);
                
                if ($dataC['status'] == 0){
                        $carrito=$dataC['data'];
                        $_SESSION['carrito']=$carrito;
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
            //	
               
        }elseif($method=='get'){	
            if(!isset($_SESSION['carrito'])){
                $_SESSION['carrito'] = array();
            }        
            if(!$_SESSION['carrito'] || $_SESSION['id_user']){
                //
                $array = array(
                    'id_edicion' => 'MOD_PEDIDOS_FILTER',
                    'edicion' => 'pedidos',
                    'cookie' => $id_cookie,
                    'id_usuario' => $_SESSION['id_user']
                );

                $dataC=Util::aResult();
                try {                   
                    $postC = http_build_query($array);
                    $dataC = $api->client->resJson('GET','cartGet?'.$postC);
                    
                    if ($dataC['status'] == 0){
                        if($dataC['data']['error'] == 1){
                            $carrito['carrito'] = '';
                        }else{
                            $carrito=$dataC['data'];
                            $_SESSION['carrito']=$carrito;
                        }    
                    }
                } catch (RequestException $e) {
                    Log::error(Psr7\str($e->getRequest()));
                    if ($e->hasResponse()) {
                        Log::error($e->getMessage());
                    }
                }
                //		
            }else{
                if($_SESSION['carrito']['error'] == 1){
                    $carrito['carrito'] = '';
                }else{
                    $carrito =  $_SESSION['carrito'];
                }                  
            }
        }

        if($carrito['carrito']){ 
            //acá completo los datos del producto
            $_div = '';
            if($carrito['error']!=1){
            foreach($carrito['carrito'] as $cart){
                $foto = isset($cart['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').'th_'.$cart['fotos'][0]['imagen_file']:env('URL_BASE_UPLOADS').'img_default/th_producto.png';
                
                $_div .= '<li class="item" id="product_id_'.$cart['id_producto'].'_'.$cart['id_color'].'_'.$cart['talle'].'">';
                    $_div .='<a href="'.route('producto',['id' => $cart['id_producto'],'name' => str_slug($cart['titulo'])]) .'" title="'.$cart['titulo'].'" class="product-image">';
                        $_div .='<img src="'.$foto.'" alt="'.$cart['titulo'].'" width="65">';
                    $_div .='</a>';
                    $_div .='<div class="product-details">';
                        $_div .='<a href="javascript:void(0);" title="Eliminar producto" class="remove-cart" onclick="remove_to_cart('.$cart['id_pedido_producto'].')">';
                          $_div .='<i class="icon-close"></i>';
                        $_div .='</a>';
                        $_div .='<p class="product-name">';
                            $_div .='<a href="'.route('producto',['id' => $cart['id_producto'],'name' => str_slug($cart['titulo'])]) .'">'.$cart['titulo'].'</a>';
                        $_div .='</p>';
                            if($cart['color']){
                                $_div .= '<div class="">Color: <span>'.$cart['color'].'</span></div>';
                            }
                            if($cart['talle']){
                                $_div .= '<div class="">Talle: <span>'.$cart['talle'].'</span></div>';
                            }
                            $_div .='<strong>'.$cart['cantidad'].'</strong> x <span class="price">'.$cart['moneda'].$cart['precio']['precio'].'</span>';
                    $_div .='</div>';
                $_div .='</li>';
                   
    
                $array = array(
                    'data' => $_div,
                    'envio' => env('MONEDA_DEFAULT').$carrito['envio']['precio'],//precio subtotal carrito
                    'subtotal' => env('MONEDA_DEFAULT').$carrito['subtotal']['precio'],//precio subtotal carrito
                    'total' => env('MONEDA_DEFAULT').$carrito['total']['precio'],//precio subtotal carrito
                    'cantidad' => count($carrito['carrito']), //cantidad de productos en carrito
                    'id' => $cart['id_producto']
                ); 
                                    
            }
             
            
         }else{
            if($_div==''){
                $_div = '<li><div class="cart-product"><p>No hay productos</p></li></div>';
            }else{
                $_SESSION['carrito'] = $carrito;
            }
             $array = array(
                'data' => $_div,
                'envio' => env('MONEDA_DEFAULT'),//precio subtotal carrito
                'subtotal' => env('MONEDA_DEFAULT'),//precio subtotal carrito
                'total' => env('MONEDA_DEFAULT'),//precio subtotal carrito
                'cantidad' => 0,//cantidad de productos en carrito
                'id' => 0
            ); 
         }
       
        echo json_encode($array);
        
        }else{
            $_div = '<li><div class="cart-product"><p>No hay productos</p></li></div>';
            $array = array(
                'data' => $_div,
                'envio' => 0,//precio subtotal carrito
                'subtotal' => 0,//precio subtotal carrito
                'total' => 0,//precio subtotal carrito
                'cantidad' => 0 //cantidad de productos en carrito
            ); 
            echo json_encode($array);
        }
    }

    public function cart(Request $request, Api $api){

        $pageTitle = env('SITE_NAME') . " - Carrito";
        $this->view_ready($api);
        $id=$request['idProd'];
        $data='';

        //if($_SESSION['id_user']!=0){
            if($id!=0){
                //relacionados
                $array_send_p = array(
                    'id_edicion' => 'MOD_PRODUCT_FILTER',
                    'edicion' => 'productos',
                    'id_relacion' => $id,
                    'id_moneda' => env('ID_MONEDA'),
                    'fotos' => 1,
                    'limit' => 8,
                    'forzar' => true,
                    'orden' => array(
                        'col' => env('ORDEN_COL'),
                        'dir' => env('ORDEN_DIR')
                    ),
                    'iDisplayLength' => 99, //registros por pagina
                    'iDisplayStart' => 0, //registro inicial (dinamico)
                );

                $res=Util::aResult();
                $rel = array();
                $relacionados = array();
                try {
                    $post = http_build_query($array_send_p);
                    $res = $api->client->resJson('GET', 'listadoProductosRelacionados?'.$post)['data'];
                    $rel = $res;
                    $relacionados['productos'] = $rel['productos'];
                } catch (RequestException $e) {
                    Log::error(Psr7\str($e->getRequest()));
                    if ($e->hasResponse()) {
                        Log::error($e->getMessage());
                    }
                }
            }else{
                $relacionados['productos']='';
            }
 
            return view('cart.cart', compact('data','pageTitle','relacionados'));
        /*}else{
            return redirect('login');
        }*/

    }

    public function procesar_pedido(Request $request, Api $api){
       
        $pageTitle = env('SITE_NAME') . " - Procesar pedido";
        $this->view_ready($api);
        $id = $request['id'];
   
        if(isset($_SESSION['carrito']['carrito'][0])){
			switch ($id) {
                case 1:
                if($_SESSION['id_user']!=0){
                if(isset ($request['procesar_pedido']) ){
                    if($request['envio_db']!='' && $request['id_tipo_envio']!='' /* && $request['id_direccion_fact']!='' */ && ($request['id_direccion_envio']!='' || $request['id_sucursal'] !='')){
                        $id_pedido = $_SESSION['carrito']['id_pedido'];
                        $envio_db = $request['envio_db'];
                        $envio_dba = $request['cost_andreani'];
                        $id_tipo_envio = $request['id_tipo_envio'];
                        $id_direccion_envio = $request['id_direccion_envio'];
                        //$id_direccion_fact = $request['id_direccion_fact'];
                        $id_sucursal = $request['id_sucursal'];
                        $fecha_sucursal = $request['fecha_sucursal'];
                        $cuit = $request['cuit_data'];
                        $dni = $request['dni_data'];
                        $telefono = $request['telefono_data'];
                        $nombre = $request['nombre_data'];
                        $razon_social = $request['razon_social_data'];
                        $tipo_facturacion = $request['tipo_facturacion_data'];
                        $_SESSION["dni"]=$dni;

                            $data = array(
                                'id_pedido' => $id_pedido,
                                'id_pedido_producto' => 0,
                                'cantidad' => 0,
                                'envio_db' => $envio_db,
                                'envio_dba' => $envio_dba,
                                'id_tipo_envio' => $id_tipo_envio,
                                'id_direccion_envio' => $id_direccion_envio,
                                //'id_direccion_fact' => $id_direccion_fact,
                                'cuit' => $cuit,
                                'dni' => $dni,
                                'telefono' => $telefono,
                                'nombre' => $nombre,
                                'razon_social' => $razon_social,
                                'tipo_facturacion' => $tipo_facturacion,
                                'id_sucursal' => $id_sucursal,
                                'fecha_sucursal' => $fecha_sucursal,
                                'id_usuario' => $_SESSION["id_user"]
                            );

                            //        
                            $array_data = array(
                                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                                'edicion' => 'pedidos',
                                'item' => $data,
                                'cookie' => ''
                            );

                            $data=Util::aResult();

                            try {
                                $post = http_build_query($array_data);
                                $data = $api->client->resJson('GET','cartUpdate?'.$post);
                                if ($data['status'] == 0){
                                        $carrito=$data['data'];
                                        $_SESSION['carrito']=$carrito;                                        
                                }
                            } catch (RequestException $e) {
                                Log::error(Psr7\str($e->getRequest()));
                                if ($e->hasResponse()) {
                                    Log::error($e->getMessage());
                                }
                            }
                            return response()->json($data);
                        }else{
                            return view('cart.cart', ['data'=>'','pageTitle'=>$pageTitle]);
                        }
                    }else{    
                        $array = array(
                                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                                'edicion' => 'pedidos',
                                'id_usuario' => $_SESSION["id_user"]
                            );
                        
                        $getDireccionEnvio=Util::aResult();
                        
                        try {
                            $postC = http_build_query($array);
                            $getDireccionEnvio = $api->client->resJson('GET','getDireccionEnvio?'.$postC);
                            if ($getDireccionEnvio['status'] == 0){
                                $dni=$getDireccionEnvio['dni'];
                                $_SESSION['dni']=$dni;
                                $getDireccionEnvio=$getDireccionEnvio['data'];
                                
                            }
                        } catch (RequestException $e) {
                            Log::error(Psr7\str($e->getRequest()));
                            if ($e->hasResponse()) {
                                Log::error($e->getMessage());
                            }
                        }
                        
                        return view('cart.procesar_pedido_1', ['getDireccionEnvio'=>$getDireccionEnvio,'pageTitle'=>$pageTitle]);
                    }
    				
                    }else{
                        return redirect('login');
                    }
				break;
 
                case 2:

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
                        if ($data['status'] == 0){
                            $carrito=$data['data'];
                        }
                    } catch (RequestException $e) {
                        Log::error(Psr7\str($e->getRequest()));
                        if ($e->hasResponse()) {
                            Log::error($e->getMessage());
                        }
                    }
                    $data_carrito = array();
                    $data_carrito['id_pedido'] = $carrito['id_pedido'];
                    $data_carrito['envio'] = $carrito['envio'];
                    $data_carrito['subtotal'] = $carrito['subtotal'];
                    $data_carrito['carrito'] = array();
                    foreach($carrito['carrito'] as $dato){
                        $foto =  isset($dato['fotos'][0]['imagen_file'])?$dato['fotos'][0]['imagen_file']:array();
                        array_push(
                            $data_carrito['carrito'],
                            array(
                                'titulo' => $dato['titulo'],
                                'alto' => $dato['alto'],
                                'ancho' => $dato['ancho'],
                                'largo' => $dato['largo'],
                                'ancho' => $dato['ancho'],
                                'peso' => $dato['peso'],
                                'fotos' => $foto,
                            )
                        );
                    }
                    /////preferencias
                    $array_send = array(
                        'id_edicion' => 'MOD_PEDIDOS_FILTER',
                        'edicion' => 'pedidos',
                        'id_usuario' => $_SESSION["id_user"],
                        'item' => $data_carrito
                    );

                    $data=Util::aResult();
                    $preference_data=array();
                    try {
                        $post = http_build_query($array_send);
                        $data = $api->client->resJson('GET', 'carGetPreference?'.$post);
                            \Log::info(print_r($data,true));
                               if($data['status']==1){
                                    $preference_data='';
                                }else{
                                    $preference_data=$data['data'];
                                    $_SESSION['carrito']['envio']['tipo']['empresa']=$data['empresa'];
                                    
                                }
                                \Log::info(print_r($preference_data,true));
                                
                    } catch (RequestException $e) {
                        Log::error(Psr7\str($e->getRequest()));
                        if ($e->hasResponse()) {
                            Log::error($e->getMessage());
                        }
                    }                   
                    
                    $respuesta = array('data'=>$carrito,'pageTitle'=>$pageTitle,'preference_data' => $preference_data);
					return response()-> json($respuesta);
					break;
				default:
					# code...
					break;
            }
        }else{
            return redirect('cart');
        }
    }

    /***********Envio ************/
    public function costoEnvio(Request $request, Api $api){
        $pedido_data = array();
        foreach($_SESSION['carrito']['carrito'] as $carrito){
            array_push(
                $pedido_data, 
                array(
                    'id_producto' => $carrito['id_producto'],
                    'cantidad' => $carrito['cantidad']
                )
            );            
        }
        $array_te = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id' => $request['id'],
            'pedido' => $pedido_data,
            'subtotal' => (int)$_SESSION['carrito']['subtotal']['precio_db']
        );

        $data=Util::aResult();
        $getTipoEnvio=array();
        try {
            $post = http_build_query($array_te);
            $data = $api->client->resJson('GET', 'getTipoEnvio?'.$post);
            if ($data['status'] == 0){
                $getTipoEnvio=$data['data'];
            }
        echo json_encode($getTipoEnvio);
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function consultarEnvio(Request $request, Api $api){ 
        $array_te = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id' => $request['id'],
            'codigo_postal' => $request['codigo']
           // 'subtotal' => $_SESSION['carrito']['subtotal']['precio_db']
        );
       
       $data=Util::aResult();
       $getTipoEnvio=array();
        try {
            $post = http_build_query($array_te);
            $data= $api->client->resJson('GET', 'consultaCostoEnvio?'.$post);
            if ($data['status'] == 0){
                $getTipoEnvio=$data['data'];
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        echo json_encode($getTipoEnvio);

    }

    public function sucursalEnvio(Request $request,Api $api){
               
        $array_te = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_pedido' => $_SESSION['carrito']['id_pedido']
        );

        $data=Util::aResult();
        $getSucursalEnvio=array();
        try {
            $post = http_build_query($array_te);
            $data= $api->client->resJson('GET', 'getSucursalEnvio?'.$post);
            if ($data['status'] == 0){
                $getSucursalEnvio=$data['data'];
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        echo json_encode($getSucursalEnvio);

    }


    public function andreaniconsult(Request $request, Api $api){ 
        \Log::info('andreaniconsult');
        $array_te = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_pedido' => 30321,  //cambiar por el id del pedido que pida andreani          
           // 'subtotal' => $_SESSION['carrito']['subtotal']['precio_db']
        );
       
       $data=Util::aResult();
       $getTipoEnvio=array();
        try {
            $post = http_build_query($array_te);
            $data= $api->client->resJson('GET', 'andreani?'.$post);
            if ($data['status'] == 0){
                $getTipoEnvio=$data['data'];
               // dd($getTipoEnvio);
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        echo json_encode($getTipoEnvio);

    }

}