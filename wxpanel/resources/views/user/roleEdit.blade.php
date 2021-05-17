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
				<i class="fa fa-group fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar ' . $aViewData['resourceLabel'] : 'Agregar ' . $aViewData['resourceLabel'] }}   
			</h6>
        </div>
<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
						{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
                                <hr class="simple">

                                <ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
									<li class="pull-right active">
											<a href="#l2" data-toggle="tab">Datos de {{ $aViewData['resourceLabel'] . (("edit" === $mode) ? ":\"$item->name\"" : "") }}</a>
									</li>
                                </ul>

                                <div id="myTabContent3" class="tab-content padding-10">
                                        <div class="tab-pane fade active in" id="l2">
                                            <div class="modal-body">
												<div>
													<div class="widget-body">
														
														<fieldset>
															<div class="row">
																<section class="row">
																	<label class="col col-md-2">Nombre del perfil *:</label>
																	<label class="input col col-md-10 row">
																		<input style="text-transform:uppercase" class="col col-md-12" type="text" name="name" required="" value="{{ ('edit' == $mode) ? $item->name : '' }}" />
																	</label>
																</section>
															</div>
														<fieldset>
														@if('edit' === $mode)
															@if(!$aViewData['users']->isEmpty())
															<section>
																<label class="input"> Usuarios que pertenecen al perfil:</label>
																<table class="table table-bordered table-striped">
																	<tbody>
																		@foreach($aViewData['users'] as $user)
																		<tr>
																			<td>{{ $user->last_name }}, {{ $user->first_name }} ({{ $user->email }})</td>
																		</tr>
																		@endforeach
																	</tbody>
																</table>
															</section>
															@else
															<label class="input"><b>No hay Usuarios que pertenezcan a este perfil</b></label>
															@endif
														@endif
													</div>
												</div>
											</div>

									</div> <!-- End div tab -->
							</div>
							<div class="row pull-right" style="margin-top:13px;margin-bottom: 13px;">
								<div class="col-md-12">
										<button  id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
										<button data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
								</div>
							</div>
{{ Form::close() }}
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
		
		//config
		var resourceTableId = '{{ $aViewData["resource"] }}_datatable_tabletools';
		
		var resourceReq = {};
		resourceReq.store = {};
        resourceReq.update = {};
		
		resourceReq.store.url = appCustom.role.STORE.url;
        resourceReq.store.verb = appCustom.role.STORE.verb;
		
		resourceReq.update.url = function(id){
            return appCustom.role.UPDATE.url(id);
        };
        resourceReq.update.verb = appCustom.role.UPDATE.verb;
		//end config
		
		var resourceFormId = "form#{{ $aViewData['resource'] . 'Form' }}";
		
		var resourceForm = {};
    
		resourceForm.$form = $(resourceFormId);

		resourceForm.formValidate = resourceForm.$form.validate();
    
		$("button#save", resourceForm.$form).click(function(e){
            if (resourceForm.formValidate.form()) {
                var data = '';

                data += resourceForm.$form.serialize() + '&';

                appCustom.ajaxRest(
                   @if("add" === $mode)     
                        resourceReq.store.url,
                        resourceReq.store.verb,
                   @else
                        resourceReq.update.url( {{ $item->id }} ),
                        resourceReq.update.verb,
                   @endif
                   data,
                   function(response){
                       if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
                            $('#' + resourceTableId)
								.dataTable()
								.fnStandingRedraw()
							;
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

   
		$(resourceFormId).submit(function(){
			return false;
		});
        
        
    });
    
    
    
</script> 