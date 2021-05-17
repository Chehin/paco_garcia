<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class ProductosController extends Controller
{

    public function viewProducto(Request $request, $id, Api $api){ 
       
        $pageTitle = env('SITE_NAME');
        $this->view_ready($api);
        $array_send = array(
            'id' => $id,
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_idioma' => 1,
            'id_moneda' => env('ID_MONEDA')
        );
 
        $rest=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET','producto?'.$post);
            $data = $res['data'];
            if(!isset($data['producto'])){
                return redirect()->route('home');
            }
            $producto = $data['producto'];
            $categoria = $data['categoria'];
            $etiquetas = $data['etiquetas'];
            $precios = $data['precios'];
            $fotos = $data['fotos'];
            
            $stock = $data['stockColor'][0]['stock_total'];
            $stockColor = $data['stockColor'];
            $subRubroGeneroMarca = $data['subrubrogeneromarca'];
            $pageTitle.= isset($categoria['rubro']['rubro'])?' - '.$producto['nombre'].' - '.$categoria['rubro']['rubro'] : $producto['nombre'];
            $pageTitle.= isset($categoria['subrubro']['subrubro'])?' - '.$categoria['subrubro']['subrubro']:'';
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

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


        //relacionados colores
        $array_send_color = array(
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
        $relacionadosColor = array();
        try {
            $post = http_build_query($array_send_color);
            $res = $api->client->resJson('GET', 'listadoProductosRelacionadosColor?'.$post)['data'];
            $rel = $res;
            $relacionadosColor['productos'] = $rel['productos'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    
        return view('productos.detalle', compact('etiquetas','producto','categoria','fotos','precios', 'stockColor','subRubroGeneroMarca','stock','relacionados','relacionadosColor','pageTitle'));
    }  

    public function listproductos(Request $request, $id_etiqueta = 0, $id_rubro = 0, $id_subrubro = 0, $name = 0, $page = 1, Api $api){
        \Log::info('pasa etiqueta');
      
        $pageTitle = env('SITE_NAME') . " - Productos ";
        $this->view_ready($api);
        $q = $request->input('q')?$request->input('q'):0;
        $id_marca = $request->input('marca')?$request->input('marca'):'';
        $deporte = $request->input('deporte')?$request->input('deporte'):'';
        $precio = $request->input('precio')?$request->input('precio'):'';
        $header = false;
        
        switch($request->input('sortList')){
            case 'nombre':
                $sort = 'inv_productos.nombre';
                $dir = 'asc';
            break;
            case 'menorPrecio':
                $sort = 'inv_productos.nombre';
                $dir = 'asc';
            break;
            case 'mayorPrecio':
                $sort = 'inv_productos.nombre';
                $dir = 'desc';
            break;
            case 'destacados':
                $sort = 'inv_productos.destacado';
                $dir = 'desc';
            break;
            case 'ofertas':
                $sort = 'inv_productos.oferta';
                $dir = 'desc';
            break;
            default:
                $sort = (string)env('ORDEN_COL');
                $dir = (string)env('ORDEN_DIR');
            break;
        }
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'fotos' => 1,
            'id_moneda' => (string)env('ID_MONEDA'),
            'orden' => array(
                'col' => $sort,
                'dir' => $dir
            ),
            'iDisplayLength' => (int)env('REGISTROS_PAGINA'), //registros por pagina
            'iDisplayStart' => ((int)$page-1)*(int)env('REGISTROS_PAGINA') //registro inicial (dinamico)
        );

        if($id_rubro){
            $array_send['filtros']['id_rubro'] = $id_rubro;
        }
        if($id_subrubro){
            $array_send['filtros']['id_subrubro'] = $id_subrubro;
        }
        if($id_etiqueta){
            $array_send['tag'] = $id_etiqueta;
        }
        if($id_marca){
            $array_send['id_marca'] = $id_marca;
        }
        if($deporte){
            $array_send['id_deporte'] = $deporte;
        }
        if($precio){
            $array_send['precio'] = $precio;
        }
        if($q){
			$array_send['search'] = $q;
        }
 
        $res=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);   
            \Log::info(print_r($post,true));
            \Log::info('pasa listadoProductos');
            $res= $api->client->resJson('GET', 'listadoProductos?'.$post)['data'];
            $data = $res;
            $productos_array = $data['productos'];
            $etiqueta_array = $data['etiqueta'];
            $categorias_array = $data['categoria'];
            $deporte_array = $data['deporte'];
            $marca_array = $data['marca'];
            $search = $q;
            $total_reg = $data['total'];
            $total_pages = ceil($total_reg/ (int)env('REGISTROS_PAGINA'));
            if($total_reg==1){
                //redirigir al producto
                return redirect()->route('producto',['id' => $productos_array[0]['id'],'name' => str_slug($productos_array[0]['titulo'])]);
			}
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        if(isset($categorias_array['rubro']['header']['0'])){
            $header = $categorias_array['rubro']['header'];
        }elseif(isset($etiqueta_array['header']['0'])){
            $header = $etiqueta_array['header'];
        }
            
        $extraParams = array(
            'getData' => $request->all(),
            'url' => array(
                'id_etiqueta' => $id_etiqueta, 
                'id_rubro' => $id_rubro, 
                'id_subrubro' => $id_subrubro, 
                'name' => str_slug($name), 
                'page' => $page
            )
        );

        //get filtros
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos'
        );
 
        $res=Util::aResult();
        $data_filtros = array();
        try {
            $post = http_build_query($array_send);
            \Log::info('pasa filtros');
            \Log::info(print_r($post,true));
            $res = $api->client->resJson('GET','filtros?'.$post);
            \Log::info(print_r($res['data'],true));
            $data_filtros = $res['data'];
            $data_rubros = $data_filtros['rubros']['data'];
            $data_marcas = $data_filtros['marcas'];
            $data_precios = $data_filtros['precios'];
            $data_deportes = $data_filtros['deportes'];
            $data_etiquetas = $data_filtros['etiquetas'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        $filtros = array(
            'rubros' => $data_rubros,
            'marcas' => $data_marcas,
            'etiquetas' => $data_etiquetas,
            'deportes' => $data_deportes,
            'precios' => $data_precios
        );
        //get filtros fin

        return view('productos.listado', compact('filtros', 'extraParams', 'page', 'total_reg', 'total_pages', 'etiqueta_array', 'categorias_array', 'productos_array', 'search','pageTitle','header'));
    }

    public function filtrproductos(Request $request ,  Api $api){
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'categorias' => (sizeof($request->input('categorias')>0))?$request->input('categorias'):null,
            'rubros' => (sizeof($request->input('rubros')>0))?$request->input('rubros'):null,
            'deportes' => (sizeof($request->input('deportes')>0))?$request->input('deportes'):null,
            'marcas' => (sizeof($request->input('marcas')>0))?$request->input('marcas'):null,
            'precios' => (sizeof($request->input('precios')>0))?$request->input('precios'):null,
            'sortlist' => $request->input('sortlist')?$request->input('sortlist'):null,
            'page' =>  $request->input('page')?$request->input('page'):(int)1
        );
        $res=Util::aResult();
        $data = array();
        $post = http_build_query($array_send);        
        $res = $api->client->resJson('GET', 'filtrop?'.$post);
        $data = $res['data'];




        return $data;

        // try {
        //     $post = http_build_query($array_send);
        //     $res = $api->client->resJson('GET', 'filtrop?'.$post);
        //     $data = $res['data'];
        //     \Log::info($data);
        //     return $data;
		// } catch (RequestException $e) {
		// 	Log::error(Psr7\str($e->getRequest()));
		// 	if ($e->hasResponse()) {
		// 		Log::error($e->getMessage());
		// 	}
        // }
    }
    

    public function cambioColor(Request $request, Api $api){
        //traer fotos, talles, codigo y stock de ese color

        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_producto' => $request->id_producto,
            'id_color' => $request->id_color,
            'id_marca' => $request->id_marca,
            'id_genero' => $request->id_genero
        );

        $res=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);            
            $res = $api->client->resJson('GET', 'cambioColor?'.$post);
            $data = $res['data'];
            return $data;
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        
    }

}
