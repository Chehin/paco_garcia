                    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
                        <div class="tab-pane fade active in" id="l1">
                            
                        <fieldset class="smart-form">
                                                              
                      <section>
                            <div class="row">
                              <label class="label col col-2">Nombre *:</label>
                              <label class="input col col-10"> 
                              <input type="hidden" name="id" id="id" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aData']->id_campania: '' }}">
                              <input type="text" name="nombre" placeholder="Nombre" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" require>
                             
                              </label>
                            </div>
                     </section>

                     <section>
										<div class="row">
											<label class="label col col-2">Destinatarios *:</label>
											<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="lista[]" id="listaIds">
													</select>
												</label>
											</div>
										</div>
                    </section>
                                    
                    <section>
                        <div class="row">
                          <label class="label col col-2">Remitente *:</label>
                          <label class="input col col-10"> 
                          <input type="text" name="remitente" placeholder="Remitente" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aData']->remitente: '' }}" require>                         
                          </label>
                        </div>
                    </section>

  
                     <section>
                            <div class="row">
                              <label class="label col col-2">Asunto *:</label>
							  <div class="col col-10">
								<input type="text" name="asunto" id="faceText" require  value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aData']->asunto: '' }}">
                             </div>
                            </div>
                     </section>

                     <section>
                        <div class="row">
                            <label class="label col col-2">Fecha *:</label>
                                <div class="col col-10">
									<label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" id="datepicker" require name="fecha" value="{{ ('edit' == $mode) ? date('d/m/Y', strtotime($item->fecha)) : '' }}">												
									</label>
                                </div>
                        </div>
                    </section>   
                                
                    <section>
                        <div class="row">
                            <label class="label col col-2">Hora *:</label>
                                <div class="col col-10">
                                    <div class="input-group">
					    				<input class="form-control" require id="clockpicker" name="hora" value="{{ ('edit' == $mode) ? $item->hora : '' }}" type="text" data-autoclose="true">
										<span class="input-group-addon" style="padding:3px 9px 2px 23px"><i class="fa fa-clock-o"></i></span>
									</div>
                                </div>
                    
                        </div>
                    </section> 

                      <section>
                          <div class="row">
                              
                              <label class="label col col-2" >Template *:</label>
                              <label class="select col col-10">
													<?php $toDropDown1 = $aViewData['aCustomViewData']['template']->prepend('Seleccione tipo', ''); ?>
													{{ Form::select(
													'templates_id_templates',
													$toDropDown1,
                                                    ("edit" == $mode) ? $aViewData['aCustomViewData']['aData']->templates_id_templates: '',
                                                    ['id'=>'plantillas']
                                                    )
													}}
													<i></i>
							</label>
                          </div>
                      </section>
  
                  </fieldset>

                 
          
                  <fieldset>
               
                    <label class="label  col-2">Mensaje:</label><br>
                    <div class="col-lg-12">  
                        <textarea id="content">
                            
                            {{ ('edit' == $mode) ? '<div id="template">'.$aViewData['aCustomViewData']['aData']->texto.'</div>' : '<div id="template"></div>' }}
                        </textarea>
                        
                        <input type="hidden" id="fill" name="content" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aData']->texto : ''  }}">                   
                    </div>
                
                  </fieldset>
                        </div>
					
						
                
						<!-- Buttons inside Form!!-->
						<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">											
							<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
                            @if('edit' == $mode) <button type="button" id="send" class="btn btn-success"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar </button> @endif
                            <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
						</div>
                    {{ Form::close() }}