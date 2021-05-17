<?php
use Illuminate\Support\Facades\Hash;

function gets_sin($prm='', $sep='') {
    //$sret = '';
    if(!isset($sret)){
        $sret = array();
        return is_array($sret)?implode('&',$sret):'';
    }
    if (!$sep) $sep=strstr($prm,'&')?'&':',';
    
    if (!is_array($prm)) $prm=explode($sep,$prm);
    if (!is_array($prm)) $prm=array($prm);
    $xprm=$prm;
    if (is_array($prm))
    foreach($prm as $k=>$v) {
        list($k1,$v1)=explode('=',$v);
        unset($xprm[$k]);
        $xprm[$k1]=$v1;
    }
    
    //->eal: se
    if (is_array($_GET)) foreach($_GET as $k=>$v) 
    if (!array_key_exists($k,$xprm) || $xprm[$k]) 
    $sret[$k] = ($k.'='.($xprm[$k]?$xprm[$k]:$v));
    
    
    
    //<-eal  			
    
    if (is_array($xprm)) foreach($xprm as $k=>$v)
    if ((!is_array($sret) || !key_exists($k,$sret)) && $v)
    $sret[$k]=$k.'='.$v;
    
    return is_array($sret)?implode('&',$sret):'';    
}

 function isMovil(){

    $tablet_browser = 0;
    $mobile_browser = 0;
    $cualquier_movil=false;
    $body_class = 'desktop';
     
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
        $body_class = "tablet";

    }
     
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
        $body_class = "mobile";
    }
     
    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
        $body_class = "mobile";
    }
     
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda ','xda-');
     
    if (in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
    }
     
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
          $tablet_browser++;
        }
    }

    if ($tablet_browser > 0 || $mobile_browser > 0) {
        $cualquier_movil=true;
    }else{
        $cualquier_movil=false;
    }
    
    return $cualquier_movil;		
}

function getSomeToken($strSize = 42) {    
    return  $tokenKey = Hash::make(\str_random($strSize) . '_' . time());
}

function getRelacionados($id, $api){
    $page = 1;
	$array_send = array(
		'id_edicion' => 'MOD_WORK_FILTER',
		'edicion' => 'work',
		'id_relacion' => $id,
		'fotos' => 1,
		'orden' => array(
			'col' => env('ORDEN_COL'),
			'dir' => env('ORDEN_DIR')
		),
        'iDisplayLength' => 99, //registros por pagina
        'iDisplayStart' => (($page-1)*99) //registro inicial (dinamico)
	);
    $post = http_build_query($array_send);
    $res = $api->client->resJson('GET', 'relacionado?'.$post);
    $relacionados = array();

    if ($res['status'] == 0){
        $relacionados = $res['data'];        
    }
    
    return $relacionados;
}

function auth($data){

    $_SESSION['id_user'] = $data['id']; 
    $_SESSION['nombre'] = $data['nombre'];
    $_SESSION['apellido'] = $data['apellido'];
    $_SESSION['email'] = $data['mail'];
    
}
?>