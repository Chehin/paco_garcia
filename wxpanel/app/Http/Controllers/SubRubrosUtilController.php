<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Rubros;

class SubRubrosUtilController extends GenericUtilController
{
    public function __construct(SubRubrosController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';

		
		$this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->where('habilitado','=','1')->lists('nombre','id');
	}
	public function obtenerSubrubros(Request $request){
		$subrubros = Util::getSubRubros($request->input('id_rubro'), 'array');
		if($subrubros){
			return array(
				'status' => 0,
				'subrubros' => $subrubros
			);
		}else{
			return array(
				'status' => 0
			);
		}
	}

}
