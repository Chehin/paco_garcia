<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;


class CuentaController extends Controller
{
    
    public function login(Request $request,Api $api){
        $pageTitle = env('SITE_NAME') . " - Ingresar";
        $this->view_ready($api);
        return view('cliente.login', ['data'=>'','pageTitle'=>$pageTitle]);
    }

    public function sendLogin(Request $request, Api $api){

        $pageTitle = env('SITE_NAME') . " - Ingresar";
        $this->view_ready($api);
	    $id_cookie = isset($_COOKIE["id_usuario_".env('APP_NAME')])?$_COOKIE["id_usuario_".env('APP_NAME')]:'';
        if($_SESSION['id_user']>0){
            $id_cookie = '';
        }

        $array_login = array(
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'id_cookie' => $id_cookie
        );
        
        $res=Util::aResult();
        $data = array();

        if(isset($request['desdelogin'])){
            $array_registro = array(
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'email' => $request->input('email'),
                'reemail' => $request->input('reemail'),
                'password' => $request->input('password'),
                'repassword' => $request->input('repassword'),
                'politicas' => $request->input('politicas'),
                'newsletter' => $request->input('newsletter')
            );
            
            $data=Util::aResult();
            try {
                $post = http_build_query($array_registro);
                $data  = $api->client->resJson('GET','registro?'.$post);

                    if($data['status']==0){
                        return view('cliente.login', ['dataReg'=>$data['data'],'pageTitle'=>$pageTitle]);
                    }else{            
                            return view('cliente.login', ['dataReg'=>$data['data'],'pageTitle'=>$pageTitle]);
                    }
                    
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }

        }else{
            try {
                $post = http_build_query($array_login);
                $res = $api->client->resJson('GET', 'login?'.$post);
            
                if ($res['data']['status'] == 0){
                    $data = $res['data'];
                    $_SESSION['id_user'] = $data['usuario']['id']; 
                    $_SESSION['nombre'] = $data['usuario']['nombre'];
                    $_SESSION['apellido'] = $data['usuario']['apellido'];
                    $_SESSION['email'] = $data['usuario']['mail'];
                    $_SESSION['dni'] = $data['usuario']['dni'];
                    $_SESSION['telefono'] = $data['usuario']['telefono'];
                    if(isset($_SESSION['carrito']['carrito'][0])){
                        return redirect('cart');
                    }else{
                        return redirect('/');
                    }
                }else{
                    if ($res['data']['status'] == 2){
                        $_SESSION['status'] = 2;
                        if($_SESSION["id_user"]){
                            return redirect('cuenta');
                        }
                        return redirect('recuperar_pass');
                    }else{
                        $data = $res['data'];
                        return view('cliente.login', compact('data','pageTitle'));
                    }
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }     
        }
    }

    public function registro(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Registro";
        $this->view_ready($api);
        return view('cliente.registro',['data'=>'','pageTitle'=>$pageTitle]);
    }

    public function sendRegistro(Request $request, Api $api){
      
        $pageTitle = env('SITE_NAME') . " - Registro";
        $this->view_ready($api);
        
        $array_registro = array(
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'telefono' => $request->input('telefono'),
			'email' => $request->input('email'),
			'reemail' => $request->input('reemail'),
			'password' => $request->input('password'),
			'repassword' => $request->input('repassword'),
			'politicas' => $request->input('politicas'),
			'newsletter' => $request->input('newsletter')
        );

        $data=Util::aResult();
        try {
            $post = http_build_query($array_registro);
            $data  = $api->client->resJson('GET','registro?'.$post);

                if($data['status']==0){
                    return view('cliente.registro', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }else{            
                        return view('cliente.registro', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }
                   
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function mailconfirmed(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Confirmación de cuenta";
        $this->view_ready($api);

        if ($request->mailConfirmed=='yes' && $request->i && $request->k) {
            $array_send = array(
                'id' => $request['i'],
                'token' => $request['k']
            );
        }

        $data =Util::aResult();

        try {
            $post = http_build_query($array_send);
            $data = $api->client->resJson('GET','emailConfirm?'.$post);
            if($data['status']==0){
                return view('cliente.mailconfirmed', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
             }else{
                return view('cliente.mailconfirmed', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
             }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function cuenta(Request $request,Api $api){
        $pageTitle = env('SITE_NAME') . " - Cuenta";
        $this->view_ready($api);
        return view('cliente.cuenta', ['data'=>'','pageTitle'=>$pageTitle]);
    }

    public function perfil(Request $request,Api $api){
        
        $pageTitle = env('SITE_NAME') . " - Perfil";
        $this->view_ready($api);
        return view('cliente.perfil', ['data'=>'','pageTitle'=>$pageTitle]);
    }

    public function sendPerfil(Request $request, Api $api){
    
        $pageTitle = env('SITE_NAME') . " - Perfil";
        $this->view_ready($api);

        $item = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_usuario'=> $_SESSION["id_user"],
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
            'dni' => $request['dni'],
            'telefono' => $request['telefono'],
			'email' => $request['email'],
			'reemail' => $request['reemail'],
			'password' => $request['password'],
            'password_confirmation' => $request['repassword'],
            'newsletter' => isset($request['newsletter'])
        );

        $data=Util::aResult();

        try {
            $post = http_build_query($item);
            $data = $api->client->resJson('GET', 'updatePerfil?'.$post);
             if($data['status']==1){
                    return view('cliente.perfil', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }else{
                    $_SESSION['nombre'] = $request->input('nombre');
                    $_SESSION['apellido'] = $request->input('apellido');
                    $_SESSION['email'] = $request->input('email');
                    $_SESSION['dni'] = $request->input('dni');
                    $_SESSION['telefono'] = $request->input('telefono');
                    
                    return view('cliente.perfil', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function recuperar_pass(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - ¿Olvid&oacute; su contrase&ntilde;a?";
        $_error_forgot='';
        $_success_forgot='';

		if($_SESSION["id_user"]){
            return redirect('cuenta');
        }
        if($request['email']){
            $array_send = array(
                'email' => $request['email']
            );
            $data=Util::aResult();
            $_SESSION['status'] = 1;
            try {
                $post = http_build_query($array_send);
                $data = $api->client->resJson('GET', 'recuperarPass?'.$post);
                if($data['status']==1){
                        $_error_forgot = $data['data']['msg'];
                    }else{
                        $_success_forgot = $data['data']['msg'];
                    }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        }else{
            if(!isset($_SESSION['status'])){
                $_SESSION['status'] = 1;
            }
        }
        $this->view_ready($api);
        return view('cliente.recuperar_pass', ['_error_forgot'=> $_error_forgot,'_success_forgot'=>$_success_forgot,'pageTitle'=>$pageTitle]);
    }
    public function reset_password(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Restablecer contrase&ntilde;a?";
        $_error_forgot='';
        $_success_forgot='';
        $_error_reset='';
        $_success_reset='';
        $_no_reset='';
        if($request['password'] && $request['repassword'] && $request['i'] && $request['k']){
            $array_send = array(
                'password' => $request['password'],
                'repassword' => $request['repassword'],
                'id' => $request['i'],
                'token' => $request['k']
            );
            $data=Util::aResult();
            try {
                $post = http_build_query($array_send);
                $data = $api->client->resJson('GET', 'resetPass?'.$post);
                if($data['status']==1){
                        $_error_reset = $data['data']['msg'];
                    }else{
                        $_success_reset = $data['data']['msg'];
                        $_no_reset = true;
                    }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }			
        }
        if($request['passRestore']!='yes' || !$request['i'] || !$request['k']){
            $_error_reset = 'Lo sentimos, el proceso no ha podido realizarse satisfactoriamente.';
            $_no_reset = true;
        }
        $this->view_ready($api);
        return view('cliente.reset_password', ['_no_reset'=>$_no_reset ,'_success_reset'=>$_success_reset,'_error_reset'=>$_error_reset,'_error_forgot'=> $_error_forgot,'_success_forgot'=>$_success_forgot,'pageTitle'=>$pageTitle]);
    }

    public function historial(Request $request, Api $api){

        $pageTitle = env('SITE_NAME') . " - Historial de Pedidos";

        $array_send = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
			'edicion' => 'pedidos',
            'id_usuario' => $_SESSION['id_user']
        );

        $pedidosHistory=Util::aResult();
        $this->view_ready($api);
        try {
            $post = http_build_query($array_send);
            $pedidosHistory = $api->client->resJson('GET', 'cartGetHistory?'.$post);
            if($pedidosHistory['status']==1){
                return view('cliente.historial', ['pedidosHistory'=>'','pageTitle'=>$pageTitle]);
            }else{
                return view('cliente.historial', ['pedidosHistory'=>$pedidosHistory['data'],'pageTitle'=>$pageTitle]);
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function direcciones(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Agregar Direcciones";
        
        $array_send = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
			'edicion' => 'pedidos',
            'id_usuario' => $_SESSION['id_user']
        );
        $this->view_ready($api);
        $data=Util::aResult();

        try {
            $post = http_build_query($array_send);
            $data = $api->client->resJson('GET', 'direcciones?'.$post);
            if($data['status']==0){
               return view('cliente.direcciones', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
            }else{
               return view('cliente.direcciones', ['data'=>'','pageTitle'=>$pageTitle]);
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function addDir(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Agregar Direcciones";
        $item = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_usuario'=> $_SESSION['id_user']
        );
        $this->view_ready($api);
        $direcciones=Util::aResult();

        try {
            $post = http_build_query($item);
            $direcciones = $api->client->resJson('GET', 'getDireccion?'.$post);
            if ($direcciones['status'] == 0){           
                return view('cliente.agregar_direcciones', ['data'=>'','pageTitle'=>$pageTitle])->with('direcciones', $direcciones['data']);       
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function sendDir(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Agregar Direcciones";
        $id=$request->input('id');
        $idE=$request->input('idE');
        $this->view_ready($api);

        $data_send = array(
                'id'=> $id,
                'idE'=> $idE,
                'id_usuario'=> $_SESSION['id_user'],
				'direccion' => $request->input('direccion'),
				'numero' => $request->input('numero'),
				'piso' => $request->input('piso'),
				'departamento' => $request->input('departamento'),
				'id_provincia' => $request->input('provincia'),
				'ciudad' => $request->input('ciudad'),
				'cp' => $request->input('cp'),
				'telefono' => $request->input('telefono'),
				'titulo' => $request->input('titulo'),
				'informacion_adicional' => $request->input('informacion_adicional')
        );
      
        //direcciones
        $item = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id_usuario'=> $_SESSION['id_user']
        );

        $direcciones=Util::aResult();

        try {
            $postD = http_build_query($item);
			$direcciones = $api->client->resJson('GET','getDireccion?'.$postD)['data'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        //

        $data=Util::aResult();

        try {
            $post = http_build_query($data_send);
            $data = $api->client->resJson('GET', 'setDireccion?'.$post);
        
            if($data['status']==0){    
                if ($request->input('returnTo')) {
                    return redirect(strip_tags($_REQUEST['returnTo']).'?id=1');
                }else{
                    if($id)  
                return view('cliente.editar_direccion', ['data'=>$data,'pageTitle'=>$pageTitle])->with('direcciones', $direcciones);
                    else
                return view('cliente.agregar_direcciones', ['data'=>$data['data'],'pageTitle'=>$pageTitle])->with('direcciones', $direcciones);
                }
            }else{              
                return view('cliente.agregar_direcciones', ['data'=>$data['data'],'pageTitle'=>$pageTitle])->with('direcciones', $direcciones);
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function deleteDir(Request $request, Api $api){
        
        $pageTitle = env('SITE_NAME') . " - Borrar Direcciones";
        
        $array_send = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
			'edicion' => 'pedidos',
            'id_usuario' => $_SESSION['id_user']
        );
        $this->view_ready($api);
        if($request['remove']){
            $itemRemove = array(
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'id_usuario' => $_SESSION['id_user'],
                'id' => $request['remove']
            ); 

            $resR=Util::aResult();

            try {
                $postR = http_build_query($itemRemove);   
                $resR= $api->client->resJson('GET', 'direccionesRemove?'.$postR);
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }

            $data=Util::aResult();

            try {
                $post = http_build_query($array_send);
                $data = $api->client->resJson('GET', 'direcciones?'.$post);
                if($data['status']==0){
                   return view('cliente.direcciones', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }else{
                   return view('cliente.direcciones', ['data'=>'','pageTitle'=>$pageTitle]);
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
    }
}

    public function editDir(Request $request, Api $api){
        $pageTitle = env('SITE_NAME') . " - Editar Direcciones";

        $id = $request['id'];
        $this->view_ready($api);
        //post
        if($request->input('direccion')!=NULL){
            $data_send = array(
                'idE' => $id,
                'id_usuario'=> $_SESSION["id_user"],
				'direccion' => $request->input('direccion'),
				'numero' => $request->input('numero'),
				'piso' => $request->input('piso'),
				'departamento' => $request->input('departamento'),
				'id_provincia' => $request->input('provincia'),
				'ciudad' => $request->input('ciudad'),
				'cp' => $request->input('cp'),
				'telefono' => $request->input('telefono'),
				'titulo' => $request->input('titulo'),
				'informacion_adicional' => $request->input('informacion_adicional')
            );
            
            $data=Util::aResult();

            try {
                $post = http_build_query($data_send);
                $data= $api->client->resJson('GET', 'setDireccion?'.$post);
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }

            //direcciones
            $item = array(
                'idE'=> $id,
                'id_edicion' => 'MOD_PEDIDOS_FILTER',
                'edicion' => 'pedidos',
                'id_usuario'=> $_SESSION['id_user']
            );

             $direcciones=Util::aResult();

            try {
                $postD = http_build_query($item);
                $direcciones = $api->client->resJson('GET', 'getDireccion?'.$postD)['data'];
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
            //
                if($data['status']==0){
                    return view('cliente.editar_direccion', ['data'=>$data['data'],'pageTitle'=>$pageTitle])->with('direcciones', $direcciones);
                }else{
                    return view('cliente.editar_direccion', ['data'=>$data['data'],'pageTitle'=>$pageTitle])->with('direcciones', $direcciones);
                }
        }else{
            
            $item = array(
				'id_edicion' => 'MOD_PEDIDOS_FILTER',
				'edicion' => 'pedidos',
				'id' => $id,
                'id_usuario'=> $_SESSION['id_user']
            );
            $data=Util::aResult();

            try {
                $post = http_build_query($item);
                $data= $api->client->resJson('GET', 'getDireccion?'.$post);
                if($data['status']==0){
                    return view('cliente.editar_direccion', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }else{
                    return view('cliente.editar_direccion', ['data'=>$data['data'],'pageTitle'=>$pageTitle]);
                }
            } catch (RequestException $e) {
                Log::error(Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
    
    public function getLocalidad(Request $request, Api $api){
        $array_te = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
            'edicion' => 'pedidos',
            'id' => $_GET['id'],
        );
        $data=Util::aResult();

        try {
            $post = http_build_query($array_te);
            $data = $api->client->resJson('GET', 'getLocalidad?'.$post);
             if($data['status']==1){
                    return $data['msg'];
                }else{    
                    return $data['data'];
                }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }

    public function logout(){
        session_destroy();
        return redirect('/');
    }

    public function tracking(Request $request,Api $api){
        
        $pageTitle = env('SITE_NAME') . " - Tracking";

        $array_send = array(
            'id_edicion' => 'MOD_PEDIDOS_FILTER',
			'edicion' => 'pedidos',
            'id_usuario' => $_SESSION['id_user']
        );

        $pedidosHistory=Util::aResult();
        $this->view_ready($api);

        try {
            $post = http_build_query($array_send);
            $pedidosHistory = $api->client->resJson('GET', 'cartGetHistory?'.$post);
            if($pedidosHistory['status']==1){
                return view('cliente.tracking', ['pedidosHistory'=>'','pageTitle'=>$pageTitle]);
            }else{
                return view('cliente.tracking', ['pedidosHistory'=>$pedidosHistory['data'],'pageTitle'=>$pageTitle]);
            }
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
    }
}