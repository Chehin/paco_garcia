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
                <i class="fa fa-envelope fa-fw "></i> {{ $aViewData['resourceLabel'] }}
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
                                <a href="#l1" data-toggle="tab">{{$aViewData['resourceLabel']}}</a>
                            </li>
                        </ul>

                        <div id="myTabContent3" class="tab-content padding-10">
                            <div class="alert alert-info fade in">
                                <button class="close" data-dismiss="alert">
                                    ×
                                </button>
                                <i class="fa-fw fa fa-info"></i>
                                <strong>Info!</strong> Se asignarán etiquetas a <strong>{{ count($aViewData['aIds']) }}</strong> productos.
                            </div>
                            {{ Form::open(array('id' => $aViewData['resource'] . 'EtiquetasForm', 'name' => $aViewData['resource'] . 'EtiquetasForm')) }}
                            <input type="hidden" name="ids" value="{{ json_encode($aViewData['aIds']) }}" />
                            <div class="tab-pane fade active in" id="l1">
                                <fieldset class="smart-form">
                                    <section>
										<div class="row">
											<label class="label col col-2">Etiquetas:</label>
											<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="etiquetasIds[]" id="etiquetasIds">
													</select>
												</label>
											</div>
										</div>
									</section>  
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
$(function(){
    var $element = $('form#productosEtiquetasForm select#etiquetasIds');
    var data = JSON.parse('<?php echo $aViewData['etiquetas']?>');
    for (var i = 0; i < data.length; i++) {
        // Create the DOM option that is pre-selected by default
        var option = new Option(data[i].nombre, data[i].id, false, false);
        // Append it to the select
        $element.append(option);
    };
    $element.select2({
        placeholder: 'Seleccionar',
        minimumInputLength: 0,
        allowClear : true,
        width : '100%'
    });
    // Update the selected options that are displayed
    $element.trigger('change');
});
</script>
<script>

$(function() {
    //DOM Settings
    var resourceDOM = {};
    var formHTMLId = '{{$aViewData['resource'] . 'EtiquetasForm'}}';

        //Requests Settings
        var resourceReq = {};
        resourceReq.store = {};
        
        resourceReq.store.url = appCustom.setEtiquetas.STORE.url;
        resourceReq.store.verb = appCustom.setEtiquetas.STORE.verb;
       
      
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        
        $("button#save", resourceDOM.$form).click(function(e){
            if (resourceDOM.formValidate.form()) {
                var data = '';                
                data += resourceDOM.$form.serialize() + '&';
                appCustom.ajaxRest(
                        resourceReq.store.url,
                        resourceReq.store.verb,
                    data,
                    function(response){
                        if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							
							//to uncheck all checkboxes
                            itemIds = [];
                            $('#' + '{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();							
                        } else {
							var type = 'nok';
							if (2 == response.status) {
								type = 'warn';
							}
							
                             appCustom.smallBox(
                                 type,
                                 response.msg,
                                 null, 
                                 'NO_TIME_OUT'
                             );
                        }
                    }
                );
            }

        });
		
       
        
   });
   
   </script>
