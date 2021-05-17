<?php
	/**
		* Description of Util
		*
		* @author martinm
	*/
	
	namespace App\AppCustom;
	
	use DB;
	use App\AppCustom\Models\FrontLanguage;
	use App\AppCustom\Models\Category;
	use App\AppCustom\Models\Sentinel\User;
	use App\AppCustom\Models\Rubros;
	use App\AppCustom\Models\SubRubros;
	use App\AppCustom\Models\SubSubRubros;
	use App\AppCustom\Models\Etiquetas;
	use App\AppCustom\Models\EtiquetasNotas;
	use App\AppCustom\Models\Monedas;
	use App\AppCustom\Models\Listas;
	use App\AppCustom\Models\PedidosProductos;
	use App\AppCustom\Models\Productos;
	use App\AppCustom\Models\PreciosProductos;
	use App\AppCustom\Models\Talles;
	use App\AppCustom\Models\CodigoStock;
	use App\AppCustom\Models\Marcas;
	use App\AppCustom\Models\Deportes;
	use App\AppCustom\Models\ProductosCodigoStock;
	use App\AppCustom\Models\SucursalesStock;
	use App\AppCustom\Models\Genero;
	use App\AppCustom\Models\TemplatesEditables;
	use App\AppCustom\Models\Mailling;
	use App\AppCustom\Models\Campaign;
	use App\AppCustom\Models\EmailTracking;
	use App\AppCustom\Models\LinkTracking;
	use App\AppCustom\Models\CampaignTesting;
	use App\AppCustom\Models\CampaignListas;
	use App\AppCustom\Models\CampaignTestingLista;
	use App\AppCustom\Models\MktListas;
	use App\AppCustom\Models\MktListasPersonas;
	use App\AppCustom\Models\MaillingTesting;
	use App\AppCustom\Models\ConfTallesEquivalencias;
	header("Content-Type: text/html;charset=utf-8");
	
	class Util {
		
		static $aMonths = [
			1 => 'Ene',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Abr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Ago',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dic',
		];
		//TODO: remove this
		static $messages = array(
        'unauthorized' => 'No se tiene permisos para ejecutar la operación',
        'dbError' => 'Error de BD',
        'itemNotFound' => 'No se ha encontrado el elemento',
		);
		
		
		static function getDefaultArrayResult() {
			return [
            'status' => 0,
            'msg'    => 'ok',
            'html'  => '',
            'data'   => [],
			];
		}
		//TODO: remove this
		static function getMenus() {
			
			$menus = 
            \DB::table('sys_menus')
			->select(
			'id_menu', 
			'menu'
			)
			->orderBy('orden', 'asc')
			->where('habilitado', 1)
			->get()
            ;
			
			foreach ($menus as &$menu) {
				$menu->aSubmenu = 
                \DB::table('sys_submenus')
				->select(
				'id_submenu', 
				'submenu'
				)
				->orderBy('orden', 'asc')
				->where('id_menu', $menu->id_menu)
				->where('habilitado', 1)
				->get()
                ;
			}
			
			return $menus;
		}
		
		static function getRubros($returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$rubros = Productos::
				selectRaw('inv_rubros.id, inv_rubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_rubros','inv_rubros.id','=','inv_productos.id_rubro')
				->where('inv_productos.habilitado',1)
				->where('inv_rubros.habilitado',1)
				->orderBy('inv_rubros.orden')
				->groupBy('inv_rubros.id')
				->get();
			}else{
				$rubros = 
				Rubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($rubros->toArray());
				}elseif ('array' == $returnAs) {
				return $rubros->toArray();
				} else {
				return true;
			}
		}

		static function getFiltroRubros() {
     	   return Rubros::select('id','nombre')->where('habilitado', 1)->orderBy('nombre')->get(); 
    	}
		
		static function getFiltroSubRubros($id) {

		   if($id!=0){
		   		return SubRubros::select('id','nombre')->where('habilitado', 1)
		   					 	->where('id_rubro',$id)
		   					 	->orderBy('nombre')
		   					 	->get();
		   }else {
		   		return SubRubros::select('id','nombre')->where('habilitado', 1)
		   						->orderBy('nombre')
		   						->get(); 
		   }
     	   
		}
		
		static function getSubRubros($id, $returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$subrubros = Productos::
				selectRaw('inv_subrubros.id, inv_subrubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_subrubros','inv_subrubros.id','=','inv_productos.id_subrubro')
				->where('inv_productos.habilitado',1)
				->where('inv_subrubros.habilitado',1)
				->where('inv_productos.id_rubro',$id)
				->orderBy('inv_subrubros.orden')
				->groupBy('inv_subrubros.id')
				->get();
			}else{
				$subrubros = 
				SubRubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->where('id_rubro',$id)
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($subrubros->toArray());
				}elseif ('array' == $returnAs) {
				return $subrubros->toArray();
				} else {
				return true;
			}
		}
		
		static function getSubSubRubros($id, $returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$subrubros = Productos::
				selectRaw('inv_subsubrubros.id, inv_subsubrubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_subsubrubros','inv_subsubrubros.id','=','inv_productos.id_subsubrubro')
				->where('inv_productos.habilitado',1)
				->where('inv_subsubrubros.habilitado',1)
				->where('inv_productos.id_rubro',$id)
				->orderBy('inv_subsubrubros.orden')
				->groupBy('inv_subsubrubros.id')
				->get();
			}else{
			$subrubros = 
				SubSubRubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->where('id_subrubro',$id)
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($subrubros->toArray());
				}elseif ('array' == $returnAs) {
				return $subrubros->toArray();
				} else {
				return true;
			}
		}
		
		static function getMarcas($returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$marcas = Productos::
				selectRaw('conf_marcas.id, conf_marcas.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')
				->where('inv_productos.habilitado',1)
				->where('conf_marcas.habilitado',1)
				->orderBy('conf_marcas.destacado','desc')
				->groupBy('conf_marcas.id')
				->get();
			}else{
				$marcas = 
				Marcas::select('id', 'nombre as text')
				->where('habilitado','1')
				->orderBy('destacado','desc')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($marcas->toArray());
				}elseif ('array' == $returnAs) {
				return $marcas->toArray();
				} else {
				return true;
			}
		}

		static function filtroMarcas(){
			return Marcas::where('habilitado', 1)->orderBy('nombre')->get(); 
		}

		static function getEtiquetas($returnAs = 'json') {
			
			$etiquetas = 
            Etiquetas::select('id', 'nombre as text')
			->where('habilitado','1')
			->get()
			;
			
			if ('json' == $returnAs) {
				return response()->json($etiquetas->toArray());
			}elseif ('array' == $returnAs) {
				return $etiquetas->toArray();
			} else {
				return true;
			}
		}

		static function getEtiquetasBlog($returnAs = 'json') {
			
			$etiquetas = 
            EtiquetasNotas::select('id', 'nombre as text')
			->where('habilitado','1')
			->get()
			;
			
			if ('json' == $returnAs) {
				return response()->json($etiquetas->toArray());
			}elseif ('array' == $returnAs) {
				return $etiquetas->toArray();
			} else {
				return true;
			}
		}
		
		static function getDeportes($returnAs = 'json' ,  $filproductos = false) {
	
			if($filproductos){
				$etiquetas = Deportes::
				selectRaw('inv_deportes.id, inv_deportes.nombre as text, count(inv_productos_deportes.id_producto) as cantidad')
				->Join('inv_productos_deportes','inv_deportes.id','=','inv_productos_deportes.id_deporte')
				->where('inv_deportes.habilitado',1)
				->groupBy('inv_deportes.id')
				->get();
			}else{
			
				$etiquetas = 
				Deportes::select('id', 'nombre as text')
				->where('habilitado','1')
				->get()
				;
			}
			
			if ('json' == $returnAs) {
				return response()->json($etiquetas->toArray());
			}elseif ('array' == $returnAs) {
				return $etiquetas->toArray();
			} else {
				return true;
			}
		}

		static function getRangoPrecios($returnAs = 'json') {
	
		
				$precios = PreciosProductos::
				selectRaw('CASE WHEN (inv_precios.precio_venta BETWEEN 0 AND 1000) 
								THEN "Hasta 1000 ,0-1000" 
							ELSE CASE WHEN (inv_precios.precio_venta BETWEEN 1000 AND 2000) 
									THEN "1000 - 2000,1000-2000"
								ELSE CASE WHEN (inv_precios.precio_venta >= 2000)
										THEN "Más de 2000,2000-99999" 
								END 
						   END
						   END text, COUNT(*) cantidad')
				->Join('inv_productos','inv_productos.id','=','inv_precios.id_producto')
				->where('inv_productos.habilitado',1)
				->groupBy('text')
				->orderBy('text','desc')
				->get();
			
			
			if ('json' == $returnAs) {
				return response()->json($precios->toArray());
			}elseif ('array' == $returnAs) {
				return $precios -> toArray();
			} else {
				return true;
			}
		}
		
		static function orderString($string) {
			$stringParts = str_split($string);
			sort($stringParts);
			return implode('', $stringParts);
		}
		
		static function getLanguages() {
			return FrontLanguage::where('habilitado', 1)->get();
		}
		
		static function getCategories() {
			return Category::where('habilitado', 1)->get();
		}
		static function getCategorie($id) {
			return Category::where('habilitado', 1)->where('id_seccion', $id)->first();
		}
		
		static function uploadBase64File($path, $fileName, $base64File, $thumbProportion) {
			
			$data = explode(',', $base64File);
			
			$im = imagecreatefromstring(base64_decode($data[1]));
			if ($im !== false) {
				
				$fileNameFull = $path . $fileName;
				//image file generate
				imagejpeg($im, $fileNameFull);
				imagedestroy($im);
				
				if (file_exists($fileNameFull)){
					//image file thumb generate
					$im=imagecreatefromjpeg($fileNameFull); 
					$width=ImageSx($im);              // Original picture width is stored
					$height=ImageSy($im);             // Original picture height is stored
					
					
					/* $b_width = 800;
					$b_height = 800;					
					$newimage_b=imagecreatetruecolor($b_width,$b_height);                 
					imageCopyResized($newimage_b,$im,0,0,0,0,$b_width,$b_height,$width,$height);
					imagejpeg($newimage_b,$path . '800_' .$fileName ); */
					
					$n_width = $width * $thumbProportion;
					$n_height = $height * $thumbProportion;
					
					$newimage=imagecreatetruecolor($n_width,$n_height);                 
					imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
					imagejpeg($newimage,$path . 'th_' .$fileName );
										
					//app img
					$a_width = $width * 0.4;
					$a_height = $height * 0.4;
					
					$newimage_a=imagecreatetruecolor($a_width,$a_height);                 
					imageCopyResized($newimage_a,$im,0,0,0,0,$a_width,$a_height,$width,$height);
					imagejpeg($newimage_a,$path . 'app_' .$fileName );
					
				}
				
				} else {
				throw new Exception('imagecreatefromstring() fail');
			}
		}
		
		static function truncateString($string,$length=100,$append="&hellip;") {
			$string = \trim($string);
			
			if(strlen($string) > $length) {
				$string = \wordwrap($string, $length);
				$string = \explode("\n", $string, 2);
				$string = $string[0] . $append;
			}
			
			return $string;
		}
		
		static function getForgotToken() {
			
			do {
				$tokenKey = \Hash::make(\str_random(50) . '_' . time());
			} while (User::where("forgot_token", "=", $tokenKey)->first() instanceof User);
			
			return $tokenKey;
			
		}
		
		static function getSomeToken($modelName, $field, $strSize = 50) {
			
			do {
				$tokenKey = \Hash::make(\str_random($strSize) . '_' . time());
			} while ($modelName::where($field, "=", $tokenKey)->first() instanceof $modelName);
			
			return $tokenKey;
			
		}
		
		static function getSomeString($modelName, $field, $strSize = 25) {
			
			do {
				$str = \str_random($strSize);
			} while ($modelName::where($field, "=", $str)->first() instanceof $modelName);
			
			return $str;
			
		}
		
		static function getLogos($idCompany) {
			
			return [
			'logo'        => sprintf(config('appCustom.logos.logo'), $idCompany),
			'logoEmail64' => sprintf(config('appCustom.logos.logoEmail64'), $idCompany),
			];
			
		}
		
		static function getLogosByCompanyId($idCompany) {
			
			$aLogos = static::getLogos($idCompany);
			
			return [
			'logo' => file_exists($aLogos['logo']) ? $aLogos['logo'] : config('appCustom.logos.logoDefault'),
			'logoEmailB64' => file_exists($aLogos['logoEmail64']) ? \file_get_contents($aLogos['logoEmail64']) : \file_get_contents(config('appCustom.logos.logoEmail64Default')),
			
			];
		}

		static function getIdByName($name) {
			
			$id = MktListas::select('id')
			->where('habilitado','1')
			->where('nombre','=',$name)
			->first()
			;
				return $id;
		}
		
		
		static function getLogosByCompany($company) {
			
			$item = 
			App\AppCustom\Models\Company::where('name', $company)
			->first()
			;
			
			if ($item) {
				$idCompany = $item->id;
				
				$aLogos = static::getLogos($idCompany);
				
				return [
				'logo' => file_exists($aLogos['logo']) ? $aLogos['logo'] : config('appCustom.logos.logoDefault'),
				'logoEmailB64' => file_exists($aLogos['logoEmail64']) ? \file_get_contents($aLogos['logoEmail64']) : \file_get_contents(config('appCustom.logos.logoEmail64Default')),
				
				];
				
				} else {
				
				return [
				'logo' => config('appCustom.logos.logoDefault'),
				'logoEmailB64' => \file_get_contents(config('appCustom.logos.logoEmail64Default')),
				
				];
			}
			
		}
		
		static function getCompanyDataByUrl($url) {
			
			$aReturn = [];
			
			if ($subdomain = static::getSubdomain($url)) {
				$company = 
				\App\AppCustom\Models\Company::where('name', $subdomain)->first();
				
				if ($company) {
					$aReturn['company'] = $company;
					$aReturn['logos'] = Util::getLogosByCompanyId($company->id);
				}
			}
			
			if (!$aReturn) {
				$aReturn['company'] = \App\AppCustom\Models\Company::find(config('appCustom.companyDefaultId'));
				$aReturn['logos'] = Util::getLogosByCompanyId(config('appCustom.companyDefaultId'));
			}
			
			return $aReturn;
		}
		
		static function getCompanyDataByThisUrl() {
			return static::getCompanyDataByUrl(\URL::to('/'));
		}
		
		static function getSubdomain($url) {
			
			$parsedUrl = parse_url($url);
			
			$host = explode('.', $parsedUrl['host']);
			
			if (count($host) > 1) {
				$subdomain = $host[0];
				
				return  $subdomain;
			}
			
			
		}
		
		static function dateOk($date, $format = 'd/m/Y') {
			$d = \DateTime::createFromFormat($format, $date);
			
			return $d && $d->format($format) === $date;
		}
		
		static function getPrecioFormat($precio) {
			$precio = number_format ($precio, 2 , ',' , '.');
			return str_replace(',00', '', $precio);
		}
		static function getMonedaDefault() {
			$moneda = Monedas::select('id','nombre','simbolo')->where('principal',1)->get()->toArray();
			return $moneda;
		}   
		static function getMonedaSimbolo($id_moneda) {
			$moneda = Monedas::select('simbolo')
			->where('id',$id_moneda)
			->first();
			$moneda = $moneda->simbolo;
			return $moneda;
		}
		static function getPrecios($id, $id_moneda) {
			$precio = PreciosProductos::
			select('precio_venta','precio_lista','descuento','precio_meli')
			->where('id_producto', $id)
			->where('id_moneda', $id_moneda)
			->first();
			if($precio){
				//si tiene descuento, va sobre el precio de lista
				if($precio->descuento>0 && $precio->precio_lista>0){
					$precio->precio_db = ($precio->precio_lista-$precio->descuento);
				}else{
					$precio->precio_db = $precio->precio_venta;
				}
			}else{
				$precio = false;
			}
			return $precio;
		}

		static function estadoPedido($e) {
			
			switch ($e) {
				case 'acordar':
					$estado = "Envios a acordar";
				break;
				case 'cash_on_delivery':
					$estado = "Pago contra reembolso";
				break;
				case 'payment_in_branch':
					$estado = "Pago en sucursal";
				break;
				case 'pending':
					$estado = "Pago en proceso";
				break;
				case 'approved':
					$estado = "Pago realizado con &eacute;xito!";
				break;
				case 'in_process':
					$estado = "El pago está siendo revisado";
				break;
				case 'rejected':
					$estado = "El pago fue rechazado";
				break;
				case 'cancelled':
					$estado = "El pago fue cancelado";
				break;
				case 'refunded':
					$estado = "La compra no se concretó";
				break;
				case 'in_mediation':
					$estado = "En disputa del pago";
				break;
				case 'acordar':
					$estado = "Env&iacute;o a acordar con ".\config('appCustom.clientName');
				break;
				case 'proceso':
					$estado = "Carrito";
				break;
				default:
					$estado=$e;
				break;
			}
			
			return $estado;
		}
		static function estadoPedidoDetalle ($e){
			switch ($e) {
				case "accredited":
					$detalle_estado="El pago fue acreditado.";
				break;
				case "pending_contingency":
					$detalle_estado="Pago suspendido hasta validar informacion.";
				break;
				case "pending_review_manual":
					$detalle_estado="Operación a revisar de forma manual - Antifraude.";
				break;
				case "pending_review_auto":
					$detalle_estado="Operación a revisar de forma automatica - Antifraude.";
				break;
				case "pending_waiting_payment":
					$detalle_estado="A la espera del pago.";
				break;
				case "pending_additional_info":
					$detalle_estado="A la espera de informacion adicional.";
				break;
				case "pending_online_validation":
					$detalle_estado="Validacion Online.";
				break;
				case "pending_card_validation":
					$detalle_estado="Validacion de datos.";
				break;
				case "pending_waiting_for_remedy":
					$detalle_estado="Validacion de datos.";
				break;
				case "pending_form_bad_filled_card_number":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_security_code":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_date":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_other":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending":
					$detalle_estado="Pendiente de finalizar una operacion.";
				break;
				case "insufficent_amount":
					$detalle_estado="Monto insuficiente.";
				break;
				case "by_collector":
					$detalle_estado="Cancelado por el vendedor.";
				break;
				case "by_payer":
					$detalle_estado="Cancelado por el comprador.";
				break;
				case "expired":
					$detalle_estado="Operación vencida.";
				break;
				case "expired":
					$detalle_estado="Operación vencida.";
				break;
				case "refunded":
					$detalle_estado="Pago devuelto al comprador.";
				break;
				case "rejected":
					$detalle_estado="Rechazado por Mercado Pago - Inhabilitado.";
				break;
				case "cc_rejected_fraud":
					$detalle_estado="Rechazado de la tarjeta / Mercado Pago - Riesgo de fraude";
				break;
				case "cc_rejected_high_risk":
					$detalle_estado="Rechazado de la tarjeta / MP - Riesgo de fraude";
				break;
				case "cc_rejected_blacklist":
					$detalle_estado="Rechazado de la tarjeta - La misma se encuentra en BlackList";
				break;
				case "cc_rejected_insufficient_amount":
					$detalle_estado="Rechazado de la tarjeta - limite insuficiente para realizar la compra";
				break;
				case "cc_rejected_other_reason":
					$detalle_estado="Rechazado de la tarjeta - Rechazado por otros motivos";
				break;
				case "cc_rejected_max_attempts":
					$detalle_estado="Rechazado de la tarjeta -  Limite de intentos de compra maximo";
				break;
				case "cc_rejected_invalid_installments":
					$detalle_estado="Rechazado de la tarjeta - Configuracion de cuotas invalidas";
				break;
				case "cc_rejected_call_for_authorize":
					$detalle_estado="Rechazado de la tarjeta - Se necesita autorizacion para procesar el pago. Debe llamar a la misma y autorizar la operación.";
				break;
				case "cc_rejected_duplicated_payment":
					$detalle_estado="Rechazado de la tarjeta - El usuario registro un pago inmediatamente antes de identicas caracteristicas";
				break;
				case "cc_rejected_card_disabled":
					$detalle_estado="Rechazado de la tarjeta - Tarjeta no habilitada";
				break;
				case "cc_rejected_card_error":
					$detalle_estado="Rechazado de la tarjeta - Informacion ingresada de la tarjeta erronea";
				break;
				case "review_fail":
					$detalle_estado="Rechazado por revision de datos fallida";
				break;
				case "payer_unavailable":
					$detalle_estado="Rechazado por comprador bloqueado en Mercado Libre / Mercado Pago";
				break;
				case "collector_unavailable":
					$detalle_estado="Rechazado por vendedor bloqueado en Mercado Libre / Mercado Pago";
				break;
				default:
					$detalle_estado=$e;
				break;
			}
			return $detalle_estado;
		}
		static function estadoEnvio ($e) {
			$estado_envio = '';
			if($e){
				switch($e){
					case "pending":
						$estado_envio='Pendiente';
					break;
					case "ready_to_ship":
						$estado_envio='Listo para enviar';
					break;
					case "shipped":
						$estado_envio='Enviado';
					break;
					case "delivered":
						$estado_envio='Entregado';
					break;
					case "not_delivered":
						$estado_envio='No entregado';
					break;
					case "cancelled":
						$estado_envio='Cancelado';
					break;
					case "en_sucursal":
						$estado_envio='Retiro en sucursal';
					break;
					default:
						$estado=$e;
					break;
				}
			}
			
			return $estado_envio;
		}
		static function metodoMercado($e){
			switch ($e){
				case "account_money":
					$metodo="Cuenta de dinero";
				break;
				case "credit_card":
					$metodo="Tarjeta de crédito ";//.$f['metodo_tipo'];
				break;
				case "debit_card":
					$metodo="Tarjeta de débito ";//.$f['metodo_tipo'];
				break;
				case "ticket":
					$metodo="Pago por Pagofácil o Rapipago";//.$f['metodo_tipo'];
				break;
				case "bank_transfer":
					$metodo="Transferencia bancaria ";//.$f['metodo_tipo'];
				break;
				case "pago_contrarembolso":
					$metodo="Pago contra reembolso";
				break;
				default:
					$metodo=$e;
				break;
			}
			return $metodo;
		}
		static function getEnum($table, $column) {
			$type = \DB::select(\DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ;
			preg_match('/^enum\((.*)\)$/', $type, $matches);
			$enum = array();
			foreach( explode(',', $matches[1]) as $value )
			{
				$v = trim( $value, "'" );
				$enum = array_add($enum, $v, $v);
			}
			return $enum;
		}

		static function enviarMailRegalo($id_lista, $id_pedido) {
			$params = 'id_rubro=0';
            $params .= '&id_lista='.$id_lista;
            
            $linkRegalo = env('FE_URL');
            $linkRegalo .= 'regalos.php?' . $params;

			$lista = Listas::find($id_lista);
			$producto = PedidosProductos::select('nombre')
										->where('pedidos_productos.id_pedido','=',$id_pedido)
										->first();

			$cliente = Listas::select('listas_usuarios.mail')
								->join('listas_usuarios','listas_usuarios.id','=','listas.id_cliente_lista')
								->where('listas.habilitado',1)
								->where('listas.id','=',$id_lista)
								->first();

			$vendedor = \config('appCustom.clientName');				
			// Envio mail de compra a los mail cargados por el cliente
			$mails = explode(';', $cliente->mail);	
			foreach ($mails as $mail) {
				\Mail::send(
	                'email.compraRegalo',
	                [
	                	'titulo' => $lista->titulo,
	                    'invitado' => 'prueba',
	                    'producto' => $producto->nombre,
	                    'link' => $linkRegalo
	                ],
	                function($message) use ($mail, $vendedor)
	                {
	                    $message->to($mail)
	                        ->subject($vendedor . '. Compra de regalo');
	                }
	            );
	        }

	        // Envio de mail al vendedor
            \Mail::send(
                'email.compraRegalo',
                [
                	'titulo' => $lista->titulo,
                    'invitado' => 'prueba',
                    'producto' => $producto->nombre,
                    'link' => $linkRegalo
                ],
                function($message) use ($vendedor)
                {
                    $message->to('matias@webexport.com.ar')
                        ->subject($vendedor . '. Compra de regalo');
                }
            );

            return 0;
		}
		
		static function cambiaAcento($string)
		{
			$cadena=utf8_decode($string);
			$vocales = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
			$acentos = array("a", "e", "i", "o", "u","A", "E", "I", "O", "U" );
			$frase = str_replace($vocales, $acentos , $cadena);

			return $frase;
		}

		static function getStock($id_producto, $estado_meli='all')
		{ 
			$colores = CodigoStock::
						select('inv_producto_codigo_stock.id_producto', 'inv_producto_codigo_stock.id','inv_producto_codigo_stock.codigo','inv_producto_codigo_stock.stock','inv_producto_codigo_stock.estado_meli','inv_producto_codigo_stock.id_color','conf_colores.nombre as nombreColor','inv_producto_codigo_stock.id_talle','conf_talles.nombre as nombreTalle')
						->join('conf_colores','conf_colores.id','=','inv_producto_codigo_stock.id_color')
                        ->leftJoin('conf_talles','conf_talles.id','=','inv_producto_codigo_stock.id_talle')
						->where('inv_producto_codigo_stock.id_producto','=',$id_producto);
			
			

			if ($estado_meli != 'all') {
				$colores = $colores->where('inv_producto_codigo_stock.estado_meli','=',$estado_meli);
			}
			$colores = $colores->get();
			return $colores;
		}
		
		public static function in_array($aArray, $field, $value) {
			if ($aArray) {
				foreach ($aArray as $item) {
					if ($item[$field] == $value) {
						return $item;
					}
				}
			}
		}
		
		public static function inArrayGetAll($aArray, $field, $value) {
			$aItems = [];
			if ($aArray) {
				foreach ($aArray as $item) {
					if (strpos(strtolower($item[$field]), strtolower($value)) !== false) {
						array_push($aItems, $item);
					}
				}
			}
			
			return $aItems;
		}
		static function parse($data,$content)
		{
			$parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
				list($shortCode, $index) = $matches;

				if( isset($data[$index]) ) {
					return $data[$index];
				} 

			}, $content);

			return $parsed;
		}

		static function matchMarcas($cadena){
		
			$posTopper = strpos($cadena, 'TOPPER');
			$posNike = strpos($cadena, 'NIKE');
			$posAdidas = strpos($cadena, 'ADIDAS');
			$posReebok = strpos($cadena, 'REEBOK');
			$posReebok1 = strpos($cadena, 'RBK');
			$posPuma = strpos($cadena, 'PUMA');
			$posSalomon = strpos($cadena, 'SALOMON');
			$posNb = strpos($cadena, 'NB');
			$posCrocs = strpos($cadena, 'CROCS');
			$posCrocs = strpos($cadena, 'CROC');
		
			if ($posTopper !== false) {
				return 'topper';
			}elseif ($posNike !== false){
				return 'nike';
			}elseif ($posAdidas !== false){
				return 'adidas';
			}elseif ($posReebok!== false){
				return 'reebok';
			}elseif ($posPuma !== false){
				return 'puma';
			}elseif ($posSalomon !== false){
				return 'salomon';
			}elseif ($posReebok1 !== false){
				return 'reebok';
			}elseif ($posNb !== false){
				return 'new balance';
			}elseif ($posCrocs !== false){
				return 'crocs';
			}

		}


		static function clear($cadena){
 		
			 $cadena = str_replace(
				array("\\", "¨", "º", "-", "~",
					 "#", "@", "|", "!", "\"",
					 "·", "$", "%", "&", "/",
					 "(", ")", "?", "'", "¡",
					 "¿", "[", "^", "<code>", "]",
					 "+", "}", "{", "¨", "´",
					 ">", "< ", ";", ",", ":",
					 ".","*","_"),
				' ',
				$cadena
			);
		 
			return $cadena;
		}
		
		static function separarCodigo($cod){
			
			$codigo = explode(".", $cod);
			
			return $codigo[2];
		}

		static function getLastUpdate() {
			return  
					\App\AppCustom\Models\ProductosImportar::
						select('inv_productos_importar.created_at','a.first_name','a.last_name')
						->orderBy('inv_productos_importar.created_at', 'desc')
						->join('users as a', 'a.id','=','inv_productos_importar.id_usuario')
						->first()
					;
		}

		static function importar($row,$rowNum,$aResult) {

				$rowNum++;
				unset($talle);
				unset($cod_producto);
				unset($marca);
				unset($genero);
				$productosActualizados = 0;
			
				$codigo = Util::separarCodigo($row['nro_rep']); //requerido
				$talle = $row['talle'];
				$descripcion = $row['des_rep'];
				$stock = $row['stock_ini']; // stock real
				$precio_de_venta = $row['contado_w']; //requerido
				$precio_de_lista = $row['lista_w'];
				$precio_de_meli = $row['ml_w'];
				$genero = utf8_encode($row['genero']);
				$tipo_medida = $row["tipo_med"];
					
				if ($codigo && $descripcion) {
					//formateo el codigo
							
						//busco si el producto existe
						$item = ProductosCodigoStock::select('id_producto')
						->where('codigo', 'like', $codigo)->first();

						//empiezo a crear o actualizar los productos
						//extraigo marca de la descripcion
						$aux = Util::clear($descripcion);								
						$marca =  Util::matchMarcas($aux);
						

						if (isset($marca)) {
							$marca = ucwords(strtolower(($marca)));
							// Verifico si la marca existe
							$marca = Marcas::where('nombre','=',$marca)->first();
	
							if (!$marca) {
								// Si no existe se debe crear la marca 
								$array_marca = array(
									'nombre' => $marca
								);
								//$request->request->add($array_marca);
								$aResult = app('App\Http\Controllers\MarcasController')->store($array_marca);
								$aResult = json_decode($aResult->getContent(),true);
								$marca = Marcas::where('nombre','=',$marca)->first();
							}
						}

						
						// Verifico si el genero existe
						if (isset($genero) && $genero!='') {
						
							if(substr($genero,0,2)=='NI'){
								$generoAux = Genero::where('id',8)->first();
							}else{
								$generoAux = Genero::where('genero','=',$genero)->first();
							}

							if(!$generoAux) {
								// Si no existe se debe crear la genero 
								$nombreGenero = $genero;
								$genero = new Genero;
								$genero->genero = $nombreGenero;
								$genero->save();
							}else{
								$genero = $generoAux;
							}
						}else{
							$generoAux = Genero::where('id',10)->first();
						}

						
						if (isset($talle)) {
							//talle
							$talleAux = Talles::select('id')->where('nombre', $talle)->where('habilitado', 1)->first();
						

							if(!$talleAux){										
								$nombreTalle = $talle;
								$talle = new Talles;
								$talle->nombre = $nombreTalle;
								$talle->habilitado = 1;
								$talle->save();
							}else{
								$talle = $talleAux;
							}
						}

						
						$alto = 15;
						$ancho = 15;
						$largo = 30;
						$peso = 800;

						
						if(!$item){
							$descripcion = ucwords(strtolower(($descripcion)));
							$array_send = array(
								'nombre' => $descripcion,
								'orden' => 0,
								'habilitado' => 0
							);
							$array_send['alto'] = $alto;
							$array_send['ancho'] = $ancho;
							$array_send['largo'] = $largo;
							$array_send['peso'] = $peso;
							
							
							if (isset($marca)){
								$array_send['id_marca'] = $marca->id;
							} else {
								$array_send['id_marca'] = '';
							}
							
							if (isset($genero) && $genero!=''){
								$array_send['id_genero'] = $genero->id;
							} else {
								$array_send['id_genero'] = '';
							}
							//para este caso no hay rubro ni subrubro usare una funcion diferente	
							//$request->request->add($array_send);
							$aResult = app('App\Http\Controllers\ProductosController')->storeImportKernel($array_send);
							$aResult = json_decode($aResult->getContent(),true);

							if ($aResult['status'] == 1) {
								$aWarns[] = "El producto no se pudo crear fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
							}else{
								$id_producto = $aResult['id_producto'];
								$aWarns[] = "El producto de la fila {$rowNum} Fue Creado.";

								//CARGAR talle y stock											
								$codStock = new ProductosCodigoStock;
								$codStock->id_producto = $id_producto;
								$codStock->id_talle = isset($talle)?$talle->id:0;
								$codStock->codigo = $codigo;
								$codStock->stock = $stock;
								$codStock->save();											

								//CARGAR PRECIO
								// obtengo la moneda por default
								if($precio_de_venta > 0 && $precio_de_lista > 0){
									$moneda_default = Util::getMonedaDefault();
									$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
			
									// Array para guardar el precio del producto
									$array_precio = array(
										'resource_id' => $id_producto,
										'id_moneda' => $id_moneda,
										'precio_venta' => $precio_de_venta,
										'precio_lista' => isset($precio_de_lista)?$precio_de_lista:null,
										'precio_meli' => $precio_de_meli
									);											

									//$request->request->add($array_precio);
									$aResult = app('App\Http\Controllers\PreciosRelatedController')->storeImportKernel($array_precio);					
									$aResult = json_decode($aResult->getContent(),true);
									if ($aResult['status'] == 1) {
										$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
									}
								}else{
									$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
								}
								//sucursal
								$stock_sucursal = new SucursalesStock;
								$stock_sucursal->id_codigo_stock = $codStock->id;
								$stock_sucursal->id_sucursal = 417;
								$stock_sucursal->stock = $stock;
								$stock_sucursal->save();

								//update_import
								$update_import = Productos::find($id_producto);
								$update_import->habilitado = 0;
								$update_import->update_import = 1;
								$update_import->save();

							}
						}else{							
							$id_producto = $item->id_producto;
							$prod = Productos::find($item->id_producto);																				
							$descripcion = ucwords(strtolower(($descripcion)));

							//actualizo marcas  (se van agregando nuevas por pedidos)
							if (isset($marca) and $prod->id_marca==0){
								$update=Productos::where('id',$prod->id)
														->update(['id_marca' => $marca->id]);
							} 

							//actualizo precio - stock -
							$id_talle = isset($talle)?$talle->id:0;										

							$codStock = ProductosCodigoStock::select('id')
											->where([
												'codigo' => $codigo,
												'id_talle' => $id_talle,
												'id_producto' => $id_producto
											])->first();

												
											if($codStock){
												//actualizo el stock y color si no inserto el nuevo que ingrese
												$update=ProductosCodigoStock::where('id',$codStock->id)
														->update(['stock' => $stock]);
											}else{
												$codStock = new ProductosCodigoStock;
												$codStock->codigo = $codigo;
												$codStock->id_talle = $id_talle;
												$codStock->id_producto = $id_producto;
												$codStock->save();
											}

								//stock por sucursal
									$stock_sucursal = SucursalesStock::
									select('id')
									->where('id_codigo_stock', $codStock->id)
									->where('id_sucursal', 417)
									->first();
									
									if($stock_sucursal){
										$update=SucursalesStock::where('id',$stock_sucursal->id)
											->update(['stock' => $stock]);
									}else{
										$stock_sucursal = new SucursalesStock;
										$stock_sucursal->id_codigo_stock = $codStock->id;
										$stock_sucursal->id_sucursal = 417;
										$stock_sucursal->stock = $stock;
										$stock_sucursal->save();
									}												
																			
								//Actualizar PRECIO
								// obtengo la moneda por default
								$moneda_default = Util::getMonedaDefault();
								$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
		
								// Array para guardar el precio del producto
								$array_precio = array(
									'resource_id' => $id_producto,
									'id_moneda' => $id_moneda,
									'precio_venta' => $precio_de_venta,
									'precio_lista' => isset($precio_de_lista)?$precio_de_lista:null,
									'precio_meli' => $precio_de_meli
								);

								// Obtengo el id del registro en la tabla inv_precios
								$id_precio = PreciosProductos::
								select('id')
								->where('id_moneda','=',$id_moneda)
								->where('id_producto','=',$id_producto)
								->first();											
							
								//$request->request->add($array_precio);
								if ($id_precio) {
									$id_precio = $id_precio->id;
									// Si tiene un precio cargado actualizo el valor
									$aResult=PreciosProductos::where('id',$id_precio)
									->update(['precio_venta' => $precio_de_venta, 'precio_lista' => $precio_de_lista, 'precio_meli' => $precio_de_meli]);
									
								} else {
									// Si no tiene un precio cargado lo creo
									if($precio_de_venta > 0 && $precio_de_lista > 0){
										$aResult = app('App\Http\Controllers\PreciosRelatedController')->storeImportKernel($array_precio);
										$aResult = json_decode($aResult->getContent(),true);
									}else{
										$aResult['status'] = 1;
										$aResult=response()->json($aResult);
										$aResult=json_decode($aResult->getContent(),true);
										$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
									}
								
									if ($aResult['status'] == 1) {
										$aWarns[] = "El precio no se pudo actualizar para la fila {$rowNum}. No Importado";
									}
								}
														
							//update_import
							$update_import = Productos::find($id_producto);
							$update_import->update_import = 1;
							$update_import->save();
						}
						$productosActualizados++;
					

				} elseif(!$codigo){
					$aWarns[] = "El codigo está vacía en la fila {$rowNum}. No Importado";
				} elseif(!$descripcion) {
					$aWarns[] = "La descripción está vacío en la fila {$rowNum}. No Importado";
				}
				
		}

		static function getTalleEquivalente($talle,$id_marca,$id_genero,$numeracion,$id_rubro){
			$talle = ConfTallesEquivalencias::
					select(\DB::raw('equivalencia,talle'))
					->where('id_marca', $id_marca)
					->where('id_genero', $id_genero)
					->where('talle', $talle)
					->where('id_numeracion', $numeracion)
					->where('id_categoria', $id_rubro)
					->orderBy('id_numeracion','asc')
					->first();
			
			return $talle;
		}
		static function getTalleEquivalenteInvertida($talle,$id_marca,$id_genero,$numeracion,$id_rubro){
			$talle = ConfTallesEquivalencias::
					select(\DB::raw('equivalencia,talle'))
					->where('id_marca', $id_marca)
					->where('id_genero', $id_genero)
					->where('equivalencia', $talle)
					->where('id_numeracion', $numeracion)
					->where('id_categoria', $id_rubro)
					->orderBy('id_numeracion','asc')
					->first();
			
			return $talle;
		}

		static function getListas($returnAs = 'json') {
			
			$listas = 
			MktListas::select('id', 'nombre as text')
			->where('habilitado','1')
			->get()
			;
	
			if ('json' == $returnAs) {
				return response()->json($listas->toArray());
			} else {
				return true;
			}
		}

		static function getFiltroListas() {
			return  MktListas::where('habilitado', 1)->orderBy('nombre')->get(); 
		}

	//******************* mailling ************************ */
	static function Templates($id){

        if($id!=0){
            return TemplatesEditables::where('id','=',$id)->get();
        }

	}
	
	static function contactos($id){
			
        return Campaign::join('campaign_listas','campaign_listas.id_campaign','=','campaign.id')
                           ->join('mkt_personas_listas','mkt_personas_listas.id_lista','=','campaign_listas.id_lista')
                           ->join('pedidos_usuarios','pedidos_usuarios.id','=','mkt_personas_listas.id_persona') 
                           ->where('campaign.id','=',$id)
                           ->select('pedidos_usuarios.id','pedidos_usuarios.mail','pedidos_usuarios.nombre as nombreCli','pedidos_usuarios.apellido as apellidoCli')
                           ->distinct() 
                           ->get(); 
                            
            
}

    static function campanias($id=''){
        
        if($id==''){
            return Campaign::join('mailling_campanias','mailling_campanias.id_campania','=','campaign.id')
                        ->join('campaign_listas','campaign_listas.id_campaign','=','campaign.id')
                        ->join('mkt_listas','mkt_listas.id','=','campaign_listas.id_lista')
                        ->where('campaign.habilitado','=',1)
                        ->where('campaign.fecha','=',date('Y-m-d'))
                        ->select('campaign.*','mailling_campanias.asunto','mailling_campanias.remitente','mailling_campanias.texto','mkt_listas.nombre as nombreLista')
                        ->groupBy('campaign.nombre')
                        ->get();
        }else{
            return Campaign::join('mailling_campanias','mailling_campanias.id_campania','=','campaign.id')
                    ->join('campaign_listas','campaign_listas.id_campaign','=','campaign.id')
                    ->join('mkt_listas','mkt_listas.id','=','campaign_listas.id_lista')
                    ->where('campaign.id',$id)
                    ->where('campaign.habilitado','=',1)
                    ->select('campaign.*','mailling_campanias.asunto','mailling_campanias.remitente','mailling_campanias.texto','mkt_listas.nombre as nombreLista')
                    ->groupBy('campaign.nombre')
                    ->get();
        }
        
    }

    static function contactosAB($id,$ab){

            return MaillingTesting::join('campaign_testing_lista','campaign_testing_lista.id_campaign','=','mailling_testing.id_campania')
                            ->join('mkt_personas_listas','mkt_personas_listas.id_lista','=','campaign_testing_lista.id_lista')
                            ->join('pedidos_usuarios','pedidos_usuarios.id','=','mkt_personas_listas.id_persona') 
                            ->where('mailling_testing.id_campania','=',$id)
                            ->where('campaign_testing_lista.id_ab','=',$ab)
                            ->select('pedidos_usuarios.id','pedidos_usuarios.mail','pedidos_usuarios.nombre as nombreCli','pedidos_usuarios.apellido as apellidoCli')
                            ->distinct() 
                            ->get();
    }

    static function campaniasA($id=''){

        if($id==''){
            return CampaignTesting::join('mailling_testing','mailling_testing.id_campania','=','campaign_testing.id')
                                        ->join('campaign_testing_lista','campaign_testing_lista.id_campaign','=','campaign_testing.id')
                                        ->join('mkt_listas','mkt_listas.id','=','campaign_testing_lista.id_lista')	
                                        ->where('mailling_testing.id_ab','=','a')
                                        ->where('campaign_testing.habilitado','=',1)		
                                        ->select('mailling_testing.*')
                                        ->distinct()
                                        ->get();
        }else{
            return CampaignTesting::join('mailling_testing','mailling_testing.id_campania','=','campaign_testing.id')
                                            ->join('campaign_testing_lista','campaign_testing_lista.id_campaign','=','campaign_testing.id')
                                            ->join('mkt_listas','mkt_listas.id','=','campaign_testing_lista.id_lista')	
                                            ->where('mailling_testing.id_ab','=','a')	
                                            ->where('campaign_testing.habilitado','=',1)
                                            ->where('mailling_testing.id_campania',$id)	
                                            ->select('mailling_testing.*')
                                            ->distinct()
                                            ->get();
        }
                                                    
    }

    static function campaniasB($id=''){

        if($id==''){
            return CampaignTesting::join('mailling_testing','mailling_testing.id_campania','=','campaign_testing.id')
                ->join('campaign_testing_lista','campaign_testing_lista.id_campaign','=','campaign_testing.id')
                ->join('mkt_listas','mkt_listas.id','=','campaign_testing_lista.id_lista')	
                ->where('mailling_testing.id_ab','=','b')
                ->where('campaign_testing.habilitado','=',1)			
                ->select('mailling_testing.*')
                ->distinct()
                ->get();
        }else{
            return CampaignTesting::join('mailling_testing','mailling_testing.id_campania','=','campaign_testing.id')
                                            ->join('campaign_testing_lista','campaign_testing_lista.id_campaign','=','campaign_testing.id')
                                            ->join('mkt_listas','mkt_listas.id','=','campaign_testing_lista.id_lista')	
                                            ->where('mailling_testing.id_ab','=','b')	
                                            ->where('campaign_testing.habilitado','=',1)		
                                            ->where('mailling_testing.id_campania',$id)	
                                            ->select('mailling_testing.*')
                                            ->distinct()
                                            ->get();
        }
        
    }

   
    static function campania($c,$lista){		
		foreach ($lista as $l) {

				$data = [
					'idcampaign' => $c->id,
					'url' => \env("FE_URL"),
					'user' => $l->id,
					'nombre' => $l->nombreCli,
					'apellido' => $l->apellidoCli,
                    'email' => $l->mail,
					
				];
				$content=Util::parse($data,$c->texto);
				$asunto=Util::parse($data,$c->asunto);
				
					try {
						\Mail::send(
							'email.campanias',
							[
								'content' => $content
							], 
							function($message) use ($c, $l, $asunto)
							{
								$message->to($l->mail)
										->subject($asunto);
							}
						);

					} catch (\Exception $e) {
						\Log::error($e->getMessage());
					}
		
			}
	}

	static function campaniaA($c,$lista){

		foreach ($lista as $l) {
			$data = [
			'idcampaignT' => $c->id_campania,
			'url' => \env("FE_URL"),
			'user' => $l->mail,
			'ab' => 'a',
			'nombre' => $l->nombreCli,
			'apellido' => $l->apellidoCli,

            'email' => $l->mail,
            'idab' => 'a',
			];
			$content=Util::parse($data,$c->texto);
			$asunto=Util::parse($data,$c->asunto);

			try {
				\Mail::send(
					'email.campanias',
					[
						'content' => $content
					], 
					function($message) use ($c, $l, $asunto)
					{
						$message->to($l->mail)
								->subject($asunto);
					}
				);

			} catch (\Exception $e) {
				\Log::error($e->getMessage());
			}	
		}				 
	}

	static function campaniaB($c,$lista){

		foreach ($lista as $l) {
			$data = [
			'idcampaignT' => $c->id_campania,
			'url' => \env("FE_URL"),
			'user' => $l->mail,
			'ab' => 'b',
			'nombre' => $l->nombreCli,
			'apellido' => $l->apellidoCli,

            'email' => $l->mail,
            'idab' => 'b',
			];
			$content=Util::parse($data,$c->texto);
			$asunto=Util::parse($data,$c->asunto);

			try {
				\Mail::send(
					'email.campanias',
					[
						'content' => $content
					], 
					function($message) use ($c, $l, $asunto)
					{
						$message->to($l->mail)
								->subject($asunto);
					}
				);

			} catch (\Exception $e) {
				\Log::error($e->getMessage());
			}
		}
    }

    static function contador($idL){
        return MktListasPersonas::where('mkt_personas_listas.id_lista',$idL)
                                ->groupBy('mkt_personas_listas.id_lista')
                                ->count();

    }
    
    static function lista($id){
        return CampaignListas::select('mkt_listas.id','mkt_listas.nombre as text')
                            ->join('mkt_listas','mkt_listas.id','=','campaign_listas.id_lista')
                            ->where('campaign_listas.id_campaign','=',$id)
                            ->get();
    }

    static function listaAB($id,$idM){
        return CampaignTestingLista::select('mkt_listas.id','mkt_listas.nombre as text')
                            ->join('mkt_listas','mkt_listas.id','=','campaign_testing_lista.id_lista')
                            ->where('campaign_testing_lista.id_campaign','=',$id)
                            ->where('campaign_testing_lista.id_mailling','=',$idM)
                            ->get();
    }

    static function getFiltroTag($term) {

        $tags = Etiquetas::selectRaw("id,nombre,created_at")->where('nombre', 'like', '%'.$term.'%')->get(); 

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $fecha=date_format($tag->created_at , 'd-m-Y');
            $formatted_tags[] = ['id' => $tag->id, 'text' => $fecha.' '.$tag->nombre];
        }

            return $formatted_tags;
    }
    
    static function getFiltroMails($term) {

        $tags = Mailling::selectRaw("id,nombre")->where('nombre', 'like', '%'.$term.'%')->get(); 

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombre];
        }

            return $formatted_tags;
    }

    static function getRatiosA($id){
		return CampaignTesting::select(DB::raw('pedidos_usuarios.mail,pedidos_usuarios.nombre,pedidos_usuarios.apellido,mailling_testing.asunto,mailling_testing.enviados,campaign_testing.*,email_tracking.*,FORMAT( (count(distinct email_tracking.id_user)/mailling_testing.enviados)*100,2) as ratio'))
						->join('email_tracking','email_tracking.campaign_testing_id','=','campaign_testing.id')
						->join('mailling_testing','mailling_testing.id_campania','=','email_tracking.campaign_testing_id')
						->join('pedidos_usuarios','pedidos_usuarios.mail','=','email_tracking.id_user')
						->where('email_tracking.id_ab','=','a')
                        ->where('mailling_testing.id_ab','=','a')
                        ->where('mailling_testing.id_campania','=',$id)
						->groupBy('email_tracking.campaign_testing_id')
						->get();
	}

	static function getRatiosB($id){
		return CampaignTesting::select(DB::raw('pedidos_usuarios.nombre,pedidos_usuarios.apellido,mailling_testing.asunto,mailling_testing.enviados,campaign_testing.*,email_tracking.*,FORMAT( (count(distinct email_tracking.id_user)/mailling_testing.enviados)*100,2) as ratio'))
						->join('email_tracking','email_tracking.campaign_testing_id','=','campaign_testing.id')
						->join('mailling_testing','mailling_testing.id_campania','=','email_tracking.campaign_testing_id')
						->join('pedidos_usuarios','pedidos_usuarios.mail','=','email_tracking.id_user')
						->where('email_tracking.id_ab','=','b')
                        ->where('mailling_testing.id_ab','=','b')
                        ->where('mailling_testing.id_campania','=',$id)
						->groupBy('email_tracking.campaign_testing_id')
						->get();
	}

	/* static function getRatiosLinkA($id){

		$data=CampaignTesting::select(DB::raw('mailling_testing.asunto,campaign_testing.*,link_tracking.*,count(link_tracking.campaign_testing_id) as ratio'))
						->join('link_tracking','link_tracking.campaign_testing_id','=','campaign_testing.id')
						->join('mailling_testing','mailling_testing.id_campania','=','link_tracking.campaign_testing_id')
						->where('link_tracking.id_ab','=','a')
						->where('mailling_testing.id_ab','=','a')
						->where('link_tracking.campaign_testing_id','=',$id)
						->groupBy('link_tracking.campaign_testing_id')
						->get();
		
		$rows = $data->count();
		
		if($rows > 0){
			return $data;
		}else{
			return false;
		}
	} */

	/* static function getRatiosLinkB($id){

		return CampaignTesting::select(DB::raw('mailling_testing.asunto,campaign_testing.*,link_tracking.*,count(link_tracking.campaign_testing_id) as ratio'))
						->join('link_tracking','link_tracking.campaign_testing_id','=','campaign_testing.id')
						->join('mailling_testing','mailling_testing.id_campania','=','link_tracking.campaign_testing_id')
						->where('link_tracking.id_ab','=','b')
						->where('mailling_testing.id_ab','=','b')
						->where('link_tracking.campaign_testing_id','=',$id)
						->groupBy('link_tracking.campaign_testing_id')
						->get();
		
		$rows = $data->count();
		
		if($rows > 0){
				return $data;
		}else{
				return false;
		}
	} */

	static function mailsA($id){
        return EmailTracking::select(DB::raw('pedidos_usuarios.id,pedidos_usuarios.nombre,pedidos_usuarios.apellido,pedidos_usuarios.mail,count(pedidos_usuarios.id) as c'))
                            ->join('pedidos_usuarios','pedidos_usuarios.mail','=','email_tracking.id_user')
							->where('email_tracking.campaign_testing_id','=',$id)
                            ->where('email_tracking.id_ab','=','a')
                            ->groupBy('pedidos_usuarios.mail')
							->get();
    }

    static function clicksByNumber($id,$mail,$ab){
        return LinkTracking::where('link_tracking.campaign_testing_id','=',$id)
                            ->where('link_tracking.id_ab','=',$ab)
                            ->where('link_tracking.id_user','=',$mail)
							->count();
    }

    static function clicksByNumberSimple($id,$idU){
        return LinkTracking::where('link_tracking.campaign_id','=',$id)
                            ->where('link_tracking.id_user','=',$idU)
							->count();
    }
    
    static function clicksByMail($id,$mail,$ab){
        return LinkTracking::select(DB::raw('count(link_tracking.id_user) as clicks,link_tracking.link'))
							->where('link_tracking.campaign_testing_id','=',$id)
                            ->where('link_tracking.id_ab','=',$ab)
                            ->where('link_tracking.id_user','=',$mail)
                            ->groupBy('link_tracking.link')
							->get();
    }

    static function clicksByMailSimple($id,$idU){
        return LinkTracking::select(DB::raw('count(link_tracking.id_user) as clicks,link_tracking.link'))
							->where('link_tracking.campaign_id','=',$id)
                            ->where('link_tracking.id_user','=',$idU)
                            ->groupBy('link_tracking.link')
							->get();
    }
    
    static function clicksTotals($id,$ab){
        return LinkTracking::where('link_tracking.campaign_testing_id','=',$id)
                            ->where('link_tracking.id_ab','=',$ab)
							->count();
    }
    
    static function clicksTotalsSimple($id){
        return LinkTracking::where('link_tracking.campaign_id','=',$id)
							->count();
	}

	static function mailsB($id){

        return EmailTracking::select(DB::raw('pedidos_usuarios.id,pedidos_usuarios.nombre,pedidos_usuarios.apellido,pedidos_usuarios.mail,count(pedidos_usuarios.id) as c'))
                            ->join('pedidos_usuarios','pedidos_usuarios.mail','=','email_tracking.id_user')
							->where('email_tracking.campaign_testing_id','=',$id)
                            ->where('email_tracking.id_ab','=','b')
                            ->groupBy('pedidos_usuarios.mail')
							->get();
    }
    
    /* static function clicksB($id,$mail){
       
        return LinkTracking::select(DB::raw('count(link_tracking.id_user) as clicks,link_tracking.link'))
							->where('link_tracking.campaign_testing_id','=',$id)
                            ->where('link_tracking.id_ab','=','b')
                            ->where('link_tracking.id_user','=',$mail)
                            ->groupBy('link_tracking.link')
							->get();
	} */

	static function mails($id){

		return EmailTracking::select(DB::raw('pedidos_usuarios.id,pedidos_usuarios.nombre,pedidos_usuarios.apellido,pedidos_usuarios.mail,count(pedidos_usuarios.id) as c'))
                            ->join('pedidos_usuarios','pedidos_usuarios.id','=','email_tracking.id_user')
							->where('email_tracking.campaign_id','=',$id)
                            ->groupBy('pedidos_usuarios.mail')
							->get();
	}

	static function getRatiosG($id){
	
		return Campaign::select(DB::raw('pedidos_usuarios.nombre,pedidos_usuarios.apellido,mailling_campanias.asunto,mailling_campanias.enviados,campaign.*,email_tracking.*,FORMAT( (count(distinct email_tracking.id_user)/mailling_campanias.enviados)*100,2) as ratio'))
                            ->join('email_tracking','email_tracking.campaign_id','=','campaign.id')
                            ->join('mailling_campanias','mailling_campanias.id_campania','=','email_tracking.campaign_id')
                            ->join('pedidos_usuarios','pedidos_usuarios.id','=','email_tracking.id_user')
                            ->where('mailling_campanias.id_campania','=',$id)
                            ->groupBy('email_tracking.campaign_id')
                            ->get();
	}
    
    static function getReporte(){
	
		return EmailTracking::select(DB::raw('FORMAT( (count(distinct email_tracking.id_user)/mailling_campanias.enviados)*100,2) as ratio, FORMAT( (count(pedidos_usuarios.id)/count(distinct pedidos_usuarios.id)), 2) as clicks,DATE_FORMAT(campaign.fecha,"%Y-%m-%d") as fecha,mailling_campanias.*,campaign.id,campaign.nombre,campaign.habilitado,campaign.created_at,mailling_campanias.enviados'))
							->join('campaign','campaign.id','=','email_tracking.campaign_id')
							->join('pedidos_usuarios','pedidos_usuarios.id','=','email_tracking.id_user')
                            ->join('mailling_campanias','mailling_campanias.id_campania','=','campaign.id')
                            ->where('campaign.habilitado',1)
							->groupBy('email_tracking.campaign_id')
							->get();
	}

	static function getReporteAB(){
			
		return EmailTracking::select(DB::raw('FORMAT( (count(distinct email_tracking.id_user)/mailling_testing.enviados)*100,2) as ratio, FORMAT( (count(pedidos_usuarios.id)/count(distinct pedidos_usuarios.id)), 2) as clicks,DATE_FORMAT(campaign_testing.fechaenvio,"%Y-%m-%d") as fechaenvio,mailling_testing.*,campaign_testing.id,campaign_testing.nombre,campaign_testing.habilitado,campaign_testing.created_at'))
							->join('campaign_testing','campaign_testing.id','=','email_tracking.campaign_testing_id')
							->join('pedidos_usuarios','pedidos_usuarios.mail','=','email_tracking.id_user')
                            ->join('mailling_testing','mailling_testing.id_campania','=','campaign_testing.id')
                            ->where('campaign_testing.habilitado',1)
							->groupBy('email_tracking.campaign_testing_id')
							->get();
	}

	static function filtroEtiquetas(){
		return Etiquetas::where('habilitado', 1)->orderBy('nombre')->get(); 
	}
	
	// *************  cta *******************
    static function cta_mail($request){		

        $contacto = array(
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
			'email' => $request['mail']
		);
		if(\Mail::send('email.ctaemail', $contacto, function($message){
			$message->to('consultas@pacogarcia.com.ar')
					->subject('Nuevo registro desde CTA - ByMovi');
        })){
			$aResult['data']['status'] = 'success';
            $aResult['data']['msg'] = 'CONTACTO_EXITO';//lang
        } else {
            $aResult['data']['status'] = 'danger';
            $aResult['data']['msg'] = 'CONTACTO_ERROR';//lang
        }
			
    }

    static function cta_mail_cliente($request){		

        $contacto = array(
            'nombre' => $request['nombre'],
            'apellido' => $request['apellido'],
			'email' => $request['mail']
		);
		if(\Mail::send('email.ctaconfirmacion', $contacto, function($message) use($contacto){
			$message->to($contacto['email'])
					->subject('Registro - Bymovi');
        })){
			$aResult['data']['status'] = 'success';
            $aResult['data']['msg'] = 'CONTACTO_EXITO';//lang
        } else {
            $aResult['data']['status'] = 'danger';
            $aResult['data']['msg'] = 'CONTACTO_ERROR';//lang
        }
			
    }
		
	}
