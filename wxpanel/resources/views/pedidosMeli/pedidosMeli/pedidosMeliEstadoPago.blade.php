<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
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
								<a href="#l1" data-toggle="tab">Estado del pago</a>
							</li>
						</ul>
						
						@if($item->collection_id)
						<div id="myTabContent3" class="tab-content padding-10">
							<div class="alert alert-block alert-warning">
								<h4 class="alert-heading">{{ $item->estado }}</h4>
								{{$item->detalle_estado}}
							</div>
							<strong>Método de pago:</strong> {{$item->metodo_mercado}}<br />
							<strong>Numero de transacción:</strong> {{$item->collection_id}}<br />
							@if($item->payment_id)
							<strong>Numero de Pago:</strong> {{$item->payment_id}}<br />
							@endif
							<strong>Fecha de aprobación:</strong> {{$item->fecha_aprobacion}}<br />
							<strong>Fecha de modificación:</strong> {{ $item->fecha_modificacion }}<br />
							<br />
							
							@if($item->tiempo_entrega)
							<strong>Tiempo de entrega:</strong> {{ $item->tiempo_entrega }}<br />
							@endif
							<strong>Envio:</strong> {{$item->costo_envio}}<br/>
							<strong>Productos:</strong> {{$item->precio_venta}}<br/>
							@if($item->estado == 'approved')
								<h2><strong>Total pagado:</strong> {{$item->total}}</h2>
							@endif
						</div>
						@else
						<div id="myTabContent3" class="tab-content padding-10">
						{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
							<div class="tab-pane fade active in" id="l1">                            
								<fieldset>
									<section>
										<div class="row">
											<label class="label col col-2">Estado del pago *: </label>
											<label class="select col-md-10">
												{{ Form::select(
													'estado_pago', 
													$item->options, 
													("edit" == $mode) ? $item->estado : 0, 
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
@include('pedidosMeli.pedidosMeli.pedidosMeliEstadoPagoEditScripts')