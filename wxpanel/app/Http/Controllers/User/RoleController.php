<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Sentinel\UserRole;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
	
	const RESOURCE = 'role';
	const RESOURCE_LABEL = 'Perfil';
	
	protected $type;


	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.panel');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
	{
        
        $aResult = Util::getDefaultArrayResult();
        
        if (\Sentinel::hasAccess(static::RESOURCE  . '.view')) {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'seccion';
                $sortDir = 'asc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $aOItems = 
                UserRole::
                    select(
						'id',
						'name',
						'updated_at'
                        
                    )
					->where('type', $this->type)
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $aOItems
                    ->where('name','like',"%{$search}%")
                ;
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
            'resourceLabel' => static::RESOURCE_LABEL,
			'resource' => static::RESOURCE,
        );
        
		$viewModule = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

        $aResult['html'] = \View::make($viewModule . '.' . static::RESOURCE."Edit")
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


		if (\Sentinel::hasAccess(static::RESOURCE  . '.create')) {
			
			$name = strtoupper($request->input('name'));

			//Validation
			$validator = 
				\Validator::make(
					['name' => $name], 
					[
						'name' => 'required|unique:roles,name',
					], 
					[
						'name.required' => 'El nombre es obligatorio',
						'name.unique' => 'El nombre ya existe',
					]
				)
			;

			if (!$validator->fails()) {

				try {

					\Sentinel::getRoleRepository()->createModel()->create([
						'name' => $name,
						'slug' => \str_slug($name),
						'type' => $this->type
					]);

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
        
        $item = \Sentinel::findRoleById($id);
        
        if ($item) {
			
			$users = $item->users()->with('roles')->get();

            $aViewData = array(
                'mode'  => 'edit',
                'item' => $item,
				'users' => $users,
				'resourceLabel' => static::RESOURCE_LABEL,
				'resource' => static::RESOURCE,
            );
			
			$viewModule = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

            $aResult['html'] = \View::make($viewModule . '.'. static::RESOURCE . "Edit")
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

		if (\Sentinel::hasAccess(static::RESOURCE  . '.update')) {

			$item = \Sentinel::findRoleById($id);
			
			$name = \strtoupper($request->input('name'));

			if ($item && ($item->type == $this->type)) {
				//Validation
				$validator = 
				\Validator::make(
					['name' => $name], 
					[
						'name' => 'required|unique:roles,name,' . $id,
					], 
					[
						'name.required' => 'El nombre es obligatorio',
						'name.unique' => 'El nombre ya existe',
					]
				)
				;

				if (!$validator->fails()) {

					$item->name = $name;

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
		
		$role = \Sentinel::findRoleById($id);
		
		if ($role && ($role->type == $this->type)) {
			
			if (\Sentinel::hasAccess(static::RESOURCE  . '.delete')) { 

				if (!($role->users()->with('roles')->get()->count() > 0)) {
					$role->delete();
				} else {
					$aResult['status'] = 1;
					$aResult['msg'] = 'El Perfil tiene usuarios asignados';
				}

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
	
	
	
}
