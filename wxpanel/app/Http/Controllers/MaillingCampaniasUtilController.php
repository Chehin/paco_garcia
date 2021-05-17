<?php

namespace App\Http\Controllers;

use App\AppCustom\Util;
use Illuminate\Http\Request;
use App\AppCustom\Models\Listas;
use App\AppCustom\Models\TemplatesEditables;
use App\AppCustom\Models\Mailling;
use App\AppCustom\Models\Campaign;
use App\AppCustom\Models\EmailTracking;
use App\AppCustom\Models\CampaignTesting;
use App\AppCustom\Models\CampaignListas;
use App\AppCustom\Models\CampaignTestingLista;
use App\AppCustom\Models\MktListasPersonas;
use App\AppCustom\Models\MktEmpresas;
use App\AppCustom\Models\MktListas;
use App\AppCustom\Models\MktProvincias;
use Illuminate\Support\Facades\DB; # Para utilizar DB en las consultas

class MaillingCampaniasUtilController extends GenericUtilController
{
    public function __construct(MaillingCampaniasController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}

	public function filtroMails(Request $request) { 
            
		$term = trim($request->q);
		$aViewData = Util::getFiltroMails($term);             

		return \Response::json($aViewData);
		  
	}
	
	public function mailSend(Request $request) { 
		//se cambia el charset para los emojis
		\DB::statement("SET NAMES 'utf8mb4'");
		\Log::info('Notificación. pasa');
		//obtener la lista de la campaña
		$lista=Util::contactos($request->id);
		
		$campanias=Util::campanias($request->id);

				if (! $campanias->isEmpty()) {
					foreach ($campanias as $c) {
						Util::campania($c,$lista);
					}

					//guardo la ultima fecha de envio
				$item=[
					'fechaenvio' => \Carbon\Carbon::now()
				];

				Campaign::where('id','=', $request->id)->update($item);

				} else {
					\Log::info('Notificación. No se encontraron campañas estandares para enviar');
				}
			
			//se vuelve a utf8
			\DB::statement("SET NAMES 'utf8'");     
	}

	public function mailSendAB(Request $request) {  
		//se cambia el charset para los emojis
		\DB::statement("SET NAMES 'utf8mb4'");		
								
				$campaniasA=Util::campaniasA($request->id);									
				$campaniasB=Util::campaniasB($request->id);
				
				if (! $campaniasA->isEmpty()) {
					$lista=	Util::contactosAB($request->id,'a');
					foreach ($campaniasA as $ca) {
						Util::campaniaA($ca,$lista);
					}

				//guardo la ultima fecha de envio
				$item=[
					'fechaenvio' => \Carbon\Carbon::now()
				];

				CampaignTesting::where('id','=', $request->id)->update($item);

				} else {
					\Log::info('Notificación. No se encontraron campañas A para enviar');
				}


				if (! $campaniasB->isEmpty()) {
					$lista=	Util::contactosAB($request->id,'b');
					foreach ($campaniasB as $cb) {
						Util::campaniaB($cb,$lista);
					}
				} else {
					\Log::info('Notificación. No se encontraron campañasB para enviar');
				}

		//se vuelve a utf8
		\DB::statement("SET NAMES 'utf8'");     
	}

}