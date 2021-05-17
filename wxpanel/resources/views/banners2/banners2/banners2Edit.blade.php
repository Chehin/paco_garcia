<?php 
$mode = $aViewData['mode'];
$item = (isset($aViewData['item'])) ? $aViewData['item'] : null;
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
                                    <a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
                            </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
                        <div class="tab-pane fade active in" id="l1">
                            <fieldset class="smart-form">
                            	<section>
                                    <div class="row">
                                        <label class="label col col-2">Tipo *:</label>
                                        <div class="col col-10">
                                            <label class="select">
                                                <?php $toDropDown1 = $aViewData['aCustomViewData']['aTipos']->prepend('Seleccione', ''); ?>
                                                {{ Form::select(
                                                        'id_tipo',
                                                        $toDropDown1,
                                                        ("edit" == $mode) ? $item->id_tipo : '',
                                                        ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_tipo']
                                                    )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Posición *:</label>
                                        <div class="col col-10">
                                            <label class="select">
                                                <?php $toDropDown3 = $aViewData['aCustomViewData']['aPosiciones']->prepend('Seleccione', ''); ?>
                                                {{ Form::select(
                                                        'id_posicion',
                                                        $toDropDown3,
                                                        ("edit" == $mode) ? $item->id_posicion : '',
                                                        ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_posicion']
                                                    )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Nombre *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								
								
								<section>
									<div class="widget-body">
										
										<hr class="simple">
										
										<ul id="myTab1" class="nav nav-tabs bordered">
											<li class="active">
												<a href="#s1" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Contenidos</a>
											</li>
											<li class="">
												<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Productos</a>
											</li>
										</ul>
				
										<div id="myTabContent1" class="tab-content padding-10">
											<div class="tab-pane fade active in" id="s1">
												<section>
													<div class="row">
														<label class="label col col-2">Contenido (tipo):</label>
														<div class="col col-10">
															<label class="select">
																<?php 
																	$toDropDown4 = 
																		$aViewData['aCustomViewData']['contenido']
																			->prepend('Todos', 'all')
																			->prepend('Ninguno', 0)
																		; 
																?>
																{{ Form::select(
																		'contenido',
																		$toDropDown4,
																		("edit" == $mode) ? $item->contenido : '',
																		['class' => 'col col-md-12', 'id' => 'contenido']
																	)
																}}
																<i></i>
															</label>
														</div>
													</div>
												</section>
												<section>
													<div class="row">
														<label class="label col col-2">Contenido (Id):</label>
														<div class="col col-10">
															<label class="input">
																<input type="text" name="contenido_id" id="contenido_id" value="{{ ('edit' == $mode) ? $item->contenido_id : '' }}" />
															</label>
														</div>
													</div>
												</section>
											</div>
											<div class="tab-pane fade" id="s2">
												<section>
													<div class="row">
														<label class="label col col-2">Productos (rubro):</label>
														<div class="col col-10">
															<label class="select">
																<?php 
																	$toDropDown4 = 
																		$aViewData['aCustomViewData']['rubros']
																			->prepend('Todos', 'all')
																			->prepend('Ninguno', 0)
																		; 
																?>
																{{ Form::select(
																		'rubro_id',
																		$toDropDown4,
																		("edit" == $mode) ? $item->rubro_id : '',
																		['class' => 'col col-md-12', 'id' => 'rubro_id']
																	)
																}}
																<i></i>
															</label>
														</div>
													</div>
												</section>
												
												<section>
													<div class="row">
														<label class="label col col-2">Producto (Id):</label>
														<div class="col col-10">
															<label class="input">
																<input type="text" name="producto_id" id="producto_id" value="{{ ('edit' == $mode) ? $item->producto_id : '' }}" />
															</label>
														</div>
													</div>
												</section>
								
											</div>
										</div>
										
										<hr class="simple">
									</div>
									
								</section>
								
								<section>
                                    <div class="row">
										<label class="label col col-2">Label submit:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="label_submit" id="label_submit" value="{{ ('edit' == $mode) ? $item->label_submit : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								
								<section>
                                    <div class="row">
										<label class="label col col-2">Repetición *:</label>
										
                                        <div class="col col-10">
											<label class="input">
												<input type="number" min="0" name="repeticion" id="repeticion" value="{{ ('edit' == $mode) ? $item->repeticion : '0' }}" />
											</label>
											<div class="note">
												<strong>En días.</strong> 0 para repetir siempre
											</div>
                                        </div>
										
                                    </div>
								</section>
								
							</fieldset>	
								<section class="row">
									<label class="col col-md-2">Texto:</label>
									<label class="textarea  col col-md-10 row">
										<input type="hidden" name="texto" id="texto" value="" />
										<div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
											<div id="textoBox">{!! ('edit' == $mode) ? $item->texto : '' !!}</div>	
										</div>
									</label>
								</section>
							
								
                            
                        </div>
						
                
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
	$(document).ready(function() {

        pageSetUp();
        // FECHA INICIO Y FECHA FIN
        $('#inicio').datepicker({
        dateFormat : 'dd/mm/yy',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                onSelect : function(selectedDate) {
                $('#fin').datepicker('option', 'minDate', selectedDate);
                }
        });
        $('#fin').datepicker({
        dateFormat : 'dd/mm/yy',
                prevText : '<i class="fa fa-chevron-left"></i>',
                nextText : '<i class="fa fa-chevron-right"></i>',
                onSelect : function(selectedDate) {
                $('#inicio').datepicker('option', 'maxDate', selectedDate);
                }
        });

        @if ('edit' != $mode)
        $('#inicio').datepicker("setDate", new Date());
        $('#fin').datepicker("setDate", new Date());
        @endif   

        // Dropzone
        Dropzone.autoDiscover = false;
        $("#my-awesome-dropzone").dropzone({
            url: appCustom.{{$aViewData['resource']}}.UPLOAD.url,
            maxFilesize: 100, // MB
            addRemoveLinks:true,
            previewsContainer:'#my-awesome-dropzone',
            thumbnailWidth: 80,
            thumbnailHeight: 60,
            clickable:'#boton_archivo',
            // acceptedFiles: 'image/*,application/pdf',
            dictRemoveFile:'Remover',
            accept  : function(file,done){
                if(this.files.length)
                {
                    $('#my-awesome-dropzone').show();
                    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                    $("#boton_archivo").css("display","none");
                }
                done();
            },
            init    : function() {
                this.on("removedfile",function (file) {
                    if(this.files.length == 0)
                    {
                        $("#my-awesome-dropzone").hide();
                        $("#boton_archivo").css("display","inline-block");
                        var val = $("form#bannersForm input#files").val();
                        val = $("form#bannersForm input#files").val('');
                    }
                });
                this.on('success',function (file, msg) {
                    var val = $("form#bannersForm input#files").val();
                    val = $("form#bannersForm input#files").val(msg);                
                });
            }
        });

        $(".archivosBanner [name=downloadfile]").click(function(e){
            event.preventDefault();
            location.href = 'download/archivos/'  + e.target.dataset.id;
        });

        $(".archivosBanner [name=dropfile]").click(function(e){
            var fileName = this.dataset.id;
            var val = $("form#bannersForm input#filesDeleted").val(fileName);
            $(".archivoAdjunto").remove();
            $("#boton_archivo").show();
        });
		

    });

</script>
@include('genericEditScripts')
