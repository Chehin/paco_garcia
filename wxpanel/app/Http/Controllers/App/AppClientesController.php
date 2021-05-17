<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use \Authorizer;
use Illuminate\Routing\Controller as BaseController;
use App\AppCustom\Models\ClientesLoginApp;
use App\AppCustom\Models\MensajeDestinatario;
use App\AppCustom\Models\Encuesta;
use App\AppCustom\Models\EncuestaPtos;

class AppClientesController extends BaseController
{
	public $modelName = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {		
		$this->modelName = 'App\AppCustom\Models\Cliente';
    }
	public function getCoordenadas($address, $localidad)
	{
		$url = "http://maps.google.com/maps/api/geocode/json?";
		$params = array(
			"country" => "AR",
			"region" => "AR",
			"locality" => $localidad
		);
		$options = array(
			"sensor"=>"false",
			"address"=>$address
		);
		$url .= http_build_query($options,'','&');
		$url_p = http_build_query($params,'','|');
		$url .= "&components=".str_replace('=',':',$url_p);
		$response = file_get_contents($url);
		$json = json_decode($response,TRUE); //generate array object from the response from the web		 
		if($json['status']=='OK'){
			return ($json['results'][0]['geometry']['location']['lat'].",".$json['results'][0]['geometry']['location']['lng']);
		}else{
			return "0";
		}
	}
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelName = $this->modelName;
        $aResult = Util::getDefaultArrayResult();
		$id = Authorizer::getResourceOwnerId();
		
        $user = $modelName::select('nombre','email','domicilio','ciudad','dni','fecha_nac','geo_latlong','id','piso_of','telefono','tipo_cliente')->find($id);
		if($user){
			$aResult['data'] = $user;
			
			//guardo los datos del dispositivo			
			$login_movil                    = new ClientesLoginApp();
			$login_movil->id_cliente        = $id;
			$login_movil->app_version      	= $request->app_version;
			$login_movil->device_uuid       = $request->cel_code;
			$login_movil->device_cordova    = $request->device_cordova;
			$login_movil->device_model      = $request->device_model;
			$login_movil->device_platform   = $request->device_platform;
			$login_movil->device_version    = $request->device_version;
			$login_movil->status            = 1;
			$login_movil->save();
			
			if($request->regid){
				$user->regid = $request->regid;
				$user->app_version = $request->app_version;
				$user->dispositivo = $request->device_platform;
				$user->save();
			}
		}else{
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.internalError');
		}


        return response()->json($aResult);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        
			$modelName = $this->modelName;
        
            //Validation
			$validator = \Validator::make(
				$request->all(), 
				[
					'nombre' => 'required',
					'email' => 'required|email|unique:clientes,email',
					'fecha_nac' => 'date_format:d/m/Y',
					'password' => 'required|confirmed',
				], 
				[
					'nombre.required' => 'El nombre es requerido',
					'email.unique' => 'El email ingresado ya existe',
					'email.required' => 'El email es requerido',
					'email.email' => 'El email no es válido',
					'fecha_nac.date_format' => 'La fecha no es válida',
					'password.required' => 'La Contraseña es requerida',
					'password.confirmed' => 'Las Contraseñas no coinciden',
				]
			)
			;

            if (!$validator->fails()) {
				
                $resource = new $modelName(
                    [
						'nombre' => $request->input('nombre'),
						'dni' => $request->input('dni'),
						'fecha_nac' => ($request->input('fecha_nac')) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha_nac')) : null,
						'domicilio' => $request->input('domicilio'),
						'ciudad' => $request->input('ciudad'),
						'geo_latlong' => $request->input('latlong'),
						'email' => $request->input('email'),
						'telefono' => $request->input('telefono'),
						'tipo_cliente' => $request->input('tipo_cliente'),
						'piso_of' => $request->input('piso_of'),
						'password' => \md5($request->input('password')),
					]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        
        return response()->json($aResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
		$modelName = $this->modelName;
			
        
        $item = $modelName::find($id);

        if ($item) {
            $validator = \Validator::make(
				$request->all(), 
				[
					'nombre' => 'required',
					'email' => 'required|email|unique:clientes,email,'.$id,
					'fecha_nac_e' => 'date_format:d/m/Y',
				], 
				[
					'nombre.required' => 'El nombre es requerido',
					'email.required' => 'El email es requerido',
					'email.unique' => 'El email ingresado ya existe',
					'email.email' => 'El email no es válido',
					'fecha_nac.date_format' => 'La fecha no es válida',
				]);
				
			$validator->after(function($validator) use ($request) {
				$confirm = $request->input('password_confirmation');
				$pass=$request->input('password');
					
				if ($confirm || $pass) {
					if ($confirm != $pass) {
						$validator->errors()->add('field', 'Las contraseñas no coinciden');
					}
				}
			});

            if (!$validator->fails()) {
				if($request->input('domicilio') != $item->domicilio){
					$item->geo_latlong = $this->getCoordenadas($request->input('domicilio'), $request->input('ciudad'));
					$aResult['geo_latlong'] = $item->geo_latlong;
				}
                $item->fill(
                    [
                        'nombre' => $request->input('nombre'),
						'dni' => $request->input('dni'),
						'fecha_nac' => ($request->input('fecha_nac_e')) ? date_format(\Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha_nac_e')),'Y-m-d') : null,
						'domicilio' => $request->input('domicilio'),
						'ciudad' => $request->input('ciudad'),
						'email' => $request->input('email'),
						'telefono' => $request->input('telefono'),
						'tipo_cliente' => $request->input('tipo_cliente'),
						'piso_of' => $request->input('piso_of'),
                    ]
                );
				
				
				if ($request->input('password_confirmation')) {
					$item->password = \md5($request->input('password'));
				}
				if (!$item->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
				$aResult['msg'] = "Perfil editado con éxito";
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
        
        
		return $aResult;
	}

	public function registro(Request $request)
    {

        $aResult = Util::getDefaultArrayResult();
        
        $modelName = $this->modelName;
        
        //Validation
		$validator = \Validator::make(
			$request->all(), 
			[
				'nombre' => 'required',
				'email' => 'required|email|unique:clientes,email',
				'fecha_nac' => 'date_format:Y-m-d',
				'password' => 'required|confirmed',
			], 
			[
				'nombre.required' => 'El nombre es requerido',
				'email.unique' => 'El email ingresado ya existe',
				'email.required' => 'El email es requerido',
				'email.email' => 'El email no es válido',
				'fecha_nac.date_format' => 'La fecha no es válida',
				'password.required' => 'La Contraseña es requerida',
				'password.confirmed' => 'Las Contraseñas no coinciden',
			]
		)
		;

        if (!$validator->fails()) {
			//tipo clientes
			if($request->input('tipo_cliente')==1){
				$_uhab = 1;
				$aResult['msg'] = "Sus datos fueron registrados con éxito. Se envió un email a su cuenta para confirmar su identidad.";
			}else{
				$_uhab = 0;
				$aResult['msg'] = "Sus datos fueron registrados con éxito. Se envió un email a su cuenta para confirmar su identidad. Un administrativo validará sus datos y será habilitado.";
			}			
            
			//geolocation
			$geo_latlong = '';
			if($request->input('ciudad') || $request->input('domicilio')){
				$geo_latlong = $this->getCoordenadas($request->input('domicilio'), $request->input('ciudad'));
			}
			$resource = new $modelName(
                [
					'nombre' => $request->input('nombre'),
					'dni' => $request->input('dni'),
					'fecha_nac' => ($request->input('fecha_nac') ? $request->input('fecha_nac') : null),
					'domicilio' => $request->input('domicilio'),
					'ciudad' => $request->input('ciudad'),
					'geo_latlong' => $request->input('latlong'),
					'email' => $request->input('email'),
					'telefono' => $request->input('telefono'),
					'tipo_cliente' => $request->input('tipo_cliente'),
					'piso_of' => $request->input('piso_of'),
					'password' => \md5($request->input('password')),
					'habilitado' => $_uhab,
					'geo_latlong' => $geo_latlong
				]
               );
               if (!$resource->save()) {
					$aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }else{
					if ($cliente = $modelName::find($resource->id)) {
						if (0 == $cliente->confirmed_mail) {
							$cliente->confirm_token = Util::getSomeToken($modelName, 'confirm_token');
							$cliente->save();
							
							\App\Http\Controllers\ClientesUtilController::enviarConfirmEmail($cliente);
						}
					}
				}
				
        } else {
			$aResult['status'] = 1;
			$aResult['msg'] = $validator->errors()->all();
		}
        
        return response()->json($aResult);
    }

	public function registrar_regid(Request $request)
	{
		$aResult = Util::getDefaultArrayResult();
		$modelName = $this->modelName;
		
		$id = $request->input('id_cliente');
		$dispositivo = $request->input('type');
		$regId = $request->input('token');
		$app_version = $request->input('app_version');
		
		if($id){
			$cliente = $modelName::find($id);
			if($cliente){
				$cliente->regid = $regId;
				$cliente->app_version = $app_version;
				$cliente->dispositivo = $dispositivo;
				$cliente->save();
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.itemNotFound');
			}
		}else{
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.itemNotFound');
		}
		return response()->json($aResult);
	}
	
	public function devices_ready(Request $request)
	{
		$modelName = $this->modelName;
		$resultados['logout'] = false;//esta habilitado el usuario?
		$resultados['mensaje'] = false;//tiene algun mensaje para mostrar?
		$resultados['cerrar_sesion'] = false;//fuerzo a cerrar la sesion
		$id_cliente = $request->input('id_cliente');
		$cel_code = $request->input('cel_code');
		
		$cliente = $modelName::find($id_cliente);
		if($id_cliente){
			//valido si todavia está habilitado el usuario que esta logeado
			if($cliente->habilitado==0){
				$resultados['logout'] = true;
			}elseif($cel_code){
				$cel_val = ClientesLoginApp::
				select('device_uuid')
				->orderBy('id','desc')
				->where('id_cliente',$id_cliente)
				->first();
				if($cel_val->device_uuid != $cel_code){
					$resultados['logout'] = true;
				}
			}elseif($cliente->logoutapp==1){ //fuerzo a cerrar la sesion
				$resultados['forzar_cerrar'] = true;
				$usuario->logoutapp = 0;
				$usuario->save();
			}
			//No es necesario el dato de cuantos mensajes tengo pendiente ya que en la app no hay un menu de mensajes
			$resultados['mensaje'] = false;
		}else{
			$resultados['logout'] = true;
		}
		return response()->json($resultados);
	}
	
	public function mensaje_leido(Request $request)
	{
		$aResult = Util::getDefaultArrayResult();
		
		$id_msg = $request->input('id_mensaje');
		$id_cliente = $request->input('id_cliente');
		$reg = MensajeDestinatario::select('id')
		->where('id_cliente',$id_cliente)
		->where('id_mensaje',$id_msg)
		->first();
		$mensaje = MensajeDestinatario::find($id_msg);
		if($mensaje){
			$mensaje->push_leido = 1;
			$mensaje->save();
		}
		return response()->json($aResult);
	}

	public function passwordForgot(Request $request)
	{
		//Validation
		$validator = \Validator::make(
			$request->all(), 
			array(
				'email' => 'required|email',
			), 
			array(
				'email.required' => 'El E-mail es requerido',
				'email.email' => 'El E-mail no es válido',
			)
		);
		if (!$validator->fails()) {
			$modelName = $this->modelName;

			if ($cliente = $modelName::where('email', $request->input('email'))->first()) {
				$cliente->forgot_token = Util::getSomeToken($modelName, 'forgot_token');
				$cliente->save();

				if(\App\Http\Controllers\ClientesUtilController::enviarPassForgotEmail($cliente)){
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Se envió un E-mail a su casilla de correo, donde podrá restablecer su contraseña';
				}else{
					$aResult['status'] = 1;
                    $aResult['msg'] = 'Hubo un problema al enviar el email a su casilla. Por favor intente de nuevo mas tarde.';
				}
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = 'El E-mail ingresado no corresponde a un cliente registrado';
			}
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = $validator->errors()->all();
		}
		return response()->json($aResult);
	}
	public function encuestaGet()
	{
		$items = Encuesta::all();
		return response()->json($items);
	}
	public function encuestaPost(Request $request)
	{
		$aResult = Util::getDefaultArrayResult();
		$resultado = $request->input('resultado');
		foreach($resultado as $resu){
			if($resu['rating'] > 0){
				$encuesta = new EncuestaPtos;
				$encuesta->id_encuesta = $resu['id'];
				$encuesta->id_cliente = $request->input('id_cliente');
				$encuesta->puntos = $resu['rating'];
				$encuesta->save();
			}
		}
		$aResult['msg'] = "¡Muchas gracias por ayudarnos a mejorar!";
		return response()->json($aResult);
	}
}