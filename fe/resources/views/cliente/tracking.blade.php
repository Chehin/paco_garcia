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
					<li><a href="{{ route('cuenta') }}">Mi cuenta</a><span>»</span></li>
					<li><strong>Seguimineto de Envios</strong></li>
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
        	<div class="cart">
        		<div class="page-content page-order"><div class="page-title">
	            	<h2>Seguimiento de envios</h2>
	          	</div>
	          	<div class="order-detail-content">
	              	<div class="table-responsive">
		                <table class="table table-bordered cart_summary">
		                  	<thead>
		                    	<tr>
		                      		<th class="cart_product">Fecha</th>
		                      		<th>Método de envio</th>
		                      		<th class="text-center">productos</th>
		                      		<th class="text-center">Costo de envío</th>
		                      		<th class="text-center">Estado de pago</th>
		                      		<th class="text-center">Estado de envío</th> 
		                      		<th class="action"></th>
		                    	</tr>
		                  	</thead>
	                  		<tbody>
							  @if($pedidosHistory)
								@foreach($pedidosHistory as $pedidos)
								
		                    	<tr>
								<td class="cart_product">{!! $pedidos['fecha'] !!}</td>
		                      		<td class="cart_description">{!! $pedidos['metodo_pago'] !!}</td>
		                      		<td class="price"><span>{!! $pedidos['moneda'] !!}{!! $pedidos['subtotal']['precio'] !!}</span></td>
		                      		<td class="price"><span>{!! $pedidos['moneda'] !!}{!! $pedidos['envio']['precio'] !!}</span></td>
		                      		<td class="cart_description">{!! $pedidos['estado'] !!}</td>
		                      		<td class="cart_description">{!! $pedidos['estado_paquete'] !!}</td>
		                      		<td class="action">
		                      			<button class="btn btn-sm btn-rounded btn-custom" data-toggle="modal" data-target="#modal-pedido-{!! $pedidos['id_pedido'] !!}">
											<i class="fa fa-search"></i>
										</button>
									</td>
		                    	</tr>
		                    	@endforeach
							  @else
							    <tr>
									<td colspan="6" align="center">No hay pedidos</td>
								</tr>
							  @endif
		                    </tbody>
		                </table>
		            </div>
		        </div>
        	</div>
        </div>
    </div>
</section>


<section>
    @if ($pedidosHistory) 
@foreach ($pedidosHistory as $pedidos)
<div id="modal-pedido-{!! $pedidos['id_pedido'] !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">
    	<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" style="color:white">Destalle del pedido</h3>
			</div><!-- End .modal-header -->
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Precio</th>
								<th>Cantidad</th>
								<th style="width: 74px;">Envío</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody>
						
							@foreach($pedidos['carrito'] as $ped_car)
							<tr>
								<td class="info-col">
									<div class="product">
										<div class="product-info">
											<h4 class="product-title">
												{!! $ped_car['titulo'] !!}
											</h4>
										</div>
									</div><!-- End .product -->
								</td>
								<td class="price-col">{!! $ped_car['moneda'] !!} {!! $ped_car['precio'] !!} </td>
								<td class="price-col">{!! $ped_car['cantidad'] !!} </td>
								<td class="quantity-col">{!! $ped_car['moneda'] !!} {!! $pedidos['envio']['precio'] !!} </td>
								<td class="subtotal-col">{!! $ped_car['moneda'] !!} {!! $pedidos['subtotal']['precio'] !!} </td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<table class="table table-bordered total-table">
				<tbody>
						<tr class="subtotal-t">
							<td>Subtotal:</td>
							<td>{!! $pedidos['moneda'] !!} {!! $pedidos['subtotal']['precio'] !!} </td>
						</tr>
						<tr>
							<td>Envío:</td>
							<td>{!! $pedidos['moneda'] !!} {!! $pedidos['envio']['precio'] !!} </td>
						</tr>
						<tr class="total-row">
							<td>Total:</td>
							<td>{!! $pedidos['moneda'] !!} {!! $pedidos['total']['precio'] !!} </td>
						</tr>
					</tbody>
				</table>
			</div><!-- End .modal-body -->
	    </div>
    </div>
</div>
@endforeach
@endif
</section>
@stop