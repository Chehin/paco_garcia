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
        
        resourceReq.store.url = appCustom.sucursales.STORE.url;
        resourceReq.store.verb = appCustom.sucursales.STORE.verb;
       
        resourceReq.update.url = function(id){
            return appCustom.sucursales.UPDATE.url(id);
        };
        resourceReq.update.verb = appCustom.sucursales.UPDATE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        
        
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
		
		//summernote
		$('#textoBox', resourceDOM.$form).summernote({
			height: 200,
			focus: false,
			tabsize: 2
		});
        
   });
    
   
</script> 

