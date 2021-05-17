<?php

extract($aViewData);
extract($aCustomViewData);

?>

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
                    <fieldset>
                                <section>
                                    <div class="row">
										<label class="label col col-2">Nombre *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="name" required="" value="{{ ('edit' == $mode) ? $item->name : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Razón Social *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="name_org" required="" value="{{ ('edit' == $mode) ? $item->name_org : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								<section>
                                    <div class="row">
										<label class="label col col-2">CUIT *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="cuit" required="" value="{{ ('edit' == $mode) ? $item->cuit : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                        <div class="row">
                                            <label class="label col col-2">Inicio de Actividades:</label>
                                            <div class="col col-10">
                                                <label class="input">
                                                    <input type="text" name="iactividades" value="{{ ('edit' == $mode) ? Carbon\Carbon::parse($item->iactividades)->format('d/m/Y')  : '' }}" />
                                                </label>
                                            </div>
                                        </div>
                                </section>
								<section>
                                    <div class="row">
										<label class="label col col-2">IVA:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="iva" value="{{ ('edit' == $mode) ? $item->iva : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section>
                            
                                <section class="row">
                                        <label class="col col-md-2">Domicilio *:</label>
                                        <label class="textarea  col col-md-10 row">
                                            <input type="hidden" name="domicilio" id="texto" value="" />
                                            <div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
                                                <div id="textoBox">{!! ('edit' == $mode) ? $item->domicilio : '' !!}</div>	
                                            </div>
                                        </label>
                                </section>
                                    
								<section>
                                    <div class="row">
                                
                                        <label class="label col col-2">Teléfono:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="telephone" value="{{ ('edit' == $mode) ? $item->telephone : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Email:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="email" value="{{ ('edit' == $mode) ? $item->email : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Sitio Web:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="web" value="{{ ('edit' == $mode) ? $item->web : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Facebook:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="facebook" value="{{ ('edit' == $mode) ? $item->facebook : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
					
								<section>
                                    <div class="row">
										<label class="label col col-2">Twitter:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="twitter" value="{{ ('edit' == $mode) ? $item->twitter : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                    </fieldset>
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
