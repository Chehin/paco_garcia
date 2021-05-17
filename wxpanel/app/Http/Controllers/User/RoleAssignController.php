<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;

class RoleAssignController extends Controller
{
	
	const RESOURCE = 'roleAssign';
	const RESOURCE_LABEL = 'Asignación de Perfiles';
	
	
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


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

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

		$item = \Sentinel::findById($id);

		if ($item) {

			$aViewData = array(
				'mode'  => 'edit',
				'item' => $item,
				'aRoles' => 
					\Sentinel::getRoleRepository()
						->where('type', $this->type)
						->get(),
				'aRolesAssigned' => 
					\Sentinel::findById($id)
						->roles()
						->where('type', $this->type)
						->get(),
				'resourceLabel' => self::RESOURCE_LABEL,
				'resource' => self::RESOURCE,
			);
			
			$viewModule = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

			$aResult['html'] = \View::make($viewModule . '.'. static::RESOURCE."Edit")
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
		
		$resourceFrom = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

		if (\Sentinel::hasAccess($resourceFrom  . '.update')) {

			$item = \Sentinel::findById($id);

			if ($item) {

				$aAllRoles = \Sentinel::getRoleRepository()->where('type', $this->type)->get();

				if (!$aAllRoles->isEmpty()) {

					foreach ($aAllRoles as $role) {
						$role->users()->detach($item);
					}

					$aOpt = $request->input('checkOpt', []);
					
					$msgWarn = '';
					array_walk($aOpt, function($value) use ($item, &$msgWarn){
						$role = \Sentinel::findRoleById($value);
						
						if (!empty($role->permissions)) {
							$role->users()->attach($item);
							
						} else {
							$msgWarn .= "{$role->name},";
						}
					});
					
					if (!empty($msgWarn)) {
						$aResult['status'] = 2;
						$aResult['msg'] = "Los siguientes Perfiles no tienen ningún permiso asignado: " . \rtrim($msgWarn, ',');
					}

				} else {
					$aResult['status'] = 1;
					$aResult['msg'] = 'No hay Perfiles para asignar';
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

	}
}
