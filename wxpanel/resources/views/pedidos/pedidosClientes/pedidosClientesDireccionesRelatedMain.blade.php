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
                            Cliente 
                    <span>> 
                            Direcciones
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
                            <section>
                                <button id="newDir" type="button" class="btn btn-labeled btn-success pull-right" style="margin-bottom:10px;">
                                    <span class="btn-label">
                                     <i class="glyphicon glyphicon-plus"></i>
                                    </span>Nueva Dirección
                               </button>
                            </section>
                            <div class="toggDiv" style="display:none;">
                                {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
                                {{ Form::hidden('resource_id', $item->id) }}
                                <fieldset class="scheduler-border" id="myTabContent3">
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Direccion *: </label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="direccion" required="" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Número *:</label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="numero" required="" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Piso: </label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="piso" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Departamento :</label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="departamento"  value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Teléfono *: </label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="telefono" required="" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Código postal *:</label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="cp" required="" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-3">Provincia *:</label>
                                                    <div class="col col-9">
                                                        <label class="select">
                                                            <?php $toDropDown1 = $aViewData['aProvincias']->prepend('Seleccione', ''); ?>
                                                            {{ Form::select(
                                                                    'id_provincia',
                                                                    $toDropDown1,'',
                                                                    ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_provincia']
                                                                )
                                                            }}
                                                            <i></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="col col-md-6">
                                            <section>
                                                <div class="row">
                                                    <label class="label col col-md-3">Ciudad *:</label>
                                                    <div class="col col-md-9">
                                                        <label class="input">
                                                            <input type="text" name="ciudad" required="" value="" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <section>
                                        <div class="row">
                                            <label class="label col col-md-2">Referencia *:</label>
                                            <div class="col col-md-10">
                                                <label class="input">
                                                    <input type="text" name="titulo" required="" value="" />
                                                </label>
                                            </div>
                                        </div>
                                    </section>
                                    <section>
                                        <div class="row">
                                            <label class="label col col-md-2">Informacion adicional:</label>
                                            <div class="col col-md-10">
                                                <label class="textarea">
                                                    <textarea row="4" name="informacion_adicional"></textarea>
                                                </label>
                                            </div>
                                        </div>
                                    </section>                                    
                                </fieldset>
                                    
                                <div class="col pull-right" style="margin-top:13px;margin-bottom: 13px;">
                                    <div class="col-md-12">
                                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                                        <button type="button" id="saveDir" data-mode="add" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Guardar 
                                        </button>
                                        <button type="button" id="cancelDir" class="btn btn-danger hidden"><i class="fa fa-times none"></i> Cancelar</button>
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
                                    <th>Referencia</th>
									<th>Dirección</th>
									<th>Provincia</th>
									<th>Ciudad</th>
                                    <th>Código postal</th>
									<th>Info. adicional</th>
									<th>Teléfono</th>
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
@include('pedidos.pedidosClientes.pedidosClientesDireccionesRelatedMainScripts')