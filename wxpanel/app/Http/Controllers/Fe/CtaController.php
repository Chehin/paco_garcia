<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\Banners2 as CTA;
use App\AppCustom\Models\Banners2;
use App\AppCustom\Models\Banners2Posiciones as Posicion;
use App\AppCustom\Models\PedidosClientes as Persona;
use App\AppCustom\Models\MktListasPersonas as ListaPersona;
use App\AppCustom\Models\MktListas as Lista;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\Category as SeccionNota;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\Productos;


class CtaController extends Controller
{
	
	public function ctaGet(Request $request)
    {		
		$ctaId = $request->input('ctaId');
		
		$cta = CTA::find($ctaId);
		$cta->increment('impresiones');
		
        $aResult = Util::getDefaultArrayResult();        
		
        if ($this->user->hasAccess('banners2' . '.view')) {
			
			
			$item = 
				CTA::where('banners2_banners2.id', $ctaId)
					->join('banners2_tipos as b','b.id','=','banners2_banners2.id_tipo')
					->select('banners2_banners2.texto','b.codigo','banners2_banners2.label_submit','banners2_banners2.id_tipo')
					->first()
			;
			
			
			$aResult['data']['texto'] = $item->texto;
			$aResult['data']['tipo'] = $item->id_tipo;
			$aResult['data']['form'] = $item->codigo;
			$aResult['data']['label_submit'] = $item->label_submit;
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }
	
	public function ctaSet(Request $request)
    {
		$cta = CTA::find($request->input('ctaId'));
		$cta->increment('clicks');
		
        $aResult = Util::getDefaultArrayResult();        
		
        if ($this->user->hasAccess('banners2' . '.view')) {
			
			//Validation
            $validator = \Validator::make(
                $request->all(), 
					[
						'mail' => 'email',
					], 
					[
						'mail.email' => 'El campo Email no es válido'
					]
            );
			
			if ($validator->fails()) {
				$aResult['status'] = 1;
				$aResult['msg'] = $validator->errors()->all();
				
				return response()->json($aResult);
			}
			
			$formFields = array_filter(
				$request->except(['idioma','id_moneda', 'ctaId', 'rid', 'params'])
			);
			
			//person
			$per = 
			Persona::
				where('mail',$request->input('mail'))
					->first()
			;
						
			if ($per) {
				if (0 === $per->registrado) {
					$per->fill($formFields);
					$per->save();
					$mail=Util::cta_mail($formFields);
					$mail=Util::cta_mail_cliente($formFields);
					
				}
				
			} else {
				$formFields['registrado'] = 0;
				$per = new Persona($formFields);
				
				$per->save();
				$mail=Util::cta_mail($formFields);
				$mail=Util::cta_mail_cliente($formFields);
			}
			
			//agrego a una lista
			$this->listAdd($cta->nombre,$per->id, 'cta');

			//list
			$this->listHandler(
				$per,
				unserialize(base64_decode($request->input('params')))
			);
			
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
        return response()->json($aResult);
    }
	
	protected function listHandler($per,$aParams) {
		//producto/s
		if (isset($aParams['rid'])) {
			$this->listHandlerProducto(
				$per,
				$aParams['rid'],
				(isset($aParams['producto_id']) ? $aParams['producto_id'] : null)
			);
		}
		//contenidos
		if (isset($aParams['seccId'])) {
			$this->listHandlerContenido(
				$per,
				$aParams['seccId'],
				(isset($aParams['contenido_id']) ? $aParams['contenido_id'] : null)
			);
		}
		
		//pagina general: nota, home,...
		if (isset($aParams['page'])) {
			$this->listHandlerGeneral($per,$aParams['page']);
		}
		
	}
	
	protected function listHandlerProducto($per, $rubroId, $productoId = null) {
			
		$rubro = Rubros::find($rubroId);
		
		$this->listAdd($rubro->nombre,$per->id, 'rubro');
		
		if ($productoId) {
			$prod = Productos::find($productoId);
			$this->listAdd(
				$prod->nombre . '_'.$prod->id,
				$per->id, 
				'producto'
			);

		}
		
	}
	
	protected function listHandlerContenido($per, $seccId, $contenidoId = null) {
		$secc = SeccionNota::find($seccId);
		$this->listAdd($secc->seccion,$per->id, 'nota_seccion');
		
		if ($contenidoId) {
			$nota = Note::find($contenidoId);
			$this->listAdd(
				$nota->titulo . '_'.$nota->id_nota,
				$per->id, 
				'nota'
			);

		}
		
	}
	
	protected function listHandlerGeneral($per, $page) {
			
		$this->listAdd($page,$per->id, 'pagina_general');
		
	}
	
	protected function listAdd($nombreLista, $perId, $tipo) {
		
		$lista = Lista::where('nombre','=',$nombreLista)->first();

		if (!$lista) {
			$lista = new Lista();
			$lista->nombre = $nombreLista;
			$lista->tipo = $tipo;
			$lista->autor = $nombreLista; //para saber el autor del cta
			$lista->save();
		}

		$listaPer =
		ListaPersona::where('id_persona', $perId)
			->where('id_lista',$lista->id)
			->first()
		;

		if (!$listaPer) {
			$listaPer = new ListaPersona();
			$listaPer->id_persona = $perId;
			$listaPer->id_lista = $lista->id;

			$listaPer->save();
		}
	}
	
	public function ctaEnabled(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();        
		
		$position = $request->input('position');
		
        if ($this->user->hasAccess('banners2' . '.view')) {
			
			$item = 
			Posicion::where('banners2_posiciones.nombre','like',$position)
				->join('banners2_banners2 as a','a.id_posicion','=','banners2_posiciones.id')
				->select('a.habilitado', 'a.id')
				->first()
			;
			
			if ($item) {
				$aResult['data'] = $item;
			} 
			
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
        return response()->json($aResult);
    }
	
	public function ctaGetByPosicion(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();        
		
		$position = $request->input('position');
		
        if ($this->user->hasAccess('banners2' . '.view')) {
			$items = 
			Posicion::where('banners2_posiciones.nombre','like',$position)
				->join('banners2_banners2 as a','a.id_posicion','=','banners2_posiciones.id')
				->leftJoin('banners2_tipos as b','b.id','=','a.id_tipo')
				->where('a.habilitado',1)
				->select(
					'a.id',
					'a.contenido',
					'a.contenido_id',
					'a.rubro_id',
					'a.producto_id',
					'a.repeticion'
				)
			;
			
			$aResult['data'] = 
				$this->selectCta(
					$items->get(), 
					explode('@', $position)[0],
					$request->input('aParams')
				);
			
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
		
        return response()->json($aResult);
    }
	
	protected function selectCta($items, $where, $aParams) {
		
		
		if ('contenido' === $where) {
			if(isset($aParams['contenido_id'])){
				$contById = 
				$items->filter(function($cta) use ($aParams){
					return $cta->contenido_id == $aParams['contenido_id'];
				});
				if ($contById->count() > 0) {

					return $contById->first();
				}
			}
							
			$contBySecc = 
			$items->filter(function($cta) use ($aParams){

				return $cta->contenido == $aParams['seccId'];
			});

			
			if ($contBySecc->count() > 0) {

				return $contBySecc->first();
			}
			
			$contAll = 
			$items->filter(function($cta){
				return 'all' === $cta->contenido;
			});
			
			if ($contAll->count() > 0) {

				return $contAll->first();
			}
			
		} //endif 'contenido'
		
		if ('producto' === $where || 'productos' === $where) {

			$prodById = 
			$items->filter(function($cta) use ($aParams){
				
				if (isset($aParams['producto_id'])) {
					return $cta->producto_id == $aParams['producto_id'];
				}
				
			});
			if ($prodById->count() > 0) {

				return $prodById->first();
			}
			
			$prodByRub = 
			$items->filter(function($cta) use ($aParams){

				return $cta->rubro_id == $aParams['rid'];
			});

			
			if ($prodByRub->count() > 0) {

				return $prodByRub->first();
			}
			
			$prodAll = 
			$items->filter(function($cta){
				return 'all' === $cta->rubro_id;
			});
			
			if ($prodAll->count() > 0) {

				return $prodAll->first();
			}

		} //endif productoss
		
		if ('home' === $where) {
			return $items->first();

		}
		
		if ('empresa' === $where) {
			return $items->first();

		}
		
		
	}
	
}