@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-shopping-cart"></i> 
                            {{ $aViewData['resourceLabel'] }}
            </h1>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 text-right">
    
    </div>
</div>
				
                            
<!-- widget grid -->
<section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="true" data-widget-colorbutton="false">
                                <header>
                                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
									
                                </header>

                                <!-- widget div-->
                                <div>

                                        <!-- widget edit box -->
                                        <div class="jarviswidget-editbox">
                                                <!-- This area used as dropdown edit box -->

                                        </div>
                                        <!-- end widget edit box -->

                                        <!-- widget content -->
                                        <div class="widget-body no-padding">
                                                <div class="widget-body-toolbar"></div>
                                                <table id="{{$aViewData['resource']}}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
                                                        <thead>
                                                                <tr>
                                                                        <th></th>
                                                                        <th>Venta</th>
                                                                        <th>Actualizacion</th>
                                                                        <th>Cliente</th>
                                                                        <th>Método de pago</th>
                                                                        <th>Estado de pago</th>
                                                                        <th>Estado de envío</th>
                                                                        <th>Productos</th>
                                                                        <th>N° Transacción</th>
                                                                        <th>N° Pago</th>
                                                                        <th>N° de Guía</th>
                                                                        <th>Factura</th>
                                                                        <th>Precio</th>
                                                                        <th>Stock Reservado</th>
                                                                </tr>
                                                        </thead>
                                                </table>

                                        </div>
                                        <!-- end widget content -->

                                </div>
                                <!-- end widget div -->

                        </div>
                        <!-- end widget -->


                </article>
                <!-- WIDGET END -->

        </div>

        <!-- end row -->

</section>
<!-- end widget grid -->
@stop

@section('custom_scripts_container')
	@include($aViewData['prefix'] . $aViewData['resource'] . '.' . $aViewData['resource'] . 'ScriptsConfig')
    @include($aViewData['prefix'] . $aViewData['resource'] . '.' . $aViewData['resource'] . 'Scripts')
@stop
