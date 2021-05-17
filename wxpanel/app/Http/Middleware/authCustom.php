<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class authCustom
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
        
        if (! Sentinel::check()) {
            //Remove REST API cookie
            return 
                \Response::make(
                        \View::make('login')
                    )
                    ->withCookie(
                        \Cookie::forget(\config('appCustom.cookieRestApiWeb'))
                    )
                ;
         
        }
    
        //Ok, go on...!
        
        return $next($request);
    }
}
