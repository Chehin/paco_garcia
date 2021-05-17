<?php

/**
 * Clase WSFE
 * Web Service Factura Electronica.
 * @author Cesar Villafañe <cesarv@sigmma.net>
 */

namespace App\AppCustom;

class WSFE extends DocumentoElectronico {
    /************* Esto es para testeo ****************
    private $WSURL="https://wswhomo.afip.gov.ar/wsfev1/service.asmx";
    /************* Esto es para produccion ****************/
    private $WSURL="https://servicios1.afip.gov.ar/wsfev1/service.asmx";
    /*****************************/
    protected $comp;
    function __construct($CUIT = '', $CERT = '', $filePrivKey = '') {
        if (!defined('_PRODUCCION_') || (_PRODUCCION_ !== true)) {
            /**************** esto es para testeo **********************************/
            $this->WSURL="https://wswhomo.afip.gov.ar/wsfev1/service.asmx";
        } 
        $PATH = "";
        parent::__construct($PATH);
        $this->log("__construct wsfe");
        $PATH=$this->PATH;

      if (!$PATH) {$PATH=realpath('.').'\fe\\';}
        parent::setPrivados ("wsfe", $PATH."wsfev1n.wsdl", $this->WSURL, $CERT, $CUIT, $filePrivKey);
		
    }

    private function setParams () {
        $this->params = new \stdClass();
        $this->params->Auth =(object) array (
                    'Token' => $this->token,
                    'Sign' => $this->sign,
                    'Cuit' => $this->getCUIT()
                );
        return $this->params;
    }
    public function autorizar($CERT = null, $CUIT=null) {
        if ($ok = parent::autorizar($CERT, $CUIT)) {$this->setParams();}
        return $ok;
    }

    /*********
     * Devuelve un array con los puntos de venta disponible para
     * el cuit actual o false si hay algun error
     ************/
    public function obtenerPuntosVTA () {
        $results=$this->clienteSoap->FEParamGetPtosVenta($this->params);
        // Si hay error de soap que devuelva false
        $ret = false;
        if (!$this->soapError($results)) {
            $ret = $results->arrayPuntosVenta;
            $this->PuntosVTA = $ret;
        }
        return $ret;
    }
    private function printValores ($method, $root) {
        //printf("Method=%s\n",$method);
		$ret = null;
        foreach ($root as $nodo) { 
			$ret = print_r($nodo, true);
			
		}
		
		return $method . PHP_EOL . $ret;
    }
    public function obtenerUltimoComprobante ($TC, $PV = null) {
        $this->log("obtenerUltimoComprobante");
        $ret = false;
        if ($this->createSoap()) {
            $this->corregirValores ($TC, $PV);

            $method="FECompUltimoAutorizado";
            $params = $this->params;
            $params->PtoVta = $PV;
            $params->CbteTipo = $TC;
           if ($X= $this->llamarSOAP($method, $params)) {
                if (isset($X->Errors)) { 
					$this->log($this->printValores($method, $X->Errors));
					
				}
                //elseif (count($X->Events)) { $this->printValores($method, $X->Events);}
                else {
					$ret =$X->CbteNro;
				}
            }
        }
        return $ret;
      }

      public function datosCliente ($DocTipo, $DocNro) {
          if ($DetReq = $this->params->FeCAEReq->FeDetReq->FECAEDetRequest) {
            $DetReq->DocTipo = (int)$DocTipo;
            $DetReq->DocNro = (float)$DocNro;
          }
          return $this;
      }
      public function datosCabecera ($nroCbte, $fecha) {
          if ($DetReq = $this->params->FeCAEReq->FeDetReq->FECAEDetRequest) {
              $DetReq->CbteDesde = (int)$nroCbte;
              $DetReq->CbteHasta = (int)$nroCbte;

              $DetReq->CbteFch = $fecha;
              $DetReq->FchServDesde=$fecha;
              $DetReq->FchServHasta=$fecha;
              $DetReq->FchVtoPago=$fecha;
          }
          return $this;
      }
      public function getTotalIVA () {
          $total = 0;
          $arrayIVA = $this->params->FeCAEReq->FeDetReq->FECAEDetRequest->Iva;
          if (is_array($arrayIVA)) {
              foreach ($arrayIVA as $k=>$i) {
                  if (is_array($i) && key_exists('Importe', $i)) {$importe = $i['Importe'];}
                  elseif ($i->Importe!==null){$importe = $i->Importe;}
                  else {$importe = $i->AlicIva->Importe;}
                  $total += (float)$importe;
              }
          }
          return $total;
      }
      /**************
       * @param float $impTotConc Importe neto no gravado.
       * @param float $impNeto Importe neto gravado.
       * @param float $impOpEx Importe exento.
       * *****************/
      public function datosTotales ($impTotConc,$impNeto,$impOpEx, $totalDeseado = 0, $impTrib=0) {
          if ($DetReq = $this->params->FeCAEReq->FeDetReq->FECAEDetRequest) {
              /*********
                Importe total del comprobante, Debe ser
                igual a Importe neto no gravado + Importe
                exento + Importe neto gravado + todos los
                campos de IVA al XX% + Importe de
                tributos.
               * En Factura/SIGMMA = TOTAL = db:Total
                *****************/
              $impIVA = $this->getTotalIVA();
              \Log::info('obtiene el iva');
              \Log::info($impIVA);
              $impTotal= $impTotConc + $impOpEx + $impNeto + $impIVA + $impTrib;
              if($totalDeseado>$impTotal) {
//                    $diferencia = $impTotal - $totalDeseado;
                    $impTotal = $totalDeseado;
//                    $impTotConc+=$diferencia;

                    $impTotConc = $impTotal - ($impOpEx + $impNeto + $impIVA + $impTrib);
                    if ($impTotConc<0) { $impTotConc=0;}
              }
              $DetReq->ImpTotal=round($impTotal,2);

              /******
                Importe neto no gravado.
                Debe ser menor o igual a Importe total y
                no puede ser menor a cero.
                No puede ser mayor al Importe total de la
                operación ni menor a cero (0).
                Para comprobantes tipo C debe ser igual a
                cero (0).
                Para comprobantes tipo Bienes Usados
                – Emisor Monotributista este campo
                corresponde al importe subtotal
               *
               * Factura/Sigmma = No Computable + (RG?)= db:no_computable + db:rg?
               * *********/
              $DetReq->ImpTotConc=round($impTotConc,2);

              /*******
                Importe neto gravado. Debe ser menor o
                igual a Importe total y no puede ser menor a cero.
                Para comprobantes tipo C este
                campo corresponde al Importe del Sub Total.
                Para comprobantes tipo Bienes Usados
                – Emisor Monotributista no debe informarse
                o debe ser igual a cero (0)
               *
               * Factura/Sigmma = Gravado+T.Gravado
               * db = Gravado+ trans_gvo
               * ***********/
              $DetReq->ImpNeto=round($impNeto,2);

              /*********
                Importe exento. Debe ser menor o igual a
                Importe total y no puede ser menor a cero.
                Para comprobantes tipo C debe ser igual a
                cero (0).
                Para comprobantes tipo Bienes Usados –
                Emisor Monotributista no debe informarse
                o debe ser igual a cero (0).
               * sigmma = Exento
               * db = exento
               * *************/
              $DetReq->ImpOpEx=round($impOpEx,2);

              /************
               * Suma de los importes del array de tributos
               * *************/
              $DetReq->ImpTrib=round($impTrib,2);

              /*******
                Suma de los importes del array de
                IVA.
                Para comprobantes tipo C debe ser igual a
                cero (0).
                Para comprobantes tipo Bienes Usados –
                Emisor Monotributista no debe informarse
                o debe ser igual a cero (0).
               * sigmma =  IVA RI + IVA RNI + IVA Tranposrte
               * db = iva + iva_rni + iva_tr
               * ********/
              $DetReq->ImpIVA=round($impIVA,2);
              \Log::info('impIVA');
              \Log::info($impIVA);
              if (!round($DetReq->ImpIVA,2) && !round($DetReq->ImpNeto,2)) {$DetReq->Iva = null;}
          }
          return $this;
      }
      protected function llamarSOAP($method, $params = null) {
          $this->log("llamarSOAP($method...)\n params= ".print_r($params,true));
        $ret = false;
        if ($params == null) {$params = $this->params;}
        $results=$this->clienteSoap->$method($params);
        $this->log("... result= ".print_r($results,true));
        if (!$this->soapError($results)) {
            $ret = $results->{$method.'Result'};
            $ret = $ret ? $ret : $results;
        }
		
		$this->log("WSURL: " . $this->WSURL);
		$this->log("XML Request: " . $this->clienteSoap->__getLastRequest());
		$this->log("XML Response: " . $this->clienteSoap->__getLastResponse());

        return $ret;
      }

      public function completarDatosBasicos ( $TC, $PV) {
          $this->corregirValores ($TC, $PV)->setParams();
          $this->params->FeCAEReq = (object) array (
              'FeCabReq' => (object) array (
                  'CantReg' => 1,
                  'PtoVta' => (int)$PV,
                  'CbteTipo' => (int)$TC
              ),
              'FeDetReq' => (object) array (
                  'FECAEDetRequest' => (object) array (
                      'Concepto'=>2, // 2=Servicios
                      'DocTipo' => null, // Tipo de doc del cliente
                      'DocNro' => null, // Nro de doc del cliente

                      'CbteDesde'=>null,
                      'CbteHasta'=>null,

                      'CbteFch'=>null,
                      'ImpTotal'=>null,
                      'ImpTotConc'=>null,
                      'ImpNeto'=>null,
                      'ImpOpEx'=>null,
                      'ImpTrib'=>null,
                      'ImpIVA'=>null,
                      'FchServDesde'=>null,
                      'FchServHasta'=>null,
                      'FchVtoPago'=>null,
                      'MonId'=>'PES',
                      'MonCotiz'=>1,
                      'CbtesAsoc' => null,
                      //'Tributos'=>array (),
                      'Iva'=> null,
                      //'Opcionales'=>array ()
              )
          )
      );
      return $this;
  }

      public function solicitarCAE(&$aRet, &$aErr) {
          \Log::info('solicitarCAE');
        $ret = "";
        if ($this->autorizado) {
            set_time_limit(0);
            $this->log("solicitarCAE... params=".print_r($this->params, true));
            $result =  $this->llamarSOAP('FECAESolicitar');
            \Log::info('result');
            \Log::info(print_r($result,true));

            if (isset($result->Errors->Err)) { 
                $aErr = $result->Errors->Err;
            }
            
            if ($this->errno) {
                $aErr[] = sprintf("Error: %s %s ", $this->errno,$this->errmsg);
                $ret="";
            } else {
                $aRet = $result->FeDetResp->FECAEDetResponse;
                if (is_array($aRet)) {$aRet = $aRet[0];}
                /********* Esta es la informacion necesaria para poder validar...
                 * tambien nos va a servir para informar los datos con los que se
                 * puede comprobar el CAE en la pagina de AFIP:
                 *  http://www.afip.gob.ar/genericos/consultaCAE/
                 */
                $resp = $result->FeCabResp;
                $json = $this->params->FeCAEReq->FeDetReq->FECAEDetRequest;
                $json->DocTipo = $this->tipoDocumento($json->DocTipo);
                $json->cuit=$resp->Cuit;
                $json->Comprobante = sprintf('%s-%04d-%08d',
                        $this->tipoComprobante($resp->CbteTipo),
                        $resp->PtoVta,
                        $aRet->CbteDesde
                        );
                        \Log::info('json 1');
                        \Log::info(print_r($json,true));
                        \Log::info(print_r($json->CbtesAsoc,true));
                if (is_array($json->CbtesAsoc)) {
                    $comprobantes = array();
                    foreach ($json->CbtesAsoc as $cbte) {
                        $comprobantes[] = sprintf('%s-%04d-%08d',
                                $this->tipoComprobante($cbte['Tipo']),
                                $cbte['PtoVta'],
                                $cbte['Nro']);
                    }
                    $json->asociados = implode(' ', $comprobantes);
                }
                    if (!$aRet) {$aRet = (object) array('CAE'=>'','json'=>null);}
                $json->CAE = $aRet->CAE;
                $aRet->json = $json;
                \Log::info('json 2');
                \Log::info(print_r($json,true));
                /******************************************/
                if (isset($result->Errors->Err)) { 
                    $aErr = $result->Errors->Err;
                        if ($this->errno) {
                            $aErr[] = sprintf("Error: %s %s ", $this->errno,$this->errmsg);;
                        }
                }
                $ret = $aRet->CAE;
            }
        } else {
            $aErr = array("Operacion no autorizada por AFIP");
            if ($this->errno) {
                $aErr[] = sprintf("Error desde AFIP: %s %s ", $this->errno,$this->errmsg);
            }
            $this->log(print_r($aErr,true));
        }
        \Log::info('ret');
        \Log::info(print_r($ret,true));
        return $ret;
      }

      public function consultarComp ($TC, $PV=null, $NRO=null) {
            if ($TC && !$PV) {
                list ($TC, $PV, $NRO) = explode('-',$TC);
            }
            $this->corregirValores ($TC, $PV)->setParams();
            $this->params->FeCompConsReq =  (object) array (
                'CbteTipo'=>$TC,
                'CbteNro'=>$NRO,
                'PtoVta'=>$PV,
            );
            $ret = false;
            if ($this->autorizado) {
                $ret = $this->llamarSOAP('FECompConsultar');
                if ($ret->ResultGet && $ret->ResultGet->ImpTotal) {
                    $json = &$ret->ResultGet;
                    $json->DocTipo = $this->tipoDocumento($json->DocTipo);
                } else {
                    $ret=false;
                }
            } else {
                $aErr = array("Operacion no autorizada por AFIP");
                if ($this->errno) {
                    $aErr[] = sprintf("Error desde AFIP: %s %s ", $this->errno,$this->errmsg);
                }
                $this->log(print_r($aErr,true));
            }
        return $ret;
      }

      public function FACB ($PV, $datos) {
        $ret = false;
        if ($this->autorizado) {
            list($id, $TD, $DocNro, $exento) = $datos;
            $TC='FAC[B]';
            $NRO=$this->obtenerUltimoComprobante($TC, $PV);
            $NRO= (int)$NRO + 1;
            $fecha = date('Ymd');

            $docTipo = $this->tipoDocumento($TD);
            $this->completarDatosBasicos($TC, $PV)
                      ->datosCabecera($NRO,$fecha)
                      ->datosCliente($docTipo, $DocNro );

            $this->datosTotales(0, 0,$exento);
            $ret = true;
        }
        return $ret;
      }
	  
	  public function emitir ($comprob, $PV, $datos) {
        $ret = false;
        if ($this->autorizado) {
            list($id, $TD, $DocNro, $importe, $importeneto, $importeiva) = $datos;
            $TC = $comprob;
            $letra = substr($TC,4,1);
            $NRO=$this->obtenerUltimoComprobante($TC, $PV);
            $NRO= (int)$NRO + 1;
            $fecha = date('Ymd');
            

            $docTipo = $this->tipoDocumento($TD);
            $this->completarDatosBasicos($TC, $PV)
                      ->datosCabecera($NRO,$fecha)
                      ->datosCliente($docTipo, $DocNro );

            /*********************
            Para tipo C:
            ImpTotConc = 0 (imp neto no gravado)
            ImpNeto corresponde al subtotal. (imp neto gravado)
            ImpOpEx =0 (imp exento)
            ImpIVA=0 (imp de iva)
            iva 0% ==> 3
            iva 10.5% ==> 4
            iva 21% ==> 5
            iva 27% ==> 6
            * ********* ************/
            if ($letra=='C') {
              
                $impTotConc = 0;
                $impNeto=$importe;
                $impOpEx=0;
               
            }
            
            if ($letra=='B') {
                $impTotConc = 0;
                $impNeto=$importe;
                $impOpEx=0;
                $this->agregarAlicIVA(3,$importe, 0);
            }
            elseif ($letra=='A') {
                $impTotConc = 0;
                $impNeto = 0;
                $impOpEx = 0;
            
                if($importeneto['importeneto21']>0){
                    $impNeto = $impNeto + $importeneto['importeneto21'];
                    $this->agregarAlicIVA(5, $importeneto['importeneto21'], $importeiva['importeiva21']);
                }
                if($importeneto['importeneto27']>0){
                    $impNeto =  $impNeto + $importeneto['importeneto27'];
                    $this->agregarAlicIVA(6, $importeneto['importeneto27'], $importeiva['importeiva27']);
                }
                if($importeneto['importeneto10']>0){
                    $impNeto =  $impNeto + $importeneto['importeneto10'];
                    $this->agregarAlicIVA(4, $importeneto['importeneto10'] , $importeiva['importeiva10']);
                }
               /*  if(){
                    $this->agregarAlicIVA(0, $importeneto['importeneto10'] , $importeiva);
                } */
    
                $impNeto = round($impNeto,2);            
            }
                    
            $this->datosTotales($impTotConc, $impNeto , $impOpEx);
            $ret = true;
        }

        return $ret;
      }

      public function agregarAlicIVA ($id,$base,$importe) {

        $items = &$this->params->FeCAEReq->FeDetReq->FECAEDetRequest->Iva;
        $base=round($base,2);
        $importe=round($importe,2);
        if (($base>0) && $id) {
            $AlicIva = array ();
            $AlicIva['Id']=(int)$id;
            $AlicIva['BaseImp']=round($base,2);
            $AlicIva['Importe']=round($importe,2);
            //$i=count($items);
            $items[]=$AlicIva;
          }
          return $this;
      }
	  
	  

}
