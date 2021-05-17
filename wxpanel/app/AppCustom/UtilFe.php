<?php
/**
 * Description of UtilFe
 *
 * @author smc
 */

namespace App\AppCustom;

use App\AppCustom\Models\Company;
use App\AppCustom\Models\Factura;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\Comprobante;
use App\AppCustom\Models\TipoComprobante;

class UtilFe {
    
    static function getCompay() {
        return Company::where('id', 1)->first();
    }

    static function getCobranzaData($id) {
        $aItem = [];
        $aux = [];
        $importes = [];
        $importesiva = [];
        $importeneto21 = 0;
        $importeneto27 = 0;
        $importeneto10 = 0;
        $aux['status'] = 0;
        $numero = '';

		$sql = 
            Factura::select('id_factura')
			->where('id_cobranza_admin','=',$id)
			->get()
			;
           
			if (!empty($sql)) {

                $aItem = Pedidos::where('id_pedido',$id)->first();
                if ($aItem['tipo_facturacion'] == 'Responsable Monotributista' || $aItem['tipo_facturacion'] == 'Responsable Inscripto') {
                    if($aItem['cuit']!='') {
                        $numero  = str_replace('-','',$aItem['cuit']);
                    }else{
                         $aux['status'] = 1;
                         $aux['msg'] = 'No tiene cargado el número de CUIT, por favor revise los datos del cliente';
                    }
                }elseif($aItem['tipo_facturacion'] == 'Consumidor Final'){
                    \Log::info('pasa');
                    if($aItem['dni']!='') {
                        \Log::info($aItem['dni']);
                         $numero  = str_replace('.','',$aItem['dni']);
                    }else{
                        \Log::info('pasa');
                        $aux['status'] = 1;
                        $aux['msg'] = 'No tiene cargado el número de DNI, por favor revise los datos del cliente';
                        \Log::info($aux);
                    }
                }
                
                $aux['id'] = $aItem['id_pedido'];
                $aux['fecha'] = $aItem['created_at'];
                $aux['tipo_doc'] = 'DNI';
                $aux['numero_doc'] = $numero;
                $aux['importe'] = $aItem['total'];
                $aux['importeneto'] = 0;
                $aux['importeiva'] = 0;

                //comprobacion del importe con iva para factura A
                if ($aItem['tipo_facturacion'] == 'Responsable Inscripto') {
                    $productos = PedidosProductos::where('id_pedido',$aItem['id_pedido'])->get();
                    
                    if(count($productos)>0){
                        foreach ($productos as $producto) {
                            $precio = PreciosProductos::where('id_producto',$producto['id_producto'])->first();

                            if($precio->iva > 0){
                                switch ($precio->iva) {
                                    case '21.0':
                                            $importeneto21 = $importeneto21 + ($producto->precio_siniva*$producto->cantidad);
                                           /*  $importeiva21 = $importeiva21 + ($producto->precio - $producto->precio_siniva)*$producto->cantidad; */
                                        break;
                                    case '27.0':
                                            $importeneto27 = $importeneto27 + ($producto->precio_siniva*$producto->cantidad);
                                            /* $importeiva27 = $importeiva27 + ($producto->precio - $producto->precio_siniva)*$producto->cantidad; */
                                        break;
                                    case '10.5':
                                            $importeneto10 = $importeneto10 + ($producto->precio_siniva*$producto->cantidad);
                                            /* $importeiva10 = $importeiva10 + ($producto->precio - $producto->precio_siniva)*$producto->cantidad; */
                                        break;
                                    default:                                       
                                        break;                                    
                                }
                            }else{
                                $aux['status'] = 1;
                                $aux['msg'] ='El producto no tiene cargado el IVA';
                            }
                        }

                        $importes['importeneto21'] = $importeneto21;
                        $importesiva['importeiva21'] = round($importeneto21*0.21,2);
                        $importes['importeneto27'] = $importeneto27;
                        $importesiva['importeiva27'] = round($importeneto27*0.27,2);
                        $importes['importeneto10'] = $importeneto10;
                        $importesiva['importeiva10'] = round($importeneto10*10.5,2);

                        $aux['importeneto'] = $importes;
                        $aux['importeiva'] = $importesiva;
                        $aux['tipo_doc'] = 'CUIT';
                    }
                }
                
                \Log::info('auxiliar array');
                \Log::info($aux);

                $aItem = $aux;
            }
						
		return $aItem;
    }
    
    static function getPtoVenta($id) {

        $aItem = Pedidos::where('id_pedido',$id)->first();
        
        //produccion
        switch ($aItem['tipo_facturacion']) {
            case 'Responsable Monotributista'://factura B
                $sql = Comprobante::leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                                    ->where('tipo_comprobante_letras.nombre','=','B')
                                    ->first();
                break;
            
            case 'Responsable Inscripto'://factura A
                $sql = Comprobante::leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                                    ->where('tipo_comprobante_letras.nombre','=','A')
                                    ->first();
                break;
            
            case 'Consumidor Final'://factura B
                $sql = Comprobante::leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                                    ->where('tipo_comprobante_letras.nombre','=','B')
                                    ->first();
                break;

            default: //factura B
                $sql = Comprobante::leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                                    ->where('tipo_comprobante_letras.nombre','=','B')
                                    ->first();
                break;
        }
        
        //test
      /*   return Comprobante::leftJoin('tipo_comprobante_letras','tipo_comprobante_letras.id','=','comprobantes.id_letra')
                                    ->where('tipo_comprobante_letras.nombre','=','B')
                                    ->first(); */
        return $sql;

    }

    static function getTipoComprobante($id_comprobante, $ndc = '') {

        $sql = TipoComprobante::where('id_tipo_comprobante',$id_comprobante)->first();

       
        if ($ndc != '') {
            switch ($sql['letra_comprobante']) {
                case 'REC[C]':
                    $sql['letra_comprobante'] = 'NDC[C]';
                    break;
                case 'FAC[C]':
                    $sql['letra_comprobante'] = 'NDC[C]';
                    break;
            }
        }
    
        return $sql['letra_comprobante'];
    }

    static function getTipoCodigoComprobante($letra){
        
        return Comprobante::leftJoin('tipo_comprobante','tipo_comprobante.id_tipo_comprobante','=','comprobantes.id_tipo_comprobante')
                            ->where('tipo_comprobante.letra_comprobante','=',$letra)
                            ->first();
    }

    static function setCobranzaAfip($aData) {

        $sql = new Factura();
        $sql->id_establecimiento = $aData['id_establecimiento'];
		$sql->id_cobranza_admin=$aData['id'];
		$sql->fecha=$aData['fecha'];
		$sql->tipo_doc=$aData['tipo_doc'];
		$sql->num_doc=$aData['numero_doc'];
		$sql->comprobante=$aData['comprobante'];
		$sql->total=$aData['importe'];
		$sql->cae=$aData['cae'];
		$sql->caev=$aData['caev'];
		//$sql->error=$aData['error'];
        $sql->fecha_fe=$aData['fecha_fe'];
        $sql->tipo_comprobante=$aData['tipo_comprobante'];
        $sql->importeneto = $aData['importeNeto'];
        $sql->save();
	
	
		return $sql;
	}

    static function setFacturaPedido($id){
        
        $pedido=Pedidos::find($id);
        $pedido->facturado = 1;
        $pedido->save();
        
    }

    static function getCodigoBarra($aEstablec, $id) {
        
        $barcode = '';
        $sql = Factura::where('cae','!=','')->where('tipo','fe')->where('id_cobranza_admin',$id)->first();
        
        // BUSCO EL CODIGO DE COMPROBANTE
        $cod = TipoComprobante::leftJoin('comprobantes','comprobantes.id_tipo_comprobante','=','tipo_comprobante.id_tipo_comprobante')
                                ->where('tipo_comprobante.letra_comprobante',$sql['tipo_comprobante'])
                                ->first();
        
        $barcode .= $aEstablec->cuit; // CUIT DEL EMISOR
        $barcode .= str_pad($cod['codigo_comprobante'], 3, "0", STR_PAD_LEFT); // TIPO COMPROBANTE
        $barcode .= str_pad($cod['punto_venta'], 5, "0", STR_PAD_LEFT); // PUNTO DE VENTA
        $barcode .= $sql['cae']; // CAE
        $barcode .= date('Ymd', strtotime($sql['caev'])); // VENCIMIENTO CAE EN FORMATO AAAAMMDD
        $barcode .= UtilFe::verificador(str_split($barcode)); // DIGITO VERIFICADOR
        return $barcode;
    }

    static function verificador($a){
        $posicion=1;
        $tot='';
        foreach($a as $k => $dig):
            $tot=$tot+ ($dig * ($posicion % 2 == 0 ? 1 : 3));
            $posicion++;
        endforeach;
        return ((int)($tot % 10 == 0 ? 0 : (10 - $tot % 10)));
    }
}