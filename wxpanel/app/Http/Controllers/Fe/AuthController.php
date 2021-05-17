<?php
	
	namespace App\Http\Controllers\fe;
	
	use Illuminate\Http\Request;
	use App\AppCustom\Util;
	use App\Http\Controllers\Fe\FeUtilController;
	use App\Http\Controllers\Controller;
	use App\AppCustom\Models\PedidosClientes;
	use Validator;
	use App\AppCustom\Cart;
	use App\AppCustom\Models\PedidosDirecciones;
	use App\AppCustom\Models\Provincias;
	use App\AppCustom\Models\Localidades;

	class AuthController extends Controller
	{
		public function __construct(Request $request)
		{
			parent::__construct($request);
			$this->resource = $request->input('edicion');
			$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		}

		public function login(Request $request){
			$aResult = Util::getDefaultArrayResult();
			
			$mail = $request->input('email');
			$password = $request->input('password');
			$id_cookie = $request->input('id_cookie');
			
			$cliente = PedidosClientes::select('id','nombre', 'apellido', 'mail','contra','confirm_mail','newsletter','telefono','dni')
			->where('mail', $mail)
			->where('habilitado',1)
			->first();
			
			if($cliente){
				if(\Hash::check($password,$cliente->contra)) {
					if($cliente->confirm_mail==0){
						$aResult['data']['status'] = 1;
						$aResult['data']['class']='alert alert-danger alert-dismissable';
						$aResult['data']['noti']='Atención!';
						$aResult['data']['msg'] = 'Su cuenta no fue confirmada, por favor verifique su casilla de correo';
						}else{
						unset($cliente->contra);
						Cart::cartLogin($id_cookie, $cliente->id);	
						$aResult['data']['status'] = 0;
						$aResult['data']['usuario'] = $cliente;
						
					}
				}else{
					if($cliente->contra=='' || $cliente->contra==NULL){
						$aResult['data']['status'] = 2;
					}else{
						$aResult['data']['class']='alert alert-danger alert-dismissable';
						$aResult['data']['noti']='Atención!';
						$aResult['data']['status'] = 1;
						$aResult['data']['msg'] = 'Usuario y/o contraseña incorrecta';
					}						
				}
				}else{
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['status'] = 1;
				$aResult['data']['msg'] = 'Usuario y/o contraseña incorrecta';
			}
			return response()->json($aResult);
		}

		public function registro(Request $request){
			
			$aResult = Util::getDefaultArrayResult();
			
			$data = array(
				'nombre' => $request->input('nombre'),
				'apellido' => $request->input('apellido'),
				'telefono' => $request->input('telefono'),
				'mail' => $request->input('email'),
				'mail_confirmation' => $request->input('reemail'),
				'password' => $request->input('password'),
				'password_confirmation' => $request->input('repassword'),
				'politicas' => $request->input('politicas'),
				'newsletter' => $request->input('newsletter')
			);
			
			$validator = Validator::make($data, [
			'nombre' => 'required|max:255',
			'apellido' => 'required|max:255',
			'telefono' => 'required|max:15',
			'mail' => 'required|email|max:255|confirmed',
			'password' => 'required|min:6|confirmed',
			'politicas' => 'accepted'
			],[
			'nombre.required' => 'El Nombre es requerido',
			'nombre.max' => 'El Nombre no puede contener más de 255 caracteres',
			'apellido.required' => 'El Apellido es requerido',
			'apellido.max' => 'El Apellido no puede contener más de 255 caracteres',
			'telefono.required' => 'El Telefono es requerido',
			'telefono.max' => 'El Telefono no puede contener más de 15 caracteres',
			'mail.required' => 'El E-mail es requerido',
			'mail.max' => 'El E-mail no puede contener más de 255 caracteres',
			'mail.confirmed' => 'La confirmación del E-mail no coincide',
			'password.required' => 'La Contraseña es requerida',
			'password.min' => 'La Contraseña debe tener al menos 6 caracteres',
			'password.confirmed' => 'La confirmación de la Contraseñas no coincide',
			'politicas.accepted' => 'Debe aceptar las políticas de privacidad'
			]);
			
			$itemToUpdate = null;
			
			$validator->after(function($validator) use (&$itemToUpdate){
				if ($validator->errors()->isEmpty()) {
					$item =
					PedidosClientes::
						where('mail','like',$validator->getData()['mail'])
							->first()
					;
					
					if ($item) {
						
						if ($item->registrado) {
							
							if ($item->confirm_mail) {
								$validator->errors()->add('mail', 'El email ya ha sido registrado. Verifique si puede acceder a través de la sección Ingresar,  a la izquierda.');
							} else {
								$validator->errors()->add('mail', 'El email ya ha sido registrado pero aún no se ha confirmado. Por favor verifique su correo y siga las instrucciones.');

							}
							
						} else {
							$itemToUpdate = $item;
						}
						
					}
				}
				
			});
			
			if ($validator->fails()) {
				
				$aResult['data']['status'] = 1;
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['msg'] = $validator->errors();
				
			}else{
				$aResult['data']['status'] = 0;
				$aResult['data']['noti']='Solo queda un paso!';
				$aResult['data']['class']='alert alert-success alert-dismissable';
				$aResult['data']['msg'] = 'Usuario registrado con éxito Se envió un email a su cuenta para confirmar su identidad. Muchas gracias';

				$aData = [
					'nombre' => $data['nombre'],
					'apellido' => $data['apellido'],
					'telefono' => $data['telefono'],
					'mail' => $data['mail'],
					'contra' => bcrypt($data['password']),
					'newsletter' => ($data['newsletter']?1:0),
					'registrado' => 1,
				];
				
				if (!$itemToUpdate) {
					$registro = PedidosClientes::create($aData);
					
					$idCliente = $registro->id;
					
				} else {
					$itemToUpdate->fill($aData);
					$itemToUpdate->save();
					
					$idCliente = $itemToUpdate->id;
				}
				
				$cliente = PedidosClientes::find($idCliente);
				
				if (0 == $cliente->confirmed_mail) {
					$cliente->confirm_token =  \base64_encode(Util::getSomeToken('App\AppCustom\Models\PedidosClientes', 'confirm_token'));
					$cliente->save();
					$email_confirm = FeUtilController::enviarConfirmEmail($cliente);
					$aResult['data']['email_confirm'] = $email_confirm;
					if($email_confirm!=1){
						$cliente->delete();
					}
				}
			}
			
			return response()->json($aResult);
		}
		

		public function emailConfirm(Request $request){
			$aResult = Util::getDefaultArrayResult();
			
			$id_cliente = $request->input('id');
			$token = base64_decode($request->input('token'));
			
			$cliente = PedidosClientes::find($id_cliente);
			if($cliente){
				if($cliente->confirm_token==$token){
					$cliente->confirm_token = '';
					$cliente->confirm_mail = 1;
					$cliente->save();
					$aResult['data']['status'] = 0;
					$aResult['data']['noti']='Notificación! ';
					$aResult['data']['class']='alert alert-success alert-dismissable';
					$aResult['data']['msg'] = '¡Su registro fue completado con éxito!.';
					}else{
					$aResult['data']['status'] = 1;
					$aResult['data']['class']='alert alert-danger alert-dismissable';
					$aResult['data']['noti']='Atención!';
					$aResult['data']['msg'] = 'Lo sentimos, la confirmación no ha podido realizarse satisfactoriamente.';
				}
				}else{
				$aResult['data']['status'] = 1;
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['msg'] = 'Lo sentimos, la confirmación no ha podido realizarse satisfactoriamente.';
			}
			return response()->json($aResult);
		}
		
		public function recuperarPass(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$mail = $request->input('email');
			if (!$mail) {
				$aResult['data']['status'] = 1;
				$aResult['data']['msg'] = 'E-mail no válido';
				}else{
				$recuperar = PedidosClientes::select('id')
				->where('mail', $mail)->where('habilitado',1)->where('confirm_mail',1)
				->first();
				if($recuperar){
					$aResult['data']['status'] = 0;
					//email de confirmacion
					$cliente = PedidosClientes::find($recuperar->id);
					$cliente->forgot_token = Util::getSomeToken('App\AppCustom\Models\PedidosClientes', 'forgot_token');
					$cliente->save();
					
					$email_forgot = FeUtilController::enviarPassForgotEmail($cliente);
					if($email_forgot == 1){
						$aResult['data']['status'] = 0;
						$aResult['data']['msg'] = 'Pedido realizado con éxito, se envió un E-mail a su correo para modificar su contraseña.';
						}else{
						$aResult['data']['status'] = 1;
						$aResult['data']['msg'] = 'Hubo un problema al enviar un correo a su cuenta.';
					}
					}else{
					$aResult['data']['status'] = 1;
					$aResult['data']['msg'] = 'El E-mail ingresado no corresponde a un usuario registrado.';
				}
			}
			return response()->json($aResult);
		}
		
		public function resetPass(Request $request){
			$aResult = Util::getDefaultArrayResult();
			
			$password = $request->input('password');
			$repassword = $request->input('repassword');
			$id_cliente = $request->input('id');
			$token = base64_decode($request->input('token'));
			
			$data = array(
			'password' => $request->input('password'),
			'password_confirmation' => $request->input('repassword')
			);
			
			$validator = Validator::make($data, [
            'password' => 'required|min:6|confirmed',
			],[
			'password.required' => 'La Contraseña es requerida',
			'password.min' => 'La Contraseña debe tener al menos 6 caracteres',
			'password.confirmed' => 'La confirmación de la Contraseñas no coincide'
			]);
			if ($validator->fails()) {
				$aResult['data']['status'] = 1;
				$aResult['data']['msg'] = $validator->errors();
				}else{
				$cliente = PedidosClientes::find($id_cliente);
				if($cliente){
					if($cliente->forgot_token==$token){
						$cliente->forgot_token = '';
						$cliente->contra = bcrypt($password);
						$cliente->save();
						$aResult['data']['status'] = 0;
						$aResult['data']['msg'] = '¡Su contraseña fue modificada con éxito!.';
						}else{
						$aResult['data']['status'] = 1;
						$aResult['data']['msg'] = 'Lo sentimos, la contraseña no se pudo modificar. Modificación no pedida.';
					}
					}else{
					$aResult['data']['status'] = 1;
					$aResult['data']['msg'] = 'Lo sentimos, la contraseña no se pudo modificar. Cliente no econtrado.';
				}
			}
			return response()->json($aResult);
		}
		
		public function direcciones(Request $request){
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$id_usuario = $request->input('id_usuario');
				$items = PedidosDirecciones::
				select(
				'pedidos_direcciones.id',
				'pedidos_direcciones.titulo',
				'pedidos_direcciones.direccion',
				'pedidos_direcciones.numero',
				'pedidos_direcciones.id_provincia',
				'pedidos_direcciones.id_localidad',
				'provincias.provincia',
				'pedidos_direcciones.ciudad',
				'pedidos_direcciones.cp',
				'pedidos_direcciones.informacion_adicional',
				'pedidos_direcciones.telefono'
				)
				->leftJoin('provincias','provincias.id','=','pedidos_direcciones.id_provincia')
				->leftJoin('localidad','localidad.id','=','pedidos_direcciones.id_localidad')
				->where('pedidos_direcciones.id_usuario', $id_usuario)
				->get();
				$aResult['data']['status'] = 0;
				$aResult['data']['data'] = $items;
				}else {
				$aResult['data']['status'] = 1;
				$aResult['data']['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		
		public function direccionesRemove(Request $request){
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$id_usuario = $request->input('id_usuario');
				$id = $request->input('id');
				$direccion = PedidosDirecciones::select('id')->where('id_usuario',$id_usuario)->where('id',$id)->first();
				if($direccion){
					$direccion->delete();
					$aResult['data']['data']=$direccion;
					} else {
					$aResult['status'] = 1;
					$aResult['msg'] = \config('appCustom.messages.itemNotFound');
				}
				}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		
		public function getDireccion(Request $request){
			$aResult = Util::getDefaultArrayResult();
			if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
				$id_usuario = $request->input('id_usuario');
				$id = $request->input('id');
				if($id){
					$direccion = PedidosDirecciones::
					select('pedidos_direcciones.id',
					'pedidos_direcciones.titulo',
					'pedidos_direcciones.direccion',
					'pedidos_direcciones.numero',
					'pedidos_direcciones.piso',
					'pedidos_direcciones.departamento',
					'pedidos_direcciones.id_provincia',
					'pedidos_direcciones.id_localidad',
					'pedidos_direcciones.ciudad',
					'pedidos_direcciones.cp',
					'pedidos_direcciones.informacion_adicional',
					'pedidos_direcciones.telefono')
					->where('id_usuario',$id_usuario)
					->where('id',$id)
					->first();
					if($direccion){
						$aResult['data']['status'] = 0;
						$aResult['data']['noti']='Notificación! ';
						$aResult['data']['class']='alert alert-success alert-dismissable';
						$aResult['data']['direccion'] = $direccion;
						$aResult['data']['msg'] = '';
						$provincia = Provincias::find($direccion->id_provincia);
						$localidades = array('' => 'Seleccionar ciudad') + Localidades::where('id_provincia','=',$provincia->id)->orderBy('nombre')->lists('nombre','id')->toArray();
						$aResult['data']['localidades'] = $localidades;
					}
				}
				$provincias = Provincias::orderBy('provincia')->lists('provincia','id');
				$aResult['data']['provincias'] = $provincias;
				}else {
				$aResult['data']['status'] = 1;
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		
		public function setDireccion(Request $request){
			$aResult = Util::getDefaultArrayResult();
			
			$id_usuario = $request->input('id_usuario');
			$id = $request->input('idE');
			 // Valido si el usuario selecciono la ciudad o ingreso el texto
			 $id_ciudad = '';
			 $id_ciudad = $request->input('ciudad');
			 $localidad = Localidades::find($id_ciudad);
			 if (!$localidad) {
				 $nombre_ciudad = $request->input('ciudad_text');                
			 } else {
				 $nombre_ciudad = $localidad->nombre;
			 }
			
			$data = array(
				'direccion' => $request->input('direccion'),
				'numero' => $request->input('numero'),
				'piso' => $request->input('piso'),
				'departamento' => $request->input('departamento'),
				'id_provincia' => $request->input('id_provincia'),
				'ciudad' => $request->input('ciudad'),
				'cp' => $request->input('cp'),
				'telefono' => $request->input('telefono'),
				'titulo' => $request->input('titulo'),
				'informacion_adicional' => $request->input('informacion_adicional')
			);
			$validator = Validator::make($data, [
				'direccion' => 'required|max:255',
				'numero' => 'required',
				'id_provincia' => 'required',
				'ciudad' => 'required|max:255',
				'cp' => 'required|max:8',
				'telefono' => 'required|max:255',
				'titulo' => 'required|max:255',
			],[
				'direccion.required' => 'La direccion es requerido',
				'direccion.max' => 'La direccion no puede contener más de 255 caracteres',
				'numero.required' => 'El número de dirección es requerido',
				'id_provincia.required' => 'La Provincia es requerida',
				'ciudad.required' => 'La Ciudad es requerida',
				'ciudad.max' => 'La Ciudad no puede contener más de 255 caracteres',
				'cp.required' => 'El Código postal es requerido',
				'cp.max' => 'El Código postal no puede tener más de 8 caracteres',
				'telefono.required' => 'El telefono es requerido',
				'telefono.max' => 'El telefono no puede tener más de 255 caracteres',
				'titulo.required' => 'El Título de referencia es requerido',
				'telefono.max' => 'El Título de referencia no puede tener más de 255 caracteres',
			]);
			if ($validator->fails()) {
				$aResult['data']['status'] = 1;
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['msg'] = $validator->errors();
			}else{
				if($id){
					$direccion = PedidosDirecciones::find($id);
					if($direccion->id_usuario!=$id_usuario){
						$aResult['data']['status'] = 1;
						$aResult['data']['class']='alert alert-danger alert-dismissable';
						$aResult['data']['noti']='Atención!';
						$aResult['data']['msg'] = \config('appCustom.messages.itemNotFound');
					}
					$aResult['data']['direccion'] = $direccion;
				}else{
					$direccion = new PedidosDirecciones;
					$direccion->id_usuario = $id_usuario;
				}
				$direccion->direccion = $data['direccion'];
				$direccion->numero = $data['numero'];
				$direccion->piso = $data['piso'];
				$direccion->departamento = $data['departamento'];
				$direccion->id_provincia = $data['id_provincia'];
				if ($localidad) {
                    $direccion->id_localidad = $localidad->id;
				}
				$direccion->ciudad = $nombre_ciudad;
				$direccion->cp = $data['cp'];
				$direccion->telefono = $data['telefono'];
				$direccion->titulo = $data['titulo'];
				$direccion->informacion_adicional = $data['informacion_adicional'];
				$direccion->save();
				$aResult['data']['status'] = 0;
				$aResult['data']['noti']='';
				$aResult['data']['class']='alert alert-success alert-dismissable';
				$aResult['data']['msg'] = 'Datos guardados exitosamente';
				$aResult['data']['direccion'] = $direccion;
				$provincias = Provincias::orderBy('provincia')->lists('provincia','id');
				$aResult['data']['provincias'] = $provincias;
				$provincia = Provincias::find($direccion->id_provincia);
                $localidades = array('' => 'Seleccionar ciudad') + Localidades::where('id_provincia','=',$provincia->id)->orderBy('nombre')->lists('nombre','id')->toArray();
                $aResult['data']['localidades'] = $localidades;
			}
			return response()->json($aResult);
		}		
		
		public function updatePerfil(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$id_usuario = $request->input('id_usuario');
			$data = array(
				'nombre' => $request->input('nombre'),
				'apellido' => $request->input('apellido'),
				'dni' => $request->input('dni'),
				'telefono' => $request->input('telefono'),
				'mail' => $request->input('email'),
				'mail_confirmation' => $request->input('reemail'),
				'password' => $request->input('password'),
				'password_confirmation' => $request->input('password_confirmation'),
				'newsletter' => $request->input('newsletter')
			);
			$data_valid = array(
				'nombre' => 'required|max:255',
				'apellido' => 'required|max:255',
				'mail' => 'required|email|max:255|unique:pedidos_usuarios,id,'.$id_usuario.'|confirmed'
			);
			$data_valid_msg = array(
				'nombre.required' => 'El Nombre es requerido',
				'nombre.max' => 'El Nombre no puede contener más de 255 caracteres',
				'apellido.required' => 'El Apellido es requerido',
				'apellido.max' => 'El Apellido no puede contener más de 255 caracteres',
				'dni.required' => 'El DNI es requerido',
				'telefono.required' => 'El Telefono es requerido',
				'mail.required' => 'El E-mail es requerido',
				'mail.unique' => 'El E-mail ingresado ya existe',
				'mail.max' => 'El E-mail no puede contener más de 255 caracteres',
				'mail.confirmed' => 'La confirmación del E-mail no coincide',
				'password.required' => 'La Contraseña es requerida',
				'password.min' => 'La Contraseña debe tener al menos 6 caracteres',
				'password.confirmed' => 'La confirmación de la Contraseñas no coincide'
			);
			if($data['password']){
				$data_valid['password'] = 'required|min:6|confirmed';
			}
			$validator = Validator::make($data, $data_valid,$data_valid_msg);
			if ($validator->fails()) {
				$aResult['data']['status'] = 1;
				$aResult['data']['class']='alert alert-danger alert-dismissable';
				$aResult['data']['noti']='Atención!';
				$aResult['data']['data'] = $data;
				$aResult['data']['msg'] = $validator->errors();
			}else{
				$registro = PedidosClientes::find($id_usuario);
				$registro->nombre = $data['nombre'];
				$registro->apellido = $data['apellido'];
				$registro->dni = $data['dni'];
				$registro->telefono = $data['telefono'];
				$registro->mail = $data['mail'];

				if($data['password']){
					$registro->contra = bcrypt($data['password']);
				}
				$registro->newsletter = ($data['newsletter']?1:0);
				$registro->save();
				
				$aResult['data']['status'] = 0;
				$aResult['data']['noti']='';
				$aResult['data']['class']='alert alert-success alert-dismissable';
				$aResult['data']['msg'] = 'Datos guardados exitosamente';
			}
			return response()->json($aResult);
		}
		
		public function getOpiniones(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$Items = array();
			if ($this->user->hasAccess($this->resource . '.update') && $this->filterNote) {
				$aItems = PedidosClientes::select('id','nombre','apellido','opinion')
				->where('destacado',1)
				->where('habilitado',1)
				->whereNotNull('opinion')
				->get();
				
				foreach($aItems as $item){
					$foto = FeUtilController::getImages($item->id,1, 'pedidosClientes');
					$data = array(
						'id' => $item->id,
						'nombre' => $item->nombre.' '.$item->apellido,
						'opinion' => $item->opinion,
						'fotos' => $foto?$foto:''
					);
					array_push($Items, $data);
				}
				$aResult['data'] = $Items;
			}else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
			return response()->json($aResult);
		}
		public function getLocalidad(Request $request){
            $aResult = Util::getDefaultArrayResult();
            $respuesta = array();
            $id = $request->input('id'); // ID provincia seleccionada por el cliente
            if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
                $prov = Provincias::find($id);
                if ($prov) {
                    $localidades = Localidades::select('id','nombre')->where('id_provincia','=',$prov->id)->orderBy('nombre')->get();
                    foreach ($localidades as $localidad) {
                        $data = array(
                            'id'    => $localidad->id,
                            'nombre'=> $localidad->nombre
                        );
                        array_push($respuesta,$data);
                    }
                }
                $aResult['data'] = $respuesta;
            }else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.unauthorized');
            }
            return response()->json($aResult);
		}
		
		public function getUser(Request $request){
			$aResult = Util::getDefaultArrayResult();
			$mail = $request->input('mail');
			if ($mail) {
				
				$recuperar = PedidosClientes::where('mail', $mail)->where('habilitado',1)->first();
				
				if($recuperar){
					$aResult['data']['status'] = 0;
					$aResult['data']['data'] = $recuperar;
				}else{
					$aResult['data']['status'] = 1;
				}

			}else{
				$aResult['data']['status'] = 1;
			}

			return response()->json($aResult);
		}

		public function userRegister(Request $request){
			$aResult = Util::getDefaultArrayResult();
		
			$data = array(
				'nombre' => $request->input('nombre'),
				'apellido' => $request->input('apellido'),
				'mail' => $request->input('email'),
				'fb_id' => $request->input('fb_id'),
				'g_id' => $request->input('g_id')
			);

			$registro = PedidosClientes::create($data);

			$recuperar = PedidosClientes::where('mail', $data['mail'])->where('habilitado',1)->first();
			
			if($recuperar){
				$aResult['data']['status'] = 0;
				$aResult['data']['data'] = $recuperar;
			}else{
				$aResult['data']['status'] = 1;
			}

			return response()->json($aResult);
		}
	}