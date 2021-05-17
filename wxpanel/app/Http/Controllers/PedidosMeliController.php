<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Meli;
use App\AppCustom\Cart;
use App\Http\Controllers\Fe\FeUtilController;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosMeli;
use App\AppCustom\Models\Comprobante;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\TipoEnvio;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\Provincias;
use App\AppCustom\Models\MercadoLibre;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Note;
use MP;
use Carbon\Carbon;

class PedidosMeliController extends Controller
{
    use ResourceTraitController;
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidosMeli';
        $this->resourceLabel = 'Pedidos. Todos';
        $this->modelName = 'App\AppCustom\Models\PedidosMeli';
        $this->viewPrefix = 'pedidosMeli.';
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
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'pedidos_pedidos_meli.created_at';
                $sortDir = 'desc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }
			
			$pedidosx = $request->input('pedidosx');

            //Search filter
            $search = \trim($request->input('sSearch'));
			
			$search1 = \trim($request->input('sSearch_1'));
			
			$search2 = \trim($request->input('sSearch_2'));
			
			$search3 = \trim($request->input('sSearch_3'));
			
			$search4 = \trim($request->input('sSearch_4'));
			
			$search5 = \trim($request->input('sSearch_5'));
			
			$search6 = \trim($request->input('sSearch_6'));

			$search7 = \trim($request->input('sSearch_7'));//filtro recurrentes

			$search8 = \trim($request->input('sSearch_8'));//filtro pedido web o meli, default web

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        \DB::raw('CONCAT(pedidos_usuarios.apellido, ", ", pedidos_usuarios.nombre) as cliente'),
						'pedidos_pedidos_meli.id_usuario',
						'pedidos_pedidos_meli.metodo_pago',
                        'pedidos_pedidos_meli.estado',
                        'pedidos_pedidos_meli.id_tipo_envio',
                        'pedidos_pedidos_meli.estado_envio',
                        'pedidos_pedidos_meli.estado_paquete',
                        'pedidos_pedidos_meli.collection_id',
                        'pedidos_pedidos_meli.payment_id',
                        'pedidos_pedidos_meli.tracking_number',
                        'pedidos_pedidos_meli.acordar_envio',
						'pedidos_pedidos_meli.id_pedido',						
                        'pedidos_pedidos_meli.precio_venta',
						'pedidos_pedidos_meli.comprado_desde',
						'pedidos_pedidos_meli.created_at',
						'pedidos_pedidos_meli.updated_at'
                    )
					->leftJoin('pedidos_usuarios','pedidos_usuarios.id','=','pedidos_pedidos_meli.id_usuario')
					->where('pedidos_pedidos_meli.id_usuario','>',0)
            ;
			
			if (1 == $pedidosx) {				
				$items
					->where('pedidos_pedidos_meli.estado_envio','!=','delivered')
					->where(function($q){
						$q->where('pedidos_pedidos_meli.estado','acordar')
							->orWhere('pedidos_pedidos_meli.estado','approved')
							->orWhere('pedidos_pedidos_meli.estado','cash_on_delivery')
							->orWhere('pedidos_pedidos_meli.estado','payment_in_branch')							
						;
					})
				;
			}elseif(2 == $pedidosx){				
				$items
					->where('pedidos_pedidos_meli.estado','proceso')
				;
			}elseif(3 == $pedidosx){				
				$items
					->where(function($q){
						$q->where('pedidos_pedidos_meli.estado','acordar')
							->orWhere('pedidos_pedidos_meli.estado','pending')
						;
					})
				;
			} else {				
				$items
					->orderByRaw('(pedidos_pedidos_meli.estado = "approved" or pedidos_pedidos_meli.estado = "in_process" or pedidos_pedidos_meli.estado = "refunded" or pedidos_pedidos_meli.estado = "pending" or pedidos_pedidos_meli.estado = "rejected" and pedidos_pedidos_meli.estado_envio <> "delivered") desc')
					->orderByRaw('pedidos_pedidos_meli.estado = "payment_in_branch" desc')
				;
			}
			
			
			if (1 == $pedidosx || 3 == $pedidosx) {
				$items->orderBy('pedidos_pedidos_meli.updated_at', 'desc');
			}else{
				$items->orderBy('pedidos_pedidos_meli.created_at', 'desc');
			}

            if ($search) {
				$items
					->leftJoin('pedidos_productos as pp','pp.id_pedido','=','pedidos_pedidos_meli.id_pedido')
					->leftJoin('inv_productos as p','p.id','=','pp.id_producto')
				;
				
                $items->where(function($query) use ($search){
                    $query
                        ->where('pedidos_usuarios.apellido','like',"%{$search}%")
                        ->orWhere('p.nombre','like',"%{$search}%")
                    ;
                });
				
				$items
					->groupBy('pedidos_pedidos_meli.id_pedido')
					;
            }
			
			//Filtros fechas
			$fecha1 = $fecha2 = null;
			if ($search1) {
				$fecha1 = Carbon::createFromFormat('d/m/Y', $search1);
			}
			
			if ($search2) {
				$fecha2 = Carbon::createFromFormat('d/m/Y', $search2);
			}
			
			if ($fecha1 && $fecha2) {
				if ($fecha1->gt($fecha2)) {
					$aResult['status'] = 1;
					$aResult['msg'] = "El rango de Fecha es inválido";
					
					return response()->json($aResult);
				}
			}
			
			if ($search1) {				
                $items->where(function($query) use ($fecha1){
					$query
						->where('pedidos_pedidos_meli.updated_at', '>=', $fecha1->format('Y-m-d')  . ' 00:00')
					;
				});
            }
			
			if ($search2) {				
                $items->where(function($query) use ($fecha2){
					$query
						->where('pedidos_pedidos_meli.updated_at', '<=', $fecha2->format('Y-m-d') . ' 23:59')
					;
				});
            }
			
			if ($search3) {				
                $items->where(function($query) use ($search3){
					
					
					if ('sin asignar' === \strtolower($search3)) {
						$query
							->whereNull('pedidos_pedidos_meli.metodo_pago')
							->orWhere('pedidos_pedidos_meli.metodo_pago','like','')
						;
					} else {
						$query
							->where('pedidos_pedidos_meli.metodo_pago','like',$search3)
						;
					}
					
					
				});
            }
			
			if ($search4) {
                $items->where(function($query) use ($search4){
					$query
						->where('pedidos_pedidos_meli.estado','like',$search4)
					;
				});
            }
			
			if ($search5) {				
				$items->where(function($query) use ($search5){
					
					if ('sin_estado' === \strtolower($search5)) {
						$query
						->whereNull('pedidos_pedidos_meli.estado_envio')
						->orWhere('pedidos_pedidos_meli.estado_envio','like','')
						;
					} else {
						$query
						->where('pedidos_pedidos_meli.estado_envio','like',$search5)
						;
					}
				});
            }
            
			if ($search6) {				
				$items->where(function($query) use ($search6){
					$query
						->where('pedidos_pedidos_meli.acordar_envio',$search6)
					;
				});
			}
			
			if ($search7) {				
				if($search7==1){
					$items->where(function($query) use ($search7){
						$query
						->where('pedidos_pedidos_meli.estado','approved')
						;
					});
					
					$items
					->groupBy('pedidos_pedidos_meli.id_usuario')
					->havingRaw('COUNT(pedidos_pedidos_meli.id_usuario) > 1')
					;
				}
			}
			

            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
				$ped_prod = PedidosProductos::select('stock_reserva')->where('id_pedido',$val['id_pedido']);
				$stock_reserva = $ped_prod->first();
				$val['created_at']	= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $val['created_at'])->format('d/m/Y H:i');
				$val['updated_at']	= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $val['updated_at'])->format('d/m/Y H:i');
				$val['stock_reserva'] = ($stock_reserva?$stock_reserva->stock_reserva:0);
				$val['productos'] = $ped_prod->count();
				$val['estado'] = Util::estadoPedido($val['estado']);
				$estado_envio = Util::estadoEnvio($val['estado_envio']);
				$val['estado_envio'] = ($estado_envio?$estado_envio:$val['estado_paquete']).($val['id_tipo_envio']==-1?'</br><strong>Delivery '.env('SITE_NAME').'</strong>':'').($val['id_tipo_envio']==3?'</br><strong>Retiro en sucursal</strong>':'');

				if(!$val['precio_venta']){
					$pedido_get = Cart::get_pedido($val['id_pedido'], false);
					if(isset($pedido_get['id_pedido'])){
						$val['precio_venta'] = $pedido_get['subtotal']['precio_db'];
					}
				}
			});
            
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;
			
			$moneda = Util::getMonedaDefault();
			$moneda = $moneda[0]['id'];
			if($request->input('id_cliente')>0){
				$array_valid = array(
					'fecha_pedido' => 'required'
				);
				$array_valid_msg = array(
					'fecha_pedido.required' => 'El campo Fecha del Pedido es requerido'
				);
			}else{
				$array_valid = array(
					'fecha_pedido' => 'required',
					'nombre' => 'required',
					'apellido' => 'required',
					'mail' => 'required',
					'password' => 'required'
				);
				$array_valid_msg = array(
					'fecha_pedido.required' => 'El campo Fecha del Pedido es requerido',
					'nombre.required' => 'El campo Nombre es requerido',
					'apellido.required' => 'El campo Apellido es requerido',
					'mail.required' => 'El campo E-mail es requerido',
					'password.required' => 'La contraseña es obligatoria'
				);
			}
			
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                $array_valid,
				$array_valid_msg
            );

            if (!$validator->fails()) {
				//creo el usuario si es nuevo.
				if($request->input('id_cliente')>0){
					$id_cliente = $request->input('id_cliente');
				}else{
					 $resourceCliente = new PedidosClientes(
						[
							'nombre'    		=> $request->input('nombre'),
							'apellido'  		=> $request->input('apellido'),
							'mail'      		=> $request->input('mail'),
							'contra' 			=> bcrypt($request->input('password')),
							'fecha_nacimiento'  => ($request->input('fecha_nacimiento')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha_nacimiento')) : null,
						]
					)
					;
					if (!$resourceCliente->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}else{
						$id_cliente = $resourceCliente->id;
					}
				}
				//creo el pedido y agrego los productos
				$resource = new Pedidos;
				$resource->id_usuario = $id_cliente;
				$resource->estado = 'proceso';
				$resource->stock_reserva = 0;
				$resource->id_moneda = $moneda;
				if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
				$id_pedido = $resource->id_pedido;
				
				$costo_total = 0;
				$cantidades_input = $request->input('cantidad');
                $productos_input = $request->input('productos');
				foreach($productos_input as $clave => $prod){
					$producto = explode('_',$prod);
					$id_producto = $producto[0];
					$id_color = $producto[1];
					$producto_data = Productos::find($id_producto);
					if($producto){
						if($id_color>0){
							$codigo = FeUtilController::getStockColor($id_producto);
							$codigo = isset($codigo['codigo']);
						}else{
							$codigo = $producto_data->codigo;
						}
						$precio = FeUtilController::getPrecios($id_producto,$moneda);
						$precio = $precio?$precio->precio_db:0;
						$costo_total = $costo_total+$precio;
					
						$resourceProd = new PedidosProductos;
						$resourceProd->id_pedido = $id_pedido;
						$resourceProd->id_moneda = $moneda;
						$resourceProd->cantidad = $cantidades_input[$clave];
						$resourceProd->stock_reserva = 0;
						$resourceProd->id_color = $id_color;
						$resourceProd->codigo = $codigo;
						$resourceProd->id_producto = $id_producto;
						$resourceProd->precio = $precio;
						$resourceProd->nombre = $producto_data->nombre;
						if (!$resourceProd->save()) {
							$aResult['status'] = 1;
							$aResult['msg'] = \config('appCustom.messages.dbError');
						}
					}
				}
				$resourcePed = Pedidos::find($id_pedido);
				$resourcePed->precio_venta = $costo_total;				
				$resourcePed->costo_envio = 0;
				$resourcePed->total = $costo_total;
				$resourcePed->precio_producto_up = 1;
                if (!$resourcePed->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }  
        
        return response()->json($aResult);
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
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
            
            $modelName = $this->modelName;
        
            $item = $modelName::find($id);

            if ($item) {
                
                //Just enable/disable resource? Habilitado
                if ('yes' === $request->input('justEnable')) {
					
					if($request->input('enable')==1){
						Cart::reservarStock($id, $item->id_sucursal);
					}else{
						Cart::liberarStock($id);
					}
                    return response()->json($aResult);
                }                
                

                //Validation
                $aRules = array(
                    'apellido' => 'required',
                    'nombre' => 'required',
                    'mail' => 'required|email'
                );

                if ($request->input('password_confirmation')) {
                    $aRules['password'] = 'required|confirmed';
                }

                $aNiceNames = array(
                    'apellido.required' => 'El apellido es obligatorio',
                    'nombre.required' => 'El nombre es obligatorio',
                    'mail.email' => 'El email ingresado no es válido',
                    'mail.unique' => 'El email ingresado ya existe',
                    'password.required' => 'La contraseña es obligatoria',
                    'password.confirmed' => 'Las contraseñas no coinciden'
                );
                    
                $validator = \Validator::make($request->all(), $aRules, $aNiceNames);

                if (!$validator->fails()) {
                    $item->fill(
                        [
                            'nombre'    		=> $request->input('nombre'),
	                        'apellido'  		=> $request->input('apellido'),
	                        'mail'      		=> $request->input('mail'),
	                        'fecha_nacimiento'  => ($request->input('fecha_nacimiento')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha_nacimiento')) : null,
                            
                        ]
                    )
                    ;

                    if ($request->get('password_confirmation')) {
                        $item->contra = bcrypt($request->input('password'));
                    }

                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = $validator->errors()->all();
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.delete')) {
            $modelName = $this->modelName;
        
            $item = $modelName::find($id);

            $pedidos_dir = PedidosDirecciones::where('id_usuario','=',$item->id)->get();
            // Borro todas las direcciones cargadas para el usuario
            if ($pedidos_dir) {
                foreach ($pedidos_dir as $pedido_dir) {
                    if (!$pedido_dir->delete()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                }
            }

            if ($item) {
                if (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }
	//metodoPago
	public function metodoPago($id){
		$aResult = Util::getDefaultArrayResult();
        
        $item = 
            Pedidos::select('id_pedido as id','metodo_pago')
                ->where('id_pedido', $id)
                ->first()
            ;
        
        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'aItem' => $item,
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
				'options' => Util::getEnum('pedidos_pedidos_meli', 'metodo_pago')
            );

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "MetodoPago")
			->with('aViewData', $aViewData)
			->render();
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
	}
	public function metodoPagoPut(Request $request, $id){
		$aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
			$modelName = $this->modelName;
            $item = $modelName::find($id);

            if ($item) {
				if($request->input('metodo_pago')){
					$item->metodo_pago = $request->input('metodo_pago');
				}
				if (!$item->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
			}else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
	
	//estadoPago
	public function estadoPago($id){
		$aResult = Util::getDefaultArrayResult();
        
        $item = Pedidos::where('id_pedido', $id)->first();
        
        if ($item) {
			if($item->collection_id){
				$item->id = $item->id_pedido;
				$item->estado = Util::estadoPedido($item->estado);
				$item->detalle_estado = Util::estadoPedidoDetalle($item->detalle_estado);
				$item->metodo_mercado = Util::metodoMercado($item->metodo_mercado);
				$item->fecha_aprobacion = ($item->fecha_aprobacion?\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->fecha_aprobacion)->format('d/m/Y H:i'):'');
				$item->fecha_modificacion =($item->fecha_modificacion? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->fecha_modificacion)->format('d/m/Y H:i'):'');
				$item->tiempo_entrega = $item->tiempo_entrega?($item->tiempo_entrega<24 ? '1 día': (ceil($item->tiempo_entrega)/24).'dias'):'';
				
				if($item->precio_venta){
					$moneda = '$';
					if($item->id_moneda){
						$moneda = Util::getMonedaSimbolo($item->id_moneda);
					}
					$item->costo_envio = $moneda.Util::getPrecioFormat($item->costo_envio);
					$item->precio_venta = $moneda.Util::getPrecioFormat($item->precio_venta);
					$item->total = $moneda.Util::getPrecioFormat($item->total);
				}
			
			}else{
				$item->options = array(
					'acordar' => 'Envios a acordar',
					'proceso' => 'Carrito',
                    'payment_in_branch' => 'Pago en sucursal',
                    'cash_on_delivery' => 'Pago contra reembolso',
					'pending' => 'Pago en proceso',
					'approved' => 'Pago realizado con &eacute;xito!',
					'in_process' => 'El pago está siendo revisado',
					'rejected' => 'El pago fue rechazado',
					'cancelled' => 'El pago fue cancelado',
					'refunded' => 'La compra no se concretó'
				);
			}
			$aViewData = array(
				'mode'  => 'edit',
				'aItem' => $item,
				'resource' => $this->resource,
				'resourceLabel' => $this->resourceLabel
			);
			$aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "EstadoPago")->with('aViewData', $aViewData)->render();
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
	}
	public function estadoPagoPut(Request $request, $id){
		$aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
			$modelName = $this->modelName;
            $item = $modelName::find($id);

            if ($item) {
				if($request->input('estado_pago')){
					$item->estado = $request->input('estado_pago');
				}
				if (!$item->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
			}else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
	
	
	//estadoEnvio
	public function estadoEnvio($id){
		$aResult = Util::getDefaultArrayResult();
        
		$item = Pedidos::where('id_pedido', $id)->first();
		
        if ($item) {
			if($item->collection_id || $item->id_tipo_envio!=0){
				$item->imprimir_etiqueta = '';
				$tipo_envio = TipoEnvio::select('nombre','empresa')->where('id_tipo',$item->id_tipo_envio)->first();
				if(!$tipo_envio){
					$tipo_envio = TipoEnvio::find($item->id_tipo_envio);
				}
                if ($item->estado_envio == 'ready_to_ship') {
                    // Debo imprimir las etiquetas
                    // Verifico si la compra fue echa desde mercado libre o la web
                    if ($item->comprado_desde == '1') {
						if($tipo_envio->empresa == 'MercadoE'){
							$token_mp = MP::get_access_token();
							$item->imprimir_etiqueta = "https://api.mercadolibre.com/shipment_labels?savePdf=Y&shipment_ids=".$item->id_envio_meli."&access_token=".$token_mp;     
						}
						                   
                    } else {
                        $mercado_libre = MercadoLibre::orderBy('id','desc')->first();        

                        if ($mercado_libre) {
                            $access_token = $mercado_libre->access_token;
                            $app_id = config('mercadolibre.app_id');
                            $app_secret = config('mercadolibre.app_secret');
                            $meli = new Meli($app_id, $app_secret, $access_token, $mercado_libre->refresh_token);
                            // Verifico si el token esta vencidos
                            if ($mercado_libre->expires < time()) {
                                // Actualizo el token vencido
                                
                                $token = $meli->refreshAccessToken();

                                // Verifico si se renovo correctamente el token
                                if ($token['httpCode'] == 200) {
                                    if ($token['body']->access_token != '' && $token['body']->refresh_token != '' && $token['body']->expires_in != '') {
                                        // Guardo el nuevo token en DB
                                        
                                        $access_token = $token['body']->access_token;
                                        $mercado_libre = new MercadoLibre();

                                        $mercado_libre->access_token = $token['body']->access_token;
                                        $mercado_libre->refresh_token = $token['body']->refresh_token;
                                        $mercado_libre->expires = time() + $token['body']->expires_in;

                                        $mercado_libre->save();
                                    }
                                }
                            }
                        }
                        $item->imprimir_etiqueta = "https://api.mercadolibre.com/shipment_labels?shipment_ids=".$item->id_envio_meli."&response_type=pdf&access_token=".$access_token;
                    }
                }
				$item->estado_envio_nombre = Util::estadoEnvio($item->estado_envio);
				$item->fecha_aprobacion = ($item->fecha_aprobacion?\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->fecha_aprobacion)->format('d/m/Y H:i'):'');
				$item->fecha_modificacion = ($item->fecha_modificacion?\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->fecha_modificacion)->format('d/m/Y H:i'):'');
				if($item->id_tipo_envio){
					if($tipo_envio){
						if($tipo_envio->empresa == 'MercadoE'){
							$empresaEnvio = 'Mercado Envios';

						}else{
							$empresaEnvio = $tipo_envio->empresa;
						}
						
						$item->tipo_envio = $tipo_envio->nombre." ".$empresaEnvio;
					}
				}
				if($item->id_direccion_envio){
					$ped_dire = PedidosDirecciones::find($item->id_direccion_envio);
					if($ped_dire){
						if($ped_dire->id_provincia){
							$provincia = Provincias::find($ped_dire->id_provincia);
							if($provincia){
								$ped_dire->provincia = $provincia->provincia;
							}
						}
					}
				}
			}
			if($item->id_tipo_envio){
				$item->options = array(
					'' => 'Sin estado',
					'pending' => 'Pendiente',
					'ready_to_ship' => 'Listo para enviar',
					'shipped' => 'Enviado',
					'delivered' => 'Entregado',
					'not_delivered' => 'No entregado',
					'en_sucursal' => 'Retiro en sucursal',
					'cancelled' => 'Cancelado'
				);
			}
			if($item->id_tipo_envio==3){
				if($item->id_sucursal > 0){
					$sucursal = Note::find($item->id_sucursal);
					$item->sucursal = $sucursal->titulo;
					$item->sucursal_fecha = Carbon::createFromFormat('Y-m-d H:i:s',$item->fecha_sucursal)->format('d/m/Y H:i');;
					
				}
			}
            $aViewData = array(
                'mode'  => 'edit',
                'aItem' => $item,
                'direccion' => isset($ped_dire)?$ped_dire:'',
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel
            );

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "EstadoEnvio")
			->with('aViewData', $aViewData)
			->render();
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
	}
	
	public function estadoEnvioPut(Request $request, $id){
		$aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
			$modelName = $this->modelName;
            $item = $modelName::find($id);

            if ($item) {
				$item->estado_envio = $request->input('estado_envio');
				if (!$item->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
			}else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
	
	//productos
	public function productos($id){
		$aResult = Util::getDefaultArrayResult();
        
        $item = Pedidos::select('id_usuario','tracking_number','id_direccion_envio','collection_id','detalle_estado','payment_id','total','precio_venta','costo_envio','id_moneda','estado', 'id_direccion_facturacion', 'tipo_facturacion', 'cuit', 'dni', 'razon_social','telefono','id_tipo_envio')
		->where('id_pedido', $id)
		->first();
        
        if ($item) {

			$usuario = PedidosClientes::find($item->id_usuario);
			if($item->id_direccion_envio){
				$ped_dire = PedidosDirecciones::find($item->id_direccion_envio);
				if($ped_dire){
					if($ped_dire->id_provincia){
						$provincia = Provincias::find($ped_dire->id_provincia);
						if($provincia){
							$ped_dire->provincia = $provincia->provincia;
						}
					}
				}
			}

			//Mis Envios, Si hubo error en el alta vuelve a hacerla
			$tipo_envio = null;
			if(isset($item->id_tipo_envio)){
				$tipo_envio = TipoEnvio::select('nombre','empresa')->where('id_tipo',$item->id_tipo_envio)->first();
				if(!$tipo_envio){
					$tipo_envio = TipoEnvio::find($item->id_tipo_envio);
				}
				if($tipo_envio->empresa == 'Mis envios' && !$item->tracking_number){
					if($item->tracking_number == 0 || $item->tracking_number=='' || $item->tracking_number==null){
						$pedido = Pedidos::select()->where('id_pedido', $id)->first();
						if ($pedido) {
							$envio = app('App\Http\Controllers\WebServices\MisEnviosController')->altaEnvio($pedido->id_pedido);
							if ($envio['codigo']==200) {
								$pedido->tracking_number = $envio['pieza_id'];
								$pedido->save();
							}
						}
					}
				}
			}

			//facturacion
			$fac_dire = PedidosDirecciones::find($item->id_direccion_facturacion);
				if($fac_dire){
					if($fac_dire->id_provincia){
						$fac_prov = Provincias::find($fac_dire->id_provincia);
						if($fac_prov){
							$fac_dire->provincia = $fac_prov->provincia;
						}
					}
				}
			if($item->tipo_facturacion=='Consumidor Final'){
				$facturacion = array(
					'tipo' => 'Consumidor Final',
					'direccion' => $fac_dire?$fac_dire:'',
					'dni' => $item->dni,
					'razon_social' => $item->razon_social
				);
			}else{
				$facturacion = array(
					'tipo' => $item->tipo_facturacion,
					'direccion' => $fac_dire?$fac_dire:'',
					'cuit' => $item->cuit,
					'dni' => $item->dni,
					'razon_social' => $item->razon_social
				);
			}
			$productos = PedidosProductos::where('id_pedido',$id)->get();
			$productos_array = array();
			$i=0;
			foreach($productos as $producto){
				$i++;
				if($producto->precio){
					$precio = Util::getPrecioFormat($producto->precio);
					$preciosiniva = Util::getPrecioFormat($producto->precio_siniva);
					$subtotal_db = $producto->precio*$producto->cantidad;
					$subtotal = Util::getPrecioFormat($subtotal_db);
				}else{
					$precio_get = FeUtilController::getPrecios($producto->id_producto,$producto->id_moneda);
					$precio = $precio_get->precio;
					$preciosiniva = $precio_get->precio_venta_niva;
					$subtotal_db = $precio_get->precio_db*$producto->cantidad;
					$subtotal = Util::getPrecioFormat($subtotal_db);
				}
				$codigo_prod = CodigoStock::where('id_producto', $id)->where('id_color',$producto->id_color)->first();
				$nombre = 'Producto eliminado';
				if($producto->nombre){
					$nombre = $producto->nombre;
				}else{
					$prod = Productos::find($producto->id_producto);
					if($prod){
						$nombre = $prod->nombre;
					}
				}
				$color = 0;
				if($producto->id_color){
					$color = Colores::find($producto->id_color);
					if($color){
						$color = $color->nombre;
					}
				}
				$talle = 0;
				if($producto->id_talle){
					$talle = Talles::find($producto->id_talle);
					if($talle){
						$talle = $talle->nombre;
					}
				}
				if(isset($item->id_tipo_envio)){
					$tipo_envio = TipoEnvio::select('nombre','empresa')->where('id_tipo',$item->id_tipo_envio)->first();
					if(!$tipo_envio){
						$tipo_envio = TipoEnvio::find($item->id_tipo_envio);
					}
					if($tipo_envio->empresa == 'Andreani' && !$producto->impresion_etiqueta){
						$envio = app('App\Http\Controllers\WebServices\AndreaniController')->altaEnvio($id);
					}
				}
				$producto_array = array(
					'i' => $i,
					'nombre' => $nombre,
					'moneda' => Util::getMonedaSimbolo($producto->id_moneda),
					'precio' => $precio,
					'precio_siniva' => $preciosiniva,
					'color' => $color,
					'talle' => $talle,
					'codigo' => $producto->codigo,
					'subtotal' => $subtotal,
					'cantidad' => $producto->cantidad,
                    'impresion_etiqueta' => $producto->impresion_etiqueta,
				);
				array_push($productos_array,$producto_array);
			}
			//Puntos de venta para FE
			$tipoPuntoVenta = Comprobante::orderBy('id','asc')->get();
			
            $aViewData = array(
                'mode'  => 'edit',
                'usuario' => $usuario,
				'direccion' => isset($ped_dire)?$ped_dire:'',
				'facturacion' => isset($facturacion)?$facturacion:'',
                'estado' => $item->estado,
                'productos' => array(
					'productos' => $productos_array,
					'total' => $item->precio_venta?Util::getPrecioFormat($item->precio_venta):0,
					'subtotal' => $item->total?Util::getPrecioFormat($item->total):0,
					'envio' => $item->costo_envio?Util::getPrecioFormat($item->costo_envio):0,
					'moneda' => $item->id_moneda?Util::getMonedaSimbolo($item->id_moneda):0
				),
                'aItem' => $item,
				'resource' => $this->resource,
				'resourceLabel' => $this->resourceLabel,
				'tipoPuntoVenta' => $tipoPuntoVenta,
				'id_pedido' => $id
            );

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "Productos")
			->with('aViewData', $aViewData)
			->render();
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
	}
	
	public function notificaciones($id){

		$aResult = Util::getDefaultArrayResult();
        
        $pedido = Pedidos::where('id_pedido', $id)->first();
        
        if ($pedido) {

			$notificaciones = PedidosNotificaciones::where('id_pedido', $pedido->id_pedido)->orderBy('updated_at', 'desc')->get();
			
            $aViewData = array(
                'mode'  => 'edit',
                'notificaciones' => $notificaciones,
                'aItem' => $pedido,
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel
            );

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "Notificaciones")
			->with('aViewData', $aViewData)
			->render();
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
	}
}
