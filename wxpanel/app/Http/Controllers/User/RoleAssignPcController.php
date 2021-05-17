<?php

namespace App\Http\Controllers\User;


class RoleAssignPcController extends RoleAssignController
{
	
	const RESOURCE = 'roleAssignPc';
	const RESOURCE_LABEL = 'Asignación de Perfiles de PC';


	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.pc');
	}
	
	
}
