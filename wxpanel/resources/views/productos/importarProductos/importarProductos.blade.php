<?php
$lastUpdate = $aViewData['lastUpdate'];
$aProcessed = isset($aViewData['aResult']) ? $aViewData['aResult'] : null;
?>

@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-download"></i>
						Importar/Sincronizar Productos
            </h1>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 text-right">
        
        
    </div>
</div>
				
                            
<!-- widget grid -->
<section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="true">
                                <header>
                                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                        <h2></h2>

                                </header>

                                <!-- widget div-->
                                <div>
					<section>
						<form class="smart-form" action="{{ route('productos/importarProductosProcesar') }}" method="post" enctype="multipart/form-data">
							{{ Form::token() }}
							<label class="label">Seleccione un archivo</label>
							<div class="input input-file">
							@if($lastUpdate)
							<span class="button"><input id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value" type="file">Seleccionar</span><input placeholder="Ultima actualización el {{ $lastUpdate->created_at->format('d/m/Y H:i')}} por {{ $lastUpdate->last_name }}, {{ $lastUpdate->first_name }}" readonly="" type="text">
							@else
							<span class="button"><input id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value" type="file">Seleccionar archivo</span><input placeholder="Subir archivo" readonly="" type="text">
							@endif
						</div>
					<footer>
					<button id="importarC" type="submit" class="btn btn-primary" onclick="$('#cargando').show(); $(this).hide();">Importar</button>
					<div class="alert alert-warning" id="cargando" style="display:none;">
                        <strong>Atención!</strong> Importando... 
                        <p>El proceso puede tardar varios minutos</p>
					</div>
				</footer>
			</form>
										  
											@if($aProcessed && 2 == $aProcessed['status'])
											<div class="alert alert-warning">
												<strong>Atención!</strong> Se han encontrado algunas advertencias.
												<br>
												<ul>
													@foreach($aProcessed['data'] as $warn)
													<li>
														{{ $warn }}
													</li>
													@endforeach
												</ul>
											</div>
											@endif
									</section>

                                </div>
                                <!-- end widget div -->

                        </div>
                        <!-- end widget -->
						
						


                </article>
                <!-- WIDGET END -->

        </div>
		
		

        <!-- end row -->

</section>
<!-- end widget grid -->
@stop

@section('custom_scripts_container')
	@include('productos.importarProductos.importarProductosScripts')
@stop



