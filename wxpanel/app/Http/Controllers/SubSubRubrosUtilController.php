<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AppCustom\Util;

class SubSubRubrosUtilController extends GenericUtilController
{
    public function __construct(SubSubRubrosController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
	}
	public function obtenerSubSubrubros(Request $request){
		$subsubrubros = Util::getSubSubRubros($request->input('id_subrubro'), 'array');
		if($subsubrubros){
			return array(
				'status' => 0,
				'subsubrubros' => $subsubrubros
			);
		}else{
			return array(
				'status' => 0
			);
		}
	}

}
