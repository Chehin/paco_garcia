<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
			
			//Added for OAuth2 Server
			\LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware::class,

			//Commented out for OAuth2 Server
            //\App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'auth.rest' => \App\Http\Middleware\authREST::class,
        'auth.custom' => \App\Http\Middleware\authCustom::class,
		
		//Added for OAuth2 Server
		'oauth' => \LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware::class,
		'oauth-user' => \LucaDegasperi\OAuth2Server\Middleware\OAuthUserOwnerMiddleware::class,
		'oauth-client' => \LucaDegasperi\OAuth2Server\Middleware\OAuthClientOwnerMiddleware::class,
		'check-authorization-params' => \LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware::class,
		'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
    ];
}
