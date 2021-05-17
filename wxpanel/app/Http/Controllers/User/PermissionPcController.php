<?php
namespace App\Http\Controllers\User;

class PermissionPcController extends PermissionController
{
	
	const RESOURCE = 'permissionPc';
	const RESOURCE_LABEL = 'Permisos de Usuarios PC';
	
	
	static $aAllPermissions = [
		'adefinir1' => [
			'label' => 'A definir 1',
			'aPermissions' => [
				'adefinir1.create' => 'Usuarios: Crear Usuarios',
				'adefinir1.update' => 'Usuarios: Modificar datos',
				'adefinir1.view' => 'Usuarios: Ver listado de usuarios y detalle',
				'adefinir1.delete' => 'Usuarios: Borrar usuarios',
				
			]
		],
		'adefinir2' => [
			'label' => 'A definir 2',
			'aPermissions' => [
				'adefinir2.create' => 'Usuarios: Crear Usuarios',
				'adefinir2.update' => 'Usuarios: Modificar datos',
				'adefinir2.view' => 'Usuarios: Ver listado de usuarios y detalle',
				'adefinir2.delete' => 'Usuarios: Borrar usuarios',
				
			]
		],
	];
	
	


	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.pc');
	}
	
    
}
