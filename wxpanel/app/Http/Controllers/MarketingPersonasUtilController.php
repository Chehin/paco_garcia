<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AppCustom\Util;

class MarketingPersonasUtilController extends GenericUtilController
{
    public function __construct(MarketingPersonasController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
	}
    
    public function obtenerProvincias(Request $request){
		$provincias = Util::getProvincias($request->input('id_pais'), 'array');
		if($provincias){
			return array(
				'status' => 0,
				'provincias' => $provincias
			);
		}else{
			return array(
				'status' => 0
			);
		}
	}
}
