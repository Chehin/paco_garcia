<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
	$usuario = (isset($aViewData['usuario'])) ? $aViewData['usuario'] : null;
	$direccion = (isset($aViewData['direccion'])) ? $aViewData['direccion'] : null;
	$productos = (isset($aViewData['productos'])) ? $aViewData['productos'] : null;
	$facturacion = (isset($aViewData['facturacion'])) ? $aViewData['facturacion'] : null;
?>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
				&times;
			</button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
			</h6>
		</div>
		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered" style="border-bottom: 0;"></ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
							<h1>Paco Garcia Web - <small>Resumen del pedido</small></h1>
							<h4>Datos de usuario</h4>
							<div class="row">
								<div class="col col-sm-6"><strong>Nombre:</strong> {{$usuario['nombre']}}</div>
								<div class="col col-sm-6"><strong>Apellido:</strong> {{$usuario['apellido']}}</div>
								<div class="col col-sm-6"><strong>E-mail:</strong> {{$usuario['mail']}}</div>
							</div>
							<hr />
							@if(isset($facturacion['tipo']))
							<h4>Datos de facturación</h4>
							<div class="row">
								@if(isset($facturacion['direccion']['direccion']))
									<div class="col col-sm-6"><strong>Dirección:</strong> {{$facturacion['direccion']['direccion']}} {{$facturacion['direccion']['numero']}}</div>
									<div class="col col-sm-6"><strong>Ciudad:</strong> {{$facturacion['direccion']['ciudad']}}</div>
									<div class="col col-sm-6"><strong>Provincia:</strong> {{$facturacion['direccion']['provincia']}}</div>
									<div class="col col-sm-6"><strong>Codigo postal:</strong> {{$facturacion['direccion']['cp']}}</div>
									@if($facturacion['direccion']['departamento'])
									<div class="col col-sm-6"><strong>Departamento:</strong> {{$facturacion['direccion']['departamento']}}</div>
									@endif
									@if($facturacion['direccion']['piso'])
									<div class="col col-sm-6"><strong>Piso:</strong> {{$facturacion['direccion']['piso']}}</div>
									@endif
									@if($facturacion['direccion']['telefono'])
									<div class="col col-sm-6"><strong>Teléfono:</strong> {{$facturacion['direccion']['telefono']}}</div>
									@endif
								@endif
								{{-- <div class="col col-sm-6"><strong>Tipo de facturación:</strong> {{$facturacion['tipo']}}</div> --}}
								@if(isset($facturacion['dni'])) 
								@if($facturacion['dni'])
								<div class="col col-sm-6"><strong>DNI:</strong> {{$facturacion['dni']}}</div>
								@endif
								@endif
								@if(isset($facturacion['cuit'])) 
								@if($facturacion['cuit'])
								<div class="col col-sm-6"><strong>Cuit:</strong> {{$facturacion['cuit']}}</div>
								@endif
								@endif
								@if(isset($facturacion['cuit'])) 
								@if($facturacion['cuit'])
								<div class="col col-sm-6"><strong>Razon Social:</strong> {{$facturacion['razon_social']}}</div>
								@endif
								@endif
							</div>
							<hr />
							@endif
							@if(isset($direccion['direccion']))
							<h4>Datos de envío</h4>
							<div class="row">
								<div class="col col-sm-6"><strong>Dirección:</strong> {{$direccion['direccion']}} {{$direccion['numero']}}</div>
								<div class="col col-sm-6"><strong>Ciudad:</strong> {{$direccion['ciudad']}}</div>
								<div class="col col-sm-6"><strong>Provincia:</strong> {{$direccion['provincia']}}</div>
								<div class="col col-sm-6"><strong>Codigo postal:</strong> {{$direccion['cp']}}</div>
								@if($direccion['departamento'])
								<div class="col col-sm-6"><strong>Departamento:</strong> {{$direccion['departamento']}}</div>
								@endif
								@if($direccion['piso'])
								<div class="col col-sm-6"><strong>Piso:</strong> {{$direccion['piso']}}</div>
								@endif
								@if($direccion['telefono'])
								<div class="col col-sm-6"><strong>Teléfono:</strong> {{$direccion['telefono']}}</div>
								@endif
								<div class="col col-sm-12"><strong>Referencia domicilio:</strong> {{$direccion['informacion_adicional']}}</div>
							</div>
							<hr />
							@endif
							@if($item->collection_id)
							<strong>Número de Transacción:</strong> {{$item->collection_id}}
							@endif
							@if($item->detalle_estado=='accredited')
							<strong>Número de Pago:</strong> {{$item->payment_id}}
							@endif
							<hr />
							@if($productos)
							<h4>Productos</h4>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>#</th>
											<th>Producto</th>
											<th>Precio</th>
											<th>Código</th>
											<th>Cantidad</th>
											<th>Etiqueta</th>
											<th>Subtotal</th>
										</tr>
									</thead>
									<tbody>
										@foreach($productos['productos'] as $producto)
										<tr>
											<td>{{$producto['i']}}</td>
											<td>
												{{$producto['nombre']}}
												@if($producto['color'])
													<br/><small>Color: {{ $producto['color'] }}</small>
												@endif
												@if($producto['talle'])
													<br/><small>Talle: {{ $producto['talle'] }}</small>
												@endif											
											</td>
											<td>{{$producto['moneda']}}{{$producto['precio']}}</td>
											<td>{{$producto['codigo']}}</td>
											<td>{{$producto['cantidad']}}</td>
											<td>
												@if($producto['impresion_etiqueta'])
												<a href="{{$producto['impresion_etiqueta']}}" target="_blank">Imprimir</a>
												@endif
											</td>
											<td>{{$producto['moneda']}}{{$producto['subtotal']}}</td>
										</tr>
										@endforeach
										@if($item->estado=='proceso')
										<tr>
											<td colspan="7" align="center"><strong>COMPRA NO FINALIZADA</strong></td>
										</tr>
										@else
										<tr>
											<td align="right" colspan="6"><strong>SUBTOTAL</strong></td>
											<td align="right"><strong>{{$productos['moneda']}}{{$productos['total']}}</strong></td>
										</tr>
										<tr>
											<td align="right" colspan="6"><strong>ENVÍO</strong></td>
											<td align="right"><strong>{{$productos['moneda']}}{{$productos['envio']}}</strong></td>
										</tr>
										<tr>
											<td align="right" colspan="6"><strong>TOTAL</strong></td>
											<td align="right"><strong style="color:#f00;font-size:16px">{{$productos['moneda']}}{{$productos['subtotal']}}</strong></td>
										</tr>
										@endif
									</tbody>
								</table>
								
							</div>
							@endif
						</div>
					</div>
					
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
        
	</div>
</div>
