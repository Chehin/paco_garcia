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
                <i class="fa fa-male fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
            </h6>
        </div>
    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div id="{{ $aViewData['resource'] }}_formContainer">

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
                        <li class="pull-right">
                            <a href="#l1" data-toggle="tab">Teléfonos</a>
                        </li>
                        <li class="pull-right active">
                            <a href="#l2" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
                        </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                        
                        <div class="tab-pane fade" id="l1">                            
                            @include('marketing.marketingPersonas.marketingPersonasEditSubTel')
                        </div>
                    
                        <div class="tab-pane fade active in" id="l2">
                            @include('marketing.marketingPersonas.marketingPersonasEditSubForm')
                        </div>
                
						<!-- Buttons inside Form!!-->
						<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
							<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
							<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
						</div>
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
		'rest/v1/empresasIds',
		'GET',
		null,
		function(result){
			var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
			var $element = $('form#' + formHTMLId + ' select#empresasIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aEmpresasAssigned']?>');
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
				placeholder: 'Seleccionar Empresas',
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
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aEmpresasAssigned']?>');
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

        appCustom.ajaxRest(
		'rest/v1/listasIds',
		'GET',
		null,
		function(result){
			var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
			var $element = $('form#' + formHTMLId + ' select#listasIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aListasAssigned']?>');
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
				placeholder: 'Seleccionar Listas',
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
@include('marketing/marketingPersonas/marketingPersonasEditScripts')