<?php
	$item = isset($aViewData['item']) ? $aViewData['item'] : null;
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-list fa-fw "></i> 
                            {{ $aViewData['resourceLabel'] }} 
                    <span>> 
                            Productos Relacionados
                    </span>
					<span>> 
                            "{{ App\AppCustom\Util::truncateString($item->titulo,50) }}"
                    </span>
            </h6>
        </div>
<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
			
                <!-- widget div-->
                <div>
                        
						<div class="alert alert-info fade in row" style="margin-bottom: 15px;">
							<i class="fa-fw fa fa-list"></i>
							<strong>Productos relacionados</strong>
						</div>
                         <div class="widget-body no-padding">
                            <div class="widget-body-toolbar">

                            </div>
							 
                            <!-- end widget div -->
                            <table id="sub2_{{ $aViewData['resource'] }}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Rubro</th>

										<th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
					
						<div class="alert alert-info fade in row" style="margin-top:4%; margin-bottom: 15px;">
							<i class="fa-fw fa fa-list"></i>
							<strong>Productos para relacionar</strong>
						</div>
						<div class="widget-body no-padding">
                            <div class="widget-body-toolbar">

                            </div>
                            <!-- end widget div -->
                            <table id="sub3_{{ $aViewData['resource'] }}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Rubro</th>
										<th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                </div>
               
                <!-- end widget content -->
        </div>
        <!-- end widget -->
    </div>
</div>
@include('productosRelatedMainScripts')