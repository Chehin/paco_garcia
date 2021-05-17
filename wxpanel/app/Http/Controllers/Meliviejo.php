<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AppCustom\Models\MercadoLibre;
use App\AppCustom\Meli;
use App\AppCustom\Util;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Preguntas;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\ConfGeneral;
use App\AppCustom\Models\ProductosCodigoStock;
use App\AppCustom\Models\PreciosProductos;

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

        // Obtengo el access_token, refresh_token, expires
        // Necesito estos datos para verificar si el token esta vencido

        $mercado_libre = MercadoLibre::orderBy('id','desc')->first();        

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

    public function verPublicacion($id)
    {
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess('productos.view')) {

            $producto = Productos::find($id);            

            if ($producto) {
                $item_meli = $this->getItem($producto->id_meli);
                if ($item_meli) {
                    $aResult['data'] = $item_meli->permalink;
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

    /*
    Publica un nuevo item en mercado libre
    Guardo los datos de la publicación, id de mercado libre
    Si el producto tiene variantes se deben guardar datos adicionales
    */
    public function createPublicacion($id)
    {
        // Pasos para crear una publicación en Mercado Libre
        // 1 - buscar la categoría sobre la cual se va a publicar y verificar si los 
        // atributos tienen variaciones
        // 2 - identificar si el producto tiene cargado el stock por color y talle
        // dependiendo sera publicacado con variaciones o no
        // 3 - publicar el producto y guardar los datos de la publicación en la DB

        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess('productos.create')) {

            $producto = Productos::find($id);

            if ($producto) {
                // Verifico si esta seleccionada la categoria
                if (empty($producto->categoria_meli)) {
                    $nombre_predict = $producto->nombremeli?$producto->nombremeli:$producto->nombre;
                    if($producto->id_rubro){
                        $rubro = Rubros::find($producto->id_rubro);
                        if($rubro){
                            $nombre_predict = $nombre_predict.' '.$rubro->nombre;
                        }
                    }
                    if($producto->id_subrubro){
                        $subrubro = SubRubros::find($producto->id_subrubro);
                        if($subrubro){
                            $nombre_predict = $nombre_predict.' '.$subrubro->nombre;
                        }
                    }
                    $categoria = $this->categoryPredict($nombre_predict, 'array');
                    if ($categoria) {
                        $producto->categoria_meli = $categoria->id;
                        if (isset($categoria->variations)) {
                            $producto->categoria_variations = '1';
                        } else {
                            $producto->categoria_variations = '0';
                        }
                    }
                }
                // Obtengo la moneda por defecto
                $moneda = Util::getMonedaDefault();
                // Obtengo el precio del producto
                $precio = Util::getPrecios($producto->id,$moneda[0]['id']);
                $precio_public = $precio->precio_meli>0?$precio->precio_meli:$precio->precio_venta;
                // Obtengo el stock del producto
                $stocks = Util::getStock($producto->id);
                // Obtengo las imagenes del producto
                $imagenes = FeUtilController::getImages($producto->id,'all', 'productos');                
                // Guardo las imagenes en un array
                $pictures = array();
                if ($imagenes) {
                    foreach ($imagenes as $imagen) {
                        $pictures[]['source'] = \config('appCustom.PATH_UPLOADS').$imagen['imagen_file'];
                    }
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar como minimo una foto - máximo 10 fotos";
                    return response()->json($aResult);
                }
                $institucional = "";
                $nota = ConfGeneral::find(3);
                if($nota){
                    $institucional = $nota->valor;
                }             
                
                //busco si el producto existe
				$codigo = ProductosCodigoStock::select('codigo')
                       ->where('id_producto', 'like', $producto->id)->first();

                if($codigo->codigo){
                    $codigo_form = explode('.', $codigo->codigo);
                    
                    if(isset($codigo_form[0])){
						$cod_producto = substr($codigo_form[0],0,-3);
                    }else{
                        $cod_producto = '';
                    }
                }
                                    
                $item = array(
                    "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre." ".$cod_producto,
                    "category_id" => $producto->categoria_meli,
                    "price" => $precio_public,
                    "currency_id" => "ARS",
                    "buying_mode" => "buy_it_now",
                    "listing_type_id" => "bronze",
                    "condition" => $producto->estado=='Usado'?"used":"new",
                    "description" => array(
                        "plain_text" => $producto->nombremeli?$producto->nombremeli:$producto->nombre. "\n\n" .$producto->sumario . "\n\n\n\n".$institucional
                    ),
                    "pictures" => $pictures,
                    "video_id" => $producto->id_video,
                    "tags" => array( "immediate_payment"),
                    "warranty" => "TODOS LOS PRODUCTOS PUBLICADOS CUENTAN CON GARANTÍA",
                    "shipping" => array(
                        "mode" => "me2",
                        "local_pick_up" => true,
                        "free_shipping" => false,
                        "free_methods" => array()
                    ),
                );
                // Verifico si el item tiene la opción de envio gratis
                if($precio_public>549){
                    $item["shipping"]["tags"] = array("mandatory_free_shipping");
                    $item["shipping"]["free_shipping"] = true;
                    $item["shipping"]["free_methods"][0]["id"] = 73328;
                    $item["shipping"]["free_methods"][0]["rule"]["free_mode"] = "country";
                    $item["shipping"]["free_methods"][0]["rule"]["value"] = null;
                }elseif($producto->envio_gratis == '1') {                    
                    $item["shipping"]["free_shipping"] = true;
                    $item["shipping"]["free_methods"][0]["id"] = 73328;
                    $item["shipping"]["free_methods"][0]["rule"]["free_mode"] = "country";
                    $item["shipping"]["free_methods"][0]["rule"]["value"] = null;
                }

                // Si el producto tiene variaciones se debe crear el 
                // arreglo para cada tipo con el stock
                if ($producto->categoria_variations || count($stocks) > 1) {
                    $variations = array();
                    if ($producto->categoria_variations == '0') {
                        // La categoria no tiene variaciones pero el producto se cargo 
                        // con colores o talles dependiendo el producto
                        // Agrego las variaciones
                        $total_stock = 0;
                        foreach ($stocks as $stock) {
                            if ($stock->stock > 0) {
                                // Obtengo las imagenes por color
                                $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all','productos',$stock->id_color);
                                if (count($imagenesColor) == 0) {
                                    $aResult['status'] = 1;
                                    $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                    return response()->json($aResult);
                                }
                                $pictures_color = array();
                                $combinations = array();
                                foreach ($imagenesColor as $imagenes) {
                                    $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                }
                                if (!empty($stock->nombreColor)) {
                                    $combinations[] = array(
                                        "name" => "Color",
                                        "value_name" => $stock->nombreColor
                                    );
                                }                                
                                if (!empty($stock->nombreTalle)) {
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
                                    $test = Util::getTalleEquivalente($stock->nombreTalle,$producto->id_marca,$producto->id_genero,$numeracion,$producto->id_rubro);
                                    if($test['equivalencia']){
                                        $nombreTalle = $test['equivalencia'];
                                    }else{
                                        $nombreTalle = $stock->nombreTalle;
                                    }
                                    $combinations[] = array(
                                        "name" => "Talla",
                                        "value_name" => $nombreTalle
                                    );
                                }
                                $variations[] = array(
                                    "attribute_combinations" => $combinations,
                                    "available_quantity" => $stock->stock,
                                    "price" => $precio_public,
                                    "picture_ids" => $pictures_color,
                                    "seller_custom_field" => $stock->codigo
                                );
                                $total_stock = $total_stock + $stock->stock;
                                $stock->estado_meli = 1;
                                $stock->save();
                            }                            
                        }
                        $item['variations'] = $variations;
                        $item['available_quantity'] = $total_stock;
                    } else {
                        // La categoria tiene variaciones 
                        // El stock del producto debe estar cargado por colores o talle 
                        // dependiendo el producto
                        $total_stock = 0;
                        foreach ($stocks as $stock) {
                            if ($stock->stock > 0) {
                                // Obtengo las imagenes por color
                                $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                if (count($imagenesColor) == 0) {
                                    $aResult['status'] = 1;
                                    $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                    return response()->json($aResult);
                                }
                                $pictures_color = array();
                                foreach ($imagenesColor as $imagenes) {
                                    $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                }
                                // Obtengo los atributos de la categoria
                                $categoryAtributes = $this->getCategoryAttributes($producto->categoria_meli);

                                $combinations = array();
                                $combinations = $this->getCombinations($stock, $producto->categoria_meli, $categoryAtributes);
                                if($combinations['var']){
                                    $variations[] = array(
                                        "attribute_combinations" => $combinations['var'],
                                        "attributes" => $combinations['atr'],
                                        "available_quantity" => $stock->stock,
                                        "price" => $precio_public,
                                        "picture_ids" => $pictures_color,
                                        "seller_custom_field" => $stock->codigo
                                    );
                                }
                                $total_stock = $total_stock + $stock->stock;
                                $stock->estado_meli = 1;
                                $stock->save();                                
                            }
                        }
                        $item['variations'] = $variations;
                        $item['available_quantity'] = $total_stock;
                    }
                } else {
                    if ($stocks[0]->stock == 0) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = "Para publicar en Mercado Libre el producto debe tener un stock mayor a 0";
                        return response()->json($aResult);
                    }
                    $stocks[0]->estado_meli = 1;
                    $stocks[0]->save();
                    // Si no tiene solo se envian el stock del producto
                    $item['available_quantity'] = $stocks[0]->stock;
                    $item['seller_custom_field'] = $stocks[0]->codigo;
                }
                
                $result = $this->meli->post('/items',$item,['access_token' => $this->access_token]);
                $result = $result['body'];                

                if (isset($result->id)) {
                    $producto->estado_meli = 1;
                    $producto->id_meli = $result->id;
                    $producto->update_meli = Carbon::now();

                    if (!$producto->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = json_encode($result->cause);
                }
                
                $aResult['data'] = $result;
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }

    /*
    Actualizo una publicación de Mercado Libre
    Guardo los datos de la publicación, id de mercado libre
    */
    public function updatePublicacion(Request $request, $id)
    {
        // Pasos para actualizar una publicación en Mercado Libre
        // 1 - Con el id_meli guardado en la DB obtengo la información de la publicación
        // 2 - identificar si el producto tiene cargado el stock por color y talle
        // dependiendo sera publicacado con variaciones o no
        // 3 - publicar el producto y guardar los datos de la publicación en la DB
        \Log::info($id);
        \Log::info($this->access_token);
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess('productos.update')) {
 
            $producto = Productos::find($id);            

            if ($producto) {
                /* Sección donde se modifica el estado de la publicación
                   Cambio de estado activa y pausada
                */
                //Just enable/disable resource?
                if ('yes' === $request->input('justEnable')) {
                    if ($request->input('enable') == 1) {
                        $status = $this->estadoPublicacion($producto->id_meli, "active");
                    } else {
                        $status = $this->estadoPublicacion($producto->id_meli, "paused");
                    }
                    if ($status) {
                        $producto->estado_meli = $request->input('enable');
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = "No se pudo actualizar la publicación, intente nuevamente.";
                    }
                    
                    if (!$producto->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                    return response()->json($aResult);
                }

                // Obtengo la publicación de Mercado Libre
                $item_meli = $this->getItem($producto->id_meli);
                if ($item_meli) {
                    
                    // Obtengo la moneda por defecto
                    $moneda = Util::getMonedaDefault();
                    // Obtengo el precio del producto
                    $precio = Util::getPrecios($producto->id,$moneda[0]['id']);
                    $precio_public = $precio->precio_meli>0?$precio->precio_meli:$precio->precio_venta;
                    // Obtengo las imagenes del producto
                    $imagenes = FeUtilController::getImages($producto->id,'all', 'productos');
                    // Guardo las imagenes en un array
                    $pictures = array();
                    if ($imagenes) {
                        foreach ($imagenes as $imagen) {
                            $pictures[]['source'] = \config('appCustom.PATH_UPLOADS').$imagen['imagen_file'];
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar como minimo una foto - máximo 10 fotos";
                        return response()->json($aResult);
                    }
                    // Obtengo el stock del producto
                    $stocks = Util::getStock($producto->id);   
                    
                    // Verifico si la categoria en la cual se publico tiene variaciones
                    \Log::info($producto->categoria_variations);
                    if ($producto->categoria_variations == 1) {
                        /************************ OPCION 1 ************************/
                        // La categoria tiene variaciones se debe buscar y actualizar cada una
                        // 1 - Obtengo las variaciones y actualizo las que se modificaron
                        \Log::info('opcion 1');
                        $stocks_1 = Util::getStock($producto->id, 'all');
                        $variations = $this->getVariations($producto->id_meli);
                        $variations = $this->updateVariations($stocks_1, $variations, $precio_public, $producto->id, $producto->id_meli, '1');
                        \Log::info(json_encode($variations));

                         //a veces se rompe la publicacion y cuando obtenemos el getvariation viene  [], entonces se manda las variates de otra forma 
                        //hago el control
                        $cont=0;
                        $contT=0;
                        
                        for ($j=0; $j < count($variations); $j++) { 
                            if(isset($variations[$j]->attribute_combinations)){
                                for ($k=0; $k <count($variations[$j]->attribute_combinations); $k++) { 
                                    if($variations[$j]->attribute_combinations[$k]->id=='SIZE'){
                                        $cont++;
                                    }
                                }
                            }
                        }                      
                                                                 
                        foreach ($stocks as $stock) {
                            $contT++;
                        }                        
                                            
                        \Log::info($cont. ' '. $contT);
                        //controlo si hubo variaciones que no se guardaron en MELI
                        if($cont!=$contT){
                            \Log::info('pasa !=');
                            // 2 - Obtengo el stock de los colores que se agregaron
                            // Son los que tienen el campo estado_meli=0 en la tabla inv_productos_codigo_stock
                            $stocks_0 = Util::getStock($producto->id, '0');
                            $res_color = json_decode(json_encode($variations),true);
                            $color_prim = isset($res_color[0]['color'])?$res_color[0]['color']:'Sin color';
                            
                            \Log::info($stocks_1);
                            foreach ($stocks_1 as $stock) {
                                if ($stock->stock > 0) {
                                    //reviso el nombre del color
                                    $stock->nombreColor = 'Sin color'?$color_prim:$stock->nombreColor;

                                    // Obtengo las imagenes por color
                                    $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                    if (count($imagenesColor) == 0) {
                                        $aResult['status'] = 1;
                                        $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                        return response()->json($aResult);
                                    }
                                    $pictures_color = array();
                                    foreach ($imagenesColor as $imagenes) {
                                        $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                    }
                                    // Obtengo los atributos de la categoria
                                    $categoryAtributes = $this->getCategoryAttributes($producto->categoria_meli);

                                    $combinations = array();
                                    $combinations = $this->getCombinations($stock, $producto->categoria_meli, $categoryAtributes);
                              
                                    $variations1[] = array(
                                        "attribute_combinations" => $combinations['var'],
                                      //  "attributes" => $combinations['atr'],
                                        "available_quantity" => $stock->stock,
                                        "price" => $precio_public,
                                        "picture_ids" => $pictures_color,
                                        "seller_custom_field" => $stock->codigo
                                    );                                  
                                    unset($stock->nombreColor);

                                    $stock->estado_meli = 1;
                                    $stock->save();
                                }
                            }
                            \Log::info('variatons1');
                            \Log::info(json_encode($variations1));
                            $item = array(                              
                                "variations" => $variations1
                            );
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                            \Log::info(json_encode($result));
                           /*  $result = $this->meli->post('/items/'.$producto->id_meli.'/variations', $variations, ['access_token' => $this->access_token]);
                            \Log::info('result');
                            \Log::info(json_encode($result)); */
                        }else{
                            //para por actualizar el titulo
                            $item = array(
                                "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,
                                "pictures" => $pictures,
                                "variations" => $variations
                            );

                            // Modifico la publicación
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                        }                                                
                    } else {                       
                        if (count($stocks) > 1) {
                            /************************ OPCION 2 ************************/
                            // La categoria no tiene variaciones pero el producto se creo 
                            // igualmente con variaciones de color o talle
                            // Se debe buscar y actualizar cada uno
                            // 1 - Obtengo las variaciones y actualizo las que se modificaron
                            \Log::info('opcion 2');
                            $stocks_1 = Util::getStock($producto->id, 'all');
                            $variations = $this->getVariations($producto->id_meli);
                            $variations = $this->updateVariations($stocks_1, $variations, $precio_public, $producto->id, $producto->id_meli, '0');

                            $item = array(
                                        "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,
                                        "pictures" => $pictures,
                                        "variations" => $variations
                            );

                            // Modifico la publicación
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);

                            // 2 - Obtengo el stock de los colores que se agregaron
                            // Son los que tiene el campo estado_meli=0 en la tabla inv_productos_codigo_stock
                            $stocks_0 = Util::getStock($producto->id, '0');
                            foreach ($stocks_0 as $stock) {
                                if ($stock->stock > 0) {
                                    // Obtengo las imagenes por color
                                    $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                    if (count($imagenesColor) == 0) {
                                        $aResult['status'] = 1;
                                        $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                        return response()->json($aResult);
                                    }
                                    $pictures_color = array();                                    
                                    foreach ($imagenesColor as $imagenes) {
                                        $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                    }
                                   /*  $combinations[] = array(
                                        "name" => "Color",
                                        "value_name" => $stock->nombreColor
                                    );
                                    $combinations[] = array(                                      
                                        "name" => "Talle",
                                        "value_name" => $stock->nombreTalle
                                    );
                                     */
                                    $combinations = array();
                                    $combinations = $this->getCombinations($stock, $producto->categoria_meli, $categoryAtributes);
                                    $variations = array(
                                        "attribute_combinations" => $combinations['var'],
                                        "available_quantity" => $stock->stock,
                                        "price" => $precio_public,
                                        "picture_ids" => $pictures_color,
                                        "seller_custom_field" => $stock->codigo
                                    );

                                    $result = $this->meli->post('/items/'.$producto->id_meli.'/variations', $variations, ['access_token' => $this->access_token]);

                                    $stock->estado_meli = 1;
                                    $stock->save();
                                }
                            }
                        } else {
                            /************************ OPCION 3 ************************/
                            // La categoria no tiene variaciones
                            // Los productos no fueron cargados por color
                            // Obtengo las imagenes del producto
                            \Log::info('opcion 3');
                            $imagenes = FeUtilController::getImages($producto->id,'all', 'productos');
                            // Guardo las imagenes en un array
                            $pictures = array();
                            if ($imagenes) {
                                foreach ($imagenes as $imagen) {
                                    $pictures[]['source'] = \config('appCustom.PATH_UPLOADS').$imagen['imagen_file'];
                                }
                            }
                            $total_stock = $stocks[0]->stock;

                            $item = array(
                                "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,
                                "price" => $precio_public,
                                "category_id" => $producto->categoria_meli,
                                "pictures" => $pictures,
                                "condition" => $producto->estado=='Usado'?"used":"new",
                                "video_id" => $producto->id_video,
                                "available_quantity" => $stocks[0]->stock,
                                "tags" => array( "immediate_payment"),
                                "shipping" => array(
                                    "mode" => "me2",
                                    "local_pick_up" => true,
                                    "free_shipping" => false,
                                    "free_methods" => array()
                                ),
                            );
                            // Verifico si el item tiene la opción de envio gratis
                            if($precio_public>549){
                                $item["shipping"]["tags"] = array("mandatory_free_shipping");
                                $item["shipping"]["free_shipping"] = true;
                                $item["shipping"]["free_methods"][0]["id"] = 73328;
                                $item["shipping"]["free_methods"][0]["rule"]["free_mode"] = "country";
                                $item["shipping"]["free_methods"][0]["rule"]["value"] = null;
                            }elseif($producto->envio_gratis == '1') {                    
                                $item["shipping"]["free_shipping"] = true;
                                $item["shipping"]["free_methods"][0]["id"] = 73328;
                                $item["shipping"]["free_methods"][0]["rule"]["free_mode"] = "country";
                                $item["shipping"]["free_methods"][0]["rule"]["value"] = null;
                            }

                            // Modifico la publicación
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);

                            if ($result['httpCode'] == 200) {
                                $result = $result['body'];
                            } else {
                                $aResult['status'] = 1;
                                $aResult['msg'] = $result['body']->message;
                                return response()->json($aResult);
                            }
                        }
                    }

                    // Para actualizar la descripción se envia a otra dirección
                    $institucional = "";
                    $nota = ConfGeneral::find(3);
                    
                    if($nota){
                        $institucional = $nota->valor;
                    }      
                           
                    $itemDescripcion = array(
                        "plain_text" => $producto->nombremeli?$producto->nombremeli. "\n\n" .$producto->sumario. "\n\n\n\n" .$institucional : $producto->nombre. "\n\n" .$producto->sumario. "\n\n\n\n" .$institucional
                    );
                    
                    // Modifico la descripción
                    $resultDescripcion = $this->meli->put('/items/'.$producto->id_meli.'/description',$itemDescripcion, ['access_token' => $this->access_token]);

                    if ($resultDescripcion['httpCode'] == 200) {
                        $resultDescripcion = $resultDescripcion['body'];
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = $resultDescripcion['body']->message;
                        return response()->json($aResult);
                    }                    
                    
                    $producto->update_meli = Carbon::now();
                    $producto->timestamps = false;
                    $producto->save();
                }
                $aResult['data'] = $resultDescripcion;
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }    

    public function deletePublicacion($id)
    {
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess('productos.delete')) {

            $producto = Productos::find($id);            

            if ($producto) {
                // Finalizo la publicación antes de borar
                $status = $this->estadoPublicacion($producto->id_meli, "closed");

                if ($status) {
                    $item = array(
                        "deleted" => "true"
                    );
                    $result = $this->meli->put('/items/'.$producto->id_meli, $item,['access_token' => $this->access_token]);
                    if ($result['httpCode'] != 200) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = $result['body']->message;
                    } else {
                        $producto->estado_meli = 0;
                        $producto->id_meli = null;
                        if (!$producto->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                        $stocks = Util::getStock($producto->id, 1);
                        foreach ($stocks as $stock) {
                            $stock->estado_meli = 0;
                            $stock->save();
                        }
                    }
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = "No se pudo borrar la publicación, intente nuevamente.";
                }                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    // Modifico el estado de la publicación.
    // Valores de $status: "closed", "active", "paused"

    private function estadoPublicacion($id_meli, $status = '')
    {
        $item = array(
            "status" => $status
        );
        $result = $this->meli->put('/items/'.$id_meli, $item,['access_token' => $this->access_token]);
        if ($result['httpCode'] == 200) {
            $status = true;
        } else {
            $status = false;
        }

        return $status;
    }

    // Borro una variación
    private function deleteVariacion($id_meli, $id_variacion)
    {
        $url = '/items/'.$id_meli.'/variations/'.$id_variacion;
        $result = $this->meli->delete($url,['access_token' => $this->access_token]);
        if ($result['httpCode'] == 200) {
            $status = true;
        } else {
            $status = false;
        }

        return $status;
    }

    // Función para predecir la categoría en base al nombre del producto
    public function categoryPredict($nombre, $tipo = false)
    {
        // Con el nombre del producto obtengo la categoria 
        // en la cual se va a publicar
        $params = array(
            'title' => $nombre
        );
        $result = $this->meli->get('/sites/MLA/category_predictor/predict',$params);
        if ($result['httpCode'] == 200) {
            if($tipo=='array'){
                return $result['body'];    
            }else{
                $aResult = Util::getDefaultArrayResult();
                $aResult['status'] = 0;
                $aResult['data'] = $result['body'];
                return response()->json($aResult);
            }
        } else {
            return false;
        }        
    }

    // Obtengo los datos del item
    private function getItem($id_meli)
    {
        // Con el id de meli obtengo los datos de la publicación
        $url = "/items/".$id_meli;
        $item = $this->meli->get($url);
        if ($item['httpCode'] == 200) {
            return $item['body'];
        } else {
            return false;
        }
    }

    // Obtengo los datos de la categoria
    public function getCategory($id_categoria, $tipo = false)
    {
        // Con el id de categoria obtengo los datos
        $url="/categories/".$id_categoria;
        $category=$this->meli->get($url);
        if ($category['httpCode'] == 200) {
            if($tipo=='array'){
                return $category['body'];
            }else{
                $aResult = Util::getDefaultArrayResult();
                $aResult['status'] = 0;
                $aResult['data'] = $category['body'];
                return response()->json($aResult);
            }
        } else {
            return false;
        }        
    }

    private function getCategoryAttributes($id_categoria)
    {
        $url="/categories/".$id_categoria."/attributes";
        $category=$this->meli->get($url);
        if ($category['httpCode'] == 200) {
            return $category['body'];
        } else {
            return false;
        }
    }


    private function getCombinations($stock, $id_categoria, $categoryAtributes)
    {
        $combinations = array();
        $attributes = array();
        $color = explode('/',$stock->nombreColor);
        $color_new = str_replace('/', '-', $stock->nombreColor);
        $i=0;
        foreach ($categoryAtributes as $atribute) {
            if ($atribute->name == 'Color') {
                $combinations[$i]["id"] = $atribute->id;
                $combinations[$i]["value_name"] = $color_new;
            }
            if ($atribute->name == 'Talle') {
                $producto = Productos::find($stock->id_producto);
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
                $test = Util::getTalleEquivalente($stock->nombreTalle,$producto->id_marca,$producto->id_genero,$numeracion,$producto->id_rubro);
                if($test['equivalencia']){
                    $nombreTalle = $test['equivalencia'];
                }else{
                    $nombreTalle = $stock->nombreTalle;
                }
                // Busco la coincidencia de los talles de ML y los talles del productos
                $combinations[$i]['id'] = $atribute->id;
                $combinations[$i]['value_name'] = $nombreTalle;
            }
            if ($atribute->name == 'Color' || $atribute->name == 'Talle') {
                $i++;
            }
            if (isset($atribute->tags->required)) {
                foreach ($atribute->values as $value1) {
                    if ($atribute->name == 'Color Primario') {
                        // Busco la coincidencia de los coleres con ML y los colores del producto
                        if (Util::cambiaAcento($value1->name) == Util::cambiaAcento($color[0])) {
                            $combinations[$i]['id'] = $atribute->id;
                            $combinations[$i]['value_id'] = $value1->id;
                            $i++;
                        }
                    }
                    
                }
            }
            if (isset($color[1])) {
                if ($color[0] != $color[1]) {
                    if ($atribute->name == 'Color Secundario') {
                        foreach ($atribute->values as $value3) {
                            if (Util::cambiaAcento($value3->name) == Util::cambiaAcento($color[1])) {
                                $combinations[$i]['id'] = $atribute->id;
                                $combinations[$i]['value_id'] = $value3->id;
                                $i++;
                            }
                        }
                    }
                }
            }
            if ($atribute->name == 'Color principal') {
                foreach ($atribute->values as $value4) {
                    if (Util::cambiaacento ($value4->name) == Util::cambiaacento ($color[0])) {
                        $attributes[0]["id"] = $atribute->id;
                        $attributes[0]["name"] = $atribute->name;
                        $attributes[0]["value_id"] = $value4->id;
                        $attributes[0]["value_name"] = $value4->name;
                    }
                }
            }            
        }
        $return = array(
            'var' => $combinations,
            'atr' => $attributes
        );
        return $return;
    }

    private function getVariations($id_meli)
    {
        // Con el id de meli obtengo los datos de la publicación
        $url = "/items/".$id_meli."/variations";
        $variations = $this->meli->get($url);
        if ($variations['httpCode'] == 200) {
            return $variations['body'];
        } else {
            return false;
        }
    }

    private function updateVariations($stocks_1, $variations, $precio_db, $id_producto, $id_meli, $opcion)
    {
        
        if ($opcion == '0') {
            // La categoria no tiene variación pero al producto se le agregaron variaciones
            $i = 0;
            foreach ($variations as $variation) {
                $b = 1;
                foreach ($stocks_1 as $stock) {
                    if (count($variation->attribute_combinations) == 1) {
                        $value_name = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if ($stock->nombreColor == $value_name) {
                            $variation->price = $precio_db;
                            $variation->available_quantity = $stock->stock;   
                            $variation->color = $value_name;                     
                            // Obtengo las fotos para la variación
                            $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                            if (count($imagenesColor) == 0) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                return response()->json($aResult);
                            }
                            $pictures_color = array();
                            foreach ($imagenesColor as $imagenes) {
                                $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                            }
                            $variation->picture_ids = $pictures_color;
                            $b = 0;
                        }
                    }

                    if (count($variation->attribute_combinations) == 2) {
                        $value_name = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if ($stock->nombreColor == $value_name || $stock->nombreColor=='Sin color') {
                            $producto = Productos::find($stock->id_producto);
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
                            $test = Util::getTalleEquivalente($stock->nombreTalle,$producto->id_marca,$producto->id_genero,$numeracion,$producto->id_rubro);
                            if($test['equivalencia']){
                                $nombreTalle = $test['equivalencia'];
                            }else{
                                $nombreTalle = $stock->nombreTalle;
                            }
                    
                            if ($nombreTalle == $variation->attribute_combinations[1]->value_name) {
                                $variation->price = $precio_db;
                                $variation->available_quantity = $stock->stock;
                                $variation->color = $value_name;
                                // Obtengo las fotos para la variación
                                $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                if (count($imagenesColor) == 0) {
                                    $aResult['status'] = 1;
                                    $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                    return response()->json($aResult);
                                }
                                $pictures_color = array();
                                foreach ($imagenesColor as $imagenes) {
                                    $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                }
                                $variation->picture_ids = $pictures_color;
                                $b = 0;
                            }
                        }
                    }
                }
                if ($b == 1) {
                    $status = $this->deleteVariacion($id_meli, $variations[$i]->id);
                    unset($variations[$i]);
                }
                $i++;
            }            
        }

        if ($opcion == '1') {
            // La categoría tiene variación
            $i = 0;            
            foreach ($variations as $variation) {
                $b = 1;
                foreach ($stocks_1 as $stock) {
                    $nombreColor = explode('/',$stock->nombreColor);
                    if (count($variation->attribute_combinations) == 1) {
                        $color_primario = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if ($nombreColor[0] == $color_primario || $nombreColor[0]=='Sin color') {
                            $variation->price = $precio_db;
                            $variation->available_quantity = $stock->stock;
                            $variation->color = $color_primario;
                            // Obtengo las fotos para la variación
                            $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                            if (count($imagenesColor) == 0) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                return response()->json($aResult);
                            }
                            $pictures_color = array();
                            foreach ($imagenesColor as $imagenes) {
                                $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                            }
                            $variation->picture_ids = $pictures_color;
                            $b = 0;
                        }
                    }
                    if (count($variation->attribute_combinations) == 2) {
                        $color_primario = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if (($nombreColor[0] == $color_primario) || $nombreColor[0]=='Sin color') {
                            if ($variation->attribute_combinations[1]->name == 'Color Secundario') {
                                if (isset($nombreColor[1])) {
                                    $color_secundario = Util::cambiaAcento($variation->attribute_combinations[1]->value_name);
                                    if ($nombreColor[1] == $color_secundario) {
                                        $variation->price = $precio_db;
                                        $variation->available_quantity = $stock->stock;
                                        $variation->color = $color_secundario;
                                        // Obtengo las fotos para la variación
                                        $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                            return response()->json($aResult);
                                        }
                                        $pictures_color = array();
                                        foreach ($imagenesColor as $imagenes) {
                                            $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                        }
                                        $variation->picture_ids = $pictures_color;
                                        $b = 0;
                                    }
                                }
                            } else {
                                $producto = Productos::find($stock->id_producto);
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
                                $test = Util::getTalleEquivalente($stock->nombreTalle,$producto->id_marca,$producto->id_genero,$numeracion,$producto->id_rubro);
                                if($test['equivalencia']){
                                    $nombreTalle = $test['equivalencia'];
                                }else{
                                    $nombreTalle = $stock->nombreTalle;
                                }
            
                                if ($nombreTalle == $variation->attribute_combinations[1]->value_name) {
                                    $variation->price = $precio_db;
                                    $variation->available_quantity = $stock->stock;
                                    $variation->color = $color_primario;
                                    // Obtengo las fotos para la variación
                                    $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                    if (count($imagenesColor) == 0) {
                                        $aResult['status'] = 1;
                                        $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                        return response()->json($aResult);
                                    }
                                    $pictures_color = array();
                                    foreach ($imagenesColor as $imagenes) {
                                        $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                    }
                                    $variation->picture_ids = $pictures_color;
                                    $b = 0;
                                }                                    
                            }
                        }
                    }

                    if (count($variation->attribute_combinations) == 3) {
                        $color_primario = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if ($variation->attribute_combinations[1]->name == 'Color Secundario') {
                            if (isset($nombreColor[1])) {
                                $color_secundario = Util::cambiaAcento($variation->attribute_combinations[1]->value_name);
                                if ($nombreColor[1] == $color_secundario) {
                                    $producto = Productos::find($stock->id_producto);
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
                                    $test = Util::getTalleEquivalente($stock->nombreTalle,$producto->id_marca,$producto->id_genero,$numeracion,$producto->id_rubro);
                                    if($test['equivalencia']){
                                        $nombreTalle = $test['equivalencia'];
                                    }else{
                                        $nombreTalle = $stock->nombreTalle;
                                    }
                                    if ($nombreTalle == $variation->attribute_combinations[2]->value_name) {
                                        $variation->price = $precio_db;
                                        $variation->available_quantity = $stock->stock;
                                        $variation->color = $color_secundario;
                                        // Obtengo las fotos para la variación
                                        $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como mínimo una foto por cada color.";
                                            return response()->json($aResult);
                                        }
                                        $pictures_color = array();
                                        foreach ($imagenesColor as $imagenes) {
                                            $pictures_color[] = \config('appCustom.PATH_UPLOADS').$imagenes['imagen_file'];
                                        }
                                        $variation->picture_ids = $pictures_color;
                                        $b = 0;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($b == 1) {
                    $status = $this->deleteVariacion($id_meli, $variations[$i]->id);
                    unset($variations[$i]);
                }
                $i++;
            }
        }
        foreach ($variations as $variation) {
            unset($variation->attribute_combinations);
            unset($variation->catalog_product_id);
            unset($variation->sale_terms);
        }
        
        return $variations;
    }

    public function publicarRespuesta(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess('productos' . '.update')) {
            $item = Preguntas::find($id);
            if ($item) {
                $array_send["question_id"] = $item->id_pregunta_meli;
                $array_send["text"] = $request->input('respuesta');
                $result = $this->meli->post('/answers',$array_send,['access_token' => $this->access_token]);
                if ($result['httpCode'] != 200) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = "Hubo un error al publicar la respuesta, intente nuevamente.";
                } else {
                    $item->estado = 1;
                    $item->fecha_respuesta = Carbon::now()->format('Y-m-d H:m:s');
                    $item->respuesta_meli = $request->input('respuesta');
                    $item->save();
                }
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

    public function login(Request $request){
        if ($this->user->hasAccess('productos' . '.update')) {
            $this->app_id = config('mercadolibre.app_id');
            $this->app_secret = config('mercadolibre.app_secret');
            $redirectURI = config('mercadolibre.app_redirect');
            $siteId = config('mercadolibre.app_sideid');

            $aResult = Util::getDefaultArrayResult();
            $this->meli = new Meli($this->app_id, $this->app_secret);
            $code = $request->input('code');
            if($code) {
                $user = $this->meli->authorize($code, $redirectURI);
                
                // Guardo el access_token, refresh_token y expires en la base de datos
                $mercado_libre = new MercadoLibre();
                $mercado_libre->access_token = $user['body']->access_token;
                $mercado_libre->refresh_token = $user['body']->refresh_token;
                $mercado_libre->expires = time() + $user['body']->expires_in;
                $mercado_libre->save();        
                
                return 
                \View::make('loginMeli')
                ->with('data', $user['body']);            
            } else {
                $url = $this->meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]);

                return 
                \View::make('loginMeli')
                ->with('url', $url);
            }
        }
    }

    public function editCategory($id_categoria, $nivel=2)
    {
        // Con el id de categoria obtengo los datos
        if($id_categoria!=-1){
            $url="/categories/".$id_categoria;
        }else{
            $url="/sites/MLA/categories";
        }
        $category=$this->meli->get($url);
        if ($category['httpCode'] == 200) {
            $aResult = Util::getDefaultArrayResult();
            $aResult['status'] = 0;
            $aResult['camino'] = $category['body'];
            if($id_categoria!=-1){
                $elementos = count($category['body']->path_from_root);
                if($elementos>1){
                    $url="/categories/".$category['body']->path_from_root[$elementos-$nivel]->id;
                }else{
                    $url="/categories/".$category['body']->path_from_root[0]->id;
                }
                $category=$this->meli->get($url);
                $aResult['categoria'] = $category['body'];
            }

            return response()->json($aResult);
        } else {
            return false;
        }        
    }
    public function updateLoteMeli(){
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess('productos.update')) {
            $array_data = array();
            //traigo los productos que fueron actualizados y deben sincronizarse
            $productos = Productos::select('id', 'nombre', 'modelo', 'update_meli','updated_at')
            ->whereNotNull('inv_productos.update_meli')
            ->whereNotNull('inv_productos.id_meli')
            ->get();
            foreach($productos as $producto){
                $modificado = 0;
                //se modifico el precio?
                $precio = PreciosProductos::select('id')
                ->where('id_producto',$producto->id)
                ->where('updated_at','>=', $producto->update_meli)
                ->first();
                if($precio){
                    $modificado = 1;
                }else{
                    //se modifico el stock?
                    $stock = ProductosCodigoStock::select('id')
                    ->where('id_producto',$producto->id)
                    ->where('updated_at','>=', $producto->update_meli)
                    ->first();
                    if($stock){
                        $modificado = 1;
                    }else{
                        //se modifico el producto?
                        if($producto->updated_at>=$producto->update_meli){
                            $modificado = 1;
                        }
                    }
                }
                if($modificado == 1){
                    $data = array(
                        'id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'codigo' => $producto->modelo
                    );
                    array_push($array_data, $data);
                }
            }

            $aViewData = array(
                'mode' => 'add',
                'resource' => 'productos',
                'item' => $array_data
            );
    
            $aResult['html'] = \View::make("productos.updateLoteMeli")
                ->with('aViewData', $aViewData)
                ->render()
            ;
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }  
}
