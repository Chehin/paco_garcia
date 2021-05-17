<?php 

$mode = $aViewData['mode'];
$aItem = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;

?>
<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
				<i class="fa fa-user fa-fw "></i> {{ ('edit' == $mode) ? 'Editar Usuario' : 'Agregar Usuario' }}
				@if('edit' == $mode)
				<span>> "{{ App\AppCustom\Util::truncateString($aItem['first_name'] . ', ' .$aItem['last_name'], 50) }}"</span>
				@endif
			</h6>
        </div>
<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"

                -->
                <header>
                        <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                        <h2>My Data </h2>

                </header>

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
                                
                            
                                <!--allalalalala -->
                                <hr class="simple">

                                <ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                                        <li>
                                                <a href="#l1" data-toggle="tab">Permisos</a>
                                        </li>
                                        <li class="pull-right active">
                                                <a href="#l2" data-toggle="tab">Datos de usuario</a>
                                        </li>
                                        
                                </ul>

                                <div id="myTabContent3" class="tab-content padding-10">
                                        <div class="tab-pane fade" id="l1">
                                            @include('user.userEditSubPrivs')
                                        </div>
                                        <div class="tab-pane fade active in" id="l2">
                                            @include('user.userEditSubForm')
                                        </div>
                                </div>
                                <!-- lalalal-->
                                
                                <div class="row pull-right" style="margin-top:13px;margin-bottom: 13px;">
                                    <div class="col-md-12">
                                            <button type="submit" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
                                            <button type="submit" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
                                    </div>
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
   
    var userSubForm = userSubForm || {};
    var userSubPrivs = userSubPrivs || {};
    
    userSubForm.$form = $("form#userForm");
    userSubPrivs.$form = $("form#userPrivs");
   
    userSubForm.formValidate = userSubForm.$form.validate();
    
    $(function(){
    
          $("button#save").click(function(e){
            if (userSubForm.formValidate.form()) {
                var data = '';
				
				//trims
				var email = $('#email', userSubForm.$form).val();
				$('#email', userSubForm.$form).val($.trim(email));

                data += userSubForm.$form.serialize() + '&';
                data += userSubPrivs.$form.serialize() + '&';

                appCustom.ajaxRest(
                   @if("add" === $mode)     
                        appCustom.user.STORE.url,
                        appCustom.user.STORE.verb,
                   @else
                        appCustom.user.UPDATE.url( {{ $aItem['id'] }} ),
                        appCustom.user.UPDATE.verb,
                   @endif
                   data,
                   function(response){
                       if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							if ($( "#datatable_tabletools" ).length) {
								$('#datatable_tabletools')
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

        
    
    
        
        
    });
    
    
    
</script> 