<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\PedidosClientes as MktPersonas;
use App\AppCustom\Models\MktListas;
use App\AppCustom\Models\MktListasPersonas;

class MarketingListasPersonasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'marketingListas';
        $this->resourceLabel = 'Listas';
        $this->modelName = 'App\AppCustom\Models\MktListas';
        $this->viewPrefix = 'marketing.';
    }
    
    public function index(Request $request){
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.view')) {            
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'id';
                $sortDir = 'desc';
            } else {
                            
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));
            

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
				MktPersonas::select(
					'pedidos_usuarios.id',
					'pedidos_usuarios.mail as email',
					\DB::raw('CONCAT(nombre, " ", apellido) as nombre'),
					'mkt_personas_listas.id_lista',
                    'mkt_personas_listas.id_persona',
                    'pedidos_usuarios.created_at'
				)
				->join('mkt_personas_listas','mkt_personas_listas.id_persona','=','pedidos_usuarios.id')
				->where('mkt_personas_listas.id_lista', $request->input('id'))
				->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
                    ;
                });
            }
            
            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();                            
            
            $total = $aItems['total'];
            $aItems = $aItems['data'];           
            
            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }
    
    public function store(Request $request)
    {		
		$aResult = Util::getDefaultArrayResult();			
		
        $aPersonasIds = json_decode($request->input('ids'));
        $id_lista = $request->input('id');
        
		if ($this->user->hasAccess($this->resource . '.create')) {
			
			$item = MktListas::find($id_lista);
			
			if ($item) {
				
                foreach ($aPersonasIds as $id_persona) {
                    $alredyRelated = MktListasPersonas::where('id_lista', $id_lista)
                                                        ->where('id_persona', $id_persona);

                    if (0 == $alredyRelated->count()) {

                        $listaPersonasRelated = 
                            new MktListasPersonas(
                                [
                                    'id_lista' => $id_lista,
                                    'id_persona' => $id_persona,
                                ]
                            );
                        if (!$listaPersonasRelated->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                    }
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}		
		return response()->json($aResult);		
    }
    
    public function quitarPersonasRelated(Request $request)
    {		
		$aResult = Util::getDefaultArrayResult();			
		
        $aPersonasIds = json_decode($request->input('ids'));
        $id_lista = $request->input('id');
        
		if ($this->user->hasAccess($this->resource . '.create')) {
			
			$item = MktListas::find($id_lista);
			
			if ($item) {
				
                foreach ($aPersonasIds as $id_persona) {
                    $alredyRelated = MktListasPersonas::where('id_lista', $id_lista)
                                                        ->where('id_persona', $id_persona);

                    if ($alredyRelated->count() > 0) {					
                        if (!$alredyRelated->delete()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = 'El contacto no esta relacionado';
                    }
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}		
		return response()->json($aResult);		
    }
    
    public function update(Request $request, $id)
    {		
		$aResult = Util::getDefaultArrayResult();			
		
		list($listaId, $personaId) = explode('_', $id);
		
        
		if ($this->user->hasAccess($this->resource . '.update')) {
			
			$item = MktListas::find($listaId);
			
			if ($item) {
				
				$alredyRelated = 
					MktListasPersonas::where('id_lista', $listaId)
						->where('id_persona', $personaId)
				;
				
				if (0 == $alredyRelated->count()) {
				
					$listaPersonasRelated = 
						new MktListasPersonas(
							[
								'id_lista' => $listaId,
								'id_persona' => $personaId,
							]
						)
						;
					if (!$listaPersonasRelated->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Ya se ha relacionado este contacto';
				}
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}		
		return response()->json($aResult);		
    }
    
    public function destroy(Request $request, $id)
    {
		$aResult = Util::getDefaultArrayResult();		
		
		list($listaId, $personasId) = explode('_', $id);
		
        
		if ($this->user->hasAccess($this->resource . '.update')) {
			
			$item = MktListas::find($listaId);
			
			if ($item) {
				
				$alredyRelated = 
					MktListasPersonas::where('id_lista', $listaId)
						->where('id_persona', $personasId)
						
				;
				
				if ($alredyRelated->count() > 0) {
					
					if (!$alredyRelated->delete()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
                    $aResult['msg'] = 'El contacto no esta relacionado';
				}
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		}		
		return response()->json($aResult);
    }
}
