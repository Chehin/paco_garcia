<script>
   
   $(function() {
        //DOM Settings
        var resourceDOM = {};
        var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
        //Requests Settings
        var resourceReq = {};
        resourceReq.store = {};
        resourceReq.update = {};
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.STORE.verb;
       
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.UPDATE.url(id);
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.UPDATE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        

         appCustom.ajaxRest(
            'rest/v1/etiquetasBlogIds',
            'GET',
            null,
            function(result){
                
                var $element = resourceDOM.$form.find('select#etiquetasBlogIds');
                
                @if ('edit' == $mode) 
                var data = JSON.parse('<?php echo $etiquetas?>');
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
                    placeholder: 'Seleccionar',
                    minimumInputLength: 0,
                    allowClear : true,
                    width : '100%'
                });
                
                // Update the selected options that are displayed
                $element.trigger('change');
            }, 
            'sync'
            );        
           
        
        $("button#save", resourceDOM.$form).click(function(e){
            if (resourceDOM.formValidate.form()) {
                var data = '';
				//summernote
                $('#texto', resourceDOM.$form).val($('#textoBox').code());
                
                data += resourceDOM.$form.serialize() + '&';

                appCustom.ajaxRest(
                    @if("add" === $mode)     
                            resourceReq.store.url,
                            resourceReq.store.verb,
                    @else
                            resourceReq.update.url( {{ $aItem['id'] }} ),
                            resourceReq.update.verb,
                    @endif
                    data,
                    function(response){
                        if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();

                            $('#' + resourceTableId).dataTable().fnStandingRedraw();
                        } else {
                             appCustom.smallBox(
                                 'nok',
                                 response.msg,
                                 null, 
                                 'NO_TIME_OUT'
                             );
                        }
                    }
                );
            }

        });

        $('#fecha').datepicker({
            dateFormat : 'dd/mm/yy',
            prevText : '<i class="fa fa-chevron-left"></i>',
            nextText : '<i class="fa fa-chevron-right"></i>'
        });
		
		//summernote
		$('#textoBox', resourceDOM.$form).summernote({
			height: 200,
			focus: false,
			tabsize: 2
        });
        
        
           
        
   });
    
   
</script> 

