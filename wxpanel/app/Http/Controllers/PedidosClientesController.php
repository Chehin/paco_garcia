<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\PedidosDirecciones;

class PedidosClientesController extends Controller
{
    use ResourceTraitController;
    
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
        $this->viewPrefix = 'pedidos.';
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
                $sortCol = 'created_at';
                $sortDir = 'desc';
            } else {
                            
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));
            //mails confirmados 
            $search0 = \trim($request->input('sSearch_0'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        'id',
                        \DB::raw('CONCAT(apellido, ", ", nombre) as nombre'),
                        'mail',
                        'telefono',
                        'fecha_nacimiento',
                        'created_at',//fecha de registracion
                        'destacado',
                        'confirm_mail',
                        'habilitado'
                    )
                    ->orderBy('created_at','desc')
            ;

            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('nombre','like',"%{$search}%")
                        ->orWhere('mail','like',"%{$search}%")
                    ;
                });
            }

            if ($search0!='' && $search0!='NULL') {
                $items->where(function($query) use ($search0){
                    $query
                        ->where('confirm_mail',$search0)
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
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'nombre' => 'required',
                    'apellido' => 'required',
                    'mail' => 'required|unique:pedidos_usuarios',
                    'password' => 'required|confirmed'
                ], 
                [
                    'nombre.required' => 'El campo Nombre es requerido',
                    'apellido.required' => 'El campo Apellido es requerido',
                    'mail.required' => 'El campo E-mail es requerido',
					'mail.unique' => 'El email ingresado ya existe',
                    'password.required' => 'La contraseña es obligatoria',
                	'password.confirmed' => 'Las contraseñas no coinciden'
                ]
            );

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'    		=> $request->input('nombre'),
                        'apellido'  		=> $request->input('apellido'),
                        'mail'      		=> $request->input('mail'),
                        'telefono'    		=> $request->input('telefono'),
                        'opinion'      		=> $request->input('opinion'),
                        'contra' 			=> bcrypt($request->input('password')),
                        'fecha_nacimiento'  => ($request->input('fecha_nacimiento')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha_nacimiento')) : null,
                    ]
                )
                ;

                if (!$resource->save()) {
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
                    $item->habilitado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }      
                //Just enable/disable resource? Destacado
                if ('yes' === $request->input('justEnable1')) {
                    $item->destacado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }      
                //Just enable/disable resource? Confirm mail
                if ('yes' === $request->input('justEnable2')) {
                    $item->confirm_mail = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }                
                

                //Validation
                $aRules = array(
                    'apellido' => 'required',
                    'nombre' => 'required',
                    'mail' => 'required|email|unique:pedidos_usuarios,mail,'.$id
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
                            'telefono'    		=> $request->input('telefono'),
							'opinion'      		=> $request->input('opinion'),
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

}
