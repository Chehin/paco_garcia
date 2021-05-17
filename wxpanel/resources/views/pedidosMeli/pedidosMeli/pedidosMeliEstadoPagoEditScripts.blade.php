<script>
  
   $(function() {
        //DOM Settings
        var resourceDOM = {};
        var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
		var redrawTableAfterSend = $('#' + formHTMLId + ' #param_redrawTableAfterSend').val();
		var redrawTableAfterSendDiferentTable = $('#' + formHTMLId + ' #param_redrawTableAfterSendDiferentTable').val();
		
		
        //Requests Settings
        var resourceReq = {};
        resourceReq.store = {};
        resourceReq.update = {};
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.estadopago.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.estadopago.STORE.verb;
       
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.estadopago.UPDATE.url(id);
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.estadopago.UPDATE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        
        $("button#save", resourceDOM.$form).click(function(e){
            if (resourceDOM.formValidate.form()) {
                var data = '';
                data += resourceDOM.$form.serialize() + '&';

                appCustom.ajaxRest(
                    @if("add" === $mode)     
                            resourceReq.store.url,
                            resourceReq.store.verb,
                    @else
                            resourceReq.update.url( '{{ $item->id_pedido }}' ),
                            resourceReq.update.verb,
                    @endif
                    data,
                    function(response){
                        if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							
							if (!redrawTableAfterSend || 'true' === redrawTableAfterSend) {
								
								var tableToRedraw = resourceTableId;
								if (redrawTableAfterSendDiferentTable) {
									tableToRedraw = redrawTableAfterSendDiferentTable;
								}
								
								$('#' + tableToRedraw).dataTable().fnStandingRedraw();
							}
                            
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
		//prevent default submitt on enter
		$('input', resourceDOM.$form).keydown(function(e){
			if(13 === e.keyCode)
			{
				e.preventDefault();
				e.stopPropagation();
				
				$("button#save", resourceDOM.$form).trigger('click');
				
			}
		});
        
   });
   
   
	
   
</script> 
