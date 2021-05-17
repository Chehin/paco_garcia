<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
	$direccion = (isset($aViewData['direccion'])) ? $aViewData['direccion'] : null;
	$tipo_envio = (isset($aViewData['tipo_envio'])) ? $aViewData['tipo_envio'] : null;
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

							@if($item->estado=='approved' && isset($tipo_envio->empresa))

								@if($tipo_envio->empresa == 'Andreani' || $tipo_envio->empresa == 'Mis envios')
									@if($item->alta_envio == 0)

									
									<div class="row">
										<div class="col-md-6">
											<button class="btn btn-primary" id="preparar_alta_envio" type="button" data-toggle="collapse" data-target="#collapseAltaEnvio" aria-expanded="false" aria-controls="collapseAltaEnvio">
												Alta Envio
											</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-md-offset-3">
											<div class="collapse" id="collapseAltaEnvio">
												<div class="card card-body bg-info text-center" style="padding-top:25px;padding-bottom:25px;">
													<form action="javascript:void(0);" class="form-inline">
														<div class="form-group mb-2" id="div_sucursal_envio">
															<label for="staticEmail2" class="sr-only">Sucursal</label>
															<select id="sucursales">
																<option value="0" seleted="true">Cargando...</option>
															</select>
														</div>
														<button id="alta_envio" class="btn btn-primary mb-2">Confirmar envio</button>
													</form>
												</div>
											</div>
										</div>
									</div>
										
										
									
									@else
									<div class="alert alert-block alert-warning">
										Envio creado en {{ $tipo_envio->empresa }}										
									</div>
									@endif
								@endif

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
<script>
	//Alta de envio
    jQuery(document).ready(function(){
		jQuery('#preparar_alta_envio').click(function(){

			$.ajaxSetup({headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content')}  });

			var parametros = {
				'_token'  : "{{ csrf_token() }}",
				'id': "{{ $item->id_pedido }}",
				'empresa': "{{ $tipo_envio->empresa }}"
			};

			$.ajax( {
				"dataType": 'json',
				"type": 'POST',
				"url": "{{ route('sucursales_envio') }}",
				"data": parametros,
				"success":  function(response){
					console.log(response);
					if (0 == response.status) {
						var select = $("#sucursales");
						select.html('<option value="0" seleted="true">Elija la sucursal</option>');
						response.data.forEach(function(item){
							select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
						});
						
						$('#sucursales').val(response.id_sucursal);

					} else {
						var type = 'nok';
						if (2 == response.status) {
							type = 'warn';
						}
						
						appCustom.smallBox(
							type,
							response.msg,
							null, 
							'NO_TIME_OUT'
						);
					}
				},
				"error":function(xhr, status, error) {
					//(possibly) one user starts more than one session
					if (401 === xhr.status) {
						window.location = 'logout';
					} else { //another error code
						appCustom.smallBox(
							'nok', 
							'Error interno. No se pudo completar la operaci&oacute;n',
							'',
							'NO_TIME_OUT'
						);
					}


				},
				"complete":function() {
					appCustom.closeModalPreloader();
				}
			});
		});
        jQuery('#alta_envio').click(function(){
			var id_sucursal = $('#sucursales').val();
            $.ajaxSetup({headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content')}  });

            var parametros = {
                '_token'  : "{{ csrf_token() }}",
				'id': "{{ $item->id_pedido }}",
				'id_sucursal': id_sucursal
            };

            $.ajax( {
                "dataType": 'json',
                "type": 'POST',
                "url": "{{ route('alta_envio') }}",
                "data": parametros,
                "success":  function(response){
					if (0 == response.status) {
                        appCustom.smallBox('ok','');
                        appCustom.hideModal();
                    } else {
                        var type = 'nok';
                        if (2 == response.status) {
                            type = 'warn';
                        }
                        
                        appCustom.smallBox(
                            type,
                            response.msg,
                            null, 
                            'NO_TIME_OUT'
                        );
                    }
                },
                "error":function(xhr, status, error) {
                    //(possibly) one user starts more than one session
                    if (401 === xhr.status) {
                        window.location = 'logout';
                    } else { //another error code
                        appCustom.smallBox(
                            'nok', 
                            'Error interno. No se pudo completar la operaci&oacute;n',
                            '',
                            'NO_TIME_OUT'
                        );
                    }
                },
                "complete":function() {
                    appCustom.closeModalPreloader();
                }
            });
        });
    });
</script>
@include('pedidos.pedidos.pedidosEstadoEnvioEditScripts')