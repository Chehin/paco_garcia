<?php 
$aProcessed = isset($aViewData['aResult']) ? $aViewData['aResult'] : null;
		
?>

@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-download"></i>
						Importar Clientes
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
											{{ Form::open(array('route' => 'importarClientes','class' => 'smart-form','action'=>'import','enctype'=>'multipart/form-data')) }}
											  
												<fieldset>
													<section>
													    <div class="row">
														<label class="label col col-2">Adjuntar archivo:</label>
														<div class="col col-10">
															<label for="file" class="input input-file">
																<div class="button"><input type="file" name="file" id="file" onchange="this.parentNode.nextSibling.value = this.value">Seleccionar archivo</div><input type="text" placeholder="Ningún archivo seleccionado" readonly="">
															</label>
														</div>
													    </div>
													</section>
												    </fieldset>

												<footer>
														<button id="importarC" type="submit" class="btn btn-primary">
															Subir
														</button>
												</footer>
											{{ Form::close() }}
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
	@include('importador.importarClientesScripts')
@stop



