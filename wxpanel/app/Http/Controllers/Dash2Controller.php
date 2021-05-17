<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Pedidos;
use App\Http\Controllers\Fe\FeUtilController;

class Dash2Controller extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'dash2';
        $this->resourceLabel = 'Dashborad. Por producto';
		$this->modelName = Pedidos::class;
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.view')) {
            
            $modelName = $this->modelName;

            $itemsOp = $this->getOp();
            $itemsCa = $this->getCa();
            $itemsCo = $this->getCo();
            $itemsCan = $this->getCan();
            $itemsAa = $this->getAa();
            $itemsAg = $this->getAg();
			
			$items = $this->prepared21($itemsOp,$itemsCa,$itemsCo,$itemsCan,$itemsAa,$itemsAg);
			
            $total = count($items);
            
            $aResult['data'] = $items;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }
	
	protected function prepared21($itemsOp,$itemsCa,$itemsCo,$itemsCan,$itemsAa,$itemsAg) {
		
		$items=
			$itemsOp->transform(function($item) use ($itemsCa,$itemsCo,$itemsCan,$itemsAa,$itemsAg){
				
				$this->prepared21Join($item,$itemsCa,'ca');
				$this->prepared21Join($item,$itemsCo,'co');
				$this->prepared21Join($item,$itemsCan,'can');
				$this->prepared21Join($item,$itemsAa,'aa');
				$this->prepared21Join($item,$itemsAg,'ag');
				
				return $item;
			})
		;
			
		return $items;
	}
	
	protected function prepared21Join(&$item,$itemsx,$prefix) {
		
		$isIn =
		$itemsx->first(function($key,$itemx) use ($item){
			return $itemx['id'] == $item->id;
		});

		if ($isIn) {
			$item->{$prefix . '_cnt'} = $isIn->cnt;
			$item->{$prefix . '_venta'} = $isIn->{$prefix . '_venta'};
			$item->{$prefix . '_ventaTot'} = $isIn->{$prefix . '_ventaTot'};
		} else {
			$item->{$prefix . '_cnt'} = 0;
			$item->{$prefix . '_venta'} = 0;
			$item->{$prefix . '_ventaTot'} = 0;
		}
		
	}
	
	protected function query() {
		$modelName = $this->modelName;
		
		return
		$modelName::
			select('c.id','c.nombre', \DB::raw('SUM(b.cantidad) as cnt'))
				->join('pedidos_productos as b','b.id_pedido','=','pedidos_pedidos.id_pedido')
				->join('inv_productos as c','c.id','=','b.id_producto')
				->whereRaw('DATE_FORMAT(pedidos_pedidos.updated_at, "%Y") = ?',[\date('Y')])
				->where('id_usuario','>',0)
				->groupBy('c.id')
		;
		
	}
	
	protected function getCa() {
		
		$query = $this->query();
		$items = $query
			->where('pedidos_pedidos.estado','like','proceso')
			->get()
		;
		
		return $this->addVenta($items, 'ca_venta');
		
	}
	protected function getAa() {
		
		$query = $this->query();
		$items = $query
			->where(function($q){
					$q->where('pedidos_pedidos.estado','acordar')
						->orWhere('pedidos_pedidos.estado','pending')
					;
			})
			->get()
		;
		
		return $this->addVenta($items, 'aa_venta');
		
	}
	protected function getAg() {
		
		$query = $this->query();
		$items = $query
			->where('pedidos_pedidos.estado_envio','!=','delivered')
			->where(function($q){
				$q->where('pedidos_pedidos.estado','acordar')
					->orWhere('pedidos_pedidos.estado','approved')
					->orWhere('pedidos_pedidos.estado','cash_on_delivery')
					->orWhere('pedidos_pedidos.estado','payment_in_branch')
				;
			})
			->get()
		;
		
		return $this->addVenta($items, 'ag_venta');
		
	}
	
	protected function getCan() {
		
		$query = $this->query();
		$items = $query
			->where('pedidos_pedidos.estado_envio','!=','delivered')
			->where(function($q){
				$q->where('pedidos_pedidos.estado','cancelled')
					->orWhere('pedidos_pedidos.estado','rejected')
					->orWhere('pedidos_pedidos.estado','refunded')
				;
			})
			->get()
		;
		
		return $this->addVenta($items, 'can_venta');
		
	}
	
	protected function getCo() {
		
		$query = $this->query();
		$items = $query
			->where('pedidos_pedidos.estado_envio','delivered')
			->get()
		;
		
		return $this->addVenta($items, 'co_venta');
		
	}
	
	protected function getOp() {
		
		$query = $this->query();
		$items = $query->get()
		;
		
		return $this->addVenta($items, 'op_venta');
		
	}
	
	protected function addVenta($items, $fieldVenta) {
		
		$items->transform(function($item) use ($fieldVenta){
			$item->{$fieldVenta} = FeUtilController::getPrecios($item->id, 1)->precio_db;
			$item->{$fieldVenta.'Tot'} = $item->{$fieldVenta} ? ($item->{$fieldVenta} * $item->cnt) : 0;
				
			return $item;
		});
		
		return $items;
	}
	
	

}
