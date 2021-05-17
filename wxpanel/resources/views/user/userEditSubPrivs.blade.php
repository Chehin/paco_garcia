<?php 
$aPremissionsAssigned = ($aItem['permissions'] ) ?  $aItem['permissions'] : [];
?>
<div class="modal-body">
	<div>
		<div class="widget-body">
					{{ Form::open(array('id'=>'userPrivs', 'name' => 'userPrivs')) }}
                    <div class="row  pull-right">
                        <label class="col col-md-12 checkbox-inline">
                            <input type="checkbox" class="checkbox style-0" name="checkAll">
                            <span><label style="font-style: italic;"><b>Todos los permisos</b></label></span>
                        </label>
                    </div>
					<?php 
					$color = false; 
					?>
					<fieldset>
					@foreach($aViewData['aPermissions'] as $kMod => $aMod)
					<?php
						$style = '';
						if ($color) {
							$style= 'style="background: #ECF3F8"';
						}
					?>
						<div class="row">		
							<section {!! $style !!}  class="row">
								<label class="col col-md-10">
									<label class="checkbox-inline">
										<input type="checkbox" class="checkbox style-0" name="checkGroup" data-id="{{$kMod}}">
										<span><b>{{$aMod['label']}}</b></span>
									</label>
								</label>

								@if(!empty($aMod['aPermissions']))
								<label class="input col col-md-12">
									<label class="col col-md-2"></label>
									<label class="col col-md-10">
										<label class="checkbox-inline">

											@foreach($aMod['aPermissions'] as $pKey => $aPermission)
												<label class="checkbox-inline">
													<?php
													$checked = (isset($aPremissionsAssigned[$pKey]) &&  1 == $aPremissionsAssigned[$pKey]) ? 'checked' : '';
													?>
													<input {{ $checked }} data-id_group="{{ $kMod }}"  type="checkbox" name="aPerms[]"  value="{{ $pKey }}" class="checkbox style-0 group{{ $kMod }} check">

													<span> {{ App\Http\Controllers\User\UserUtilController::permissionLabel($pKey) }}</span>
												</label>
											@endforeach



									</label>

								</label>
								@endif

							</section>
						</div>
						<?php 
							$color = !$color; 
						?>
					@endforeach
					</fieldset>
					{{ Form::close() }}
		</div>	
    </div>
</div>
<script>
    $(function(){
		var resourceFormId = '#userPrivs';
		
		//checkboxes handling
		$(resourceFormId + ' input[name=checkGroup]').change(function(e){
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
			
            $('input[name=checkGroup], .check', $(resourceFormId)).prop('checked', check);
        });
		
		$('input[name=checkGroup], .check', $(resourceFormId)).change(function(){
            var check = true;
            if ($('input[name=checkGroup]', $(resourceFormId)).length !== $('input[name=checkGroup]:checked', $(resourceFormId)).length) {
                check = false;
            }
            
            $('input[name=checkAll]', $(resourceFormId)).prop('checked', check);
        });
		
		@if("edit" === $mode)
			$('.check', $(resourceFormId)).trigger('change');
		@endif
        
        
        
    });
</script>

