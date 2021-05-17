<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Banners;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\BannersTipos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;

class BannersController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
        $this->id = $request->input('id');
    }
    public function index(Request $request)
    {
    	$aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view')) {
        	$now = Carbon::now()->format('Y-m-d');
        	$aItem = Banners::where('habilitado',1)->where('id_posicion',$this->id)->where('inicio','<=',$now)->where('fin','>=',$now)->first();
			if ($aItem) {
				// Actualizo el número de impresiones
				$aItem->impresiones = $aItem->impresiones + 1;
				$aItem->save();
			
				$data = array(
					'banner' => $aItem,
					'salida' => ''
				);
				if ($aItem->texto != '') { // Texto personalizado
					$data['salida'] = $aItem->texto;			
				} else {
					$path = \config('appCustom.PATH_BANNERS');
					// Busco el código de banners tipos
					$aTipoBanner = BannersTipos::find($aItem->id_tipo);
					$tmp = $aTipoBanner->codigo;
					$val = $path . $aItem->banners;
					// Dimensiones pop / flash
					$ancho_pop = $aItem->anchopop;
					$alto_pop = $aItem->altopop;
					$ancho_flash = $aItem->anchoflash;
					$alto_flash = $aItem->altoflash;

					$link = "linker/".$aItem->id;
					$objetivo = $aItem->target;
					$link1=$aItem->link;
					$salida9=$tmp;
					$salida6=str_replace("anchoflash", $ancho_flash, $salida9);
					$salida5=str_replace("altoflash", $alto_flash, $salida6);
					$salida4=str_replace("objetivo", $objetivo,$salida5);
					$salida3=str_replace("link", $link,$salida4);
					$salida2=str_replace("altopop", $alto_pop,$salida3);
					$salida1=str_replace("anchopop", $ancho_pop,$salida2);
					$data['salida'].=str_replace("archivo", $val, $salida1);
			}
				
				$aResult['data'] = $data;
			}

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

    public function banners_click(Request $request)
    {
    	$aResult = Util::getDefaultArrayResult();
    	if ($this->user->hasAccess($this->resource . '.view')) {
	    	$aItem = Banners::find($this->id);
			if ($aItem) {
				$aItem->clicks = $aItem->clicks + 1;
				$aItem->save();
			}
			if($aItem->id_etiqueta>0){
				$etiqueta = Etiquetas::find($aItem->id_etiqueta);
				if($etiqueta){
					$aItem['etiqueta'] = $etiqueta->nombre;
				}else{
					$aItem['etiqueta'] = 'banner';
				}
			}
			$data = array(
				'banner' => $aItem
			);
			$aResult['data'] = $data;
		 } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }
}