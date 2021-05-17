<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Sentinel\User;
use Illuminate\Pagination\Paginator;
use Sentinel;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	
	const RESOURCE = 'user';
	const RESOURCE_LABEL = 'Usuario';
	
	
	static $aAllPermissions = [];
	
	
	public function __construct(Request $request) {
		parent::__construct($request);
		
		static::$aAllPermissions = PermissionController::$aAllPermissions;
	}
	
	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
        
		$aResult = Util::getDefaultArrayResult();
		/* TODO:
		* se debe agregar en cada metodo de cada controlador 
		* una validacion para solicitudes por API (sin sesión)
		*/



		if (Sentinel::hasAccess('user.view')) {

			$pageSize = $request->input('iDisplayLength', 10);
			$offset = $request->input('iDisplayStart');
			$currentPage = ($offset / $pageSize) + 1;

			if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
				$sortCol = 'nombre';
				$sortDir = 'asc';
			} else {
				$sortDir = $request->input('sSortDir_0');
			}

			//Search filter
			$search = \trim($request->input('sSearch'));

			//Other filter
			$search0 = \trim($request->input('sSearch_0'));


			Paginator::currentPageResolver(function() use ($currentPage) {
				return $currentPage;
			});
			

			$aOItems = User::ByCompany($this->id_company)
				->select(
					'id as id_usuario', 
					\DB::raw('CONCAT(last_name, " ", first_name) as nombre'), 
					'phone as telefono',
					'email as mail', 
					'enabled as habilitado',
					'last_login as f_ingreso',
					'deleted_at'
				)
				->where('oculto',0)
				->orderBy($sortCol, $sortDir)
				;

			if ($search) {
				$aOItems->where(function($query) use ($search){
					$query
						->where('last_login','like',"%{$search}%")
						->orWhere('first_name','like',"%{$search}%")
						->orWhere('last_name','like',"%{$search}%")
						->orWhere('email','like',"%{$search}%")
					;
				});
			}

			if ('deleted' === $search0) {
				$aOItems->onlyTrashed();
			}

			$aOItems = $aOItems
				->paginate($pageSize)
			;

			$aItems = $aOItems->toArray();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aResult = Util::getDefaultArrayResult();

        $aViewData = array(
            'mode' => 'add',
            'aPermissions' => static::$aAllPermissions,
        );
        
        

        $aResult['html'] = \View::make('user.userEditMain')
            ->with('aViewData', $aViewData)
            ->render()
        ;

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
        
        
        if (Sentinel::hasAccess('user.create')) {
        
            //Validation
            $aRules = array(
                'apellido' => 'required',
                'nombre' => 'required',
                'mail' => 'required|email|unique:users,email',
                'password' => 'required|confirmed'
            );

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
				
				$aCredentials = [
					'id_company' => $this->id_company,
					'last_name' => $request->input('apellido'),
					'first_name' => $request->input('nombre'),
					'phone' => $request->input('telefono'),
					'email'    => $request->input('mail'),
					'password' => $request->input('password'),
					'enabled' => $request->input('habilitado'),
				];
				
				try {
					
					$item = Sentinel::registerAndActivate($aCredentials);
					
					$aPerms = $request->input('aPerms', []);
				
					$aPermsAux = [];
					array_walk($aPerms, function($value, $key) use (&$aPermsAux){
						$aPermsAux[$value] = true;
					});

					$item->permissions = $aPermsAux;

					$item->save();
					
					
				} catch (\Exception $e) {
					$aResult['status'] = 1;
                    $aResult['msg'] = $e->getMessage();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        $item = User::findByCompany($id, $this->id_company);
        
        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'aItem' => $item->toArray(),
                'aPermissions' => static::$aAllPermissions,
            );

            $aResult['html'] = \View::make('user.userEditMain')
                ->with('aViewData', $aViewData)
                ->render()
            ;
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
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
        
        if (Sentinel::hasAccess('user.update')) {
			
			$user = User::withTrashed()
					->where('id', $id)
					->where('id_company', $this->id_company)
					->first()
				;

            if ($user) {
				
				//Just enable/disable resource?
                if ('yes' === $request->input('justEnable')) {
                    $aCredentials = ['enabled' => $request->input('enable')];
                    if (! Sentinel::update($user, $aCredentials)) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
						
					}

                    return response()->json($aResult);
                }
				
				if ('yes' === $request->input('undelete')) {
					
					if (!$user->trashed()) {
						$aResult['status'] = 1;
                        $aResult['msg'] = 'El usuario no está borrado';
					} else {
						$user->restore();
					}
					
					return response()->json($aResult);
				}
				
				
                //Validation
                $aRules = array(
                    'apellido' => 'required',
                    'nombre' => 'required',
                    'mail' => 'required|email'
                );

                if (trim($request->input('mail')) !== $user->email) {
                    $aRules['mail'] .= '|unique:users,email';
                }

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
					
					$aPerms = $request->input('aPerms', []);
				
					$aPermsAux = [];
					array_walk($aPerms, function($value, $key) use (&$aPermsAux){
						$aPermsAux[$value] = true;
					});
					
					
                    $aCredentials = array(
                        'last_name' => $request->input('apellido'),
                        'first_name' => $request->input('nombre'),
                        'phone' => $request->input('telefono'),
                        'email' => $request->input('mail'),
                        'enabled' => $request->input('habilitado'),
						'permissions' => $aPermsAux,
                        )
                    ;

                    if ($request->get('password_confirmation')) {
                        $aCredentials['password'] = $request->input('password');
                    }

                    if (! \Sentinel::update($user, $aCredentials)) {
                    
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
	public function destroy(Request $request, $id)
	{
		$aResult = Util::getDefaultArrayResult();

		if (Sentinel::hasAccess('user.delete')) {
			
			$item = User::where('id', $id)
					->where('id_company', $this->id_company)
					->first()
			;

			if ($item) {
				if ($item->id !== Sentinel::getUser()->id) {
					if (!$item->delete()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}
				} else {
					$aResult['status'] = 1;
					$aResult['msg'] = 'El usuario activo en la sesión no puede borrarse';
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
