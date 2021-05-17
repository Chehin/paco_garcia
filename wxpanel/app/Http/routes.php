<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\AppCustom\Util;
use App\Http\Controllers\User\UserUtilController;
use Illuminate\Http\Request;

Route::get('loginMeli', 'MeliController@login');

Route::get('login', ['middleware' => ['csrf'], 'as' => 'login', 'uses' => 'HomeController@showLogin']);
Route::post('login', ['middleware' => ['csrf'],'as' => 'login', 'uses' => 'HomeController@doLogin']);

Route::get('passwordForgot', ['middleware' => ['csrf'], 'as' => 'passwordForgot', 'uses' => 'HomeController@showPasswordForgot']);
Route::get('passwordChange/{token}', ['middleware' => ['csrf'], 'as' => 'passwordChange', 'uses' => 'HomeController@showPasswordChange']);

Route::post('passwordForgot', ['middleware' => ['csrf'], 'uses' => 'HomeController@passwordForgotMail']);
Route::post('passwordChange', ['middleware' => ['csrf'], 'uses' => 'HomeController@passwordForgotChange']);

Route::get('mail', function(){
	return View::make('email.passwordForgot');
});


Route::get('logout',function(){

	//Blank REST API token from DB
	UserUtilController::clearApiTokenCurrentUser();

	Sentinel::logout();

	//Remove REST API cookie
	return 
		Response::make(
				View::make('login')
			)
			->withCookie(
				Cookie::forget(\config('appCustom.cookieRestApiWeb'))
			)
		;

});

Route::group(['middleware' => ['web','auth.custom','csrf']], function(){
	Route::get('/', function() {
		//default index
		
			$response = 
				Response::make(
					View::make('blank')
				)
			;
		
		
		if (!Cookie::get(\config('appCustom.cookieRestApiWeb'))) {

			$response->withCookie(
				\config('appCustom.cookieRestApiWeb'), 
				(Sentinel::getUser()->email . ':' . Sentinel::getUser()->api_token)
			)
			;
		}

		return $response;
	});
	
	//User
	//Main View
	Route::get('user', ['as' => 'user', 'uses' => 'User\UserUtilController@showMainView']);
	
	//User Image
	Route::get('userImg/imageMain', ['as' => 'user/image', 'uses' => 'UserUtilController@showMainViewImage']);
	//Role Module
	Route::get('role', ['as' => 'role', 'uses' => 'User\RoleUtilController@showMainView']);		
	
	Route::get('comprobante', ['as' => 'comprobante', 'uses' => 'ComprobanteUtilController@showMainView']);
	
	//News
	//Main View
	Route::get('news', ['as' => 'news', 'uses' => 'NewsUtilController@showMainView']);
	//Main View Image
	Route::get('news/imageMain/{id}', ['as' => 'news/image', 'uses' => 'NewsUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('news/noteRelatedMain/{id}', ['as' => 'news/noteRelated', 'uses' => 'NewsUtilController@showMainViewNoteRelated']);
	
	//Blogs
	//Main View
	Route::get('blog', ['as' => 'blog', 'uses' => 'BlogUtilController@showMainView']);
	//Main View Image
	Route::get('blog/imageMain/{id}', ['as' => 'blog/image', 'uses' => 'BlogUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('blog/noteRelatedMain/{id}', ['as' => 'blog/noteRelated', 'uses' => 'BlogUtilController@showMainViewNoteRelated']);
	
	//Slider
	//Main View
	Route::get('slider', ['as' => 'news/slider', 'uses' => 'SliderUtilController@showMainView']);
	//Main View Image
	Route::get('slider/imageMain/{id}', ['as' => 'slider/image', 'uses' => 'SliderUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('slider/noteRelatedMain/{id}', ['as' => 'slider/noteRelated', 'uses' => 'SliderUtilController@showMainViewNoteRelated']);	

	Route::get('empresaSis', ['as' => 'empresaSis', 'uses' => 'EmpresaSisUtilController@showMainView']);
	Route::get('empresaSis/imageMain/{id}', ['as' => 'empresaSis/image', 'uses' => 'EmpresaSisUtilController@showMainViewImage']);
	//mailling
	//Main views
	Route::get('maillingDiagramador', ['as' => 'mailling/maillingDiagramador', 'uses' => 'MaillingDiagramadorUtilController@showMainView']);
	Route::get('maillingTipos', ['as' => 'mailling/maillingTipos', 'uses' => 'MaillingTiposUtilController@showMainView']);
	Route::get('maillingCampanias', ['as' => 'mailling/maillingCampanias', 'uses' => 'MaillingCampaniasUtilController@showMainView']);
	Route::get('maillingEstadisticas/{id}', ['as' => 'mailling/maillingEstadisticas', 'uses' => 'MaillingEstadisticasUtilController@showMainViewReport']);
	Route::get('maillingEstadisticasSimples/{id}', ['as' => 'mailling/maillingEstadisticasSimples', 'uses' => 'MaillingEstadisticasSimplesUtilController@showMainViewReport']);
	Route::get('maillingTemplates', ['as' => 'mailling/maillingTemplates', 'uses' => 'MaillingTemplatesUtilController@showMainView']);
	Route::get('maillingEstadisticasReport', ['as' => 'mailling/maillingEstadisticasReport', 'uses' => 'MaillingEstadisticasReportUtilController@showMainView']);
	Route::get('maillingEstadisticasAbReport', ['as' => 'mailling/maillingEstadisticasAbReport', 'uses' => 'MaillingEstadisticasAbReportUtilController@showMainView']);
	Route::get('filtroMails', ['as' => 'fMail', 'uses' => 'MaillingCampaniasUtilController@filtroMails']);
	//
	Route::post('froalaImage', ['as' => 'imageFroala', 'uses' => 'ImageUtilController@cargarImagen']);
	//template
	Route::get('template', ['as' => 'ttemplate', 'uses' => 'MaillingTiposUtilController@getTemplates']);
	Route::get("mailSend", ['as' => 'mailSend', 'uses' => 'MaillingCampaniasUtilController@mailSend']);
	Route::get("mailSendAB", ['as' => 'mailSendAB', 'uses' => 'MaillingCampaniasUtilController@mailSendAB']);

	Route::get('importarClientes', ['as' => 'importarClientes', 'uses' => 'ImportarClientesUtilController@showMainView']);
	Route::post('importarClientes', ['as' => 'importarClientes', 'uses' => 'ImportarClientesController@import']);
	
	//CTA
	Route::get('banners2', ['as' => 'banners2/banners2', 'uses' => 'Banners2UtilController@showMainView']);
	Route::get('banners2Posiciones', ['as' => 'banners2/banners2Posiciones', 'uses' => 'Banners2PosicionesUtilController@showMainView']);
	Route::get('banners2Tipos', ['as' => 'banners2/banners2Tipos', 'uses' => 'Banners2TiposUtilController@showMainView']);
	Route::get('banners2/personasRelatedMain/{id}', ['as' => 'banners2/personasRelated', 'uses' => 'Banners2UtilController@showMainViewPersonasRelated']);

	Route::get('marketingPersonas', ['as' => 'marketing/marketingPersonas', 'uses' => 'MarketingPersonasUtilController@showMainView']);
	Route::get('marketingListas', ['as' => 'marketing/marketingListas', 'uses' => 'MarketingListasUtilController@showMainView']);
	Route::get('marketingListas/personasRelatedMain/{id}', ['as' => 'marketing/personasRelated', 'uses' => 'MarketingListasUtilController@showMainViewPersonasRelated']);
	
	//Newsletter
	//Main View
	Route::get('newsletter', ['as' => 'newsletter', 'uses' => 'NewsletterUtilController@showMainView']);
	//Main View Image
	Route::get('newsletter/imageMain/{id}', ['as' => 'newsletter/image', 'uses' => 'NewsletterUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('newsletter/noteRelatedMain/{id}', ['as' => 'newsletter/noteRelated', 'uses' => 'NewsletterUtilController@showMainViewNoteRelated']);

				
	Route::get('rubros', ['as' => 'productos/rubros', 'uses' => 'RubrosUtilController@showMainView']);
	Route::get('rubros/imageMain/{id}', ['as' => 'rubros/image', 'uses' => 'RubrosUtilController@showMainViewImage']);	

	Route::get('subRubros', ['as' => 'productos/subRubros', 'uses' => 'SubRubrosUtilController@showMainView']);
	Route::get('subsubRubros', ['as' => 'productos/subsubRubros', 'uses' => 'SubSubRubrosUtilController@showMainView']);

	Route::get('etiquetas', ['as' => 'productos/etiquetas', 'uses' => 'EtiquetasUtilController@showMainView']);
	Route::get('etiquetas/imageMain/{id}', ['as' => 'etiquetas/image', 'uses' => 'EtiquetasUtilController@showMainViewImage']);	

	Route::get('etiquetasBlog', ['as' => 'blog/etiquetasBlog', 'uses' => 'EtiquetasBlogUtilController@showMainView']);
	Route::get('etiquetasBlog/imageMain/{id}', ['as' => 'etiquetasBlog/image', 'uses' => 'EtiquetasBlogUtilController@showMainViewImage']);	

	Route::get('deportes', ['as' => 'productos/deportes', 'uses' => 'DeportesUtilController@showMainView']);

	Route::get('productos', ['as' => 'productos/productos', 'uses' => 'ProductosUtilController@showMainView']);
	Route::get('productos/imageMain/{id}', ['as' => 'productos/image', 'uses' => 'ProductosUtilController@showMainViewImage']);
	
	Route::get('productos/imageSliderMain/{id}', ['as' => 'productos/image', 'uses' => 'ProductosUtilController@showMainViewImage']);
	
	Route::get('productos/preciosRelatedMain/{id}', ['as' => 'productos/preciosRelated', 'uses' => 'ProductosUtilController@showMainViewPreciosRelated']);
	Route::get('productos/productosRelatedMain/{id}', ['as' => 'productos/productosRelated', 'uses' => 'ProductosUtilController@showMainViewProductosRelated']);
	Route::get('productos/preguntasRelatedMain/{id}', ['as' => 'productos/preguntasRelated', 'uses' => 'ProductosUtilController@showMainViewPreguntasRelated']);
	Route::get('productos/productosRelatedColor/{id}', ['as' => 'productos/productosRelatedColor', 'uses' => 'ProductosUtilController@showMainViewProductosRelatedColor']);

	//ImportarProductos
	Route::get('importarProductos', ['as' => 'productos/importarProductos', 'uses' => 'ImportarProductosUtilController@showMainView']);
	Route::post('importarProductos', ['as' => 'productos/importarProductosProcesar', 'uses' => 'ImportarProductosController@procesar']);

	Route::get('marcas', ['as' => 'configuracion/marcas', 'uses' => 'MarcasUtilController@showMainView']);
	Route::get('marcas/imageMain/{id}', ['as' => 'marcas/image', 'uses' => 'MarcasUtilController@showMainViewImage']);

	Route::get('colores', ['as' => 'configuracion/colores', 'uses' => 'ColoresUtilController@showMainView']);
	//Main View Image
	Route::get('colores/imageMain/{id}', ['as' => 'colores/image', 'uses' => 'ColoresUtilController@showMainViewImage']);

	Route::get('talles', ['as' => 'configuracion/talles', 'uses' => 'TallesUtilController@showMainView']);

	Route::get('monedas', ['as' => 'configuracion/monedas', 'uses' => 'MonedasUtilController@showMainView']);

	Route::get('general', ['as' => 'configuracion/general', 'uses' => 'ConfGeneralUtilController@showMainView']);

	Route::get('banners', ['as' => 'banners/banners', 'uses' => 'BannersUtilController@showMainView']);
	Route::get('bannersClientes', ['as' => 'banners/bannersClientes', 'uses' => 'BannersClientesUtilController@showMainView']);
	Route::get('bannersPosiciones', ['as' => 'banners/bannersPosiciones', 'uses' => 'BannersPosicionesUtilController@showMainView']);
	Route::get('bannersTipos', ['as' => 'banners/bannersTipos', 'uses' => 'BannersTiposUtilController@showMainView']);
	
	Route::get('pedidosMeli', ['as' => 'pedidosMeli/pedidosMeli', 'uses' => 'PedidosMeliUtilController@showMainView']);
	
	Route::get('pedidos', ['as' => 'pedidos/pedidos', 'uses' => 'PedidosUtilController@showMainView']);
	Route::get('pedidos/selectProducto', ['as' => 'pedidos/pedidos/selectProducto', 'uses' => 'PedidosUtilController@selectProducto']);
	Route::get('pedidosClientes', ['as' => 'pedidos/pedidosClientes', 'uses' => 'PedidosClientesUtilController@showMainView']);
	Route::get('pedidosClientes/selectCliente', ['as' => 'pedidos/pedidosClientes/selectCliente', 'uses' => 'PedidosClientesUtilController@selectCliente']);
	//Main View Image
	Route::get('pedidosClientes/imageMain/{id}', ['as' => 'pedidosClientes/image', 'uses' => 'PedidosClientesUtilController@showMainViewImage']);
	Route::get('pedidosClientes/direccionesRelatedMain/{id}', ['as' => 'pedidosClientes/direccionesRelated', 'uses' => 'PedidosClientesUtilController@showMainViewDireccionesRelated']);
	Route::get('filtroSubrubros', ['as' => 'fsubrubros', 'uses' => 'ProductosUtilController@filtroSubRubros']);

	Route::get("syncModal", ["as" => "syncModal", "uses" => "ImportarProductosController@syncModal"]);
	Route::get("syncCheck", ["as" => "syncCheck", "uses" => "ImportarProductosController@getLastSyncStatus"]);

	//Dash
	Route::get('dash', ['as' => 'dash', 'uses' => 'DashUtilController@showMainView']);
	Route::get('dash2', ['as' => 'dash2', 'uses' => 'Dash2UtilController@showMainView']);
	Route::get('dash3', ['as' => 'dash3', 'uses' => 'Dash3UtilController@showMainView']);
	
	Route::get('pedidos1', ['as' => 'pedidos1', 'uses' => 'Pedidos1UtilController@showMainView']);
	Route::get('pedidos2', ['as' => 'pedidos2', 'uses' => 'Pedidos2UtilController@showMainView']);
	Route::get('pedidos3', ['as' => 'pedidos3', 'uses' => 'Pedidos3UtilController@showMainView']);
	Route::get('download/archivos/{fileName}', function($fileName){
        $fileFullName = \config('appCustom.UPLOADS_BANNERS') . $fileName;
        $aFileName = explode('_', $fileName);
        $fileDownloadName = base64_decode($aFileName[1]);
        return Response::download($fileFullName, $fileDownloadName);
	});
	//MELI
	Route::get('createPublicacion/{id}', 'MeliController@createPublicacion');
	Route::get('updatePublicacion/{id}', 'MeliController@updatePublicacion');
	Route::get('verPublicacion/{id}', 'MeliController@verPublicacion');
	Route::delete('deletePublicacion/{id}', 'MeliController@deletePublicacion');
	Route::get('updateLoteMeli', 'MeliController@updateLoteMeli');

	//Sucursales
	//Main View
	Route::get('sucursales', ['as' => 'sucursales', 'uses' => 'SucursalesUtilController@showMainView']);
	//Main View Image
	Route::get('sucursales/imageMain/{id}', ['as' => 'sucursales/image', 'uses' => 'SucursalesUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('sucursales/noteRelatedMain/{id}', ['as' => 'sucursales/noteRelated', 'uses' => 'SucursalesUtilController@showMainViewNoteRelated']);
	


});

// Route group for REST API versioning (Web app)
Route::group(['prefix' => 'rest/v1', 'middleware' => 'auth.rest'], function(){
	
	
	Route::resource("user", "User\UserController");
	Route::resource("role", "User\RoleController");
	Route::resource("permission", "User\PermissionController");
	Route::resource("roleAssign", "User\RoleAssignController");
	
	Route::resource("userImg", "ImageUserController");		
	
	Route::resource("comprobante", "ComprobanteController");
	Route::resource("empresaSis", "EmpresaSisController",['except' => ['create', 'store', 'destroy']]);
	Route::resource("empresaSisImage", "ImageController");
	
	Route::resource("news", "NewsController");
	Route::resource("newsImage", "ImageController");
	Route::resource("newsNoteRelated", "NoteRelatedController");
	Route::resource("newsNoteLanguage", "NewsNoteLanguageController");

	Route::resource("blog", "BlogController");
	Route::resource("blogImage", "ImageController");
	Route::resource("blogNoteRelated", "RelatedController");
    Route::resource("blogNoteLanguage", "BlogNoteLanguageController");
	
	Route::resource("rubros", "RubrosController");
	Route::resource("rubrosImage", "ImageController");
	Route::get('rubrosIds', function(){
		return Util::getRubros();
	});

	Route::resource("subRubros", "SubRubrosController");
	Route::resource("subsubRubros", "SubSubRubrosController");

	Route::resource("deportes", "DeportesController");

	Route::resource("etiquetas", "EtiquetasController");
	Route::resource("etiquetasImage", "ImageController");

	Route::resource("etiquetasBlog", "EtiquetasBlogController");
	Route::resource("etiquetasBlogImage", "ImageController");

	Route::resource("note", "NoteController");
	
	Route::resource("productos", "ProductosController");
	Route::resource("productosImage", "ImageController");
	Route::resource("productosImageSlider", "ImageController");
	Route::get('etiquetasIds', function(){
		return Util::getEtiquetas();
	});

	Route::get('etiquetasBlogIds', function(){
		return Util::getEtiquetasBlog();
	});
	Route::get('deportesIds', function(){
		return Util::getDeportes();
	});
	Route::resource("preciosRelated", "PreciosRelatedController");
	Route::post('preciosRelated/editInLine', ['as' => 'PreciosRelatedController/editInLine', 'uses' => 'PreciosRelatedController@editInLine']);
	Route::resource("productosProductosRelated", "ProductosRelatedController");
	Route::resource("productosProductosRelatedColor", "ProductosRelatedColorController");
	Route::get("productosPreguntas", "PreguntasRelatedController@index");
	Route::get("productosPreguntas/{id}/edit", "PreguntasRelatedController@edit");
	Route::post("productosPreguntas/{id}", "MeliController@publicarRespuesta");

	Route::post('createPublicacion/{id}', 'MeliController@createPublicacion');
	Route::put('updatePublicacion/{id}', 'MeliController@updatePublicacion');
	Route::get('verPublicacion/{id}', 'MeliController@verPublicacion');
	Route::delete('deletePublicacion/{id}', 'MeliController@deletePublicacion');

	Route::get('categoryPredict/{nombre}', 'MeliController@categoryPredict');
	Route::get('categoriaMeli/{id_categoria}/{array}', 'MeliController@getCategory');
	
	Route::get('editCatMeli/{id_cat}/{nivel}', 'MeliController@editCategory');

	Route::resource("marcas", "MarcasController");
	Route::resource("marcasImage", "ImageController");

	Route::resource("colores", "ColoresController");
	Route::resource("coloresImage", "ImageController");
	Route::resource("talles", "TallesController");
	Route::resource("monedas", "MonedasController");

	Route::resource("general", "ConfGeneralController");

	Route::resource("banners", "BannersController");
    Route::post('banners/upload',['as' => 'banners/upload', 'uses' => 'BannersController@upload']);
    Route::resource("bannersClientes", "BannersClientesController");
    Route::resource("bannersPosiciones", "BannersPosicionesController");
    Route::resource("bannersTipos", "BannersTiposController");

	Route::resource("pedidos", "PedidosController");
	Route::resource("pedidosMeli", "PedidosMeliController");

	Route::get("pedidoMetodopago/{id}/edit", "PedidosController@metodoPago");
	Route::put("pedidoMetodopago/{id}", "PedidosController@metodoPagoPut");
	
	Route::get("pedidoEstadopago/{id}/edit", "PedidosController@estadoPago");
	Route::put("pedidoEstadopago/{id}", "PedidosController@estadoPagoPut");
	
	Route::get("pedidoEstadoenvio/{id}/edit", "PedidosController@estadoEnvio");
	Route::put("pedidoEstadoenvio/{id}", "PedidosController@estadoEnvioPut");

	Route::post('alta_envio', ['as' => 'alta_envio', 'uses' => 'PedidosController@altaEnvio']);
	Route::post('sucursales_envio', ['as' => 'sucursales_envio', 'uses' => 'PedidosController@sucursales_envio']);
	
	Route::get("pedidoProductos/{id}/edit", "PedidosController@productos");
	Route::get("pedidoNotificaciones/{id}/edit", "PedidosController@notificaciones");


	Route::get("pedidoMeliMetodopago/{id}/edit", "PedidosMeliController@metodoPago");
	Route::put("pedidoMeliMetodopago/{id}", "PedidosMeliController@metodoPagoPut");
	
	Route::get("pedidoMeliEstadopago/{id}/edit", "PedidosMeliController@estadoPago");
	Route::put("pedidoMeliEstadopago/{id}", "PedidosMeliController@estadoPagoPut");
	
	Route::get("pedidoMeliEstadoenvio/{id}/edit", "PedidosMeliController@estadoEnvio");
	Route::put("pedidoMeliEstadoenvio/{id}", "PedidosMeliController@estadoEnvioPut");
	
	Route::get("pedidoMeliProductos/{id}/edit", "PedidosMeliController@productos");

	#pedidos1
	Route::resource("pedidos1", "Pedidos1Controller");
	Route::get("pedido1Metodopago/{id}/edit", "Pedidos1Controller@metodoPago");
	Route::put("pedido1Metodopago/{id}", "Pedidos1Controller@metodoPagoPut");
	
	Route::get("pedido1Estadopago/{id}/edit", "Pedidos1Controller@estadoPago");
	Route::put("pedido1Estadopago/{id}", "Pedidos1Controller@estadoPagoPut");
	
	Route::get("pedido1Estadoenvio/{id}/edit", "Pedidos1Controller@estadoEnvio");
	Route::put("pedido1Estadoenvio/{id}", "Pedidos1Controller@estadoEnvioPut");
	
	Route::get("pedido1Productos/{id}/edit", "Pedidos1Controller@productos");
	Route::get("pedido1Notificaciones/{id}/edit", "PedidosController@notificaciones");
	#pedidos2
	Route::resource("pedidos2", "Pedidos2Controller");
	Route::get("pedido2Metodopago/{id}/edit", "Pedidos2Controller@metodoPago");
	Route::put("pedido2Metodopago/{id}", "Pedidos2Controller@metodoPagoPut");
	
	Route::get("pedido2Estadopago/{id}/edit", "Pedidos2Controller@estadoPago");
	Route::put("pedido2Estadopago/{id}", "Pedidos2Controller@estadoPagoPut");
	
	Route::get("pedido2Estadoenvio/{id}/edit", "Pedidos2Controller@estadoEnvio");
	Route::put("pedido2Estadoenvio/{id}", "Pedidos2Controller@estadoEnvioPut");
	
	Route::get("pedido2Productos/{id}/edit", "Pedidos2Controller@productos");
	Route::get("pedido2Notificaciones/{id}/edit", "PedidosController@notificaciones");
	#pedidos3
	Route::resource("pedidos3", "Pedidos3Controller");
	Route::get("pedido3Metodopago/{id}/edit", "Pedidos3Controller@metodoPago");
	Route::put("pedido3Metodopago/{id}", "Pedidos3Controller@metodoPagoPut");
	
	Route::get("pedido3Estadopago/{id}/edit", "Pedidos3Controller@estadoPago");
	Route::put("pedido3Estadopago/{id}", "Pedidos3Controller@estadoPagoPut");
	
	Route::get("pedido3Estadoenvio/{id}/edit", "Pedidos3Controller@estadoEnvio");
	Route::put("pedido3Estadoenvio/{id}", "Pedidos3Controller@estadoEnvioPut");
	
	Route::get("pedido3Productos/{id}/edit", "Pedidos3Controller@productos");
	Route::get("pedido3Notificaciones/{id}/edit", "PedidosController@notificaciones");
	##
	Route::resource("pedidosClientes", "PedidosClientesController");
	Route::resource("pedidosClientesImage", "ImageController");
	Route::resource("direccionesRelated", "DireccionesRelatedController");
	

	Route::resource("slider", "SliderController");
	Route::resource("sliderImage", "ImageController");
	Route::resource("sliderNoteRelated", "NoteRelatedController");
	Route::resource("itemRelation", "ItemRelationController");
	Route::get('itemRelationRelated', ["as" => "itemRelationRelated", "uses" => "ItemRelationController@itemsRelated"]);
	
	
	
	Route::get("provincia", ['uses' => 'ProvinciaController@getProvinciaByPais']);
	
	Route::get('obtenerSubrubros', 'SubRubrosUtilController@obtenerSubrubros');
	Route::get('obtenerSubSubrubros', 'SubSubRubrosUtilController@obtenerSubSubrubros');

	Route::resource("sucursales", "SucursalesController");
	Route::resource("sucursalesImage", "ImageController");
	Route::resource("sucursalesNoteRelated", "NoteRelatedController");
	Route::resource("sucursalesNoteLanguage", "SucursalesNoteLanguageController");
	
	Route::get("getTallesPorRubroSubrubro/{rubroId}/{subrubroId}", "TallesUtilController@getTallesPorRubroSubrubro");

	Route::resource("dash", "DashController");
	Route::resource("dash2", "Dash2Controller");
	Route::resource("dash3", "Dash3Controller");
	Route::resource("newsletter", "NewsletterController");
	
	//CTA
	Route::resource("banners2", "Banners2Controller");
	Route::post('banners2/upload',['as' => 'banners2/upload', 'uses' => 'Banners2Controller@upload']);
	Route::resource("banners2Posiciones", "Banners2PosicionesController");
	Route::resource("banners2Tipos", "Banners2TiposController");

	Route::resource("maillingDiagramador", "MaillingDiagramadorController");
	Route::resource("maillingTipos", "MaillingTiposController");
	Route::resource("maillingCampanias", "MaillingCampaniasController");
	Route::resource("maillingEstadisticas", "MaillingEstadisticasController");
	Route::resource("maillingEstadisticasSimples", "MaillingEstadisticasSimplesController");
	Route::resource("maillingTemplates", "MaillingTemplatesController");
	Route::resource("maillingEstadisticasReport", "MaillingEstadisticasReportController");
	Route::resource("maillingEstadisticasAbReport", "MaillingEstadisticasAbReportController");

	Route::get('ListaIds', function(){
		return Util::getListas();
	});
	
	Route::resource("marketingPersonas", "MarketingPersonasController");
	Route::get("obtenerProvincias", "MarketingPersonasUtilController@obtenerProvincias");
    Route::get("empresasIds", function(){
		return Util::getEmpresas();
	});
    Route::get("oportunidadesIds", function(){
		return Util::getOportunidades();
	});
  
    Route::resource("marketingEmpresas", "MarketingEmpresasController");
    Route::get("personasIds", function(){
		return Util::getPersonas();
	});
    Route::resource("marketingListas", "MarketingListasController");
	
	Route::resource("personasRelated", "MarketingListasPersonasController");
	
	Route::post("personasRelated/quitarPersona", "MarketingListasPersonasController@quitarPersonasRelated");

	##fe
	Route::get("fe", "FacturaElectronicaController@index");
	Route::post("fe", "FacturaElectronicaController@index");

	Route::get('facturacion', 'FacturaElectronicaController@impresion')->name('facturacion');
	Route::post('facturacion', 'FacturaElectronicaController@impresion')->name('facturacion');
	
	Route::get("setEtiquetas/create", "ProductosController@setEtiquetas");
	Route::post("setEtiquetas", "ProductosController@setEtiquetasPost");
});

// Route group for Client REST API versioning (oAuth2 Authentication)
Route::group(['prefix' => 'client/rest/v1'], function(){
	
	
	Route::post('access_token', function() {
		$token = Authorizer::issueAccessToken();
		return response()->json($token);
	});

	Route::group(['middleware' => 'oauth'], function () {
		
		Route::post("productosAdd", "Api\ApiProductosController@store");

		Route::get("clientes", "Api\ApiClientesController@index");

		Route::get("pedidos", "Api\ApiPedidosController@index");
		Route::put("pedidos/{id}", "Api\ApiPedidosController@update");
        
        Route::get("localidades", "Api\ApiLocalidadesController@index");
        Route::post("localidadesAdd", "Api\ApiLocalidadesController@store");
        
        Route::get("provincias", "Api\ApiProvinciasController@index");
        Route::post("provinciasAdd", "Api\ApiProvinciasController@store");
	});

});
 
Route::group(['prefix' => 'frontClient/rest/v1'], function(){
	//Front
	Route::resource("idioma", "Fe\IdiomaController");
	Route::post("contacto", "Fe\ContactoController@send");
	
	Route::get("menu", "Fe\ProductosController@menu");

	//productos
	Route::get("rubros", "Fe\ProductosController@rubros"); 
	Route::get("filtros", "Fe\ProductosController@filtros");
	Route::get("producto", "Fe\ProductosController@producto");
	Route::get("listadoProductos", "Fe\ProductosController@listado");
	Route::get("listadoProductosRelacionados", "Fe\ProductosController@relacionados");
	Route::get("listadoProductosRelacionadosColor", "Fe\ProductosController@relacionadosColor");
	Route::get("cambioColor", "Fe\ProductosController@cambiarColor");
	Route::get("autocomplete", "Fe\ProductosController@autocomplete");
	Route::get("filtrop", "Fe\ProductosController@filtroproductos"); 
	
	//novedades
	Route::get("nota", "Fe\NotasController@nota");
	Route::get("listadoNotas", "Fe\NotasController@listado");
	
	Route::get("slider", "Fe\SliderController@slider");
	Route::get("sliderListado", "Fe\SliderController@destacadosSlider");
	
	Route::get("relacionado", "Fe\RelacionadoController@relacionado");
	//Front fin
	
	Route::resource("search","Fe\ProductosController@search");
	//Cart
	Route::get('cartAdd','Fe\CartController@add');
	Route::get('cartGet','Fe\CartController@get');
	Route::get('cartRemove','Fe\CartController@remove');
	Route::get('cartUpdate','Fe\CartController@update');
	Route::get('cartGetHistory','Fe\CartController@getHistory');
	Route::get('carGetPreference','Fe\CartController@carGetPreference');
	Route::get('cartCheckout','Fe\CartController@cartCheckout');
	Route::get('notificaciones_meli','Fe\CartController@notificaciones_meli');
	Route::get('notificaciones_mercadolibre','Fe\MeliController@notificaciones_mercadolibre');
	Route::get('todoPago','Fe\CartController@todoPago');
	Route::get('validarPagoTP','Fe\CartController@validarPagoTP');
	Route::get('estadoPagoPut','Fe\CartController@estadoPagoPut');
	Route::get('email','Fe\CartController@email');
	//Cart Fin

	//Marcas
	Route::get("listadoMarcas", "Fe\MarcasController@listado");

	// Envios
	Route::get('getTipoEnvio','Fe\EnvioController@getTipoEnvio');
	Route::get('setTipoEnvio','Fe\EnvioController@setTipoEnvio');
	Route::get('consultaCostoEnvio','Fe\EnvioController@consultaCostoEnvio');
	Route::get('getDireccionEnvio','Fe\EnvioController@getDireccionEnvio');
	Route::get('getCostoEnvio','Fe\EnvioController@getCostoEnvio');

	Route::get('getSucursalEnvio','Fe\EnvioController@getSucursalEnvio');

	//Andreani
	Route::get('andreani','Fe\EnvioController@andreani');
	Route::get('getAndreaniSucursales','WebServices\AndreaniController@getSucursales');
	Route::get('obtenerSucursalDestinoAndreani/{id_direccion}','WebServices\AndreaniController@obtenerSucursalDestino');

	//Mis envios
	Route::get('getMisEnviosPrecio','WebServices\MisEnviosController@getPrecioEnvio');
	Route::get('getMisEnviosSucursales','WebServices\MisEnviosController@getSucursales');
	Route::get('getMisEnviosTiposDocumentos','WebServices\MisEnviosController@getTiposDocumentos');
	Route::get('getMisEnviosAccessToken','WebServices\MisEnviosController@obtenerToken');
	Route::get('altaEnvioMisEnvios/{id_pedido}','WebServices\MisEnviosController@altaEnvio');


	Route::get('trackingMisEnvios/{id}','Fe\MisEnviosController@trackingMisEnvios');
	
	Route::get('encontrarSucursalMisPedidos/{cp}','Fe\MisEnviosController@encontrarSucursalMisPedidos');
	

	// Envios Fin
	
	// Mercado libre
	Route::get('setAccessToken','Fe\MeliController@setAccessToken');
	
	//auth
	Route::get("login", "Fe\AuthController@login");	
	Route::get("registro", "Fe\AuthController@registro");
	Route::get("emailConfirm", "Fe\AuthController@emailConfirm");
	Route::get("recuperarPass", "Fe\AuthController@recuperarPass");
	Route::get("resetPass", "Fe\AuthController@resetPass");
	Route::get("direcciones", "Fe\AuthController@direcciones");
	Route::get("direccionesRemove", "Fe\AuthController@direccionesRemove");
	Route::get("getDireccion", "Fe\AuthController@getDireccion");
	Route::get("setDireccion", "Fe\AuthController@setDireccion");
	Route::get("updatePerfil", "Fe\AuthController@updatePerfil");
	Route::get("getLocalidad", "Fe\AuthController@getLocalidad");
	Route::get("user", "Fe\AuthController@getUser");
	Route::get("userRegister","Fe\AuthController@userRegister");
	//auth fin
	Route::get("getOpiniones", "Fe\AuthController@getOpiniones");
	
	// Banners
    Route::resource("banners_front", "Fe\BannersController");
    Route::resource("banners_click", "Fe\BannersController@banners_click");
	//Front fin	

	Route::resource("newsletter","Fe\NewsletterController@store");

	Route::get("blog", "Fe\BlogController@index");
	Route::get("blog/{id}", "Fe\BlogController@nota");

		//tracking mail-link
	Route::get("trackingMail", "Fe\TrackingController@trackingMail");
	Route::get("trackingLink", "Fe\TrackingController@trackingLink");
	
	//cta
	Route::get("ctaGet", "Fe\CtaController@ctaGet");
	Route::get("ctaSet", "Fe\CtaController@ctaSet");
	Route::get("ctaEnabled", "Fe\CtaController@ctaEnabled");
	Route::get("ctaGetByPosicion", "Fe\CtaController@ctaGetByPosicion");

	
});