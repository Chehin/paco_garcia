<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\UserRole;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class PermissionUtilController extends Controller
{
	
	public static function permissionLabel($permission) {
		
		list(,$permissionOriginal) = explode('.', $permission);
		
		switch($permissionOriginal) {
			case 'view':
				return 'Ver';
			case 'create':
				return 'Crear';
			case 'update':
				return 'Modificar';
			case 'delete':
				return 'Eliminar';
		}
		
	}
	
}
