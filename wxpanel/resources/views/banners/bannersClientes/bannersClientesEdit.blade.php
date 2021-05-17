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
                                        <label class="label col col-2">Apellido *:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="apellido" required="" value="{{ ('edit' == $mode) ? $item->apellido : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>
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
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Empresa:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="empresa" value="{{ ('edit' == $mode) ? $item->empresa : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Celular:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="celular" value="{{ ('edit' == $mode) ? $item->celular : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>                                
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">E-mail *:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="mail" required="" value="{{ ('edit' == $mode) ? $item->mail : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>                                
                            </fieldset>
                        </div>
						<!--	Google map-->
						<div class="row">
							<div class="col-md-12">
								<div id='map_canvas'></div>
								
							</div>
						</div>
						<!--	Google map End-->
                
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
