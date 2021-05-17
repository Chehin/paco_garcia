<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Productos;


class PedidosUtilController extends GenericUtilController
{
    public function __construct(PedidosController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
		
		$this->aCustomViewData['aMetPago'] = Util::getEnum('pedidos_pedidos', 'metodo_pago');
		
		$this->itemNameField = 'titulo';

		
	}
	
	public function selectProducto(Request $request) {
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view')) {
				$buscar = $request->input('q');
				$page_limit = $request->input('page_limit');
				
				$return_arr = array();
				$ret = array();		
				
				$productos_fil = Productos::
				select( \DB::raw("IF(inv_producto_codigo_stock.codigo, inv_producto_codigo_stock.codigo, inv_productos.codigo) as codigo,IF(inv_producto_codigo_stock.codigo, inv_producto_codigo_stock.stock, inv_productos.stock) as stock,inv_productos.id, inv_producto_codigo_stock.id_color, inv_productos.nombre"))
				->leftJoin('inv_producto_codigo_stock','inv_producto_codigo_stock.id_producto','=','inv_productos.id')
				->where('inv_productos.habilitado',1)
				->where(function($query) use ($buscar){
                    $query
                        ->where('inv_productos.nombre','like',"%{$buscar}%")
                        ->orWhere('inv_productos.codigo','like',"%{$buscar}%")
                        ->orWhere('inv_producto_codigo_stock.codigo','like',"%{$buscar}%")
                    ;
                })
				->take($page_limit)->skip(0);
				
				$productos = $productos_fil->get();
				foreach ($productos as $producto){
						
					$row_array['id'] = $producto->id.'_'.($producto->id_color?$producto->id_color:0);
					$row_array['text'] = $producto->nombre." (Codigo: ".$producto->codigo.")";
					$row_array['stock'] = $producto->stock;
						
					array_push($return_arr,$row_array);
				}
				$aResult['data'] = $return_arr;
				
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.itemNotFound');
			}
			return response()->json($aResult);
		}
	
}