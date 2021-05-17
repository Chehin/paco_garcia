<?php
/**
 * Clase DocumentoElectronico
 *
 * @author cesar
 */

namespace App\AppCustom;

define ('_LOG_', true);
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);
ini_set('default_socket_timeout', 120);

class DocumentoElectronico {

    static $taToken, $taSign, $taCUIT;
	
    public function setTA ($taToken, $taSign, $taCUIT) {
        \session(['taToken' => $taToken]);
        \session(['taSign' => $taSign]);
        \session(['taCUIT' => str_replace('-','',$taCUIT)]);

      
        \Log::info(\session('taToken'));
        
    }
    public function getTA (&$taToken, &$taSign, &$taCUIT) {
        $taToken=  \session('taToken');
        $taSign=  \session('taSign');
        $taCUIT= str_replace('-','', \session('taCUIT'));
    }

    private $arrayTC = array (
        1=>'FAC[A]',
        3=>'NDC[A]',
        4=>'REC[A]',
        6=>'FAC[B]',
        8=>'NDC[B]',
		9=>'REC[B]',
		11=>'FAC[C]',
		13=>'NDC[C]',
		15=>'REC[C]',
        51=>'FAC[M]',
        53=>'NDC[M]',
        54=>'REC[M]'
        );
    private $aDocTipo = array (
        80=>'CUIT',
        89=>'LE',
        90=>'LC',
        94=>'PAS',
        96=>'DNI',
    );
    public             $errno = 0, $errmsg = "";
    protected $clienteSoap,
            $token, $sign,
            $PRIVATEKEY, $CERT,
            $params,
            $autorizado = false,
            $PuntosVTA = array();
/**************** esto es para testeo **********************************/
	
	
	
   /*  private $fileKey= "cesar.key",
            $fileCERT = "certificado_afip_testing.crt",
            $CUIT=20230556432;
    private $WSAAURL="https://wsaahomo.afip.gov.ar/ws/services/LoginCms",
            $WSURL="https://servicios1.afip.gov.ar/wsfev1/service.asmx";
	 */
	
	
/**************** fin de: esto es para testeo **********************************/
/**************** esto es para produccion ***********************************/
    private $fileKey= "/var/www/html/vhosts/puertoonline.com/wxpanel/fe/20247906933.key",
            $fileCERT = "/var/www/html/vhosts/puertoonline.com/wxpanel/fe/webexport2-virginia_72155960d52bfac6.crt",
            $CUIT=30610966221; // cuit de la agencia (cesar)
    private $WSAAURL="https://wsaa.afip.gov.ar/ws/services/LoginCms",
            $WSURL="https://serviciosjava.afip.gov.ar/wsmtxca/services/MTXCAService";
/**************** fin de: esto es para produccion **********************************/
    private $WSAAWSDL= "wsaa.wsdl",
            $WSWSDL="wsfev1n.wsdl",
            $service="wsfe";

    protected $PATH = "";

    protected function setPrivados ($service, $WSWSDL, $WSURL, $CERT=null, $CUIT=NULL, $filePriv = NULL) {
        $this->service = $service;
        $this->WSWSDL = $WSWSDL;
        $this->WSURL = $WSURL;
        if ($CERT) {$this->CERT = $CERT;}
        if ($CUIT) {$this->CUIT = str_replace('-','',$CUIT);}
        $PATH=realpath('.').'\fe\\';
        $this->PATH = $PATH;
        if ($filePriv!==NULL) {
            //$this->PRIVATEKEY = openssl_pkey_get_private(file_get_contents($PATH.$filePriv));
			$this->PRIVATEKEY = openssl_pkey_get_private(file_get_contents($filePriv));
        }
    }

    function __construct($p='') {
        session_start();
        if($p) {$this->PATH=$p;}
        if (!defined('_PRODUCCION_') || (_PRODUCCION_ !== true)) {
            /**************** esto es para testeo **********************************/
            $this->fileKey= "cesar.key";
            $this->fileCERT = "certificado_afip_testing.crt";
            $this->CUIT=20230556432;
            $this->WSAAURL="https://wsaahomo.afip.gov.ar/ws/services/LoginCms";
            $this->WSURL="https://servicios1.afip.gov.ar/wsfev1/service.asmx";
        }
        $ini = ini_set("soap.wsdl_cache_enabled", 0);
        $PATH=$this->PATH;
        if (!$PATH) {$PATH=realpath('.').'\fe\\';}
        $this->CERT = file_get_contents($PATH.$this->fileCERT);
        $this->PRIVATEKEY = openssl_pkey_get_private(file_get_contents($PATH.$this->fileKey));
    }
    public function getCUIT() {
        $cuit = $this->CUIT;
        return (float)(str_replace(array('-','.'),'',$cuit));
    }
    private function CreateTRA() {
      $TRA = new \SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>' .
        '<loginTicketRequest version="1.0">'.
        '</loginTicketRequest>');
      $TRA->addChild('header');
      $TRA->header->addChild('uniqueId',date('U'));
      $TRA->header->addChild('generationTime',date('c',date('U')-600));
      $TRA->header->addChild('expirationTime',date('c',date('U')+600));
      $TRA->addChild('service',$this->service);
      return $TRA;
    }
    protected function log ($LOG="") {
        $LOG = sprintf("%s %s\n", date('d/m/Y H:i:s'), $LOG);
        $Ym = date('Ym');
        if (defined('_LOG_') && _LOG_===true) { file_put_contents($this->PATH.'hola_mundo'.$Ym.'.txt', $LOG,FILE_APPEND);}
    }
    protected function setError ($cod, $msg) {
        $this->errno = $cod;
        $this->errmsg = $msg;
        $LOG = sprintf("ERROR SOAP!!! Cod: %s  ERROR: %s",$cod,$msg);
        $this->log($LOG);
    }
    protected function soapError ($results) {
        if (is_soap_fault($results)) {
          $this->setError($results->faultcode,$results->faultstring);
          return true;
      }
      return false;
    }
    private function SignTRA($TRA) {
        $tmpfname = tempnam("/wsfe", "WSFE");
        $tmpTRAtmp= tempnam("/wsfe", "WSFE");
        file_put_contents($tmpfname, $TRA->asXML());
        $STATUS=openssl_pkcs7_sign($tmpfname, $tmpTRAtmp, $this->CERT,
            array($this->PRIVATEKEY ,''),
            array(),
            !PKCS7_DETACHED
        );
        $CMS="";
        if (!$STATUS) {
            $this->log(getcwd());
           $this->setError(1,"ERROR al generar la firma, VERIFICAR CLAVE PRIVADA");
        }
        else {
          $inf=fopen($tmpTRAtmp, "r");
          $i=0;
          while (!feof($inf)) {
              $buffer=fgets($inf);
              if ( $i++ >= 4 ) {$CMS.=$buffer;}
          }
          fclose($inf);
          unlink($tmpfname);
          unlink($tmpTRAtmp);
        }
        return $CMS;
    }
    private function CallWSAA($CMS) {
      $PATH=$this->PATH;
      if (!$PATH) {$PATH=realpath('.').'\fe\\';}
      // si no devuelve false, un posible error es porque el certificado usado no es correcto.
      $clienteSoap =new \SoapClient($PATH.$this->WSAAWSDL, array(
              'soap_version'   => SOAP_1_2,
              'location'       => $this->WSAAURL,
              'exceptions'     => 0
              ));
      $this->log("Llamando a loginCms...");
      $results=$clienteSoap->loginCms(array('in0'=>$CMS));
      if ($this->soapError($results)) { return false;}
      return $results->loginCmsReturn;
    }
    private function setParams () {
        $this->params = (object) array(
                    'authRequest' => (object) array (
                        'token' => $this->token,
                        'sign' => $this->sign,
                        'cuitRepresentada' =>  $this->getCUIT()
                    )
                );
        return $this->params;
    }
    protected function createSoap () {
        $ok = true;
        if ($this->clienteSoap instanceof \SoapClient) { return true;}
        $this->log("createSoap..");
        try {
            $this->log('context_create..');
            $context = stream_context_create([
                'ssl' => [
                    // set some SSL/TLS specific options
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            $this->log('new SoapCliente..');
            $this->clienteSoap = new \SoapClient ($this->WSWSDL,
                array(
//                        'soap_version' => SOAP_1_2,
//                      'location'     => $this->WSURL,
//                      'cache_wsdl'   => WSDL_CACHE_NONE,
                      'exceptions'   => 0,
//                      'encoding'     => 'ISO-8859-1',
//                      'features'     => SOAP_USE_XSI_ARRAY_TYPE + SOAP_SINGLE_ELEMENT_ARRAYS,
//                      'trace'        => 0,
                      'keep_alive'   => false, // uh, solo si php version >= 5.4
//                    'connection_timeout' => 600, //120,
                    'stream_context' => $context,
					'trace' => 1, //para podear loguear los xmls request/response (martincito)
//                    'ssl_method' => SOAP_SSL_METHOD_SSLv3
                    ));
            $this->clienteSoap->__setLocation($this->WSURL);
        }
        catch (Exception $ex) {
            $this->setError($ex->faultcode, $ex->faultstring);
            $ok = false;
        }
        return $ok;
    }
	
    public function autorizar ($CERT = null, $CUIT=null) {
        set_time_limit(0);
        $this->params = null;
        $ok = false;
        if (defined('_PRODUCCION_') && (_PRODUCCION_ === true)) {
            if ($CERT) {$this->CERT = $CERT;}
            if ($CUIT) {$this->CUIT = $CUIT;}
        }
        $TRA=$this->CreateTRA();
		$this->log("autorizar()");
        if (($CMS=$this->SignTRA($TRA)) &&
             ($TA=$this->CallWSAA($CMS))) {
            $TA=new \SimpleXMLElement($TA);
            $this->token = (string)($TA->credentials->token);
            $this->sign = (string)($TA->credentials->sign);
            $this->setTA($this->token, $this->sign, $this->CUIT);
            $ok=true;
			$this->log("autorizar(): token nuevo");
        } elseif ($this->errno=="ns1:coe.alreadyAuthenticated") {
			
			$this->log("autorizar(): ns1:coe.alreadyAuthenticated");
			
            $this->getTA($taToken, $taSign, $taCUIT);
            if ($taCUIT==$this->CUIT)  {
				$this->log("autorizar(): reutilizacion token");
                $this->token=$taToken;
                $this->sign=$taSign;
				
				$this->errno = null;
                $ok=true;
            }
        }
        if ($ok) {
            $this->setParams();
            $ok = $this->createSoap();
        }
        $this->autorizado = $ok;
        return $ok;
    }
    /*********
     * Devuelve un array con los puntos de venta disponible para
     * el cuit actual o false si hay algun error
     ************/
    public function obtenerPuntosVTA () {
        $this->log("obtenerPuntosVTA");
        $results=$this->clienteSoap->consultarPuntosVenta($this->params);
        // Si hay error de soap que devuelva false
        $ret = false;
        if (!$this->soapError($results)) {
            $ret = $results->arrayPuntosVenta;
            $this->PuntosVTA = $ret;
        }
        return $ret;
    }
    protected function corregirValores (&$TC, &$PV) {
      if (!$PV && is_array($this->PuntosVTA) && count($this->PuntosVTA)) {
          $PV = $this->PuntosVTA[0];
      }
      if (is_string($TC)) {$TC = $this->tipoComprobante($TC);}
      return $this;
    }
    public function obtenerUltimoComprobante ($TC, $PV = null) {
        $this->log("obtenerUltimoComprobante");
        $this->corregirValores ($TC, $PV);
        $params = $this->params;
        $params->consultaUltimoComprobanteAutorizadoRequest->codigoTipoComprobante=$TC;
        $params->consultaUltimoComprobanteAutorizadoRequest->numeroPuntoVenta=$PV;
        $results=$this->clienteSoap->consultarUltimoComprobanteAutorizado($params);
        $ret = false;
        if ($this->soapError($results)) {
            printf("Fault: %s\nFaultString: %s\n",$results->faultcode, $results->faultstring);
        }
        elseif (isset($results->arrayErrores)) {
            foreach ($results->arrayErrores->codigoDescripcion as $E) {
                //printf("%d3  %s\n",$E->codigo, $E->descripcion);
                //  1502 => Para la CUIT, Tipo de Comprobante y Punto de Ventas requeridos
                //  no se registran comprobantes en las bases del Organismo
                if ($E->codigo==1502) {$ret=0;}
            }
        }
        else {$ret =$results->numeroComprobante;}
        return $ret;
      }

      public function tipoComprobante ($TC) {
          $ret = "";
          if (is_numeric($TC)) { $ret = $this->arrayTC[$TC];}
          else {$ret = array_search(strtoupper($TC), $this->arrayTC);}
          return $ret;
      }
      public function tipoDocumento ($TD) {
          $ret = "";
          if (is_numeric($TD)) {$ret = $this->aDocTipo[$TD];}
          else {$ret = array_search(strtoupper($TD), $this->aDocTipo);}
          return $ret;
      }
      public function tipoLetra ($TC, &$tipo=null, &$letra=null) {
        if (is_numeric($TC)) { $TC = $this->arrayTC[$TC];}
        $tipo = substr($TC, 0, 3);
        $letra = substr($TC,4,1);
        return array($tipo, $letra);
     }
}
