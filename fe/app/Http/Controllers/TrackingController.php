<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;


class TrackingController extends Controller
{


    public function index(Request $request, Api $api){
		
        $aResult=Util::aResult();
		$this->view_ready($api);
		try {			
            $aData=array(
                'id' => $request->input('id'),
			    'user' => $request->input('user'),
			    'idT' => $request->input('idT'),
			    'idab' => $request->input('idab')
			);

			$post = http_build_query($aData);
			$aResult = $api->client->resJson('GET', 'trackingMail?'.$post);
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
		}

    }

    public function link(Request $request, Api $api){
		
        $aResult=Util::aResult();

		try {			
            $aData=array(
                'id' => $request->input('id'),
			    'user' => $request->input('user'),
			    'idT' => $request->input('idT'),
                'idab' => $request->input('idab'),
                'link'=> $request->input('link'),
                'nombre'=> $request->input('nombre'),
			);
            
			$post = http_build_query($aData);
			$aResult = $api->client->resJson('GET', 'trackingLink?'.$post);
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        
        echo "<script>window.location= '".$request->input('link').'&nombre='.$request->input('nombre').'&mail='.$request->input('mail')."';</script>";
    }

    
}