<?php

namespace App\Http\Controllers\User;


class RolePcController extends RoleController
{
	
	const RESOURCE = 'rolePc';
	const RESOURCE_LABEL = 'Perfil de Usuario de Plataforma Comercial';


	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.pc');
	}
	
	
	
}
