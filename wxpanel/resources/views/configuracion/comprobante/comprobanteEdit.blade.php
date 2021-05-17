<?php

extract($aViewData);
extract($aCustomViewData);

?>

	
	<style>

</style>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $mode) ? 'Editar' : 'Agregar' }} {{ $resourceLabel }}
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
                                    <a href="#l1" data-toggle="tab">Datos de {{$resourceLabel}}</a>
                            </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                    {{ Form::open(array('id' => $resource . 'Form', 'name' => $resource . 'Form', 'class' => 'smart-form')) }}
								<section>
                                    <div class="row">
										<label class="label col col-2">Clase *:</label>
										<div class="col col-10">
											<label class="input">
											{{ Form::select(
												'id_clase', 
												$tipoComprobanteClases->pluck('nombre', 'id')->prepend('Seleccione...',''), 
												('edit' == $mode) ? $item->id_clase : '', 
												['class' => 'col col-md-12', 'required' => 'required']
												) 
											}}	
											</label>
										</div>
                                        </div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Letra *:</label>
										<div class="col col-10">
											<label class="input">
											{{ Form::select(
												'id_letra', 
												$tipoComprobanteLetras->pluck('nombre', 'id')->prepend('Seleccione...',''), 
												('edit' == $mode) ? $item->id_letra : '', 
												['class' => 'col col-md-12', 'required' => 'required']
												) 
											}}	
											</label>
										</div>
                                        </div>
								</section>
								
								<section>
                                    <div class="row">
										<label class="label col col-2">Tipo Comprobante *:</label>
										<div class="col col-10">
											<label class="input">
											{{ Form::select(
												'id_tipo_comprobante', 
												$tipoComprobante->pluck('tipo_comprobante', 'id_tipo_comprobante')->prepend('Seleccione...',''), 
												('edit' == $mode) ? $item->id_tipo_comprobante : '', 
												['class' => 'col col-md-12', 'required' => 'required']
												) 
											}}	
											</label>
										</div>
                                        </div>
								</section>
								
								{{-- <section>
                                    <div class="row">
										<label class="label col col-2">Fiscal *:</label>
										<div class="col col-10">
											<label class="input">
											{{ Form::select(
												'fiscal', 
												['' => 'Seleccione...','0' => 'No', 1 => 'Sí'], 
												('edit' == $mode) ? $item->fiscal : '', 
												['class' => 'col col-md-12']
												) 
											}}	
											</label>
										</div>
                                        </div>
								</section> --}}
					
								{{-- <section>
                                    <div class="row">
										<label class="label col col-2">Autoimpresión *:</label>
										<div class="col col-10">
											<label class="input">
											{{ Form::select(
												'autoimpresion', 
												['' => 'Seleccione...',0 => 'No', 1 => 'Sí'], 
												('edit' == $mode) ? $item->autoimpresion : '', 
												['class' => 'col col-md-12', 'required' => 'required']
												) 
											}}	
											</label>
										</div>
                                        </div>
								</section> --}}
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Punto Venta *:</label>
										<div class="col col-10">
											<label class="input">
												<input name="punto_venta" type="number" value="{{ ('edit' == $mode) ? $item->punto_venta : '' }}" required="required" />
											</label>
										</div>
									</div>
								</section>

								<section>
										<div class="row">
											<label class="label col col-2">Descripcion Pto. Vta.*:</label>
											<div class="col col-10">
												<label class="input">
													<input name="descripcion" type="text" value="{{ ('edit' == $mode) ? $item->descripcion : '' }}" required="required" />
												</label>
											</div>
										</div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Domicilio Fiscal *:</label>
										<div class="col col-10">
											<label class="input">
												<input name="domicilio_fiscal" type="text" value="{{ ('edit' == $mode) ? $item->domicilio_fiscal : '' }}" required="required"/>
											</label>
										</div>
									</div>
								</section>
					
								
						<!-- Buttons inside Form!!-->
						<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
							<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
							<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
						</div>
                    {{ Form::close() }}
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
<script>
</script>
@include('genericEditScripts')
@include('configuracion.comprobante.comprobanteEditScripts')
