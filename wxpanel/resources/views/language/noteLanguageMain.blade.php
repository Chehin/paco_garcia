<?php
$item = (isset($aViewData['item'])) ? $aViewData['item'] : null;
?>
<style>
.lengu{
	background: #fff;
	padding: 10px 20px;
	float: left;
	width: 100%;
	border: 2px solid #e6e6e6;
}
</style>
<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-flag fa-fw"></i> 
                            Idioma
                    <span>> 
                            {{ $aViewData['resourceLabel'] }} 
                    </span>
                    <span>> 
                            "{{ App\AppCustom\Util::truncateString($item->titulo, 50) }}"
                    </span>   
            </h6>
        </div>
<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <!-- widget div-->
                <div>
                        
                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                        <div class="tab-pane fade active in lengu" id="myTabContent3">
                            {{ Form::open(['id' => $aViewData['resource'] . 'FormLang', 'name' => $aViewData['resource'] . 'FormLang']) }}
                                {{ Form::hidden('id_nota', $item->id_nota) }}
                                <fieldset>
									<legend class="scheduler-border">Cargar idioma</legend>
                                    <div>
											<section class="row">
                                                <label class="col col-md-2">Idioma *:</label>
                                                <label class="select col-md-10 row">
                                                    <?php $aViewData['langs']->prepend('Seleccione un idioma...');?>
                                                    {{ Form::select(
                                                        'id_idioma', 
                                                        $aViewData['langs'], 
                                                        null, 
                                                        ['class' => 'col col-md-12']
                                                        ) 
                                                    }}
                                                </label>
                                            </section>
                                            <section class="row">
                                                    <label class="col col-md-2">Título *:</label>
                                                    <label class="input col col-md-10 row">
                                                        <input class="col col-md-12" type="text" name="titulo" required="" value="" />
                                                    </label>
                                            </section>
											<section class="row">
                                                    <label class="col col-md-2">Sumario:</label>
                                                    <label class="input col col-md-10 row">
                                                        <input class="col col-md-12" type="text" name="sumario"  value="" />
                                                    </label>
                                            </section>
											<section class="row">
                                                    <label class="col col-md-2">Keyword:</label>
                                                    <label class="input col col-md-10 row">
                                                        <input class="col col-md-12" type="text" name="keyword"  value="" />
                                                    </label>
                                            </section>
											<section class="row">
												<label class="col col-md-2">Texto *:</label>
												<label class="textarea  col col-md-10 row">
														<input type="hidden" name="texto" id="texto" value="" />
														<div style="border:1px solid #929292; width:100%;" class="inbox-message no-padding">
																<div id="textoBox"></div>	
														</div>
												</label>
											</section>
                                    </div>
                                </fieldset>

                                <div class="row pull-right" style="margin-top:13px;margin-bottom: 13px;">
                                        <div class="col-md-12">
                                            @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                                            <button type="button" id="save" data-mode="add" class="btn btn-primary">
                                                    <i class="fa fa-save"></i> Guardar Idioma 
                                                </button>
                                            <button type="button" id="cancel" class="btn btn-danger hidden"><i class="fa fa-times none"></i> Cancelar Edición</button>
                                            @endif
                                        </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                        @endif
                        <br clear="all"/>
                        
                        
                         <div style="margin-top:2%;border-top: 2px solid #e6e6e6;" class="widget-body no-padding">
                            <div class="widget-body-toolbar">

                            </div>
                            <!-- end widget div -->
                            <table id="{{$aViewData['resource']}}_lang_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
                                <thead>
                                    <tr>
                                        <th>Idioma</th>
                                        <th>Título</th>
                                        <th>Sumario</th>
										<th>Actualizado</th>
										<th>Habilitado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                </div>
               
                <!-- end widget content -->
        </div>
        <!-- end widget -->
    </div>
</div>
@include('language.noteLanguageMainScripts')