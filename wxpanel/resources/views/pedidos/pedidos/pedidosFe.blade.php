<script>
        $(document).ready(function() {
            $('#form_fe').submit(function() {
                
                var $form		= $(this);
               /*  var $dataStatus	= $form.find('.data-status'); */
        
               /*  var response = $form.find('input[name="contra"]'); */
                
                /* if(response.length == 0){
                    $dataStatus.show().html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Por favor ingrese la contraseña</strong></div>');
                    return false;
                }else{ */
                    var submitData	= $form.serialize();
                    /* var $contra		= $form.find('input[name="contra"]');
                    var $submit		= $form.find('button[name="submit"]'); */
                    
                    //7$contra.attr('disabled', 'disabled');
                        
                    //$dataStatus.show().html('<div class="alert alert-info"><strong>Enviando...</strong></div>');
                    
                    $.ajax({ // Send an offer process with AJAX
                        method: 'POST',
                        url: '/rest/v1/fe',
                        data: submitData,
                        dataType: 'json',
                        success: function(msg){
                            if(msg.msg=='ok'){
                               /*  $("#sub1_{{ $aViewData['resource'] }}_datatable_tabletools").show();
                                $dataStatus.hide(); */
                                console.log('ok');
                            }else{
                                    /* $dataStatus.show().html('<div class="alert alert-info"><strong>Contraseña incorrecta</strong></div>');
                                    $contra.removeAttr('disabled');
                                    $submit.removeAttr('disabled');  */                       
                                    console.log('nok');
                            }
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                
                    return false;
                //}
            });
        });
</script>