<script type="text/javascript">
	
	var sub1Form = sub1Form || {};
    var sub2Form = sub2Form || {};
	
	var $formContainer = $("#{{ $aViewData['resource'] }}" + '_formContainer');
	
    sub1Form.$form = $("form#{{ $aViewData['resource'] }}" + 'Sub1', $formContainer);
	sub2Form.$form = $("form#{{ $aViewData['resource'] }}" + 'Sub2', $formContainer);
   
    sub1Form.formValidate = sub1Form.$form.validate();
    
    $(function(){
    
		$("button#save", $formContainer).click(function(e){
           
            if (sub1Form.formValidate.form()) {
                var data = '';
				
				

                data += sub1Form.$form.serialize() + '&';
				data += sub2Form.$form.serialize() + '&';
               
                appCustom.ajaxRest(
                   @if("add" === $mode)     
                        appCustom.{{ $aViewData['resource'] }}.STORE.url,
                        appCustom.{{ $aViewData['resource'] }}.STORE.verb,
                   @else
                        appCustom.{{ $aViewData['resource'] }}.UPDATE.url( {{ $item->id }} ),
                        appCustom.{{ $aViewData['resource'] }}.UPDATE.verb,
                   @endif
                   data,
                   function(response){
                       if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							if ($( "#{{ $aViewData['resource'] }}_datatable_tabletools" ).length) {
								$('#{{ $aViewData['resource'] }}_datatable_tabletools')
									.dataTable()
									.fnStandingRedraw()
								;
							}
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

        $("button#send", $formContainer).click(function(e){
           
            if (sub1Form.formValidate.form()) {
                var data = '';
				
				

                data += sub1Form.$form.serialize() + '&';
				data += sub2Form.$form.serialize() + '&';
               
                appCustom.ajaxRest(
                   @if("add" === $mode)     
                        appCustom.{{ $aViewData['resource'] }}.STORE.url,
                        appCustom.{{ $aViewData['resource'] }}.STORE.verb,
                   @else
                        appCustom.{{ $aViewData['resource'] }}.UPDATE.url( {{ $item->id }} ),
                        appCustom.{{ $aViewData['resource'] }}.UPDATE.verb,
                   @endif
                   data,
                   function(response){
                       if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							if ($( "#{{ $aViewData['resource'] }}_datatable_tabletools" ).length) {
								$('#{{ $aViewData['resource'] }}_datatable_tabletools')
									.dataTable()
									.fnStandingRedraw()
								;
							}
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
		
		//prevent default submitt on enter
		$('input', $formContainer).keydown(function(e){
			if(13 === e.keyCode)
			{
				e.preventDefault();
				e.stopPropagation();
				
				$("button#save", $formContainer).trigger('click');
				
			}
		});
			
    
    
        
        
    });
	
		
    
	
	
	
    
    
</script>
