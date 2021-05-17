<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AppCustom\Util;
use App\AppCustom\Cart;
use App\Http\Controllers\Fe\FeUtilController;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\PedidosDirecciones;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\TipoEnvio;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\Provincias;

class ApiPedidosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidos';
        $this->resourceLabel = 'Pedidos';
        $this->modelName = 'App\AppCustom\Models\Pedidos';
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
            
            $pageSize = $request->input('iDisplayLength', 1000);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'pedidos_pedidos.updated_at';
                $sortDir = 'desc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            //Other filter
			$search1 = \trim($request->input('sSearch_1'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                		'pedidos_pedidos.id_pedido',
                		'pedidos_usuarios.id as id_usuario',
                		'pedidos_usuarios.nombre as nombre_cliente',
                		'pedidos_usuarios.apellido as apellido_cliente',
                        'pedidos_pedidos.cuit',
                        'pedidos_pedidos.dni',
                        'pedidos_pedidos.tipo_facturacion',
                        'pedidos_pedidos.metodo_pago',
                        'pedidos_pedidos.estado',
                        'pedidos_pedidos.collection_id',
                        'pedidos_pedidos.payment_id',
                        'pedidos_pedidos.total',
                        'pedidos_pedidos.facturado',
                        'pedidos_pedidos.id_direccion_envio',
                        'pedidos_pedidos.updated_at as created_at'
                    )
					->leftJoin('pedidos_usuarios','pedidos_usuarios.id','=','pedidos_pedidos.id_usuario')
					->where('pedidos_pedidos.id_usuario','>',0)
					->where('estado','=', 'approved')
                    ->orWhere('metodo_pago','=','Contrareembolso')
                    ->orWhere('metodo_pago','=','Pago en sucursal')
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('pedidos_usuarios.apellido','like',"%{$search}%")
                    ;
                });
            }

            if ($search1 != null) {
            	$items->where(function($query) use ($search1){
                    $query
                        ->where('pedidos_pedidos.facturado','=',$search1)
                    ;
                });
            }
            
            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
				$ped_prod = PedidosProductos::select('cantidad','id_pedido','id_producto','id_moneda','codigo')->where('id_pedido',$val['id_pedido'])->get()->toArray();
                if ($ped_prod) {
                    array_walk($ped_prod, function(&$prod,$key){
                        $precio_get = FeUtilController::getPrecios($prod['id_producto'],$prod['id_moneda']);
                        $producto_get = FeUtilController::getProducto($prod['id_producto']);
                        if ($producto_get && $precio_get) {
                            $prod['nombre'] = $producto_get->nombre;
                            $prod['id_info_manager'] = $producto_get->id_info_manager;
                            $prod['precio'] = $precio_get->precio_db;
                        }
                    });
                    $val['productos'] = $ped_prod;
                }
                $dir_envio = PedidosDirecciones::select('pedidos_direcciones.id','pedidos_direcciones.titulo','pedidos_direcciones.telefono','pedidos_direcciones.direccion','pedidos_direcciones.numero','pedidos_direcciones.piso','pedidos_direcciones.departamento','pedidos_direcciones.ciudad','provincias.provincia','provincias.codigo as codigo_provincia','localidad.codigo as codigo_localidad')
                                                ->join('provincias','provincias.id_provincia','=','pedidos_direcciones.id_provincia')
                                                ->leftJoin('localidad','localidad.id','=','pedidos_direcciones.id_localidad')
                                                ->where('pedidos_direcciones.id_usuario','=',$val['id_usuario'])
                                                ->where('pedidos_direcciones.habilitado','=','1')
                                                ->where('pedidos_direcciones.id','=',$val['id_direccion_envio'])->first();
                $val['observaciones'] = $dir_envio;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;

            $item = $modelName::where('id_pedido',$id)
            					->where('facturado','0')
            					->first();

            if ($item) {
            	//Validation
                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'facturado' => 'boolean',
                    ], 
                    [
                        'facturado.boolean' => 'El campo facturado debe tener un valor 1 o 0',
                    ]
                );
                if (!$validator->fails()) {
                    $item->facturado = $request->input('facturado');
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
                $aResult['msg'] = 'El elemento no se ha encontrado (quizás fue borrado o ya está facturado)';
            }
        } else {
        	$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }
}
