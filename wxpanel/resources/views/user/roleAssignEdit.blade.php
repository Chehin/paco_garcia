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
										<a href="#l2" data-toggle="tab">Perfiles del usuario: <b>"{{ $item->last_name }}, {{ $item->first_name }}"</b></a>
									</li>
                                </ul>

                                <div id="myTabContent3" class="tab-content padding-10">
									<div class="tab-pane fade active in" id="l2">
										<div class="modal-body">
											<div>
												<div class="widget-body">
													<div class="row  pull-right">
														<label class="col col-md-12 checkbox-inline">
															<input type="checkbox" class="checkbox style-0" name="checkAll">
															<span><label style="font-style: italic;"><b>Todos los perfiles</b></label></span>
														</label>
													</div>

													<fieldset>
														@foreach($aViewData['aRoles'] as $kRole => $role)
															<div class="row">		
																<section class="row">
																	<label class="col col-md-10">
																		<label class="checkbox-inline">
																			<?php
																			
																			$roleFound = 
																				$aViewData['aRolesAssigned']->filter(function($item) use($role) {
																								return $item->id == $role->id;
																				})
																				->first()
																			;
																			
																			$checked = ($roleFound) ? 'checked' : '';
																					
																			?>
																			<input {{ $checked }} type="checkbox" class="checkbox style-0" name="checkOpt[]" value="{{ $role->id }}" data-id="{{ $role->id }}">
																			<span><b>{{ $role->name }}</b></span>
																		</label>
																	</label>

																</section>
															</div>
														@endforeach

													<fieldset>
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
		var resourceReq = {};
		 resourceReq.update = {};
		
		resourceReq.update.url = function(id){
			return appCustom.roleAssign.UPDATE.url(id);
		};
		resourceReq.update.verb = appCustom.roleAssign.UPDATE.verb;
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
			resourceReq.update.url( {{ $item->id }} ),
			resourceReq.update.verb,
			data,
			function(response){
				if (0 == response.status) {
					 appCustom.smallBox('ok','');
					 appCustom.hideModal();
				} else {
					var boxType = 'nok';
					if (2 == response.status) {
						boxType = 'warn';
					}

					 appCustom.smallBox(
						boxType,
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
      
		//checkboxes handling
		$(resourceFormId + ' input[name=checkOpt\\[\\]]').change(function(e){
            var id = $(this).data('id');
			
            var check = true;
            if (!this.checked) {
                check = false;
            }
            
            $(resourceFormId + ' .group' + id).prop('checked', check);
        });
		
		$(resourceFormId + ' .check').change(function(){
            var id = $(this).data('id_group');
            var check = true;
            
            if ($('.group' + id + ':checked').length !== $('.group' + id).length) {
                check = false;
            }
            
            $(resourceFormId + ' input[data-id='+ id +']').prop('checked', check);
            
        });
		
		$(resourceFormId + ' input[name=checkAll]').change(function(){
            
            var check = true;
            
            if (!this.checked) {
                check = false;
            }
			
            $('input[name=checkOpt\\[\\]], .check', $(resourceFormId)).prop('checked', check);
        });
		
		$('input[name=checkOpt\\[\\]], .check', $(resourceFormId)).change(function(){
            var check = true;
            if ($('input[name=checkOpt\\[\\]]', $(resourceFormId)).length !== $('input[name=checkOpt\\[\\]]:checked', $(resourceFormId)).length) {
                check = false;
            }
            
            $('input[name=checkAll]', $(resourceFormId)).prop('checked', check);
        });
		
		@if("edit" === $mode)
			$('.check', $(resourceFormId)).trigger('change');
		@endif
		
        
    });
	
	
	
    
    
    
</script> 