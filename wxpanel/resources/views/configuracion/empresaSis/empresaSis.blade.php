@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-cog"></i> 
                            {{ $aViewData['resourceLabel'] }}
            </h1>
    </div>
</div>
				
                            
<!-- widget grid -->
<section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="true">
                                <header>
                                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                        <h2></h2>

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
                                                <div class="widget-body-toolbar">

                                                </div>
                                                <table id="{{$aViewData['resource']}}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
													<thead>
														<tr>
															<th>Nombre</th>
															<th>Razón Social</th>
                                                            <th>Imágenes</th>
                                                            <th></th>
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

    <script src="js/plugin/cropit/jquery.cropit.js"></script>
@stop
