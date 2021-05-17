<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facturacion</title>
</head>
<style>
    *{margin:15px 13px 0;padding:0;}
    table *{
        margin: 0;
        width: 100%;
    }
</style>
<body>
        <table style="border-collapse: collapse; width: 635px; height: 435px;" cellspacing="0">
            <tbody>
                <tr>
                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: none; border-top: 1px solid black; height: 118px; text-align: justify; vertical-align: middle; white-space: normal; width: 285px;" colspan="2" rowspan="5">
                        <span style="font-size:12px;display:block;margin-top:-6.3%">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif;">
                                    <img src="https://puertoonline.com/images/logo.png" alt="" style="margin-left:10px;width:145px; heigth:80px;">
                                    <div><br></div>
                                    <div style="margin-left:10px;">
                                        Dirección: {!! $detalle['domicilio'] !!} <br>
                                        Teléfono: {!! $detalle['telephone'] !!} <br>
                                        Email: {!! $detalle['email'] !!} <br><br>
                                    </div> 
                                </span>
                            </span>
                        </span>
                    </td>

                    <td style="vertical-align: middle; white-space: normal; width: 81px; border: 1px solid black;" colspan="2" rowspan="4">
                        <span style="font-size:15px">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif">
                                    <p style="font-size:40px; margin-left:45px;margin-top:-15px;">
                                        @php
                                        switch ($detalle['tipo_comprobante']) {
                                            case 'FAC[A]':
                                                echo 'A';
                                                break;
                                            
                                            case 'FAC[B]':
                                                echo 'B';
                                                break;
                                            
                                            case 'FAC[C]':
                                                echo 'C';
                                                break;
                                            
                                            default:
                                                echo 'C';   
                                                break;
                                        }
                                        @endphp
                                    </p>
                                    <p style="margin-left:35px;">Cod. {!! $cod !!}</p><br>
                                </span>
                            </span>
                        </span>
                    </td>

                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: 1px solid black; text-align: left; vertical-align: middle; white-space: normal; width: 249px;" colspan="4">
                        @php
                            $num=explode($detalle['tipo_comprobante'].'-',$detalle['comprobante']);
                        @endphp  
                        <span style="font-size:14px; display:block;margin-top:-20px;">
                                <span style="color:black">
                                    <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    FACTURA N&deg;: {!! $num[1] !!}
                                </span>
                            </span>
                        </span>
                        
                    </td>
                </tr>

                <tr>
                    <td style="border-bottom:none; border-left:none; border-right:1px solid black; border-top:none; height:28px; vertical-align:middle; white-space:normal; width:263px" colspan="4">
                        <span style="font-size: 14px;display:block;margin-top:-30px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    Fecha:&nbsp; {!!$detalle['fecha_fec']!!}
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: none; height: 10px; vertical-align: middle; white-space: normal; width: 249px;" colspan="4">
                        <span style="font-size: 14px;display:block;margin-top:-33px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    C.U.I.T.: {!!$detalle['cuit']!!}
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
                
                <tr>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: none; height: 18px; vertical-align: middle; white-space: normal; width: 249px;" colspan="4">
                        <span style="font-size: 14px;display:block;margin-top:-34px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    Ingresos Brutos:
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
               
                <tr>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 49px;">
                       {{--  <span style="font-size: 15px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;
                                </span>
                            </span>
                        </span> --}}
                    </td>
                    
                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: none; border-top: none; vertical-align: middle; white-space: normal; width: 45px;">
                        {{-- <span style="font-size: 15px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;
                                </span>
                            </span>
                        </span> --}}
                    </td>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 250px;" colspan="4">
                        <span style="font-size: 14px;display:block;margin-top:-37px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    Fecha de Inicio: {!! $detalle['inicioact'] !!}
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; height: 21px; vertical-align: middle; white-space: normal; width: 331px;" colspan="3">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                    <strong><span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;Informaci&oacute;n del cliente</span></strong>
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: 1px solid black; vertical-align: middle; white-space: normal; width: 297px;" colspan="5">
                        <span style="font-size: 14px;">
                            <span style="color: black;"><strong><span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;Condiciones de Venta</span></strong>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; height: 20px; vertical-align: middle; white-space: normal; width: 331px;" colspan="3">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;Cliente:</span> 
                                {!! $detalle['nombre'].' '.$detalle['apellido'] !!}
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 297px;" colspan="5">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;Condici&oacute;n de venta:&nbsp;
                                </span>
                                {!! $detalle['metodo_pago'] !!}
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; height: 20px; vertical-align: middle; white-space: normal; width: 331px;" colspan="3">
                        <span style="font-size: 14px;"><span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                        &nbsp;Direcci&oacute;n:
                                </span>
                                {!! $detalle['direccion'] !!}
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 297px;" colspan="5">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;Condici&oacute;n:</span>
                            </span> 
                            {!! $detalle['tipo_facturacion'] !!}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; height: 20px; vertical-align: middle; white-space: normal; width: 331px;" colspan="3">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                @if($detalle['tipo_comprobante']=='FAC[A]')
                                    <span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;CUIT:</span>
                                    {!! $detalle['cuit'] !!}
                                @else
                                    <span style="font-family:Calibri,sans-serif;margin-left:10px;">&nbsp;DNI:</span>
                                    {!! $detalle['dni'] !!}
                                @endif
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 297px;" colspan="5">
                        <span style="font-size: 15px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-top: none; height: 21px; vertical-align: middle; white-space: normal; width: 331px;" colspan="3">
                        <span style="font-size: 14px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;Email:
                                </span>
                                {!! $detalle['mail'] !!}
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 297px;" colspan="5">
                        <span style="font-size: 15px;">
                            <span style="color: black;">
                                <span style="font-family:Calibri,sans-serif;margin-left:10px;">
                                    &nbsp;
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #595959; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 13px;">
                        <span style="font-size: 13px;">
                            <span style="color: white;">
                                <strong><span style="font-family: Calibri,sans-serif;">CANT</span></strong>
                            </span>
                        </span>
                    </td>
                    <td style="background-color: #595959; border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 364px;" colspan="4">
                        <span style="font-size: 13px;">
                            <span style="color: white;">
                                <strong><span style="font-family: Calibri,sans-serif;">DESCRIPCI&Oacute;N</span></strong>
                            </span>
                        </span>
                    </td>
                    <td style="background-color: #595959; border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 23px;">
                        <span style="font-size: 13px;">
                            <span style="color: white;">
                                <strong><span style="font-family: Calibri,sans-serif;">IVA</span></strong>
                            </span>
                        </span>
                    </td>
                    <td style="background-color: #595959; border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 96px;">
                        <span style="font-size: 13px;">
                            <span style="color: white;">
                                <strong><span style="font-family: Calibri,sans-serif;">P. UNITARIO</span></strong>
                            </span>
                        </span>
                    </td>
                    <td style="background-color: #595959; border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 63px;">
                        <span style="font-size: 13px;">
                            <span style="color: white;">
                                <strong><span style="font-family: Calibri,sans-serif;">IMPORTE</span></strong>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 13px;">
                        <span style="font-size:13.5px">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif">
                                    @foreach ($productos as $item)
                                        <p style="margin-top: 5px;text-align:center"> {!! $item->cantidad!!}</p> 
                                    @endforeach
                                </span>
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; vertical-align: middle; white-space: normal; width: 264px;" colspan="4" rowspan="1">
                        <span style="font-size:15px">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif">                                            
                                    @foreach ($productos as $item)
                                        <p style="margin-top: 3px;font-size: 11px;text-align:left"> &nbsp; {!! $item->detalle!!}</p>
                                    @endforeach
                                </span>
                            </span>
                        </span>
                    </td>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; vertical-align: center; white-space: normal; width: 43px;"rowspan="1" >
                        @if($detalle['tipo_comprobante']=='FAC[A]')
                                <span style="font-family:Calibri,sans-serif"> 
                                @foreach ($productos as $item)
                                                <span style="margin-top: 6px; font-size: 12px;text-align:left"> &nbsp; {!! $item->iva!!} %</span> <br>
                                @endforeach
                                </span>
                        @endif
                    </td>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 76px;" rowspan="1">
                        <span style="font-size:13.5px">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif">                                            
                                    @foreach ($productos as $item)
                                        <p style="margin-top: 3px;text-align:center;font-size: 12spx;">$ {!! $item->precio!!}</p> 
                                    @endforeach
                                </span>
                            </span>
                        </span>                        
                    </td>
                    <td style="border-bottom: none; border-left: 1px solid black; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 43px;" rowspan="1">
                        <span style="font-size:13.5px">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif">                                            
                                    @foreach ($productos as $item)
                                        <p style="margin-top: 4px;text-align:center;font-size: 12px;">$ {!! $item->importe!!}</p> 
                                    @endforeach
                                </span>
                            </span>
                        </span>
                    </td>
                </tr>
              
                <tr>
                    <td style="border-left:1px solid black;border-right: 1px solid black; height: 22px; text-align: center; vertical-align: middle; white-space: normal; width: 13px;">
                    </td>
                    <td style="border-left:1px solid black;border-right: 1px solid black; height: 22px; text-align: center; vertical-align: middle; white-space: normal; width: 220px;" colspan="4">
                    </td>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 23px;">
                        <span style="font-size: 15px;"><span style="color: black;"><span style="font-family: Calibri,sans-serif;">&nbsp;</span></span></span></td>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; text-align: center; vertical-align: middle; white-space: normal; width: 96px;">
                        <span style="font-size: 16px;"><span style="color: black;"><span style="font-family: Calibri,sans-serif;">Total:</span></span></span></td>
                    <td style="border-bottom: 1px solid black; border-left: none; border-right: 1px solid black; border-top: none; vertical-align: middle; white-space: normal; width: 63px;">
                        <span style="font-size: 13px;"><span style="color: black;"><b><span style="font-family: Calibri,sans-serif;">&nbsp; ${!! $detalle['total'] !!}</span></b></span></span></td>
                </tr>
                @if($detalle['tipo_comprobante']=='FAC[A]')
                <tr>
                    <td style="height: 21px; vertical-align: middle; white-space: normal; width: 631px; border: 1px solid black;" colspan="8">
                        <span style="font-size: 12px;"><span style="color: black;">
                            <span style="font-family: Calibri,sans-serif;margin-left:75%">
                                Importe Neto Gravado: $ {{ $detalle['importeneto'] }}
                            </span>
                            </span>
                        </span>
                    </td>
                </tr>
                @else
                <tr>
                    <td style="vertical-align: middle; white-space: normal; width: 631px; border-top: 1px solid black;" colspan="8">
                    </td>
                </tr>
                @endif

                <tr>
                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-top: none; height: 25px; text-align: left; vertical-align: middle; white-space: normal; width: 631px;" colspan="8">

                        <div><br></div>
                        <span style="font-size:15px;">
                            <span style="color:black">
                                <span style="font-family:Calibri,sans-serif;">
                                    <div style="margin-left:10px">
                                        {!! DNS1D::getBarcodeHTML($barcode, "C128",1,33,"black", true); !!}                                         
                                    </div>
                                </span>
                            </span>
                        </span>
                        <div style="font-size:11px; margin-left: 4%;padding-bottom :1%;">
                            {!! $barcode!!}
                        </div>

                        <span style="font-size: 14px;">
                            <span style="color: black;display:block; margin-left:70%; margin-top:-54px;">
                                <strong>
                                    <span style="font-family: Calibri,sans-serif;">
                                            C.A.E. :&nbsp; {!! $detalle['cae'] !!} <br> 
                                            Vto.:&nbsp;&nbsp;{!! $detalle['caevenc'] !!}
                                    </span>
                                </strong>
                            </span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>            
</body>
</html>
