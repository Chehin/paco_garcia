<?php
return [
	
	'companyDefaultId' => 1,
	
	'logos' => [
		'logoDefault' => 'img/logo.png',
		'logoEmail64Default' => 'img/logoEmail.b64',
		
		'logo' => 'img/logos/%d/logo.png',
		'logoEmail64' => 'img/logos/%d/logoEmail.b64'
	],
	 
	'messages' => [
        'unauthorized' => 'No cuenta con el permiso para realizar esta acción',
        'dbError' => 'Error interno de Base de Datos',
        'internalError' => 'Error interno',
        'itemNotFound' => 'El elemento no se ha encontrado (quizá fue deshabilitado o borrado)',
		'wrongRequest' => 'La solicitud es incorrecta',
		'someWarnings' => 'Se encontraron algunas advertencias'
    ],
	
	'UPLOADS_BE' => '../../fe/public/uploads/',
	'PATH_UPLOADS' => \env('FE_URL').'uploads/',
	'UPLOADS_BE_USER' => 'uploads/user/',
	'UPLOADS_EMPRESASIS_IMG' => \env('UPLOADS_EMPRESASIS_IMG'),
	
	'PATH_BANNERS' => \env('FE_URL').'/uploads/banners/',
	'UPLOADS_BANNERS' => '../../fe/public/uploads/banners/',
	
	'MOD_WORK_FILTER' => '-2',
	'MOD_BLOG_FILTER' => '9',
	'MOD_SUCURSALES_FILTER' => '-10',
	'MOD_PRODUCT_FILTER' => '-9',
	'MOD_NEWS_FILTER' => '1',
	'MOD_NEWSSLIDER_FILTER' => '2',
	'MOD_COMPANY_FILTER' => '-1',
	'MOD_PEDIDOS_FILTER' => '10',
	'MOD_NEWSLETTER_FILTER' => '-8',
	
	//Enable/Disable global Model log activity
	'modelLogFeature' => true,
	
	'clientRestPrefix' => 'client/rest/',	
	'frontClientRestPrefix' => 'frontClient/rest/',
	'frontClientRestID' => 51,//usuario de front
	
	//default image values
	'image' => [
		'cropSize' => ['w' => 350, 'h' => 350],
		'thumbProportion' => 0.7,
	],
	
	//push
	'GOOGLE_GCM' => [
		'GOOGLE_API_KEY' => 'AIzaSyCJHnaR65v5oyBQX4-VY31ODkg7D95cYiA',
		'GOOGLE_GCM_URL' => 'https://fcm.googleapis.com/fcm/send',
	],
	
	'cookieRestApiWeb' => 'CookieRestApiWeb' .  '_' . \env('APP_NAME'),
	
	'userType' => ['panel' => 1, 'pc' => 5],
	
	'roleType' => ['panel' => 1, 'pc' => 5],
	
	'idInternalFeUser' => 51,
	
	'clientName' => 'Paco Garcia',
	'shortName' => 'PG',
	'clientVentas' => 'sabrina.cuevas@webexport.com.ar',

	'ANDREANI_CLIENTE' => '0012006306',
	'ANDREANI_USUARIO' => 'PACOGARCIA_WS',
	'ANDREANI_PASS' => 'Andreani',
	'ANDREANI_AMBIENTE' => 'prod',
	'ANDREANI_URL'=> 'https://api.andreani.com/',

	'MIS_ENVIOS_API_KEY' => '77cf98ac9d2ed2e195e09fe8666c62e7',
	'MIS_ENVIOS_SECRET_KEY' => '12deae55dca54ca246148b637a09f201',

	'TP_API_KEY'=> /* 'TODOPAGO 22e4bf25d8364a45b5c112d708319531' */'TODOPAGO f5e13bbe01c9407bad7d9de791710bf5',
	'TP_KEY'=> /* '22e4bf25d8364a45b5c112d708319531' */'f5e13bbe01c9407bad7d9de791710bf5',
	'TP_MERCHANT_ID'=>/* 387885 */1074302,


	'mercadolibre' => [
		'app_id' => \env('ML_APP_ID'), 
		'app_secret' => \env('ML_APP_SECRET'),
		'app_redirect' => \env('ML_APP_REDIRECT'),
		'app_sideid' => \env('ML_APP_SITEID')
	]
];