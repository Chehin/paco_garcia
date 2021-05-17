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
                <i class="fa fa-tag fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
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
                                        <label class="label col col-2">Color *:</label>
                                        <div class="col col-10">
                                            <div class="btn-group" data-toggle="buttons">
                                                
                                                <label class="btn btn-default {{ ('edit' == $mode) ? ($item->color=='0') ? 'active' : '' : 'active' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="0" {{ ('edit' == $mode) ? ($item->color=='0') ? 'checked' : '' : 'checked' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>

                                                <label class="btn menu-primary {{ ('edit' == $mode) ? ($item->color=='primary') ? 'active' : '' : '' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="primary" {{ ('edit' == $mode) ? ($item->color=='primary') ? 'checked' : '' : '' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>

                                                <label class="btn menu-success {{ ('edit' == $mode) ? ($item->color=='success') ? 'active' : '' : '' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="success" {{ ('edit' == $mode) ? ($item->color=='success') ? 'checked' : '' : '' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>

                                                <label class="btn menu-info {{ ('edit' == $mode) ? ($item->color=='info') ? 'active' : '' : '' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="info" {{ ('edit' == $mode) ? ($item->color=='info') ? 'checked' : '' : '' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>

                                                <label class="btn menu-warning {{ ('edit' == $mode) ? ($item->color=='warning') ? 'active' : '' : '' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="warning" {{ ('edit' == $mode) ? ($item->color=='warning') ? 'checked' : '' : '' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>

                                                <label class="btn menu-danger {{ ('edit' == $mode) ? ($item->color=='danger') ? 'active' : '' : '' }}">
                                                    <input type="radio" name="color" autocomplete="off" value="danger" {{ ('edit' == $mode) ? ($item->color=='danger') ? 'checked' : '' : '' }}>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </label>
                                            
                                            </div>
                                        </div>
                                    </div>                                    
                                </section>
								<section>
                                    <div class="row">
										<label class="label col col-2">Sumario:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="sumario" value="{{ ('edit' == $mode) ? $item->sumario : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Menu:</label>
                                        <div class="col col-10">
                                            <label class="checkbox">
                                                <input type="checkbox" name="menu" id="menu" value="1" {{ ('edit' == $mode) ? ($item->menu) ? 'checked' : '' : '' }}>
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Rubros:</label>
                                        <div class="col col-10">
                                            <label class="select"> 
                                                <select multiple style="width: 100%" class="select2" name="rubrosIds[]" id="rubrosIds">
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </section>	

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Orden:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="orden" value="{{ ('edit' == $mode) ? $item->orden : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>							
                            </fieldset>
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
    $(function(){

        var newEtiquetaSubRubros = [];
        appCustom.ajaxRest(
            'rest/v1/rubrosIds', 
            'GET', 
            null, 
            function(result){

                var $element = $('form#etiquetasForm select#rubrosIds');
                for (var i = 0; i < result.length; i++) {
                    // Create the DOM option that is pre-selected by default
                    var option = new Option(result[i].text, result[i].id, false, false);
                    // Append it to the select
                    $element.append(option);
                };
                $element.select2({
                    placeholder: 'Seleccionar',
                    minimumInputLength: 0,
                    allowClear : true,
                    width : '100%'
                });                 
                  
                @if ('edit' == $mode) 
                var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aRubrosAssigned']?>');
                var dataSelect = [];
                $.each(data ,function( index, value ) {
                    dataSelect.push(value.id);
                });
                $element.val(dataSelect).trigger("change");
                @endif
            }, 
            'sync'
        );        
        
    });
</script>

@include('genericEditScripts')
