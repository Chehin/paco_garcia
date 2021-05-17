<?php

namespace App\Http\Controllers;

use App\AppCustom\Models\Sentinel\User;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\User\UserUtilController;
use App\AppCustom\Util;
use Illuminate\Http\Request;
use Sentinel;
use View;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}
        
	public function showLogin()
	{
		
		// show the form
		return 
			\View::make('login')
		;
	}

		
	public function doLogin(Request $request, UserUtilController $userUtil)
	{
		$validator = 
			\Validator::make(
				$request->all(),
				[
					'email'    => 'required',
					'password' => 'required',
				], 
				[
					'email.required' => 'El email es requerido',
					'password.required' => 'La contraseña es requerida',
				]
			)
		;

		if ($validator->fails()) {
			return \Redirect::to('login')
				->withErrors($validator)
				->withInput($request->except('password'))
			;
		} else {

			$credentials = [
				'email' => $request->email,
			];

			try {

				$user = Sentinel::findByCredentials($credentials);

				if (!$user || !$user->enabled){
					//user not enabled
					$validator->errors()->add('field', 'El usuario no existe o no está habilitado');

					return 
						\Redirect::to('login')
							->withErrors($validator)
							->withInput($request->except('password'))
					;
				}
				
				$aCompany = Util::getCompanyDataByUrl(\URL::to('/'));
				
				if ($aCompany['company']->id != $user->id_company) {
					//company doesn't match
					$validator->errors()->add('field', 'Parámetros incorrectos');

					return 
						\Redirect::to('login')
							->withErrors($validator)
							->withInput($request->except('password'))
					;
				}
				

				$credentials['password'] = $request->password;
				$remember = $request->input('remember') ? true : false;
				

				if(Sentinel::authenticate($credentials, $remember)) {

					//Login ok!
					$user->api_token = $userUtil->generateApiToken();
					$user->save();
					
					\session(['id_company' => $user->id_company]);

					$returnTo = $request->input('returnTo');
					
					
					return redirect('/')->with('returnTo', $returnTo);
					
				} else {
					//Wrong credentials
					$validator->errors()->add('field', 'El usuario o la contraseña son incorrectos');

					return 
						back()
							->withErrors($validator)
							->withInput($request->except('password'))
					;
				}

			} catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
				$validator->errors()->add('field', $e->getMessage());

				return 
					back()
						->withErrors($validator)
						->withInput($request->except('password'))
					;
			} catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
				$validator->errors()->add('field', $e->getMessage());

				return 
					back()
						->withErrors($validator)
						->withInput($request->except('password'))
					;
			} catch (\Exception $e) {
				$validator->errors()->add('field', config('appCustom.messages.internalError'));

				return 
					back()
						->withErrors($validator)
						->withInput($request->except('password'))
					;
			} 

		}


	}

		
	public function showPasswordForgot()
	{
		// show the form
		return 
			View::make('passwordForgot')
			;
	}

	public function showPasswordChange($encodedToken)
	{
		return 
			View::make('passwordChange')
				->with('forgotEncodedToken', $encodedToken)
			;
	}
		
	public function passwordForgotMail()
	{
		//Validation
		$validator = \Validator::make(
			\Request::all(), 
			array(
				'email' => 'required|email',
			), 
			array(
				'email.required' => 'El E-mail es requerido',
				'email.email' => 'El E-mail no es válido',
			)
		);

		if (!$validator->fails()) {
			$user = User::where('email', \Request::get('email'))
				->where('enabled', 1)
				->first()
			;

			if ($user) {

				$token = Util::getForgotToken();

				$link = \asset('passwordChange');
				$link .= '/' . \base64_encode($token);
								
				$aLogos = Util::getLogosByCompanyId($user->id_company);
				$company = \App\AppCustom\Models\Company::find($user->id_company);

				try {
					\Mail::send(
						'email.passwordForgot', 
						[
							'user' => $user->first_name .' '. $user->last_name, 
							'link' => $link,
							'logoEmailB64' => $aLogos['logoEmailB64'],
							'company' => $company,
						], 
						function($message) use ($user, $company)
						{
							$message->to($user->email)
								->subject($company->name_org . '. Restitución de contraseña');
						}
					)
					;

					$user->forgot_token = $token;
					$user->save();

				} catch (\Exception $e) {
					\Log::error($e->getMessage());
				}

				return 
					back()
						->with('forgotOk', "Un email fue enviado a '$user->email'. Siga las instrucciones para restablecer su contraseña.")
				;

			} else {
				$validator->errors()->add('email', 'El email no existe o el usuario está deshabilitado');

				return 
					back()
						->withErrors($validator)
						->withInput()
				;
			}

		} else {
			return 
				back()
					->withErrors($validator)
					->withInput()
			;
		}

	}


	public function passwordForgotChange() {

		//Validation
		$validator = \Validator::make(
			\Request::all(), 
			array(
				'password' => 'required|confirmed',
				'forgotEncodedToken'=> 'required' ,
			), 
			array(
				'password.required' => 'La contraseña es obligatoria',
				'password.confirmed' => 'Las contraseñas no coinciden',
				'forgotEncodedToken.required' => 'El token de restitucion es requerido',
			)
		);

		$encodedToken = \Request::get('forgotEncodedToken');

		if (!$validator->fails()) {

			try {
				$user = User::where('forgot_token', \base64_decode($encodedToken))->first();
			} catch (\Exception $e) {
				$validator->errors()->add('forgotEncodedToken', \config('appCustom.messages.wrongRequest') . ' (No se ha podido procesar la solicitud)');

				return 
					back()
						->withErrors($validator)
						->withInput()
					;
			}

			if ($user) {
				Sentinel::update($user, ['password' => \Request::get('password')]);

				return 
					back()
						->with('changeOk', "La restitución se ha hecho satisfactoriamente.")
				;

			} else {
				$validator->errors()->add('forgotEncodedToken', \config('appCustom.messages.wrongRequest') . ' (El token de restitución es inválido)');

				return 
					back()
						->withErrors($validator)
						->withInput()
				;
			}

		} else {

			return 
				back()
					->withErrors($validator)
					->withInput()
				;
		}

	}
		

}
