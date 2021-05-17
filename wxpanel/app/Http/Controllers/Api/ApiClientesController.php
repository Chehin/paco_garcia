<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\PedidosDirecciones;

class ApiClientesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'pedidosClientes';
        $this->resourceLabel = 'Cliente';
        $this->modelName = 'App\AppCustom\Models\PedidosClientes';
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
                $modelName::select(
                        'pedidos_usuarios.id',
                        'pedidos_usuarios.apellido',
                        'pedidos_usuarios.nombre',
                        'pedidos_usuarios.mail',
                        'pedidos_usuarios.dni',
                        'pedidos_usuarios.cuit',
                        'pedidos_usuarios.tipo_facturacion',
                        'pedidos_usuarios.habilitado'
                    )
                    ->where('pedidos_usuarios.confirm_mail', 1)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
                    ;
                });
            }
            
            $aItems = $items
                ->paginate($pageSize)
            ;

            foreach ($aItems as $item) {
            	$direcciones = PedidosDirecciones::select('pedidos_direcciones.id','pedidos_direcciones.titulo','pedidos_direcciones.telefono','pedidos_direcciones.direccion','pedidos_direcciones.numero','pedidos_direcciones.piso','pedidos_direcciones.departamento','pedidos_direcciones.ciudad','provincias.provincia','provincias.codigo as codigo_provincia','localidad.codigo as codigo_localidad')
            		->join('provincias','provincias.id_provincia','=','pedidos_direcciones.id_provincia')
                    ->leftJoin('localidad','localidad.id','=','pedidos_direcciones.id_localidad')
            		->where('pedidos_direcciones.id_usuario','=',$item->id)
            		->where('pedidos_direcciones.habilitado','=','1')
            		->get()
            		->toArray();
            	$data = array(
            		'id'                => $item->id,
            		'apellido'          => $item->apellido,
            		'nombre'            => $item->nombre,
            		'mail'              => $item->mail,
            		'habilitado'        => $item->habilitado,
                    'dni'               => $item->dni,
                    'cuit'              => $item->cuit,
                    'tipo_facturacion'  => $item->tipo_facturacion,
            		'direcciones'       => $direcciones
            	);
            	array_push($aResult['data'], $data);
            }
            
            $total = $aItems->total(); 
            
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }
}
