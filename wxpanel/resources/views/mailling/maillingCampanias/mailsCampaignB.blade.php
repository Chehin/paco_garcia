
<div class="widget-body">
    
    {{ Form::open(array('id' =>  $aViewData['resource']. 'Sub2')) }}
                            
                <fieldset class="smart-form">
                    <input type="hidden" name="idB" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataB']->id: '' }}">    
                     <section>
					    <div class="row">
							<label class="label col col-2">Destinatarios *:</label>
								<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="listaB[]" id="listaIdsB">
													</select>
												</label>
								</div>
                        </div>
                    </section>
                    
                    <section>
                        <div class="row">
                          <label class="label col col-2">Remitente *:</label>
                          <label class="input col col-10"> 
                          <input type="text" name="remitenteb" placeholder="Remitente" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataB']->remitente: '' }}" require>                         
                          </label>
                        </div>
                    </section>

                     <section>
                            <div class="row">
                              <label class="label col col-2">Asunto *:</label>
                              <div class="col col-10"> 
								<input type="text" name="asuntoB"  id="faceText2" require value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataB']->asunto: '' }}">
                             
                              </div>
                            </div>
                     </section>

                     <section>
                        <div class="row">
                            <label class="label col col-2">Fecha *:</label>
                                <div class="col col-10">
									<label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" id="datepickerB" require name="fechaB" value="{{ ('edit' == $mode) ? date('d/m/Y', strtotime($aViewData['aCustomViewData']['aDataB']->fecha)) : '' }}">												
									</label>
                                </div>
                        </div>
                    </section>   
                                
                    <section>
                        <div class="row">
                            <label class="label col col-2">Hora *:</label>
                                <div class="col col-10">
                                    <div class="input-group">
					    				<input class="form-control" require id="clockpickerB" name="horaB" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataB']->hora : '' }}" type="text" data-autoclose="true">
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
													'templates_id_templatesB',
													$toDropDown1,
                                                    ("edit" == $mode) ? $aViewData['aCustomViewData']['aDataB']->templates_id_templates: '',
                                                    ['id'=>'plantillasB']
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
                        <textarea id="contentB">
                            
                            {{ ('edit' == $mode) ? '<div id="templateB">'.$aViewData['aCustomViewData']['aDataB']->texto.'</div>' : '<div id="templateB"></div>' }}
                        </textarea>
                        
                        <input type="hidden" id="fillB" name="contentB" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataB']->texto : '' }}">                   
                    </div>
                
                  </fieldset>
          
    {{ Form::close() }}
</div>