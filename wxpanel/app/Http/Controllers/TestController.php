<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller
{
    public function test()
    {
    	$ml_app_id     = config('mercadolibre.app_id');
		$ml_app_secret = config('mercadolibre.app_secret');

    	echo "<pre>";
    	echo $ml_app_id;
    	echo "<pre>";
    	echo $ml_app_secret;
    }
}
