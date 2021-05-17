<?php
$msg = isset($aViewData['msg']) ? $aViewData['msg'] : null;
$data = isset($aViewData['data']) ? $aViewData['data'] : null;
$status = isset($aViewData['status']) ? $aViewData['status'] : null;
?>


<style>
.toggDiv{
        background: #fff;
        padding: 10px 20px;
        float: left;
        width: 100%;
        border: 2px solid #e6e6e6;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-exclamation-triangle fa-fw "></i> 
                            Productos 
                    <span>> 
                            Advertencias
                    </span>
            </h6>
        </div>
        <!-- NEW WIDGET START -->
        <article>

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget well" id="myModalSync" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
    			
                <!-- widget div-->
                <div id="_formContainer">                    
					
                    
                    <div class="tab-pane fade active in">
                        
                        <div>
                            <div class="toggDiv">
                                
                                <fieldset class="scheduler-border" id="myTabContent3">
                                    <section>
                                        <div class="row">
                                            @if($status == 2 )
											<div class="alert alert-warning">
												<strong>Atención!</strong> Se han encontrado algunas advertencias.
												<br>
												<ul>
													@foreach($data as $warn)
													<li>
														{{ $warn }}
													</li>
													@endforeach
												</ul>
                                            </div>
                                            @else
                                            <div class="alert alert-success">
												<br>
												Los productos se cargaron correctamente
                                            </div>
											@endif
                                        </div>
                                    </section>
                                    
                                </fieldset>
                                    
                                
                                
                            </div>                            
                        </div>
                                                
                    </div>           
                        
                </div>
               
                <!-- end widget content -->
            </div>
            <!-- end widget -->
        </article>
    </div>
</div>
