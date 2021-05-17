<?php

namespace App\AppCustom\oAuth2;

use Sentinel;

class PasswordGrantVerifier
{
	
    public function verify($username, $password)
    {
		
		$credentials = [
            'email'    => $username,
            'password' => $password,
        ];
		
		try {

			//	$user = Sentinel::findByCredentials($credentials);
			$user = Sentinel::forceAuthenticate($credentials);
			if (!$user || 1 != $user->client_rest){
				return false;
			}

			return $user->id;

		} catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
			return false;
		} catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}
    }
}

