<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppDeliveryController extends Controller
{
	public $modelName = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {		
		$this->modelName = 'App\AppCustom\Models\Product';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$id_tipo_cliente = $request->input('id_tipo_cliente');
		
		$aResult = Util::getDefaultArrayResult();
		
        $modelName = $this->modelName;
		$items = $modelName::
		leftJoin('productos_categorias as a','a.id','=','productos.id_categoria')
		->where('productos.habilitado',1)
		->where('a.habilitado',1);		
		
		$productos = $items->select('productos.id','productos.nombre','productos.sumario','productos.precio'.$id_tipo_cliente. ' as precio','a.nombre as categoria','productos.id_categoria','productos.habilitado','productos.destacado')->orderBy('productos.destacado','desc')->orderBy('productos.nombre')
		->get()->toArray();
		
		$categories = $items->select('a.nombre as categoria','productos.id_categoria')->groupBy('productos.id_categoria')->orderBy('productos.id_categoria')
		->get()->toArray();
		
		$aResult['data'] = $productos;
		$aResult['categorias'] = $categories;
		
		return $aResult;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		//creo el pedido
		$data = json_decode($request->input('data'));
		$configuracion = json_decode($request->input('configuracion'));
		$envio = json_decode($request->input('envio'));
		
		if(isset($envio->geo_latlong)){
			$geo_latlong = app('App\Http\Controllers\App\AppClientesController')->getCoordenadas($envio->domicilio, $envio->ciudad);
		}else{
			$geo_latlong = $envio->geo_latlong;
		}
		
        $aResult = Util::getDefaultArrayResult();
		$resource = new \App\AppCustom\Models\Pedido([
			'forma_pago' => $configuracion->forma_pago,
			'hora_entrega' => $configuracion->horario,
			'id_cliente' => $configuracion->id_cliente,
			'comentarios' => (isset($configuracion->comentario)?$configuracion->comentario:''),
			
			'telefono' => $envio->telefono,
			'domicilio' => $envio->domicilio,
			'piso_of' => ($envio->piso_of?$envio->piso_of:''),
			'ciudad' => $envio->ciudad,
			'geo_latlong' => $geo_latlong,
			'estado' => 0
		]);
		if (!$resource->save()) {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.dbError');
		}else{
			foreach($data as $producto){
				$pedido_producto = new \App\AppCustom\Models\Pedido_Detalle([
					'id_pedido' => $resource->id,
					'id_producto' => $producto->id_producto,
					'precio' => $producto->precio,
					'peso' => $producto->peso
				]);
				if (!$pedido_producto->save()) {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.dbError');
				}
			}
		}		
		return $aResult;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function delivery_data(Request $request)
	{
		$id = $request->input('id_cliente');
		$aResult = Util::getDefaultArrayResult();
		
		//busco el ultimo pedido del cliente
		$envio = \App\AppCustom\Models\Pedido::
		select('telefono','piso_of','geo_latlong','ciudad','domicilio')
		->where('id_cliente',$id)
		->orderBy('id','desc')
		->first();
		
		//formas de pago
		$formas_pago = \App\AppCustom\Models\PedidoFormaPago::
		select('id','forma_pago')
		->where('habilitado',1)
		->get()->toArray();
		
		$aResult['data']['configuracion']['horario'] = 'Lunes a Sabado 9:00 a 13:30 hs. y 18:00 a 21:30 hs. / Domingos: 9:00 a 13:30 hs.';
		$aResult['data']['configuracion']['forma_pago'] = $formas_pago;
		$aResult['data']['envio'] = $envio;
		
		return $aResult;
	}

	public function pedidos_data(Request $request)
	{
		$id = $request->input('id_cliente');
		$aResult = Util::getDefaultArrayResult();
		
		$pedidos = \App\AppCustom\Models\Pedido::
		select('id','created_at','estado')
		->where('estado','!=',9)
		->where('id_cliente',$id)
		->orderBy('created_at','desc')
		->get();
		foreach($pedidos as $data){
			$total = 0;
			switch($data->estado){
				case '0': 
					$estado = 'Pendiente';
				break;
				case '1': 
					$estado = 'En Curso';
				break;
				case '2': 
					$estado = 'Cancelado';
				break;
				case '3': 
					$estado = 'Cerrado';
				break;				
			}			
			//productos
			$productos = \App\AppCustom\Models\Pedido_Detalle::
			select('pedidos_detalle.id_producto as id','pedidos_detalle.precio','pedidos_detalle.peso as cantidad','productos.nombre','productos.id_categoria','productos_categorias.nombre as categoria')
			->leftJoin('productos','productos.id','=','pedidos_detalle.id_producto')
			->leftJoin('productos_categorias','productos_categorias.id','=','productos.id_categoria')
			->where('pedidos_detalle.id_pedido',$data->id)
			->get();
			foreach ($productos as $i) {
				$total += ($i->precio*$i->cantidad);
			}
			$productos = $productos->toArray();
			$pedido = array(
				'id' => $data->id,
				'created_at' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y'),
				'estado' => $estado,
				'productos' => $productos,
				'total' => $total
			);
			array_push($aResult['data'],$pedido);
		}
		
		return $aResult;
	}

}
