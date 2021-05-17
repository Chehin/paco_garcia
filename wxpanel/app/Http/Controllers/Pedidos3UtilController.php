<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;


class Pedidos3UtilController extends PedidosUtilController
{
    public function __construct(Pedidos3Controller $res) {
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;

		$this->aCustomViewData['aMetPago'] = Util::getEnum('pedidos_pedidos', 'metodo_pago');
		
		$this->itemNameField = 'titulo';

		
	}
	
	
	
}