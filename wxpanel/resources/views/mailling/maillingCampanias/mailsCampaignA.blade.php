
<div class="widget-body">
    
    {{ Form::open(array('id' =>  $aViewData['resource']. 'Sub1')) }}
                            
                <fieldset class="smart-form">
                    <input type="hidden" name="idA" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->id: '' }}">                                  
                      <section>
                            <div class="row">
                              <label class="label col col-2">Nombre Campaña *:</label>
                              <label class="input col col-10"> 
                              <input type="hidden" name="id" id="id" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->id_campania: '' }}">
                              <input type="text" name="nombre" placeholder="Nombre" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" require>
                             
                              </label>
                            </div>
                     </section>
                     <hr><br>
                     <section>
					    <div class="row">
							<label class="label col col-2">Destinatarios *:</label>
								<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="listaA[]" id="listaIdsA">
													</select>
												</label>
								</div>
                        </div>
                    </section>

                    <section>
                        <div class="row">
                          <label class="label col col-2">Remitente *:</label>
                          <label class="input col col-10"> 
                          <input type="text" name="remitentea" placeholder="Remitente" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->remitente: '' }}" require>                         
                          </label>
                        </div>
                    </section>
                                    
                     <section>
                            <div class="row">
                              <label class="label col col-2">Asunto *:</label>
                              <div class="col col-10"> 
								<input type="text" name="asuntoA" id="faceText3" require  value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->asunto: '' }}">
                             
                              </div>
                            </div>
                     </section>
					 
					 

                     <section>
                        <div class="row">
                            <label class="label col col-2">Fecha *:</label>
                                <div class="col col-10">
									<label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" id="datepickerA" require name="fechaA" value="{{ ('edit' == $mode) ? date('d/m/Y', strtotime($aViewData['aCustomViewData']['aDataA']->fecha)) : '' }}">												
									</label>
                                </div>
                        </div>
                    </section>   
                                
                    <section>
                        <div class="row">
                            <label class="label col col-2">Hora *:</label>
                                <div class="col col-10">
                                    <div class="input-group">
					    				<input class="form-control" require id="clockpickerA" name="horaA" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->hora : '' }}" type="text" data-autoclose="true">
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
													'templates_id_templatesA',
													$toDropDown1,
                                                    ("edit" == $mode) ? $aViewData['aCustomViewData']['aDataA']->templates_id_templates: '',
                                                    ['id'=>'plantillasA']
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
                        <textarea id="contentA">
                            
                            {{ ('edit' == $mode) ? '<div id="templateA">'.$aViewData['aCustomViewData']['aDataA']->texto.'</div>' : '<div id="templateA"></div>' }}
                        </textarea>
                        
                        <input type="hidden" id="fillA" name="contentA" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['aDataA']->texto : '' }}">                   
                    </div>
                
                  </fieldset>
          
    {{ Form::close() }}
</div>