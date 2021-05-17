<?php

namespace App\Http\Middleware;

use Closure;
use App;

class authREST
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
		if (! $cookie = $request->cookie(\config('appCustom.cookieRestApiWeb'))) {
			App::abort(401, 'An API Token cookie is required');
		}
        
		try {
			$tokenRestApi = \Crypt::decrypt($cookie);
		} catch (\Exception $e) {
			App::abort(401, 'API Token cookie is invalid');
		}

        list($userLogin, $token) = \explode(':', $tokenRestApi);

        $user = App\AppCustom\Models\Sentinel\User::where('email', $userLogin)
			->where('api_token',$token)
			->where('enabled',1)
			->first()
		;

        if (!$user) {
            App::abort(401, 'Wrong API Token credentials');
        }

        //Ok, go on...!
        
        return $next($request);
    }
}
