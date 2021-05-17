<div class="widget-body">
{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
            
                            <fieldset>
								<section>
                                    <div class="row">
										<label class="label col col-2">Nombre *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>

                                @if('edit'==$mode)
                                 @foreach($aViewData['aCustomViewData']['aTemplates'] as $val) 
                                    @foreach($aViewData['aCustomViewData']['aTemplate'] as $ival)
                                    @if($val->tipo=='Editable')  
                                        @if($val->id==$ival->templates_id_templates && $val->tipo=='Editable') 
                                        <div class="col col-2">        
                                        <br><div class="inline-group">
                                            <label class="radio">
                                                <input type="radio" name="templates_id_templates[]" value="{!! $val->id !!}" checked>
                                                <i></i>{!! $val->nombre !!}
                                            </label>
                                        </div>
                                        </div>
                               
                                        <div class="col col-9 table-bordered text-center"  style="margin-left: 115px;">{!! $val->template !!}</div>
                                        <br><br>
                                        @else 
                                        <div class="col col-2">        
                                        <br><div class="inline-group">
                                            <label class="radio">
                                                <input type="radio" name="templates_id_templates[]" value="{!! $val->id !!}">
                                                <i></i>{!! $val->nombre !!}
                                            </label>
                                        </div>
                                        </div>
                               
                                        <div class="col col-9 table-bordered" style="margin-left: 115px;">{!! $val->template !!}</div>
                                        <br><br>
                                        @endif
                                    @endif
                                    @endforeach
                                 @endforeach
                                @else
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Elegir Template:*</label>
                                        <div class="col-md-12" >
                                        @foreach($aViewData['aCustomViewData']['aTemplate'] as $val)                                  
                                        
                                            @if($val->tipo=='Editable')
                                                <div class="col col-6" style="margin-left: 115px;">        
                                                <br><div class="inline-group">
                                                    <label class="radio">
                                                        <input type="radio" name="templates_id_templates[]" value="{!! $val->id !!}">
                                                        <i></i>{!! $val->nombre !!}
                                                    </label>
                                                </div>
                                                </div>
                                    
                                                <div class="col col-9 table-bordered" style="margin-left: 115px;">{!! $val->template !!}</div>
                                                    
                                                <br><br>
                                            @endif
                                            
                                        @endforeach;
                                        </div>                                        
                                    </div>
                                </section>
                                 @endif

                                  <!-- Buttons inside Form!!-->
									<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">
										<div style="padding:0;" class="col-md-12">
											<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
											<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
										</div>
                                    </div>

                            </fieldset>
        {{ Form::close() }}
</div>