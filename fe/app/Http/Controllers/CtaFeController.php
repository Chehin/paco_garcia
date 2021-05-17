<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class CtaFeController extends Controller{
	
	protected $cta = null;
	protected $show = false;
	protected $pageId = '';
	protected $position = null;
	protected $ctaId = null;
	protected $aParams = [];

	public function __construct() {
		
	}

	static function inicial($position, $aParams){
		$position = $position;
		$aParams = $aParams;
		
		$render = new CtaFeController(); 
		$render->render($position,$aParams);
	}

	public function ctaAjx(Request $request, Api $api){
		$aResult=Util::aResult();
	
		if ($request['cta1']) {
			unset($_POST['cta1']);
			$post = http_build_query($_POST);		
			try {			
				$aResult = $api->client->resJson('GET', 'ctaSet?'.$post);
				return response()->json($aResult);
			} catch (RequestException $e) {
				Log::error(Psr7\str($e->getRequest()));
				if ($e->hasResponse()) {
					Log::error($e->getMessage());
				}
			}	
		}

	}

	public function cta($ctaId,$params,Api $api){
		header('Access-Control-Allow-Origin: *');
		$aCtaData=Util::aResult();
		$data=array(
			'ctaId' => $ctaId
		);
		
		try {			
			$post = http_build_query($data);
			$aCtaData = $api->client->resJson('GET', 'ctaGet?'.$post)['data'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
		}
		
		return view('cta.cta',compact('aCtaData','params','ctaId'));
	}
	
	public function render($position,$aParams) {
		if ($this->ctaEnabled($position,$aParams)) {
			
			$this->prepareCta($position,$aParams);
			
			if (!$this->show) {
				return false;
			}
			
			$type = $this->getType($position);
			switch($type){
				case 'popup':
					$this->renderModal($position,$aParams);
					break;
				case 'content':
					$this->renderContent($position,$aParams);
					break;
				default:
					Log::info("CTA: Tipo de CTA no encontrado: $type");
			}
		}
	}
	
	protected function prepareCta($position,$aParams) {
		if ($this->cta['contenido'] || $this->cta['contenido_id']) {
			
			if ('all' !== $this->cta['contenido']) {
				
				//para el session storage
				if ($this->cta['contenido']) {
					$this->pageId = '_' . $this->cta['contenido'];
				}
				
				if ($this->cta['contenido_id']) {
					$this->pageId .= '_' . $this->cta['contenido_id'];
				}
				
				$this->show = true;
				
			} else { //todas las notas
				//para crear luego la lista
				$aParams['page'] = 'cualquier nota';
				$this->show = true;
			}
		}
		
		//productos y producto
		if ($this->cta['rubro_id'] || $this->cta['producto_id']) {
			
			if ('all' !== $this->cta['rubro_id']) {
				
				//para el session storage
				if ($this->cta['rubro_id']) {
					$this->pageId = '_' . $this->cta['rubro_id'];
				}
				
				if ($this->cta['producto_id']) {
					$this->pageId .= '_' . $this->cta['producto_id'];
				}
				
				$this->show = true;
				
			} else { //todas las notas
				//para crear luego la lista
				$aParams['page'] = 'cualquier producto';
				$this->show = true;
			}
		}
//		if ($this->aParams['rid']) { 
//			$this->show = true;
//		}
		
		//home
		if (isset($aParams['home'])) {
			$aParams['page'] = 'home';
			$this->show = true;
		}
		
	}
	
	protected function renderModal($position,$aParams) {
		ob_start();
?>
<script>

	$(function(){
		var ctaId = <?php echo $this->ctaId ?>;
		var ctaStorage = "cta.<?php echo $position . $this->pageId; ?>";
		var params = '<?php echo base64_encode(serialize($aParams)); ?>';
		var name = "ctaInternal"+ctaId;
		
		<?php if(!isset($_COOKIE['ctaInternal'.$this->ctaId])) {?>
			setCookie("ctaInternal"+ctaId, 'Shown', 365);	//en caso que no exista creo una cookie por cada popup
		<?php } ?>
		
		var ctaTest = getCookie(name);
		
		$.get("cta/" + ctaId + '/' + params, function(result){
			
			$('#ctaModal').remove();
			$('body').append('<div id="ctaModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">' + result + '</div>');
			$('#ctaModal').on('shown.bs.modal', function (e) {
				$('body').removeClass('modal-open').css('padding-right',0);
			});
			
			<?php if (0 === $this->cta['repeticion']): ?>
					
					$("#ctaModal").modal('show');
					
			<?php else:?>
		
				if(ctaTest=='Shown'){
					$("#ctaModal").modal('show');
					setCookie("ctaInternal"+ctaId, 'noShown', <?php echo $this->cta['repeticion'] ?>);//seteo la cookie por 7 dias para que no muestre el popup
				}else{
					<?php if(!isset($_COOKIE['ctaInternal'.$this->ctaId])) {?>
						setCookie("ctaInternal"+ctaId, 'Shown', <?php echo $this->cta['repeticion'] ?>);//cuando la cookie expire la vuelvo a crear por 7 dias mas
					<?php } ?>
				}
			
			<?php endif;?>
	
		});
		//boton para volver a abrir
		$("#moreInfo").show();
		$("#moreInfo").on('click',function(){
			$("#ctaModal").modal('show');
		});
	});

	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

</script>
<?php 
		$out = ob_get_contents();
		ob_end_clean();
		
		echo $out;

	} //ends renderModal
	

	
	protected function ctaEnabled($position,$aParams) {
		$api = new Api;
		$aItem=Util::aResult();

		$data=array(
			'position' => $position,
			'aParams' => $aParams
		);

		try {			
			$post = http_build_query($data);
			$aItem = $api->client->resJson('GET', 'ctaGetByPosicion?'.$post);
			if ($aItem['data']) {
				$this->cta = $aItem['data'];
				$this->ctaId = $aItem['data']['id'];
				
				return true;
				
			}

		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
		}	
	}
	
	protected function getType($position) {
		return explode('@', $position)[1];
	}
	
	protected function renderContent() {
		if ($this->cta['texto']) {
			echo $this->cta['texto'];
			echo '<br>';
			
			if ($aItem['data']['habilitado']) {
				return $aItem['data']['habilitado'];
			} else {
				return 0;
			}
		}
		
		echo $this->cta['codigo'];
		
	}
	
}
