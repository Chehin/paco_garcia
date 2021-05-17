<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\AppCustom\Util;
	use App\AppCustom\Models\PedidosClientes;
	
	
	class PedidosClientesUtilController extends GenericUtilController
	{
		public function __construct(PedidosClientesController $res) {
			
			parent::__construct();
			
			$this->resource = $res->resource;
			$this->resourceLabel = $res->resourceLabel;
			$this->user = $res->user;
			$this->modelName = $res->modelName;
			$this->viewPrefix = $res->viewPrefix;
			//$this->itemNameField = 'titulo';
			
			$this->aExtraParams['imageCropW'] = 102;
			$this->aExtraParams['imageCropH'] = 102;
		}
		public function showMainViewDireccionesRelated($id) {
			
			$aResult = Util::getDefaultArrayResult();
			
			if ($item = \App\AppCustom\Models\PedidosClientes::find($id)) {
				if ($this->user->hasAccess($this->resource . '.view')) {
					
					$aViewData['item'] = $item;
					$aViewData['aProvincias'] = \App\AppCustom\Models\Provincias::all()->lists('provincia','id');
					$aViewData['resource'] = $this->resource;
					$aViewData['resourceLabel'] = $this->resourceLabel;
					$aViewData['aCustomViewData'] = $this->aCustomViewData;
					
					$aResult['html'] = 
                    \View::make('pedidos.pedidosClientes.pedidosClientesDireccionesRelatedMain')
					->with('aViewData', $aViewData)
					->render()
                    ;
					} else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.unauthorized');
				}
				} else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.itemNotFound');
			}
			
			
			return response()->json($aResult);
		}
		public function selectCliente(Request $request) {
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view')) {
				$buscar = $request->input('q');
				$page_limit = $request->input('page_limit');
				
				$return_arr = array();
				$ret = array();		
				
				$personas_fil = PedidosClientes::
				select('id','apellido','nombre','mail')
				->where('habilitado',1)
				->where(function($query) use ($buscar){
                    $query
                        ->where('apellido','like',"%{$buscar}%")
                        ->orWhere('mail','like',"%{$buscar}%")
                    ;
                })
				->take($page_limit)->skip(0);
				
				if($personas_fil->count() == 0){
					$row_array['id'] = -1;
					$row_array['text'] = $buscar. " - (Nuevo)";
					array_push($return_arr,$row_array);
				}else{
					$personas = $personas_fil->get();
					foreach ($personas as $persona){
						
						$row_array['id'] = $persona->id;
						$row_array['text'] = $persona->apellido." ".$persona->nombre." (".$persona->mail.")";
						$row_array['apellido'] = $persona->apellido;
						$row_array['nombre'] = $persona->nombre;
						$row_array['email'] = $persona->mail;
						
						array_push($return_arr,$row_array);
					}
				}
				$aResult['data'] = $return_arr;
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.itemNotFound');
			}
			return response()->json($aResult);
		}
	}