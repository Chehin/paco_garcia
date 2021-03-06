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
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\Marcas;
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
    Guardo los datos de la publicaci??n, id de mercado libre
    Si el producto tiene variantes se deben guardar datos adicionales
    */
    public function createPublicacion($id)
    {
        // Pasos para crear una publicaci??n en Mercado Libre
        // 1 - buscar la categor??a sobre la cual se va a publicar y verificar si los 
        // atributos tienen variaciones
        // 2 - identificar si el producto tiene cargado el stock por color y talle
        // dependiendo sera publicacado con variaciones o no
        // 3 - publicar el producto y guardar los datos de la publicaci??n en la DB

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

                $marca = Marcas::find($producto->id_marca)->nombre;

                if(!$marca){
                    $aResult['status'] = 1;
                    $aResult['msg'] = "No se encontro la marca del producto";
                    return response()->json($aResult);
                }

                $genero = Genero::find($producto->id_genero)->genero;
                
                if(!$genero){
                    $aResult['status'] = 1;
                    $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar el genero al producto";
                    return response()->json($aResult);
                }

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
                    $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar como minimo una foto - m??ximo 10 fotos";
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
                   // "warranty" => "TODOS LOS PRODUCTOS PUBLICADOS CUENTAN CON GARANT??A",
                    "sale_terms" => array(
                            array(                    
                                    "id" => "WARRANTY_TYPE",
                                    "value_name" => "Garant??a del vendedor"                    
                                )
                    ), 
                    "attributes" => array(                       
                        array(
                            "id" => "BRAND",                                        
                            "value_name" => $marca
                        ),
                        array(
                            "id" => "GENDER",                                        
                            "value_name" => $genero
                        ),
                        array(
                            "id" => "MODEL",                                        
                            "value_name" => $producto->modelo
                        )
                    ),
                    "shipping" => array(
                        "mode" => "me2",
                        "local_pick_up" => true,
                        "free_shipping" => false,
                        "free_methods" => array()
                    ),
                );
                // Verifico si el item tiene la opci??n de envio gratis
                if($precio_public>2500){
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
                                    $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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

                                if($producto->ean!=''){
                                    
                                    $attributes  = array(
                                        "id" => "GTIN",   
                                        //"name" => "EAN",                                      
                                        "value_name" => $producto->ean
                                    );

                                    $variations[] = array(
                                        "attribute_combinations" => $combinations,
                                        "attributes" =>  [$attributes],                                 
                                        "available_quantity" => $stock->stock,
                                        "price" => $precio_public,
                                        "picture_ids" => $pictures_color,
                                        "seller_custom_field" => $stock->codigo
                                    );
                                }else{
                                    $variations[] = array(
                                        "attribute_combinations" => $combinations,                                 
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
                        \Log::info('create');
                    } else {
                        \Log::info('else create');
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
                                    $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
                \Log::info(json_encode($item));
                $result = $this->meli->post('/items',$item,['access_token' => $this->access_token]);
                $result = $result['body'];                
                \Log::info(json_encode($result));
                
              
                if($result->status==400){
                    \Log::info($result->message);
                    $aResult['status'] = 1;
                    $aResult['msg'] = $result->message;
                    return response()->json($aResult); 
                }

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
    Actualizo una publicaci??n de Mercado Libre
    Guardo los datos de la publicaci??n, id de mercado libre
    */
    public function updatePublicacion(Request $request, $id)
    {
        // Pasos para actualizar una publicaci??n en Mercado Libre
        // 1 - Con el id_meli guardado en la DB obtengo la informaci??n de la publicaci??n
        // 2 - identificar si el producto tiene cargado el stock por color y talle
        // dependiendo sera publicacado con variaciones o no
        // 3 - publicar el producto y guardar los datos de la publicaci??n en la DB
        \Log::info($id);
        \Log::info($this->access_token);
        $aResult = Util::getDefaultArrayResult();
        $resultDescripcion = "";
        $freezed_by_deal = 0;

        if ($this->user->hasAccess('productos.update')) {
 
            $producto = Productos::find($id);            

            if ($producto) {                
                $genero = Genero::find($producto->id_genero);
                
                if(!$genero){
                    $aResult['status'] = 1;
                    $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar el genero al producto";
                    return response()->json($aResult);
                }else{
                    $genero=$genero->genero;
                }
                /* Secci??n donde se modifica el estado de la publicaci??n
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
                        $aResult['msg'] = "No se pudo actualizar la publicaci??n, intente nuevamente.";
                    }
                    
                    if (!$producto->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                    return response()->json($aResult);
                }

                // Obtengo la publicaci??n de Mercado Libre
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

                    $color_prim = 'Sin Color';
                    $idV = '-';

                    if ($imagenes) {
                        foreach ($imagenes as $imagen) {
                            $pictures[]['source'] = \config('appCustom.PATH_UPLOADS').$imagen['imagen_file'];
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = "Para publicar en Mercado Libre se debe cargar como minimo una foto - m??ximo 10 fotos";
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

                        \Log::info('cont '.$cont); \Log::info('contT '.$contT);

                        if(!empty($variations)){                            
                            $res_color = json_decode(json_encode($variations),true);
                            $color_prim = isset($res_color[0]['color'])?$res_color[0]['color']:'Sin color';
                            $idV = isset($res_color[0]['id'])?$res_color[0]['id']:'-';                            
                        }

                        if(empty($variations) || $cont!=$contT){                                                                                                          
                            foreach ($stocks_1 as $stock) {
                                if ($stock->stock>=0) {              
                                    $stock->nombreColor = 'Sin color'?$color_prim:$stock->nombreColor;                      

                                    // Obtengo las imagenes por color                                    
                                    $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                    if (count($imagenesColor) == 0) {
                                        $aResult['status'] = 1;
                                        $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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

                                    if (!empty($stock->nombreTalle)) {
                                        $combinations[] = array(
                                            "name" => "Talle",
                                            "value_name" => $nombreTalle
                                        );
                                    }
                        
                                    \Log::info($idV);
                                    if($idV!='-' and $producto->ean!=''){
                                        
                                        $attributes  = array(
                                            "id" => "GTIN",   
                                            //"name" => "EAN",                                      
                                            "value_name" => $producto->ean
                                        );

                                        $variations0[] = array(                                            
                                            "attribute_combinations" => $combinations,     
                                            "attributes" =>  [$attributes],
                                            "available_quantity" => $stock->stock,
                                            "price" => $precio_public,
                                            "picture_ids" => $pictures_color,
                                            "seller_custom_field" => $stock->codigo 
                                        );  

    
                                       //$this->putVariations($producto,$idV);
                                       
                                    }else{
                                        $variations0[] = array(                                            
                                            "attribute_combinations" => $combinations,     
                                            "available_quantity" => $stock->stock,
                                            "price" => $precio_public,
                                            "picture_ids" => $pictures_color,
                                            "seller_custom_field" => $stock->codigo 
                                        );  
                                    }
                                     

                                                                                                                                                                            
                                }                                
                            }      
                            \Log::info('variations0');
                            \Log::info(json_encode($variations0));                          
                        }
                        
                        if(empty($variations) || $cont!=$contT ){
                            $item = array(
                                "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,                                
                                "pictures" => $pictures,
                                "variations" => $variations0
                            );
                        }else{
                            $item = array(
                                "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,                                
                                "pictures" => $pictures,
                                "variations" => $variations
                            );
                        }
                       
                        \Log::info('item');
                        \Log::info(json_encode($item));
                        // Modifico la publicaci??n
                        $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                        \Log::info(json_encode($result));

                        if ($result['httpCode'] != 200) {
                            for($i=0; $i<count($result['body']->cause); $i++ ){
                                if($result['body']->cause[$i]){                                               
                                    foreach($result['body']->cause[$i] as $r){                                                 
                                        if($r=='285'){
                                            //error porque el precio tiene un descuento y se manda el precio sin descuento activo la bandera para armar solo el stock
                                            $freezed_by_deal = 1;                                                                                   
                                        }

                                        if($r=='165'){
                                            //error porque TIENE MAL CARGADO LOS TALLES
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "El atributo de variaci??n est?? duplicado. Combinaciones de atributos DEBEN SER ??nicos permitidos..";
                                            return response()->json($aResult);                                                                             
                                        }
                                    }
                                }
                            }
                        }
                        
                        $variationsInit = $this->getVariations($producto->id_meli);                                                                                                             
                        if($freezed_by_deal == 1){
                                $variations = $this->updateVariationsByDeal($stocks_1, $variationsInit, $precio_public, $producto->id, $producto->id_meli, '0');
                                \Log::info('$variations');
                                \Log::info(json_encode($variations));    
                                $item = array(                                                                    
                                    "variations" => $variations
                                );                           
                                $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                                \Log::info(json_encode($result));
                        }
                       
                    } else {                       
                        if (count($stocks) > 1) {
                            /************************ OPCION 2 ************************/
                            // La categoria no tiene variaciones pero el producto se creo 
                            // igualmente con variaciones de color o talle
                            // Se debe buscar y actualizar cada uno
                            // 1 - Obtengo las variaciones y actualizo las que se modificaron                            
                            $stocks_1 = Util::getStock($producto->id, 'all');
                            $variationsInit = $this->getVariations($producto->id_meli);                                                                                                               
                            $variations = $this->updateVariations($stocks_1, $variationsInit, $precio_public, $producto->id, $producto->id_meli, '0');
                            \Log::info('opcion 2 - VARIACIONES');
                            \Log::info($variations);
                            

                            if(isset($variations['status'])){
                                if($variations['status']==1){
                                    \Log::info( $variations['status'] );
                                    return response()->json($variations);
                                }
                            }
                           

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

                            \Log::info('cont '.$cont); \Log::info('contT '.$contT);

                            if(!empty($variations)){
                                $res_color = json_decode(json_encode($variations),true);
                                $color_prim = isset($res_color[0]['color'])?$res_color[0]['color']:'Sin color';
                                $idV = isset($res_color[0]['id'])?$res_color[0]['id']:'-';     
                            }

                            if(empty($variations) || $cont!=$contT){                                                                                   
                                foreach ($stocks_1 as $stock) {
                                    if ($stock->stock>=0) {           
                                        
                                        $stock->nombreColor = 'Sin color'?$color_prim:$stock->nombreColor;                             
                                        // Obtengo las imagenes por color
                                        $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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

                                        if (!empty($stock->nombreTalle)) {
                                            $combinations[] = array(
                                                "name" => "Talle",
                                                "value_name" => $nombreTalle
                                            );
                                        }

                                        \Log::info($idV);
                                        if($idV!='-' and $producto->ean!=''){
                                            $attributes  = array(
                                                "id" => "GTIN",                                        
                                                "value_name" => $producto->ean
                                            ); 

                                            $variations0[] = array(                                            
                                                "attribute_combinations" => $combinations,   
                                                "attributes" => [$attributes],                                         
                                                "available_quantity" => $stock->stock,
                                                "price" => $precio_public,
                                                "picture_ids" => $pictures_color,
                                                "seller_custom_field" => $stock->codigo 
                                            ); 
        
                                        //$this->putVariations($producto,$idV);
                                        }else{
                                            $variations0[] = array(                                            
                                                "attribute_combinations" => $combinations,                                          
                                                "available_quantity" => $stock->stock,
                                                "price" => $precio_public,
                                                "picture_ids" => $pictures_color,
                                                "seller_custom_field" => $stock->codigo 
                                            ); 
                                        }
                                                                            
                                                                                                                                                                                
                                    }                                
                                }      
                                \Log::info('variations0');
                                \Log::info(json_encode($variations0));                          
                            }

                            if(empty($variations) || $cont!=$contT){
                                $item = array(
                                    "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,
                                    "pictures" => $pictures,
                                    "variations" => $variations0
                                );
                            }else{
                                $item = array(
                                    "title" => $producto->nombremeli?$producto->nombremeli:$producto->nombre,
                                    "pictures" => $pictures,
                                    "variations" => $variations
                                );
                            }
                            
                            
                            // Modifico la publicaci??n
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                            \Log::info(json_encode($result));
                            
                            if ($result['httpCode'] != 200) {
                                for($i=0; $i<count($result['body']->cause); $i++ ){
                                    if($result['body']->cause[$i]){                                               
                                        foreach($result['body']->cause[$i] as $r){                                                 
                                            if($r=='285'){
                                                //error porque el precio tiene un descuento y se manda el precio sin descuento activo la bandera para armar solo el stock
                                                $freezed_by_deal = 1;                                                                                   
                                            }

                                            if($r=='165'){
                                                //error porque TIENE MAL CARGADO LOS TALLES
                                                $aResult['status'] = 1;
                                                $aResult['msg'] = "El atributo de variaci??n est?? duplicado. Combinaciones de atributos DEBEN SER ??nicos permitidos..";
                                                return response()->json($aResult);                                                                             
                                            }
                                        }
                                    }
                                }
                            }
                          
                            $variationsInit = $this->getVariations($producto->id_meli);                                                                                                             
                            if($freezed_by_deal == 1){
                                $variations = $this->updateVariationsByDeal($stocks_1, $variationsInit, $precio_public, $producto->id, $producto->id_meli, '0');
                                \Log::info(json_encode($variations));    
                                $item = array(                                                                    
                                    "variations" => $variations
                                );                           
                                $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                                \Log::info(json_encode($result));
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
                            // Verifico si el item tiene la opci??n de envio gratis
                            if($precio_public>2499){
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

                            // Modifico la publicaci??n
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                            \Log::info(json_encode($result));

                            if ($result['httpCode'] != 200) {
                                for($i=0; $i<count($result['body']->cause); $i++ ){
                                    if($result['body']->cause[$i]){                                               
                                        foreach($result['body']->cause[$i] as $r){                                                 
                                            if($r=='240'){
                                                //error porque el precio tiene un descuento y se manda el precio sin descuento activo la bandera para armar solo el stock
                                                $freezed_by_deal = 1;                                                                                   
                                            }
                                        }
                                    }
                                }
                            }
                          
                            $variationsInit = $this->getVariations($producto->id_meli);                                                                                                             
                            $stocks_1 = Util::getStock($producto->id, 'all');
                            \Log::info('variationsInit');
                            \Log::info(json_encode($variationsInit));

                            $cont=0;
                            $contT=0;
                                
                            for ($j=0; $j < count($variationsInit); $j++) { 
                                if(isset($variationsInit[$j]->attribute_combinations)){
                                    for ($k=0; $k <count($variationsInit[$j]->attribute_combinations); $k++) { 
                                        if($variationsInit[$j]->attribute_combinations[$k]->id=='SIZE'){
                                            $cont++;
                                        }
                                    }
                                }
                            }

                            foreach ($stocks as $stock) {
                                $contT++;
                            }

                            \Log::info('cont '.$cont); \Log::info('contT '.$contT);

                            if(!empty($variationsInit)){
                                $res_color = json_decode(json_encode($variationsInit),true);
                                $color_prim = isset($res_color[0]['color'])?$res_color[0]['color']:'Sin color';
                                $idV = isset($res_color[0]['id'])?$res_color[0]['id']:'-';     
                            }

                            if(empty($variationsInit) || $cont!=$contT){                                                                                   
                                foreach ($stocks_1 as $stock) {
                                    if ($stock->stock>0) {
                                         //reviso el nombre del color
                                        $stock->nombreColor = 'Sin color'?$color_prim:$stock->nombreColor;
                                        // Obtengo las imagenes por color
                                        $imagenesColor = FeUtilController::getImagesByColor($producto->id,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
                                            return response()->json($aResult);
                                        }

                                        $pictures_color = array();
                                        $combinations = array();
                                        
                                        foreach ($imagenesColor as $imagenes) {
                                            $pictures_color[] = \config('appCustom.PATH_UPLOADS'). 'productos/'.$imagenes['imagen_file'];
                                        }
                                        
                                        if (!empty($stock->nombreColor)) {
                                            $combinations[] = array(
                                                "name" => "Color",
                                                "value_name" => $stock->nombreColor
                                            );
                                        }      
                                        
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

                                        if (!empty($stock->nombreTalle)) {
                                            $combinations[] = array(
                                                "name" => "Talle",
                                                "value_name" => $nombreTalle
                                            );
                                        }

                                        \Log::info($idV);
                                        if($idV!='-' and $producto->ean!=''){
                                            $attributes  = array(
                                                "id" => "GTIN",                                        
                                                "value_name" => $producto->ean
                                            ); 

                                            $variations0[] = array(                                            
                                                "attribute_combinations" => $combinations,    
                                                "attributes" => [$attributes],                                        
                                                "available_quantity" => $stock->stock,
                                                "price" => $precio_public,
                                                "picture_ids" => $pictures_color,
                                                "seller_custom_field" => $stock->codigo 
                                            ); 
        
                                        //$this->putVariations($producto,$idV);
                                        }else{
                                            $variations0[] = array(                                            
                                                "attribute_combinations" => $combinations,                                           
                                                "available_quantity" => $stock->stock,
                                                "price" => $precio_public,
                                                "picture_ids" => $pictures_color,
                                                "seller_custom_field" => $stock->codigo 
                                            ); 
                                        }

                                                                                                                                                                                
                                    }                                
                                }                                
                            }
                                                                                              
                                
                            if(empty($variationsInit) || $cont!=$contT){                                    
                                $item = array(                                                                    
                                    "variations" => $variations0
                                );     
                            }else{
                                $variations = $this->updateVariationsByDeal($stocks_1, $variationsInit, $precio_public, $producto->id, $producto->id_meli, '0');
                                \Log::info(json_encode($variations));    
                                $item = array(                                                                    
                                    "variations" => $variations
                                );     
                            }
                            
                                                    
                            \Log::info('item');
                            \Log::info(json_encode($item));
                            $result = $this->meli->put('/items/'.$producto->id_meli, $item, ['access_token' => $this->access_token]);
                            \Log::info(json_encode($result));
                            


                            if ($result['httpCode'] == 200) {
                                $result = $result['body'];
                            } else {
                                $aResult['status'] = 1;
                                $aResult['msg'] = $result['body']->message;
                                return response()->json($aResult);
                            }
                        }
                    }

                    // Para actualizar la descripci??n se envia a otra direcci??n
                    $institucional = "";
                    $nota = ConfGeneral::find(3);
                    
                    if($nota){
                        $institucional = $nota->valor;
                    }      
                           
                    $itemDescripcion = array(
                        "plain_text" => $producto->nombremeli?$producto->nombremeli. "\n\n" .$producto->sumario. "\n\n\n\n" .$institucional : $producto->nombre. "\n\n" .$producto->sumario. "\n\n\n\n" .$institucional
                    );
                    
                    // Modifico la descripci??n
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
                // Finalizo la publicaci??n antes de borar
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
                    $aResult['msg'] = "No se pudo borrar la publicaci??n, intente nuevamente.";
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

    // Modifico el estado de la publicaci??n.
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

    // Borro una variaci??n
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

    // Funci??n para predecir la categor??a en base al nombre del producto
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
        // Con el id de meli obtengo los datos de la publicaci??n
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
        // Con el id de meli obtengo los datos de la publicaci??n
        $url = "/items/".$id_meli."/variations";
        $variations = $this->meli->get($url);
        if ($variations['httpCode'] == 200) {
            return $variations['body'];
        } else {
            return false;
        }
    }

    private function putVariations($producto,$idV){
           
        $attributes  = array(
            "id" => "GTIN", 
            //"name" => "EAN",                                            
            "value_name" => $producto->ean
        );
        
        
        $variations["variations"] = [[
            "id" => $idV,
            "attributes" => [$attributes]
        ]];
        \Log::info('putvariations');
        \Log::info(json_encode($variations));
        $result = $this->meli->put('/items/'.$producto->id_meli, $variations , ['access_token' => $this->access_token]);
        \Log::info(json_encode($result));
    }


    private function updateVariationsByDeal($stocks_1, $variations, $precio_db, $id_producto, $id_meli, $opcion)
    {
        $atribute = array();
        
        if ($opcion == '0') {
            // La categoria no tiene variaci??n pero al producto se le agregaron variaciones
            
            foreach ($variations as $variation) {
                
                foreach ($stocks_1 as $stock) {                   

                    if (count($variation->attribute_combinations) == 2) {
                        $value_name = Util::cambiaAcento($variation->attribute_combinations[0]->value_name);
                        if ($stock->nombreColor == $value_name || $stock->nombreColor=='Sin color') {
                            if ($stock->nombreTalle == $variation->attribute_combinations[1]->value_name) {                                
                                $atribute[] = array(
                                    "id" =>  $variation->id,
                                    "available_quantity" => $stock->stock
                                );                                      
                                
                            }
                        }
                    }
                }
               
            }            
        }


        /* foreach ($variations as $variation) {
            unset($variation->attribute_combinations);
            unset($variation->catalog_product_id);
            unset($variation->sale_terms);
        } */
        
        return $atribute;
    }

    private function updateVariations($stocks_1, $variations, $precio_db, $id_producto, $id_meli, $opcion)
    {
        
        if ($opcion == '0') {
            // La categoria no tiene variaci??n pero al producto se le agregaron variaciones
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
                            // Obtengo las fotos para la variaci??n
                            $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                            if (count($imagenesColor) == 0) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
                                // Obtengo las fotos para la variaci??n
                                $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                if (count($imagenesColor) == 0) {
                                    $aResult['status'] = 1;
                                    $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
            // La categor??a tiene variaci??n
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
                            // Obtengo las fotos para la variaci??n
                            $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                            if (count($imagenesColor) == 0) {
                                $aResult['status'] = 1;
                                $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
                                        // Obtengo las fotos para la variaci??n
                                        $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
                                    // Obtengo las fotos para la variaci??n
                                    $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                    if (count($imagenesColor) == 0) {
                                        $aResult['status'] = 1;
                                        $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
                                        // Obtengo las fotos para la variaci??n
                                        $imagenesColor = FeUtilController::getImagesByColor($id_producto,'all', 'productos',$stock->id_color);
                                        if (count($imagenesColor) == 0) {
                                            $aResult['status'] = 1;
                                            $aResult['msg'] = "Debe cargar como m??nimo una foto por cada color.";
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
            ->where('inv_productos.estado_meli','=',1)
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
