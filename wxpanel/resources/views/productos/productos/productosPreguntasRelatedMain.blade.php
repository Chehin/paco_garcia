<?php
	$item = isset($aViewData['item']) ? $aViewData['item'] : null;
?>
<style>
.toggDiv{
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
                <i class="fa fa-map-marker fa-fw "></i> 
                            Productos 
                    <span>> 
                            Preguntas
                    </span>
            </h6>
        </div>
        <!-- NEW WIDGET START -->
        <article>

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
    			
                <!-- widget div-->
                <div id="{{ $aViewData['resource'] }}_formContainer">                    
					
                    @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                    <div class="tab-pane fade active in">
                        
                        <div>
                            <div class="toggDiv" style="display:none;">
                                {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
                                {{ Form::hidden('resource_id', $item->id) }}
                                <fieldset class="scheduler-border" id="myTabContent3">
                                    <section>
                                        <div class="row">
                                            <label class="label col col-md-2">Pregunta:</label>
                                            <div class="col col-md-10">
                                                <label class="input">
                                                    <input type="text" name="pregunta" required="" readonly="" />
                                                </label>
                                            </div>
                                        </div>
                                    </section>
                                    <section>
                                        <div class="row">
                                            <label class="label col col-md-2">Respuesta:</label>
                                            <div class="col col-md-10">
                                                <label class="textarea">
                                                    <textarea id="respuesta" name="respuesta"></textarea>
                                                </label>
                                            </div>
                                        </div>
                                    </section>
                                </fieldset>
                                    
                                <div class="col pull-right" style="margin-top:13px;margin-bottom: 13px;">
                                    <div class="col-md-12">
                                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                                        <button type="button" id="saveRespuesta" data-mode="edit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>
                                        <button type="button" id="cancelRespuesta" class="btn btn-danger hidden"><i class="fa fa-times none"></i> Cancelar</button>
                                        @endif
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>                            
                        </div>
                                                
                    </div>
                    @endif

                    <br clear="all" />

                    <div style="margin-top:2%;border-top: 2px solid #e6e6e6;" class="widget-body no-padding">
                        <div class="widget-body-toolbar">

                        </div>
						 
                        <!-- end widget div -->
                        <table id="sub1_{{ $aViewData['resource'] }}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
									<th>Pregunta</th>
									<th>Fecha</th>
									<th>Estado</th>
									<th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                        
                </div>
               
                <!-- end widget content -->
            </div>
            <!-- end widget -->
        </article>
    </div>
</div>
@include('productos.productos.productosPreguntasRelatedMainScripts')