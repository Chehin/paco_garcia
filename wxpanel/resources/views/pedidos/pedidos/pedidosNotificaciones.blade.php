<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
	$notificaciones = (isset($aViewData['notificaciones'])) ? $aViewData['notificaciones'] : null;
?>

<div class="modal-dialog modal-lg">    
    <div class="modal-content">        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
				&times;
			</button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> Notificaciones
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
							<h1> {{ env('SITE_NAME')}} - <small>Notificaciones del pedido</small></h1>
							<h4>Datos de Pedido</h4>
                            <div class="row">
                                <div class="col-xs-6"><strong>Pedido Nro:</strong> {{$item->id_pedido}}</div>
                                <div class="col-xs-6"><strong>Para:</strong> {{$item->nombre}}</div>
                                <div class="col-xs-6"><strong>Estado:</strong> {{$item->estado}}</div>
                                <div class="col-xs-6"><strong>Metodo de Pago:</strong> {{$item->metodo_pago}}</div>
                            </div>
							<br>
							<br>
							@if(count($notificaciones)>0)
							<h4>Notificaciones</h4>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Estado</th>
											<th>Texto</th>
											<th>M&aacute;s Informacion</th>
											<th>Emisor</th>
											<th>Fecha</th>
										</tr>
									</thead>
									<tbody>
										@foreach($notificaciones as $notificacion)
										<tr>
											<td>{{$notificacion['status']}}</td>
											<td><div style="width:160px; overflow-wrap: break-word;"><p>{{$notificacion['texto']}}</p></div></td>
											<td><div style="width:300px; overflow-wrap: break-word;"><p>{{$notificacion['more_info']}}</p></div></td>
											<td>{{$notificacion['emisor']}}</td>
											<td>{{$notificacion['updated_at']}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
								
							</div>
							@else
							<h4>Sin Notificaciones</h4>
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
