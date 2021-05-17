<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;

class Dash3Controller extends Dash2Controller
{
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'dash3';
        $this->resourceLabel = 'Dashborad. Por Rubro';
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

            $itemsOp = $this->getOp();
            $itemsCa = $this->getCa();
            $itemsCo = $this->getCo();
            $itemsCan = $this->getCan();
            $itemsAa = $this->getAa();
            $itemsAg = $this->getAg();
			
			$items = $this->prepared21($itemsOp,$itemsCa,$itemsCo,$itemsCan,$itemsAa,$itemsAg);
			$items = array_values($this->prepared31($items));
			
			
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
	
	protected function prepared31($items) {
		
		$items = $items->groupBy('rubro');
		
		$aRet = [];
		foreach ($items as $rubro => $items2) {
			$aRet[$rubro]['nombre'] = $rubro;
			
			$aRet[$rubro]['cnt'] = $items2->sum('cnt');
			$aRet[$rubro]['op_ventaTot'] = $items2->sum('op_ventaTot');
			
			$aRet[$rubro]['ca_cnt'] = $items2->sum('ca_cnt');
			$aRet[$rubro]['ca_ventaTot'] = $items2->sum('ca_ventaTot');
			
			$aRet[$rubro]['co_cnt'] = $items2->sum('co_cnt');
			$aRet[$rubro]['co_ventaTot'] = $items2->sum('co_ventaTot');
			
			$aRet[$rubro]['can_cnt'] = $items2->sum('can_cnt');
			$aRet[$rubro]['can_ventaTot'] = $items2->sum('can_ventaTot');
			
			$aRet[$rubro]['aa_cnt'] = $items2->sum('aa_cnt');
			$aRet[$rubro]['aa_ventaTot'] = $items2->sum('aa_ventaTot');
			
			$aRet[$rubro]['ag_cnt'] = $items2->sum('ag_cnt');
			$aRet[$rubro]['ag_ventaTot'] = $items2->sum('ag_ventaTot');
		}
		
		return $aRet;
	}
	
	
	protected function query() {
		$modelName = $this->modelName;
		
		return
		$modelName::
			select(
				'c.id',
				'c.nombre',
				'd.id as id_rubro',
				'd.nombre as rubro',
				\DB::raw('SUM(b.cantidad) as cnt')
			)
				->join('pedidos_productos as b','b.id_pedido','=','pedidos_pedidos.id_pedido')
				->join('inv_productos as c','c.id','=','b.id_producto')
				->join('inv_rubros as d','d.id','=','c.id_rubro')
				->whereRaw('DATE_FORMAT(pedidos_pedidos.created_at, "%Y") = ?',[\date('Y')])
				->where('pedidos_pedidos.id_usuario','>',0)
				->groupBy('c.id')
		;
		
	}
	
	
	
	

}
