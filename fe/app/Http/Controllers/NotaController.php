<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class NotaController extends Controller
{

    public function listslide(Request $request,$id,$page = 1, $name = 0, Api $api){
        $this->view_ready($api);
        $pageTitle = env('SITE_NAME');
        
        $array_send = array(
            'id_nota' => $id,
            'id_moneda' => 1,
            'id_edicion' => 'MOD_NEWS_FILTER',
            'edicion' => 'news',
            'page' => $page, //registro inicial (dinamico)
            'iDisplayLength' => (int)env('REGISTROS_PAGINA')+1, //registros por pagina
            'iDisplayStart' => ((int)$page-1)*((int)env('REGISTROS_PAGINA')+1) //registro inicial (dinamico)
        );
        $res=Util::aResult();

        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET','sliderListado?'.$post);
            
            if ($res['status'] == 0){
                $slider = $res['data']['slider'];
                $data_news = $res['data']['news'];
                $productos_array = $res['data']['productos'];

                if(count($productos_array)==1 && !$data_news){
                    //redirigir al producto
                    return redirect()->route('producto',['id' => $productos_array[0]['id'],'name' => str_slug($productos_array[0]['titulo'])]);
                }elseif(count($data_news)==1 && !$productos_array){
                    return redirect()->route('nota',['id' => $data_news[0]['id'],'name' => str_slug($data_news[0]['titulo'])]);
                }
                $total_reg = $res['data']['total'];
                $total_pages = ceil($total_reg/ (int)env('REGISTROS_PAGINA'));
            }
            $extraParams = array(
                'getData' => $request->all(),
                'url' => array(
                    'id' => $id,
                    'name' => str_slug($name), 
                    'page' => $page
                )
            );     
            return view('slider.listado', compact('slider', 'data_news','productos_array','pageTitle','extraParams', 'page', 'total_reg', 'total_pages'));
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }     
    }  

   
    public function nota(Request $request, $id, Api $api){
        $this->view_ready($api);
        $pageTitle = env('SITE_NAME');

        $array_send = array(
            'id' => $id,
            'id_edicion' => 'MOD_NEWS_FILTER',
            'edicion' => 'news'
        );
        $res =Util::aResult();
        $nota = array();
        $fotos = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET', 'nota?'.$post);
            if ($res['status'] == 0){
                $nota = $res['data']['nota'];              
                $fotos = $res['data']['fotos'];
                $pageTitle.=' - '.$nota['titulo'];
            }
        return view('notas.nota', compact('nota','fotos','pageTitle'));
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
                
    }

    public function notaSuc(Request $request, $id, Api $api){
        $pageTitle = env('SITE_NAME');
        $this->view_ready($api);

        $array_send = array(
            'id' => $id,
            'id_edicion' => 'MOD_SUCURSALES_FILTER',
            'edicion' => 'news'
        );
        $res =Util::aResult();
        $nota = array();
        $fotos = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET', 'nota?'.$post);
            if ($res['status'] == 0){
                $nota = $res['data']['nota'];         
                $fotos = $res['data']['fotos'];
                $pageTitle.=' - '.$nota['titulo'];
            }
        return view('notas.nota', compact('nota','fotos','pageTitle'));
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
                
    }

    public function listNotas(Request $request, $id, Api $api){

        $pageTitle = env('SITE_NAME');
        $page = 1;
        $id_rel = $request->input('id_rel')?$request->input('id_rel'):'';

        $array_send = array(
            'id_edicion' => 'MOD_NEWS_FILTER',
            'edicion' => 'news',
            'fotos' => 1,
            'orden' => array(
                'col' => env('ORDEN_COL'),
                'dir' => env('ORDEN_DIR')
            ),
            'id_seccion' => $id,
            'id_rel' => $id_rel,
            'iDisplayLength' => 99,
            'iDisplayStart' => ($page-1)*env('REGISTROS_PAGINA') //registro inicial (dinamico)
        );

        $res=Util::aResult();

        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET', 'listadoNotas?'.$post);
            if ($res['status'] == 0){
                $data =$res['data'];
            }
        $this->view_ready($api);
        return view('notas.index', compact('data','pageTitle'));
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

}