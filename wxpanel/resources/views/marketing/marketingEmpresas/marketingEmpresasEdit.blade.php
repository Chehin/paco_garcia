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
										<label class="label col col-3">Razón Social *:</label>
                                        <div class="col col-9">
											<label class="input">
												<input type="text" name="razon_social" required="" value="{{ ('edit' == $mode) ? $item->razon_social : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
										<label class="label col col-3">Dominio:</label>
                                        <div class="col col-9">
											<label class="input">
												<input type="text" name="dominio" value="{{ ('edit' == $mode) ? $item->dominio : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section>
								<section>
                                    <div class="row">
										<label class="label col col-3">Email *:</label>
                                        <div class="col col-9">
											<label class="input">
												<input type="text" name="email" required="" value="{{ ('edit' == $mode) ? $item->email : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section>                                
                                <section>
                                    <div class="row">
										<label class="label col col-3">Dirección *:</label>
                                        <div class="col col-9">
											<label class="input">
												<input type="text" name="direccion" value="{{ ('edit' == $mode) ? $item->direccion : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
										<label class="label col col-3">Telefono:</label>
                                        <div class="col col-9">
											<label class="input">
                                                <input type="text" name="telefono" value="{{ ('edit' == $mode) ? $item->telefono : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-3">País *:</label>
                                        <div class="col col-9">
                                            <label class="select">
                                                <?php $toDropDown1 = $aViewData['aCustomViewData']['aPaises']->prepend('Seleccionar Pais', ''); ?>
                                                {{ Form::select(
                                                'id_pais',
                                                $toDropDown1,
                                                ("edit" == $mode) ? $item->id_pais : '',
                                                ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_pais']
                                                )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-3">Provincia:</label>
                                        <div class="col col-9">
                                            <label class="select">
                                                {{ Form::select(
                                                'id_provincia',
                                                $aViewData['aCustomViewData']['aProvincias'],
                                                ("edit" == $mode) ? $item->id_provincia : '',
                                                ['class' => 'col col-md-12','id'=> 'id_provincia']
                                                )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
										<label class="label col col-3">Ciudad:</label>
                                        <div class="col col-9">
											<label class="input">
                                                <input type="text" name="ciudad" value="{{ ('edit' == $mode) ? $item->ciudad : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-3">Personas:</label>
                                        <div class="col col-9">
                                            <label class="select"> 
                                                <select multiple style="width: 100%" class="select2" name="personasIds[]" id="personasIds">
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-3">Oportunidades:</label>
                                        <div class="col col-9">
                                            <label class="select"> 
                                                <select multiple style="width: 100%" class="select2" name="oportunidadesIds[]" id="oportunidadesIds">
                                                </select>
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
		
        appCustom.ajaxRest(
		'rest/v1/personasIds',
		'GET',
		null,
		function(result){
			var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
			var $element = $('form#' + formHTMLId + ' select#personasIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aPersonasAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar Personas',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );

        appCustom.ajaxRest(
		'rest/v1/oportunidadesIds',
		'GET',
		null,
		function(result){
			var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
			var $element = $('form#' + formHTMLId + ' select#oportunidadesIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aPersonasAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar Oportunidades',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );
        
	});
    
    $( "select[name=id_pais]" ).change(function() {
        var id_pais = $( this ).val();
        appCustom.ajaxRest(
            appCustom.{{$aViewData['resource']}}.OBTENER_PROVINCIAS.url,
            appCustom.{{$aViewData['resource']}}.OBTENER_PROVINCIAS.verb,
            {id_pais: id_pais}, 
            function(result) {
                if (0 == result.status) {
                    if (result.provincias) {
                        $('#id_provincia').html('<option value="" selected="selected">Seleccionar Provincia</option>');
                        for (var i = 0; i < result.provincias.length ; i++) {
                            $('#id_provincia').append('<option value="'+result.provincias[i].id+'">'+result.provincias[i].text+'</option>');
                        }
                    } else {
                        $('#id_provincia').html('<option value="" selected="selected">No hay Provincias</option>');
                    };
                } else {
                    appCustom.smallBox(
                        'nok',
                        result.msg,
                        null,
                        'NO_TIME_OUT'
                    );
                }
            }
        );
    });
</script>
@include('genericEditScripts')