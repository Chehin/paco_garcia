<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserUtilController;
use App\AppCustom\Util;
use Illuminate\Http\Request;
use Sentinel;

class HomeClientRestController extends Controller {

			
	public function doLogin(Request $request, UserUtilController $userUtil)
	{
		
		$aResult = Util::getDefaultArrayResult();
		
		$credentials = [
				'email' => $request->header('php-auth-user'),
		];
		
		try {
		
			$user = Sentinel::findByCredentials($credentials);

			if (1 !== $user->client_rest || 1 !== $user->client_enabled) {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			
			$credentials['password'] = $request->header('php-auth-pw');

			if(Sentinel::authenticate($credentials)) {

				//Login ok!
				$user->api_token = $userUtil->generateApiToken();
				
				
				$user->save();
				
				//TODO....
				
				//TokenRestApi

				/*return 
					\Redirect::to('/');*/
			} else {
				//Wrong credentials
				$aResult['status'] = 1;
				$aResult['msg'] = 'El usuario o la contraseña son incorrectos';
			}
		
		} catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
			$aResult['status'] = 1;
			$aResult['msg'] = $e->getMessage();
		} catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
			$aResult['status'] = 1;
			$aResult['msg'] = $e->getMessage();
		} catch (\Exception $e) {
			$aResult['status'] = 1;
			$aResult['msg'] = config('appCustom.messages.internalError');
		} 
		
		

		return response()->json($aResult);
	}
	
	
	public function createUser(Request $request) {
		//Sentinel::registerAndActivate(['email' => date('H_i') . '@m.com', 'password'=>'123456', 'client_rest' => 1 ]);
		$credentials = [
				'email' => $request->header('php-auth-user'),
				'password' => $request->header('php-auth-pw'),
				
			];
		
		$user = Sentinel::findByCredentials($credentials); //->where('client_rest',0)->first();
		//$user = Sentinel::authenticate($credentials);
		
		print_r($user);
	}

		
		

}
