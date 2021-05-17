<?php

namespace App\Http\Controllers;

use PDF;
use App\AppCustom\UtilFe;
use App\AppCustom\Util;
use App\AppCustom\Wsfe;
use Illuminate\Http\Request;
use App\AppCustom\Models\VistaFactura;
use \Milon\Barcode\DNS1D;

class FacturaElectronicaController extends Controller
{

    public function index(Request $request)
    {
        $aResultJson = Util::getDefaultArrayResult();        
        $establecim = UtilFe::getCompay();
                    
        
        if (!$establecim) {
            $aResultJson['status'] = 1;
            $aResultJson['msg'] = 'La Empresa no existe';
        }
        
        
        $aToFac = UtilFe::getCobranzaData($request['id']);
        
        if (empty($aToFac)) {
            $aResultJson['status'] = 1;
            $aResultJson['msg'] = 'No se ha encontrado esta cobranza o ya ha sido procesada';
        }else{
            if($aToFac['status']==1){
                $aResultJson['status'] = 1;
                $aResultJson['msg'] = $aToFac['msg'];
            }
        }

        
        //Error?
        if (1 === $aResultJson['status']) {
            return response()->json($aResultJson);
        }else{

            if ('local' == \env('APP_ENV')) {	
                //TEST
                $certCompany = base_path('public\fe\webexport-certificado-test.crt');
                $privCompany  = base_path('public\fe\privada_test');
                $cuitCompany = 20247906933;
            } else {
                //PRODUCCION
                $certCompany = base_path('public\fe\webexport2-virginia_72155960d52bfac6.crt');
                $privCompany  = base_path('public\fe\20247906933.key');
                
                //afip config
                $cuitCompany = $establecim["cuit"];                
            }
        
            // Obtengo el punto de venta 
            if ($request['id']) {
                $ptoVtaComprobante = UtilFe::getPtoVenta($request['id']);
                $ptoVta = $ptoVtaComprobante['punto_venta'];
                $comprobante = UtilFe::getTipoComprobante($ptoVtaComprobante['id_tipo_comprobante']);
            }else{
                $aResultJson['status'] = 1;
                $aResultJson['msg'] = 'Comprobante - Pto. de Venta no especificado para: '.\env('MAIL_FROM_NAME');
                exit(json_encode($aResultJson));
            } 
            
            if (empty($ptoVta)) {
                $aResultJson['status'] = 1;
                $aResultJson['msg'] = 'Comprobante - Pto. de Venta no especificado para: '.\env('MAIL_FROM_NAME');
                exit(json_encode($aResultJson));
            }
            
            $oWsfe = new WSFE($cuitCompany, file_get_contents($certCompany), $privCompany);
        
            //Autenticacion
            if (!$oWsfe->autorizar()) {
                $aResultJson['status'] = 1;
                if ($oWsfe->errno) {
                    $aResultJson['msg'] =  "ERROR: [".$oWsfe->errno."] ".$oWsfe->errmsg."<br/>";
                }else {
                    $aResultJson['msg'] =  'No autorizado, ver certificados o clave privada';
                }
                exit(json_encode($aResultJson));
            }
                
            $aResultJson = array_merge($aResultJson,[
                //'result' => 0,
                'id_establecimiento' => $establecim['id'],
                'id' => $aToFac['id'],
                'fecha' => $aToFac['fecha'],
                'tipo_doc' => $aToFac['tipo_doc'],
                'numero_doc' => $aToFac['numero_doc'],
                'importe' => $aToFac['importe'],
                'importeneto' => $aToFac['importeneto'],
                'importeiva' => $aToFac['importeiva']
            ])
            ;
            
            if ($oWsfe->emitir
                (
                    $comprobante,
                    $ptoVta, 
                    [ $aResultJson['id'],$aResultJson['tipo_doc'],$aResultJson['numero_doc'],$aResultJson['importe'],$aResultJson['importeneto'],$aResultJson['importeiva']  ]
                )
            ) {
                $aRet = [];
                $aErr = [];
            
                if ($cae = $oWsfe->solicitarCAE($aRet, $aErr)) {
                    $aResultJson['comprobante']=$aRet->json->Comprobante;
                    $aResultJson['cae'] = $cae;
                    $aResultJson['caev'] = \DateTime::createFromFormat('Ymd', $aRet->CAEFchVto)->format('Y-m-d');
                    $aResultJson['fecha_fe'] = date('Y-m-d');
                    $aResultJson['importeNeto'] = $aRet->json->ImpNeto;
                    
                } else {
                    $aResultJson['status'] = 1;
            
                    $aErr = (!$aErr) ? ['Error. No se ha obtenido el CAE, Verifique los datos de esta factura'] : $aErr;
            
                    $aResultJson['msg'] = json_encode($aErr);
                    
                }
            } else {
                $aResultJson['status'] = 1;
                $aResultJson['msg'] = json_encode(['Error FACB. No se ha podido procesar esta factura']);
            
            }
            $aResultJson['tipo_comprobante'] = $comprobante;
            
            if (0 === $aResultJson['status']) {
                UtilFe::setCobranzaAfip($aResultJson);
                UtilFe::setFacturaPedido($request['id']);                
            }
            
            return response()->json($aResultJson);

        }//fin del if Error
        
    }

    public function impresion(Request $request){
        
        $establecim = UtilFe::getCompay();
        
        $detalle = VistaFactura::find($request['id']);
        $productos = VistaFactura::where('id_cobranza_admin',$request['id'])->get();
        $cod= UtilFe::getTipoCodigoComprobante($detalle['tipo_comprobante']);
        $barcode = UtilFe::getCodigoBarra($establecim,$request['id']);
        $pdf = PDF::loadView('pedidos.pedidos.facturaPdf',['detalle'=>$detalle,'productos'=>$productos,'barcode'=>$barcode,'cod'=>$cod['codigo_comprobante']]);
        
        return $pdf->stream();
        return response()->json(['msg'=>'ok']);
    }
}
