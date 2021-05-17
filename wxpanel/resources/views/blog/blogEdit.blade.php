<?php 
$mode = $aViewData['mode'];
$etiquetas= isset($aViewData['etiquetasBlog'])?$aViewData['etiquetasBlog']: null;
$aItem = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
?>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-user fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
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
                                            
                                                <fieldset>
                                                        <div>
                                                        
                                                                <section class="row">
                                                                        <label class="col col-md-2">Fecha *:</label>
                                                                        <label class="input col col-md-10 row">
                                                                                <input type="text" autocomplete="off" name="fecha" id="fecha" value="{{ ('edit' == $mode) ? date('d/m/Y', strtotime($aItem['fecha'])) : date('d/m/Y') }}" required />
                                                                        </label>
                                                                </section>
                                                                <section class="row">
                                                                        <label class="col col-md-2">Título *:</label>
                                                                        <label class="input col col-md-10 row">
                                                                            <input class="col col-md-12" type="text" name="titulo" required="" value="{{ ('edit' == $mode) ? $aItem['titulo'] : '' }}" />
                                                                        </label>
                                                                </section>
                                                                <section class="row">
                                                                        <label class="col col-md-2">Sumario *:</label>
                                                                        <label class="input col col-md-10 row">
                                                                            <input class="col col-md-12" type="text" name="sumario" required="" value="{{ ('edit' == $mode) ? $aItem['sumario'] : '' }}" />
                                                                        </label>
                                                                </section>
                                                                <section class="row">
                                                                        <label class="col col-md-2">Etiquetas:</label>
                                                                        <label class="select col col-md-10 row"> 
                                                                                <select multiple style="width: 100%" class="select2" name="etiquetasBlogIds[]" id="etiquetasBlogIds" autocomplete="off">
                                                                                </select>
                                                                </section>
																<section class="row">
                                                                    <label class="col col-md-2">Keyword :</label>
                                                                    <label class="input col col-md-10 row">
																		<input class="col col-md-12" type="text" name="keyword" value="{{ ('edit' == $mode) ? $aItem['keyword'] : '' }}" />
																	</label>
                                                                </section>
                                                                <section class="row">
                                                                        <label class="col col-md-2">Id Video:</label>
																		
                                                                        <label class="input col col-md-10 row">
																			<small class="pull-left" style="    line-height: 27px;">http://www.youtube.com/watch?v=</small>
                                                                            <input class="col col-md-8" type="text" name="video_id" value="{{ ('edit' == $mode) ? $aItem['id_video'] : '' }}" />
                                                                        </label>
                                                                </section>
                                                                <section class="row">
                                                                    <label class="col col-md-2">Texto *:</label>
                                                                    <label class="textarea  col col-md-10 row">
                                                                            <input type="hidden" name="texto" id="texto" value="" />
                                                                            <div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
                                                                                    <div id="textoBox">{!! ('edit' == $mode) ? $aItem['texto'] : '' !!}</div>	
                                                                            </div>
                                                                    </label>
                                                                </section>
                                                                <section class="row">
                                                                        <label class="col col-md-2">Orden:</label>
                                                                        <label class="input col col-md-10 row">
                                                                            <input class="col col-md-12" type="text" name="orden" value="{{ ('edit' == $mode) ? $aItem['orden'] : '' }}" />
                                                                        </label>
                                                                </section>
                                                                
                                                        </div>
                                                <fieldset>
                                        </div>
                                
										<!-- Buttons inside Form!!-->
										<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">
											<div style="padding:0;" class="col-md-12">
												<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
												<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
											</div>
										</div>
                                {{ Form::close() }}
								</div>
                        </div>
                        
                        <!-- end widget content -->

                </div>
                <!-- end widget div -->

        </div>
        <!-- end widget -->
        
    </div>
</div>
<script>
</script>
@include('blog.blogEditScripts')