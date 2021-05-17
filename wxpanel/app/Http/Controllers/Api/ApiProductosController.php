<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\PreciosProductos;
use App\Http\Controllers\Fe\FeUtilController;

class ApiProductosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'productos';
        $this->resourceLabel = 'Productos';
        $this->modelName = 'App\AppCustom\Models\Productos';
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        $error = array();
        if ($this->user->hasAccess($this->resource . '.create')) {
            
            $modelName = $this->modelName;

            $productos = $request->input('productos');

            foreach ($productos as $producto) {
                // Limpio las variables para que no se creen los productos con rubros, subrubros y marcas incorrectos
                unset($rubro);
                unset($subrubro);
                unset($marca);
                unset($atributos);
                unset($color);
                unset($talle);
                $producto = json_decode($producto);
                if(isset($producto->dimensiones)){
                    $productoDimensiones = json_decode(json_encode($producto->dimensiones,true),true);
                }
                
                // Verifico si el rubro existe
                if (isset($producto->rubro) && isset($producto->subrubro)) {
                    $rubro = Rubros::where('nombre','=',$producto->rubro)->first();
                    if (!$rubro) {
                        // Si no existe se debe crear el rubro
                        $array_rubro = array(
                            'nombre' => $producto->rubro
                        );
                        $request->request->add($array_rubro);
                        $aResult = app('App\Http\Controllers\RubrosController')->store($request);
                        $aResult = json_decode($aResult->getContent(),true);
                        $rubro = Rubros::where('nombre','=',$producto->rubro)->first();
                        
                        //echo "<pre>";print_r($rubro);echo "</pre>";die();
                        
                    }
                    
                    // Verifico si el subrubro existe
                    $subrubro = SubRubros::where('nombre','=',$producto->subrubro)
                                        ->where('id_rubro', $rubro->id)
                                        ->first();

                    if (!$subrubro) {
                        // Si no existe se debe crear el subrubro
                        $array_subrubro = array(
                            'nombre' => $producto->subrubro,
                            'id_rubro' => $rubro->id,
                            'orden' => 0
                        );
                        $request->request->add($array_subrubro);
                        $aResult = app('App\Http\Controllers\SubRubrosController')->store($request);
                        $aResult = json_decode($aResult->getContent(),true);
                        $subrubro = SubRubros::where('nombre','=',$producto->subrubro)
                                            ->where('id_rubro', $rubro->id)
                                            ->first();
                    }
                }

                if (isset($producto->marca)) {
                    // Verifico si la marca existe
                    $marca = Marcas::where('nombre','=',$producto->marca)->first();
                    if (!$marca) {
                        // Si no existe se debe crear la marca 
                        $array_marca = array(
                            'nombre' => $producto->marca
                        );
                        $request->request->add($array_marca);
                        $aResult = app('App\Http\Controllers\MarcasController')->store($request);
                        $aResult = json_decode($aResult->getContent(),true);
                        $marca = Marcas::where('nombre','=',$producto->marca)->first();
                    }
                }
                if (isset($producto->genero)) {
                    // Verifico si el genero existe
                    $genero = Genero::where('genero','=',$producto->genero)->first();
                    if (!$genero) {
                        // Si no existe se debe crear la genero 
                        $genero = new Genero;
                        $genero->genero = $producto->genero;
                        $genero-save();
                    }
                }
                
                //armo el json para stock por sucursal
                if(isset($producto->atributos)){
                    $productoAtributos = $producto->atributos;
                    //color y talle debo buscar el id, y si no existe crearlos
                    array_walk($productoAtributos, function(&$val,$key){
                        $stock = array();
                        foreach ($val->stock[0] as $nombre => $valor) {
                            $sucursal = Note::select('id_nota')->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))->where('habilitado', 1)->where('antetitulo', $nombre)->first();
                            if(!$sucursal){
                                $sucursal = new Note;
                                $sucursal->id_edicion = \config('appCustom.MOD_SUCURSALES_FILTER');
                                $sucursal->titulo = $nombre;
                                $sucursal->antetitulo = $nombre;
                                $sucursal->save();
                            }
                            $dato = array(
                                'id' => $sucursal->id_nota,
                                'stock' => $valor
                            );
                            array_push($stock, $dato);
                        };
                        $val->stock = $stock;
                        //color
                        $color = Colores::select('id')->where('nombre', $val->color)->where('habilitado', 1)->first();
                        if(!$color){
                            $color = new Colores;
                            $color->nombre = $val->color;
                            $color->habilitado = 1;
                            $color->save();
                        }
                        $val->id_color = $color->id;
                        unset($val->color);
                        //talle
                        $talle = Talles::select('id')->where('nombre', $val->talle)->where('habilitado', 1)->first();
                        if(!$talle){
                            $talle = new Talles;
                            $talle->nombre = $val->talle;
                            $talle->habilitado = 1;
                            $talle->save();
                        }
                        $val->id_talle = $talle->id;
                        unset($val->talle);
                        
                        $val->estado_meli = 1;
                    });
                }else{
                    $productoAtributos = array();
                }

                // Verico si existe el producto 
                $item = $modelName::where('id_api','=',$producto->id)->first();

                if (!$item) {
                    // Si no existe el producto se debe crear
                    // Array para crear un nuevo producto
                    
                    $array_send = array(
                        'id_api' => $producto->id,
                        'nombre' => $producto->nombre,
                        'sumario' => $producto->descripcion,
                        'ean' => $producto->ean,
                        'sku' => $producto->sku,
                        'orden' => 0,
                        'habilitado' => 0,
                        'stockColor' => json_encode(array($productoAtributos))
                    );
                    if (isset($productoDimensiones)) {
                        $array_send['alto'] = $productoDimensiones[0]['alto'];
                        $array_send['ancho'] = $productoDimensiones[0]['ancho'];
                        $array_send['largo'] = $productoDimensiones[0]['largo'];
                        $array_send['peso'] = $productoDimensiones[0]['peso'];
                    }
                                        
                    if (isset($rubro) && isset($subrubro) && isset($marca) && isset($genero)) {
                        $array_send['id_rubro'] = $rubro->id;
                        $array_send['id_subrubro'] = $subrubro->id;
                        $array_send['id_marca'] = $marca->id;
                        $array_send['id_genero'] = $genero->id;
                    } else {
                        $array_send['id_rubro'] = '';
                        $array_send['id_subrubro'] = '';
                        $array_send['id_marca'] = '';
                        $array_send['id_genero'] = '';
                    }

                    $request->request->add($array_send);

                    $aResult = app('App\Http\Controllers\ProductosController')->store($request);                    
                    
                    $aResult = json_decode($aResult->getContent(),true);

                    if ($aResult['status'] == 1) {
                        array_push($error, 'El producto ' . $producto->nombre . ', id: ' . $producto->id . ' no se pudo crear');
                    } elseif ($producto->precio_venta) {
                        // obtengo la moneda por default
                        $moneda_default = Util::getMonedaDefault();
                        $id_moneda = ($moneda_default?$moneda_default[0]['id']:1);

                        // Obtengo el producto creado
                        $item = $modelName::select('id')->where('id_api','=',$producto->id)->first();
                        // Array para guardar el precio del producto
                        $array_precio = array(
                            'resource_id' => $item->id,
                            'id_moneda' => $id_moneda,
                            'precio_venta' => $producto->precio_venta,
                            'precio_lista' => isset($producto->precio_lista)?$producto->precio_lista:null
                        );

                        $request->request->add($array_precio);

                        $aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);

                        $aResult = json_decode($aResult->getContent(),true);
                    }

                } else {
                    // Se debe actualizar el producto
                    $array_send = array(
                        'id_api' => $producto->id,
                        'ean' => $producto->ean,
                        'sku' => $producto->sku,
                        'nombre' => $item->nombre,
                        'id_rubro' => $item->id_rubro,
                        'id_subrubro' => $item->id_subrubro,
                        'id_marca' => $item->id_marca,
                        'id_genero' => $item->id_genero,
                        'alto' => $item->alto,
                        'ancho' => $item->ancho,
                        'largo' => $item->largo,
                        'peso' => $item->peso,
                        'orden' => $item->orden,
                        'stockColor' => json_encode(array($productoAtributos))
                    );

                    $request->request->add($array_send);

                    $aResult = app('App\Http\Controllers\ProductosController')->update($request,$item->id);
                    
                    $aResult = json_decode($aResult->getContent(),true);

                    if ($aResult['status'] == 1) {
                        array_push($error, 'El producto ' . $producto->nombre . ', id: ' . $producto->id . ' no se pudo actualizar');
                    } elseif ($producto->precio_venta) {
                        // obtengo la moneda por default
                        $moneda_default = Util::getMonedaDefault();
                        $id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
                    
                        // Array para actualizar el precio del producto
                        $array_precio = array(
                            'resource_id' => $item->id,
                            'id_moneda' => $id_moneda,
                            'precio_venta' => $producto->precio_venta,
                            'precio_lista' => isset($producto->precio_lista)?$producto->precio_lista:null
                        );

                        // Obtengo el id del registro en la tabla inv_precios
                        $id_precio = PreciosProductos::select('id')->where('id_moneda','=',$id_moneda)
                                                        ->where('id_producto','=',$item->id)->first()->id;

                        $request->request->add($array_precio);

                        if ($id_precio) {
                            // Si tiene un precio cargado actualizo el valor
                            $aResult = app('App\Http\Controllers\PreciosRelatedController')->update($request,$id_precio);
                        } else {
                            // Si no tiene un precio cargado lo creo
                            $aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);
                        }

                        $aResult = json_decode($aResult->getContent(),true);
                    }
                }
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        $aResult['data'] = $error;
        return response()->json($aResult);
    }
}
