<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;
use Session;

class ContactoController extends Controller
{


    public function index(Request $request, Api $api){
	
		
		$pageTitle=env('SITE_NAME') .' - Contacto';
		$this->view_ready($api);
		return view('contacto.index',compact('okmge','pageTitle'));
	}
	

	public function send(Request $request, Api $api){

		$aResult=Util::aResult();
		$menu=Util::aResult();
		$okmge='success|<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Gracias por contactarnos. Nos comunicaremos a la brevedad</div>';
		
		try {
			
			$aData=array(
                'nombre' => $request->input('nombre'),
			    'telefono' => $request->input('telefono'),
			    'email' => $request->input('email'),
			    'mensaje' => $request->input('mensaje')
			);

			$post = http_build_query($aData);
			$aResult = $api->client->resJson('POST', 'contacto?'.$post);
			$pageTitle=env('SITE_NAME') .' - Contacto';
		
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
		} 
			
			return $okmge;
    }

    
}