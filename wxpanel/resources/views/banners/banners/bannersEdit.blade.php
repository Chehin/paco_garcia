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
                    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
                        <div class="tab-pane fade active in" id="l1">
                            
                            <fieldset>
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
                                        <label class="label col col-2">Cliente *:</label>
                                        <div class="col col-10">
                                            <label class="select">
                                                <?php $toDropDown2 = $aViewData['aCustomViewData']['aClientes']->prepend('Seleccione', ''); ?>
                                                {{ Form::select(
                                                        'id_cliente',
                                                        $toDropDown2,
                                                        ("edit" == $mode) ? $item->id_cliente : '',
                                                        ['class' => 'col col-md-12', 'required' => '','id' => 'id_cliente']
                                                    )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Posici??n *:</label>
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
                                    <div class="row">
										<label class="label col col-2">Links:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="link" value="{{ ('edit' == $mode) ? $item->link : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Target:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="target" value="{{ ('edit' == $mode) ? $item->target : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Etiqueta:</label>
                                        <div class="col col-10">
                                            <label class="select">
                                                <?php $toDropDown4 = $aViewData['aCustomViewData']['aEtiquetas']->prepend('Seleccione', ''); ?>
                                                {{ Form::select(
                                                        'id_etiqueta',
                                                        $toDropDown4,
                                                        ("edit" == $mode) ? $item->id_etiqueta : '',
                                                        ['class' => 'col col-md-12']
                                                    )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-md-2">Archivo:</label>
                                        <div class="col col-md-10">
                                            <label class="input">
                                                @if("edit" == $mode && !empty($item->banners))
                                                <div class="archivoAdjunto">
                                                    <ul class="demo-btns">
                                                        <li>
                                                            <div class="btn-group archivosBanner">
                                                                <button title="Descargar archivo" name="downloadfile" data-id="{{ $item->banners }}" class="btn btn-default">
                                                                    {{ $item->bannersDecoded }}
                                                                </button>
                                                                <button title="Eliminar archivo" name="dropfile" data-id="{{ $item->banners }}" class="btn btn-default dropdown-toggle">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                                <div class="text-xs-center">
                                                    <div class="campo">
                                                        <a href="javascript:void(0);" id="boton_archivo" class="btn btn-primary" style="{{ ('edit' == $mode) ? ($item->banners) ? 'display:none' : '' : ''}}">
                                                            <i class="fa fa-paperclip"> </i>
                                                            Adjuntar Archivo
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="form-group text-xs-center">
                                                    <div class="col-xs-12 campo" id="recibo" name="recibo">
                                                        <div id="my-awesome-dropzone" class="dropzone" style="display: none"></div>
                                                    </div>
                                                </div>                                           
                                                <input type="hidden" name="files" id="files"></input>
                                                <input type="hidden" name="filesDeleted" id="filesDeleted"></input>
                                            </label>
                                        </div>
                                    </div>
                                </section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Alto Flash:</label>
                                        <div class="col col-4">
											<label class="input">
												<input type="text" name="altoflash" value="{{ ('edit' == $mode) ? $item->altoflash : '' }}" />
											</label>
                                        </div>
                                        <label class="label col col-2">Ancho Flash:</label>
                                        <div class="col col-4">
											<label class="input">
												<input type="text" name="anchoflash" value="{{ ('edit' == $mode) ? $item->anchoflash : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								<section>
                                    <div class="row">
										<label class="label col col-2">alto PopUp:</label>
                                        <div class="col col-4">
											<label class="input">
												<input type="text" name="altopopup" value="{{ ('edit' == $mode) ? $item->altopopup : '' }}" />
											</label>
                                        </div>
                                        <label class="label col col-2">Ancho PopUp:</label>
                                        <div class="col col-4">
											<label class="input">
												<input type="text" name="anchopopup" value="{{ ('edit' == $mode) ? $item->anchopopup : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Fecha Inicio:</label>
                                        <div class="col col-4">
											<label class="input">
												<i class="icon-append fa fa-calendar"></i>
												<input type="text" name="inicio" id="inicio" value="{{ ('edit' == $mode) ? $item->inicio->format('d/m/Y') : '' }}" />
											</label>
                                        </div>
                                        <label class="label col col-2">Fecha Fin:</label>
                                        <div class="col col-4">
											<label class="input">
												<i class="icon-append fa fa-calendar"></i>
												<input type="text" name="fin" id="fin" value="{{ ('edit' == $mode) ? $item->fin->format('d/m/Y') : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>						
								<section class="section-textarea">
                                    <div class="row">
										<label class="label col col-2">Texto:</label>
                                        <div class="col col-10">
											<label class="textarea">
												<textarea row="3" name="texto">{{ ('edit' == $mode) ? $item->texto : '' }}</textarea>
											</label>
                                        </div>
                                    </div>
								</section>
                            </fieldset>
                        </div>
						<!--	Google map-->
						<div class="row">
							<div class="col-md-12">
								<div id='map_canvas'></div>
								
							</div>
						</div>
						<!--	Google map End-->
                
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
        }) 

    });
</script>
@include('genericEditScripts')
