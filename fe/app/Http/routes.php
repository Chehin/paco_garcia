<?php
use Illuminate\Http\Request;
use App\AppCustom\Api;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$app->get('/', function () use ($app) {
//    return $app->version();
//});

 
$app->get('/', ['as' => 'home', 'uses' => 'HomeController@home']);
$app->get('listado_slide/{id}/{name}', ['as' => 'listado_slide', 'uses' => 'NotaController@listslide']);
$app->get('listado_slide/{id}/{name}/{page}', ['as' => 'listado_slide', 'uses' => 'NotaController@listslide']);
$app->get('linker/{id}', ['as' => 'linker', 'uses' => 'Controller@clickBanner']);

$app->get('contacto', ['as' => 'contacto', 'uses' => 'ContactoController@index']);
$app->post('contacto', ['as' => 'contacto', 'uses' => 'ContactoController@send']);

$app->get('producto/{id}/{name}', ['as' => 'producto', 'uses' => 'ProductosController@viewProducto']);
$app->get('productos/{id_etiqueta}/{id_rubro}/{id_subrubro}/{name}/{page}', ['as' => 'productos', 'uses' => 'ProductosController@listProductos']);
 
$app->get('filtroproductos', ['as' => 'filtroproductos', 'uses' => 'ProductosController@filtrproductos']);

$app->get('ajax/cambioColor', ['as' => 'ajax/cambioColor', 'uses' => 'ProductosController@cambioColor']);

$app->get('nota/{id}/{name}', ['as' => 'nota', 'uses' => 'NotaController@nota']);
$app->get('notaSuc/{id}/{name}', ['as' => 'notaSuc', 'uses' => 'NotaController@notaSuc']);
$app->get('notas/{id}/{name}', ['as' => 'notas', 'uses' => 'NotaController@listNotas']);

$app->get('ofertas', ['as' => 'ofertas', 'uses' => 'HomeController@ofertas']);
$app->get('productos', ['as' => 'productosTotal', 'uses' => 'HomeController@productos']);

//cart
$app->get('cart', ['as' => 'cart', 'uses' => 'CartController@cart']);
$app->post('cart', ['as' => 'cart', 'uses' => 'CartController@cart']);
$app->get('procesar_pedido', ['as' => 'procesar_pedido', 'uses' => 'CartController@procesar_pedido']);
$app->post('procesar_pedido', ['as' => 'procesar_pedido', 'uses' => 'CartController@procesar_pedido']);
$app->post('update_cart', ['as' => 'update_cart', 'uses' => 'CartController@update_cart']);
$app->get('update_cart', ['as' => 'update_cart', 'uses' => 'CartController@update_cart']);

//envio
$app->get('costoEnvio', ['as' => 'costoEnvio', 'uses' => 'CartController@costoEnvio']);
$app->get('consultarEnvio', ['as' => 'consultarEnvio', 'uses' => 'CartController@consultarEnvio']);

//alta para andreani
$app->get('andreaniconsult', ['as' => 'andreaniconsult', 'uses' => 'CartController@andreaniconsult']);

//cuenta
$app->get('login', ['as' => 'login', 'uses' => 'CuentaController@login']);
$app->post('login', ['as' => 'login', 'uses' => 'CuentaController@sendLogin']);

$app->get('auth/{provider}', ['as' => 'auth', 'uses' => 'AuthController@redirectToProvider']);
$app->get('callback/{provider}',['as' => 'callback', 'uses' => 'AuthController@handleProviderCallback']);

$app->get('registro', ['as' => 'registro', 'uses' => 'CuentaController@registro']);
$app->post('registro', ['as' => 'registro', 'uses' => 'CuentaController@sendRegistro']);
$app->get('mailconfirmed', ['as' => 'mailconfirmed', 'uses' => 'CuentaController@mailconfirmed']);
$app->post('mailconfirmed', ['as' => 'mailconfirmed', 'uses' => 'CuentaController@sendLogin']);
$app->get('cuenta', ['as' => 'cuenta', 'uses' => 'CuentaController@cuenta']);
$app->get('perfil', ['as' => 'perfil', 'uses' => 'CuentaController@perfil']);
$app->post('perfil', ['as' => 'perfil', 'uses' => 'CuentaController@sendPerfil']);
$app->get('historial',['as' => 'historial', 'uses' => 'CuentaController@historial']);
$app->get('logout',['as' => 'logout', 'uses' => 'CuentaController@logout']);
$app->get('recuperar_pass', ['as' => 'recuperar_pass', 'uses' => 'CuentaController@recuperar_pass']);
$app->post('recuperar_pass', ['as' => 'recuperar_pass', 'uses' => 'CuentaController@recuperar_pass']);
$app->get('reset_password', ['as' => 'reset_password', 'uses' => 'CuentaController@reset_password']);
$app->post('reset_password', ['as' => 'reset_password', 'uses' => 'CuentaController@reset_password']);

$app->get('direcciones', ['as' => 'direcciones', 'uses' => 'CuentaController@direcciones']);
$app->get('agregar_direccion',['as' => 'agregar_direccion', 'uses' => 'CuentaController@addDir']);
$app->post('agregar_direccion',['as' => 'agregar_direccion', 'uses' => 'CuentaController@sendDir']);
$app->get('borrar_direccion', ['as' => 'borrar_direccion', 'uses' => 'CuentaController@deleteDir']);
$app->get('getLocalidad', ['as' => 'getLocalidad', 'uses' => 'CuentaController@getLocalidad']);
$app->get('editar_direccion', ['as' => 'editar_direccion', 'uses' => 'CuentaController@editDir']);
$app->post('editar_direccion', ['as' => 'editar_direccion', 'uses' => 'CuentaController@editDir']);

//pago
$app->get('todopago', ['as' => 'todopago', 'uses' => 'PagoController@todoPago']);
/* $app->post('todoPago', ['as' => 'todoPago', 'uses' => 'PagoController@todoPago']); */
$app->get('exito_tp', ['as' => 'exito_tp', 'uses' => 'PagoController@exito_tp']);
$app->get('notificaciones', ['as' => 'notificaciones', 'uses' => 'PagoController@notificaciones']);
$app->post('notificaciones', ['as' => 'notificaciones', 'uses' => 'PagoController@notificaciones']);
$app->post('notificaciones_meli', ['as' => 'notificaciones_meli', 'uses' => 'PagoController@notificaciones_meli']);
$app->get('checkout', ['as' => 'checkout', 'uses' => 'PagoController@checkout']);
$app->post('checkout', ['as' => 'checkout', 'uses' => 'PagoController@checkout']);

$app->get('sucursalEnvio', ['as' => 'sucursalEnvio', 'uses' => 'CartController@sucursalEnvio']);

$app->get('blog/{page}', ['as' => 'blog', 'uses' => 'HomeController@viewBlog']);
$app->get('blog/{id_nota}/{name}', ['as' => 'blog_nota', 'uses' => 'HomeController@viewBlogNota']);

$app->get('autocomplete',['as' => 'autocomplete', 'uses'=>'HomeController@autocomplete']);
$app->get('search', ['as' => 'search', 'uses' => 'HomeController@search']);
$app->post('search', ['as' => 'search', 'uses' => 'HomeController@search']);

//cta
$app->get("cta/{ctaId}/{params}", ['as' => 'cta', 'uses' => 'CtaFeController@cta']);
$app->get("ctaGet", ['as' => 'ctaGet', 'uses' => 'CtaFeController@ctaGet']);
$app->get("ctaSet", ['as' => 'ctaSet', 'uses' => 'CtaFeController@ctaSet']);
$app->get("ctaEnabled", ['as' => 'ctaEnabled', 'uses' => 'CtaFeController@ctaEnabled']);
$app->post("ctaAjx",['as' => 'ctaAjx', 'uses' => 'CtaFeController@ctaAjx']);

$app->get('tracking', ['as' => 'tracking', 'uses' => 'CuentaController@tracking']);
//tracking mailling
$app->get('tracking_mail', ['as' => 'tracking_mail', 'uses' => 'TrackingController@index']);
$app->get('tracking_link', ['as' => 'tracking_link', 'uses' => 'TrackingController@link']);
$app->get('blog/{page}', ['as' => 'blog', 'uses' => 'HomeController@viewBlog']);
$app->get('blog/{id_nota}/{name}', ['as' => 'blog_nota', 'uses' => 'HomeController@viewBlogNota']);

//url fijas
$app->get('cybermonday', ['as' => 'cybermonday', function (Request $request,Api $api) use ($app) {
    return $app->make('App\Http\Controllers\ProductosController')->listProductos($request, $id_etiqueta = 15, $id_rubro = 0, $id_subrubro = 0, $name = 'cybermonday', $page = 1, $api);
}]);


//para mandar el email de compra en caso de emergencias
$app->get('email', ['as' => 'email', 'uses' => 'PagoController@enviar_mail']);