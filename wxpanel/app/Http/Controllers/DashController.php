<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Pedidos;

class DashController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'dash';
        $this->resourceLabel = 'Dashboard. Por Mes';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
		\ini_set('max_execution_time', 180);
		
		$aResult = Util::getDefaultArrayResult();
        
        if (!$this->user->hasAccess($this->resource . '.view')) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
		switch ($request->input('d1')) {
			case 1:
				$aResult['data'] = $this->d11();
				break;
			case 2:
				$aResult['data']['data'] = $this->d12();
				$aResult['data']['totales'] = $this->totales($aResult['data']['data']);
				break;
			default:
				break;
		}
		
		return response()->json($aResult);
        
    }
	
	protected function totales($aData) {
		return [
			'op' => array_sum(array_column($aData, 'op')),
			'ca' => array_sum(array_column($aData, 'ca')),
			'co' => array_sum(array_column($aData, 'co')),
			'can' => array_sum(array_column($aData, 'can')),
			'aa' => array_sum(array_column($aData, 'aa')),
			'ag' => array_sum(array_column($aData, 'ag')),
		];
	}
	
	protected function d12() {
		
		$items = 
			$this->query2()
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['op'] = $this->prepared12($items);
		
		$items = 
			$this->query2()
				->where('estado','proceso')
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['ca'] = $this->prepared12($items);
		
		$items = 
			$this->query2()
				->where('estado_envio','delivered')
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['co'] = $this->prepared12($items);
		
		$items = 
			$this->query2()
				->where('estado_envio','!=','delivered')
				->where(function($q){
					$q->where('estado','cancelled')
						->orWhere('estado','rejected')
						->orWhere('estado','refunded')
					;
				})
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['can'] = $this->prepared12($items);
		
		$items = 
			$this->query2()
				->where(function($q){
					$q->where('estado','acordar')
						->orWhere('estado','pending')
					;
				})
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['aa'] = $this->prepared12($items);
		
		$items = 
			$this->query2()
				->where('estado_envio','!=','delivered')
				->where(function($q){
					$q->where('estado','acordar')
						->orWhere('estado','approved')
						->orWhere('estado','cash_on_delivery')
						->orWhere('estado','payment_in_branch')
					;
				})
				->get()
				->groupBy(function ($item, $key) {
					return (int) $item['mes'];
				})
		;
				
		$aData['ag'] = $this->prepared12($items);
		
		return $this->prepared12All($aData);
	}
	
	protected function prepared12All($aData) {
		
		$aRet = [];
		for($i=1;$i<=12;$i++){
			
			foreach ($aData as $k => $aItem) {
				if (isset($aItem[$i])) {
					$aRet[$i][$k] = $aItem[$i]['venta'];
				} else {
					$aRet[$i][$k] = 0;
				}
			}
			
			if ($i == date('m')) {
				break;
			}
			
		}
		
		return $aRet;
		
	}
	
	protected function prepared12($items) {
		
		foreach ($items as &$item) {
			foreach ($item as &$item2) {
				$item2->venta = \App\AppCustom\Cart::get_pedido($item2->id_pedido, false)['subtotal']['precio_db'];
			}
			
		}
		
		$a=[];
		foreach ($items as $k => $item) {
			$a[$k]['venta'] = $item->sum('venta');
		}
		
		return $a;
	}
	
	protected function d11() {
		
		$items = [];
		
		$items['op'] = 
			$this->query()
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
				
		;
				
		$items['ca'] =  
			$this->query()
				->where('estado','proceso')
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
		;
				
		$items['co'] =  
			$this->query()
				->where('estado_envio','delivered')
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
		;
				
				
		$items['can'] =  
			$this->query()
				->where('estado_envio','!=','delivered')
				->where(function($q){
					$q->where('estado','cancelled')
						->orWhere('estado','rejected')
						->orWhere('estado','refunded')
					;
				})
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
		;
				
				
		$items['aa'] =  
			$this->query()
				->where(function($q){
					$q->where('estado','acordar')
						->orWhere('estado','pending')
					;
				})
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
		;
				
		$items['ag'] =  
			$this->query()
				->where('estado_envio','!=','delivered')
				->where(function($q){
					$q->where('estado','acordar')
						->orWhere('estado','approved')
						->orWhere('estado','cash_on_delivery')
						->orWhere('estado','payment_in_branch')
					;
				})
				->get()
				->keyBy(function ($item) {
					return (int) $item['mes'];
				})
				->transform(function($item){
					$item->mes = Util::$aMonths[(int)$item->mes];
					return $item;
				})
		;
				
		$aRet = [];
		foreach ($items as $k => &$item) {
			$aItem = $item->toArray();
			for($m=1;$m<=12;$m++){
				if (!isset($aItem[$m])) {
					$aItem[$m]['mes'] = Util::$aMonths[$m];
					$aItem[$m]['cnt'] = 0;
				}
			}
			ksort($aItem);
			$aRet[$k] = $aItem;
		}
				

		
		return $aRet;
		
		
	}
	
	protected function query() {
		return
		Pedidos::
			select(\DB::raw('DATE_FORMAT(updated_at, "%m") as mes, count(*) as cnt'))
				->whereRaw('DATE_FORMAT(updated_at, "%Y") = ?',[\date('Y')])
				->where('id_usuario','>',0)
				->groupBy(\DB::raw('DATE_FORMAT(updated_at, "%m")'))
				->orderBy('mes')
		;
	}

	protected function query2() {
		return
		Pedidos::
			select(\DB::raw('DATE_FORMAT(updated_at, "%m") as mes, id_pedido'))
				->whereRaw('DATE_FORMAT(updated_at, "%Y") = ?',[\date('Y')])
				->where('id_usuario','>',0)
				->groupBy(\DB::raw('DATE_FORMAT(updated_at, "%m")'))
				->groupBy('id_pedido')
				->orderBy('mes')
		;
	}

}
