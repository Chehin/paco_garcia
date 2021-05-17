<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Precios extends Controller
{
    
    public function precios(Request $request){
        
       
        $json='{
            "messages": [
                {"text": "El listado de precios es el siguiente:"}
            ],
            "set_attributes" : {
                "precio1" : "220.121",
                "precio2" : "120.000",
                "precio3" : "340.000"        
            }
        }';

        echo $json;
    }

}