<script>
	var marketingPersonaForm = marketingPersonaForm || {};
    var marketingPersonaTel = marketingPersonaTel || {};
    var $formContainer = $("#{{ $aViewData['resource'] }}" + '_formContainer');

	marketingPersonaForm.$form = $("form#marketingPersonasForm", $formContainer);

    marketingPersonaForm.formValidate = marketingPersonaForm.$form.validate();        

    marketingPersonaTel.$form = $("form#marketingPersonasTel", $formContainer);

    marketingPersonaTel.formValidate = marketingPersonaTel.$form.validate();

    $("button#save", $formContainer).click(function(e){
        if (marketingPersonaForm.formValidate.form()) {
            var data = '';

            data += marketingPersonaForm.$form.serialize() + '&';
            data += marketingPersonaTel.$form.serialize() + '&';

            appCustom.ajaxRest(
                @if("add" === $mode)     
                        appCustom.marketingPersonas.STORE.url,
                        appCustom.marketingPersonas.STORE.verb,
                @else
                        appCustom.marketingPersonas.UPDATE.url( '{{ $item->id }}' ),
                        appCustom.marketingPersonas.UPDATE.verb,
                @endif
                data,
                function(response){
                    if (0 == response.status) {
                        appCustom.smallBox('ok','');
                        appCustom.hideModal();
						
						if ($( "#marketingPersonas_datatable_tabletools" ).length) {
							$('#marketingPersonas_datatable_tabletools')
								.dataTable()
								.fnStandingRedraw()
							;
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
</script>