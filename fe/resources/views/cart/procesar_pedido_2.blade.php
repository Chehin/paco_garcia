@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>        
                    <li><a href="{{ route('home') }}">Inicio</a><span>»</span></li>
                    <li><a href="{{ route('cart') }}">Carrito</a><span>»</span></li>
                    <li><strong>Procesar pedido</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
    
        <div class="col-main">
            <div class="cart cart_box">
                <div class="page-content page-order">                    
                    <div class="col-sm-9">
                        <div class="page-title">
                            <div class="row">
                                <h2>DETALLE DE PEDIDO</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="order-detail-content">
								<div class="page-loader l-cart">
									<div class="spinner">
									  <div class="dot1"></div>
									  <div class="dot2"></div>
									</div>
								</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered cart_summary">
                                        <thead>
                                            <tr>
                                                <th class="cart_product">Productos agregados</th>
                                            </tr>
                                        </thead>
                                        <tbody class="top-cart-content1">
                                            <td>
                                                <!-- Begin shopping cart content -->
                                                <div class="cart-content">
                                                    <ul id="cart-product-list" class="cart-sidebar mini-products-list">                       
                                                    </ul>
                                                </div>
                                                <!-- End shopping cart content -->
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="page-title">
                            <h2>RESUMEN</h2>
                        </div>
                        <div class="cart-collaterals">
                            <div class="totals box_prices" style="display: none;">
                                <div class="inner cart-box">
                                    <table id="shopping-cart-totals-table" class="table shopping-cart-table-total table-bordered cart_summary">
                                        <colgroup>
                                            <col>
                                            <col width="1">
                                        </colgroup>                                        
                                        <tbody>
                                            <tr class="cart-total">
                                                <td style="" class="a-left" colspan="1"> Subtotal </td>
                                                <td style="" class="a-right"><span class="subtotal"></span></td>
                                            </tr>
                                            <tr class="cart-envio">
                                                <td style="" class="a-left" colspan="1"> Envío </td>
                                                <td style="" class="a-right envio-col"><span class="price">@if(isset($_SESSION['carrito']['envio']['precio_db'])) $ {!! $_SESSION['carrito']['envio']['precio_db'] !!} @endif</span></td>
                                            </tr>
                                            <tr class="cart-total">
                                                <td style="" class="a-left" colspan="1"> <b>Total</b> </td>
                                                <td style="" class="a-right"><span class="total"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>                                    
                                </div>
                                <!--inner--> 
                            </div>
                        </div>
                    </div>
 
                    <div class="" id="medios_pago">
                        <div class="col-sm-12">
                            @if($preference_data!='')
                            <div id="medios_pago">
                                <div class="row">
                                    <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/785X40.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="785" height="40" class="img-responsive"/>                                                                         
                                </div>
                                
                            </div>
                            @endif

                            @if ($data['envio']['tipo']['empresa'] != 'MercadoE') 
                                <div class="actions col-sm-4 text-center">
                                    <a href="todopago?id_pedido=<?=$data['id_pedido']?>&total=<?=$data['total']['precio']?>" class="btn boton-rosa">Pagar con Todo Pago</a>        
                                    <img src="https://todopago.com.ar/sites/todopago.com.ar/files/kit_boton_192x55_04.jpg" alt="" />
                                </div>      
                            @endif

                            @if(isset($preference_data["response"]["init_point"]))
                            <div class="col-sm-6">
                                <a target="_blank" href="<?php echo $preference_data["response"]["init_point"]; ?>" name="MP-Checkout" class="green-L-Rn-Tr" mp-mode="redirect">Pagar con Mercado Pago</a>
                                <script type="text/javascript">
                                (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
                                </script>
                            </div>
                            @endif

                            {{-- <div class="actions col-sm-4">
                                <a href="checkout?opcion=1" class="btn bt-lg btn-custom pull-left colorWhite"><i class="fa fa-money"></i>  Pagar en sucursal</a>
                            </div>
                            <div style="margin-top: 13px">
                                @if(isset($data['envio']['tipo']['id_tipo'] ))
                                @if ($data['envio']['tipo']['id_tipo'] == 1)
                                <div class="actions col-sm-4">
                                    <a href="checkout?opcion=1" class="btn bt-lg btn-custom pull-left colorWhite"><i class="fa fa-money"></i>  Pagar en sucursal</a>
                                </div>
                                @elseif ($data['envio']['tipo']['id_tipo'] == -1)
                                <div class="actions col-sm-4">
                                    <a href="checkout?opcion=2" class="btn bt-lg btn-custom pull-left colorWhite"><i class="fa fa-money"></i>  Pago contra reembolso</a>
                                </div>
                                @endif
                                @endif
                            </div> --}}
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop