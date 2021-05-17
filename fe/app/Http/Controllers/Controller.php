<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class Controller extends BaseController
{
    public function __construct()
    {
        session_start();
        config('services');
        set_time_limit(120); 
    }

    public function view_ready($api){        
        //menu 
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos'
        );
        $menu=Util::aResult();
		try {			
             $post = http_build_query($array_send);
             $menu = $api->client->resJson('GET', 'menu?'.$post);
             view()->share( 'menu_web', $menu['data']);
        
		} catch (RequestException $e) {
			\Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        //menu footer
        $array_send = array(
                'id_edicion' => 'MOD_NEWS_FILTER',
                'edicion' => 'news',
                'fotos' => 0,
                'orden' => array(
                    'col' => env('orden'),
                    'dir' => env('ASC')
                ),
                'iDisplayLength' => 99, //registros por pagina
                'iDisplayStart' => 0 //registro inicial (dinamico)
        );
        $data = array();
        //institucional
        $array_send_e = $array_send;
        $array_send_e['id_seccion'] = 2;
        $array_send_e['destacado'] = 1;
        $post_e = http_build_query($array_send_e);
        $res_e = $api->client->resJson('GET', 'listadoNotas?'.$post_e);                
        $data['institucional'] = $res_e['data'];
        
        //ayuda
        $array_send_n = $array_send;
        $array_send_n['id_seccion'] = 1;
        $array_send_e['destacado'] = 1;
        $post_n = http_build_query($array_send_n);
        $res_n = $api->client->resJson('GET', 'listadoNotas?'.$post_n);                
        $data['ayuda'] = $res_n['data'];

        //sucursales
        $array_send_s = $array_send;
        $array_send_s['id_edicion'] = 'MOD_SUCURSALES_FILTER';
        $array_send_s['id_seccion'] = 3;
        $post_s= http_build_query($array_send_s);
        $res_s = $api->client->resJson('GET', 'listadoNotas?'.$post_s);
        $data['sucursales'] = $res_s['data'];
        
        view()->share( 'menu_footer', $data);
    }

    public function adparser($api, $id){
         //banners
         $array_send = array(
            'edicion' => 'banners',
            'id' => $id
        );
        $banners=Util::aResult();
        $banner = '';
        try {	
            $post = http_build_query($array_send);
            \Log::info(print_r($post,true));
            $banners= $api->client->resJson('GET', 'banners_front?'.$post)['data'];
            if($banners){
                $banner = $banners['salida'];
            }
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
        return $banner;
    }
    public function clickBanner($id , Api $api){
        $array_send = array(
            'edicion' => 'banners',
            'id' => $id
        );

        $click_banner = Util::aResult();

        try {
            $post = http_build_query($array_send);
            $click_banner = $api->client->resJson('GET','banners_click?'.$post);
            if ($click_banner['status'] == 0){
                $click_banner = $click_banner['data'];
            }
            if ($click_banner['banner']) {
                $link = $click_banner['banner']['link'];
            } else {
                $link = "index.php";
            }
            
            return redirect($link);
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }        
    }

}
