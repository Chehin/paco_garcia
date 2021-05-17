<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosNotificaciones;
use App\AppCustom\Models\Provincias;

class GoogleMapsController extends Controller
{
    static function geoCodificar($id_direccion, $id_pedido=''){		
		$aResult = Util::getDefaultArrayResult();
		\Log::info('geocodificar');
		\Log::info($id_pedido);


		$direccion = PedidosDirecciones::find($id_direccion);
		$prov = Provincias::find($direccion->id_provincia);
        
        if($prov){
			\Log::info('MAPS');

            //Direccion para Google Api
            $map_address = $direccion->direccion. ' '.$direccion->numero.' '.$direccion->ciudad.' '.$prov->provincia.' Argentina';
            
            $url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDPGUWUeNkS7HfMXZO33taHOci4nYrsYXQ&sensor=false&address=".urlencode($map_address);
            $lat_long = file_get_contents($url);

			$response = json_decode($lat_long, true);
			\Log::info(print_r($response, true));
			
	    	if ($response['status'] == 'OK') {
				$aResult['data'] = $response;
				\Log::info('ok');
				
				
            }else{
				\Log::info('notif');

				//Guardo una Notificacion
				$notificacion = new PedidosNotificaciones();
				$notificacion->id_pedido = $id_pedido;
				$notificacion->texto = $geoCoordenadas;
				$notificacion->status = 'Maps no encontro su direccion';
				$notificacion->more_info = $map_address;
				$notificacion->emisor = 'Google maps';
				$notificacion->save();
			
				$aResult['status'] = 1;
            	$aResult['msg'] = 'Direccion No encontrada';
			}
        }else{
			\Log::info('No prov');

            $aResult['status'] = 1;
            $aResult['msg'] = 'Provincia no encontrada';
		}
		
		return $aResult;
	}
}