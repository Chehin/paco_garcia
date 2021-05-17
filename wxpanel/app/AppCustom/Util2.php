<?php
/**
 * Description of Util2
 *
 * @author martinm
 */

namespace App\AppCustom;

use Authorizer;


class Util2 {
    
	static function userFeHasAccess($permission) {
		
		$userFeId = Authorizer::getResourceOwnerId();
		
		$item = Models\UserPcAgencia::where('userpc_id', $userFeId)
			->select('b.permissions')
			->join('agencias as a', 'userspc_agencias.agencia_id','=','a.id')
			->join('planes as b', 'a.id_plan','=','b.id')
			->first()
		;
		
		if ($item) {
			if ($item->permissions) {
				
				try {
				
					$permissions = \json_decode($item->permissions);
					
					\Log::info(print_r($permissions, true));
					
					if ($permissions->{$permission}) {
						return true;
					}
					
					
				}catch(\Exception $e){
					return false;
				}
				
			}
			
		}
		
		return false;
		
		//\Log::info(print_r(\json_decode($item->permissions), true));
		//print_r($item->permissions);
		
		//dd($item->permissions);
		
		
		
//		$agenciaUser = Models\UserPcAgencia::where('userpc_id', $userFeId)->first();
//		
//		if ($agenciaUser) {
//			$agencia = Models\Agencia::find($agenciaUser->agencia_id);
//			
//			if ($agencia) {
//				
//			}
//		}
		
	}
	
	
	
    
}
