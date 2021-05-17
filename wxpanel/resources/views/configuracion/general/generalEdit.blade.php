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
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
            </h6>
        </div>
    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
                    <hr class="simple">

                    <ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
                                    <a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
                            </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
                        <div class="tab-pane fade active in" id="l1">
                            
                            <fieldset>
								<section>
                                    <div class="row">
										<label class="label col col-md-2">Tipo *:</label>
                                        <div class="col col-md-10">
											<label class="input">
												<input type="text" name="tipo" required="" value="{{ ('edit' == $mode) ? $item->tipo : '' }}" disabled  />
											</label>
                                        </div>
                                    </div>
								</section>
								<!-- <section>
                                    <div class="row">
										<label class="label col col-2">Valor *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="valor" required="" value="{{ ('edit' == $mode) ? $item->valor : '' }}" />
											</label>
                                        </div>
                                    </div>
                                </section> -->
                                <section>
                                    <div class="row">
                                        <label class="label col col-md-2">Texto:</label>
                                        <label class="textarea  col col-md-10">
                                            <input type="hidden" name="valor" id="texto" value="" />
                                            <div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
                                                <textarea name="valor" rows="30" value="">{!! ('edit' == $mode) ? $item->valor : '' !!}
                                                </textarea>	
                                            </div>
                                        </label>
                                    </div>    
                                </section>
                            </fieldset>
                            
                        </div>
                        <!--
                        <section>
                            <div class="row">
                                <label class="label col col-md-2">Texto:</label>
                                <label class="textarea  col col-md-10 row">
                                    <input type="hidden" name="valor" id="texto" value="" />
                                    <div style="border:1px solid #929292" class="no-padding">
                                        <div id="textoBox">{!! ('edit' == $mode) ? $item->valor : '' !!}</div>	
                                    </div>
                                </label>
                            </div>    
                        </section>-->
						<!-- Buttons inside Form!!-->
						<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
							<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
							<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
						</div>
                    {{ Form::close() }}
					</div>
                </div>
                
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </article>
        
    </div>
</div>
<script>
</script>
@include('genericEditScripts')
