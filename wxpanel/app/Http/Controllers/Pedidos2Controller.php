<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pedidos2Controller extends PedidosController
{
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidos2';
        $this->resourceLabel = 'Pedidos. En Carrito';
        $this->modelName = 'App\AppCustom\Models\Pedidos';
        $this->viewPrefix = 'pedidos.pedidos.';
    }
	
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
	{
		$request->request->set('pedidosx', 2);
		return parent::index($request);
	}

   
	
}
