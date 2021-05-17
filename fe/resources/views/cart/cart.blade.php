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
                    <li><strong>Carrito</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
    @if($_SESSION['carrito']==NULL)
        <div class="alert alert-danger alert-dismissable">
            <span class="alert-icon"><i class="fa fa-warning"></i></span>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>¡Atención!</strong> Error inesperado
        </div>
    @endif
    
        <div class="col-main">
            <div class="cart cart_box">
                <div class="page-content page-order">                    
                    <div class="col-sm-7">
                        <div class="page-title">
                            <div class="row">
                                <h2>CARRITO</h2>
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
                                                <th class="cart_product">Producto agregados</th>
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
                                        <tfoot>
                                            <tr class="first last">
                                                <td colspan="50" class="a-right last">
                                                <button type="button" title="Continuar comprando" class="button btn-continue" onclick="window.location='{!!env('URL_BASE')!!}productos/1/0/0/0/1'">Continuar comprando</button>
												</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="page-title">
                            <h2>RESUMEN</h2>
                        </div>
                        <div class="cart-collaterals">
                            <div class="totals box_prices" style="display: none;">
                                <div class="inner">
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
                                            <tr>
                                                <td style="" class="a-left" colspan="1"> Envío </td>
                                                <td style="" class="a-right"><span class="price">A calcular</span></td>
                                            </tr>
                                            <tr class="cart-total">
                                                <td style="" class="a-left" colspan="1"> <b>Total</b> </td>
                                                <td style="" class="a-right"><span class="total">$249.98</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- <ul class="checkout"> --}}
                                        
                                            <button type="button" title="Procesar compra" class="button btn-proceed-checkout" onclick="window.location='{{route('procesar_pedido',['id'=>1])}}'">Procesar pedido</button>
                                   
                                        <br>
                                        <br>
                                    {{-- </ul> --}}
                                </div>
                                <!--inner--> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($relacionados['productos']!='')
<!-- Main Container End -->
<div class="separacion"></div>
<!-- Related Product Slider -->
@if(count($relacionados['productos'])>0)
<div class="container">
	<div class="special-products">
		<div class="page-header"><h2><span class="title-prod"> Productos relacionados </span></h2></div>
		<div class="special-products-pro">
			<div class="slider-items-products">
				<div id="special-products-slider" class="product-flexslider hidden-buttons">
					<div class="slider-items">
						@foreach($relacionados['productos'] as $producto)
						<div class="product-item">
							<div class="item-inner">
								<div class="product-thumbnail">
									@if(isset($producto['oferta']))
                                        @if($producto['oferta']==1) <div class="icon-sale-label sale-left">-{{$producto['precios']['oferta']}}%</div>@endif
                                    @endif
									@if(isset($producto['fotos']))
									<div class="pr-img-area">
										<a title="{{$producto['titulo']}}" href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}">
										<figure>
											<img class="first-img" src="{{(isset($producto['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').'th_'.$producto['fotos'][0]['imagen_file']:'images/img_default/th_producto.jpg') }}" alt="{{(isset($producto['fotos'][0]['epigrafe'])?$producto['fotos'][0]['epigrafe']:$producto['titulo']) }}" />
											@if(isset($producto['fotos'][1]['imagen_file']))
											<img class="hover-img" src="{{ env('URL_BASE_UPLOADS').'th_'.$producto['fotos'][1]['imagen_file'] }}" alt="{{(isset($producto['fotos'][1]['epigrafe'])?$producto['fotos'][1]['epigrafe']:$producto['titulo']) }}" />
											@endif
										</figure>
										</a>
									</div>
									@endif
								</div>
								<div class="item-info">
									<div class="info-inner">
										<div class="item-title">
											<a title="{{$producto['titulo']}}" href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}">{{$producto['titulo']}}</a>
										</div>
										<div class="item-content">
											<div class="item-price">
												<div class="price-box">
												@if(isset($producto['precios']['precio']))
													@if($producto['precios']['precio']>0)
													<p class="special-price">
														<span class="price-label">Precio Especial</span>
														<span class="price">
															{{ env('MONEDA_DEFAULT') }}{{$producto['precios']['precio']}}
														</span>
													</p>
													@endif
												@endif
												@if(isset($producto['precios']['precio_lista']) and $producto['oferta']==1)
													<p class="old-price">
														<span class="price-label">Precio regular:</span>
														<span class="price">
															{{ env('MONEDA_DEFAULT') }}{{$producto['precios']['precio_lista']}}
														</span>
													</p>
												@endif
												</div>
											</div>
											<div class="pro-action">
												<a href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}" class="add-to-cart">
													{{-- <i class="fa fa-shopping-cart"></i> --}}
													<span>Ver Detalle</span>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
@endif



@stop
