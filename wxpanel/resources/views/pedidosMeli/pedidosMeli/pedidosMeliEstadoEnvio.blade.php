<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
	$direccion = (isset($aViewData['direccion'])) ? $aViewData['direccion'] : null;
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
						<!--        <p>
                            Tabs inside well and pulled right
                            <code>
							.tabs-pull-right
                            </code>
                            (Bordered Tabs)
						</p> -->
						<hr class="simple">
						
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
								<a href="#l1" data-toggle="tab">Estado del envío</a>
							</li>
						</ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
						@if($item->collection_id || $item->id_tipo_envio!=0)
							@if($item->estado_envio_nombre)
				
							<div class="alert alert-block alert-warning">
								<h4 class="alert-heading">{{$item->estado_envio_nombre}}</h4>
								{{$item->estado_envio_detalle}}
							</div>
							@endif

							{{-- @if(isset($item->sucursal))
							<strong style="font-size:16px">Retiro en sucursal</strong><br />
							<strong>Sucursal:</strong> {{$item->sucursal}}<br />
							<strong>Fecha de retiro:</strong> {{$item->sucursal_fecha}}<br />
							<strong>Persona que retira:</strong> {{$item->nombre}}<br>
							<strong>Dni:</strong> {{$item->dni}}	<br>
							@endif --}}
							<strong style="font-size:16px">Retiro en sucursal</strong><br />
							{{-- <strong>Sucursal:</strong> {{$item->sucursal}}<br />
							<strong>Fecha de retiro:</strong> {{$item->sucursal_fecha}}<br /> --}}
							<strong>Persona que retira:</strong> {{$item->nombre}}<br>
							<strong>Dni:</strong> {{$item->dni}}	<br>
							@if($item->imprimir_etiqueta)
									<a href="{{$item->imprimir_etiqueta}}" target="_blank"> Imprimir Etiqueta</a>
								@endif
							<br />
							@if($item->collection_id)
							<strong>Numero de transacción:</strong> {{$item->collection_id}}<br />
							@endif
							@if($item->tipo_envio)
							<strong>Enviado por:</strong> {{$item->tipo_envio}}<br />
							@endif
							@if($item->fecha_aprobacion)
							<strong>Fecha de aprobación:</strong> {{$item->fecha_aprobacion}}<br />
							@endif
							@if($item->fecha_modificacion)
							<strong>Fecha de modificación:</strong> {{ $item->fecha_modificacion }}<br />
							@endif
							<br />
							
							@if(isset($direccion->direccion))
							<strong style="font-size:16px">Datos de envío</strong><br />
							<strong>Referencia:</strong> {{$direccion->titulo}}<br />
							<strong>Dirección:</strong> {{$direccion->direccion}} {{$direccion->numero}}<br />
							<strong>Provincia:</strong> {{$direccion->provincia}}<br />
							<strong>Ciudad:</strong> {{$direccion->ciudad}}<br />
							<strong>Código postal:</strong> {{$direccion->cp}}<br />
							<strong>Teléfono:</strong> {{$direccion->telefono}}<br />
							<strong>Información adicional:</strong> {{$direccion->informacion_adicional}}<br />
								@if($item->costo_envio_andreani!=0 && $item->costo_envio_andreani!='') 
									<strong>Precio de envio de Andreani para Paco Garcia:</strong> $ {{$item->costo_envio_andreani}}<br /> 
								@endif
							@endif
						
						@endif
						@if($item->options)
						
						{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
							<div class="tab-pane fade active in" id="l1">                            
								<fieldset>
									<section>
										<div class="row">
											<label class="label col col-2">Estado del Envío *: </label>
											<label class="select col-md-10">
												{{ Form::select(
													'estado_envio', 
													$item->options, 
													("edit" == $mode) ? $item->estado_envio : 0, 
													['class' => '']
													) 
												}}
												<i></i>
											</label>
										</div>
									</section>
								</fieldset>
							</div>
					
							<!-- Buttons inside Form!!-->
							<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
								<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
								<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
							</div>
						{{ Form::close() }}
						</div>
						@endif					
					</div>
					
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
        
	</div>
</div>
@include('pedidosMeli.pedidosMeli.pedidosMeliEstadoEnvioEditScripts')