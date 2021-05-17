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

        <div class="row">		
                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-4" data-widget-editbutton="false" role="widget">
                       
                        <header role="heading" class="ui-sortable-handle">
                            <div class="jarviswidget-ctrls" role="menu"> 
                              <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> 
                              <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> 
                            </div>
                            <div class="widget-toolbar" role="menu">
                               
                            </div>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Campañas Reportes</h2>				
                            
                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
        
                        <!-- widget div-->
                        <div role="content">
                            
                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">
                                <!-- This area used as dropdown edit box -->
                                
                            </div>
                            <!-- end widget edit box -->
                            
                            <!-- widget content -->
                            <div class="widget-body no-padding">
                                
                                    <div id="chartContainer" style="height: 370px; width: 80%; margin: 43px 0px 0px 72px"></div>
                                
                            </div>
                            <!-- end widget content -->
                            
                        </div>
                        <!-- end widget div -->
                        
                    </div>
                    <!-- end widget -->
        
                </article>
                <!-- WIDGET END -->
                
            </div>
    

            <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable" id="wid-id-0" data-widget-editbutton="false" role="widget">
                    <header role="heading" class="ui-sortable-handle">
                        <div class="jarviswidget-ctrls" role="menu">   
                            <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> 
                            <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> 
                        </div>
                       
                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                        <h2>Detalle de Campañas</h2>
    
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
    
                    <!-- widget div-->
                    <div role="content">
    
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
    
                        </div>
                        <!-- end widget edit box -->
    
                        <!-- widget content -->
                        <div class="widget-body">                        
                            <div class="table-responsive">
                            
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre de Campaña</th>
                                            <th>Fecha de envio</th>
                                            <th>Cantidad de envios</th>
                                            <th>% de apertura</th>
                                            <th>% de clicks</th>
                                            <th>Más info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($aViewData['reportAB'] as $r)
                                                    <tr>    
                                                            <td>{!!$r->nombre !!}</td>
                                                            <td>{!!$r->fecha !!} {!!$r->hora !!}</td>
                                                            <td>{!!$r->enviados !!}</td>
                                                            <td>{!!$r->ratio !!}</td>
                                                            <td>{!!$r->clicks !!}</td>
                                                            <td><a href="{{route('mailling/maillingEstadisticas',['id'=>$r->id_campania])}}" class="btn btn-default"><i class="fa fa-file"></i> Ver Más</a></td>
                                                    </tr>
                                            @endforeach   
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <!-- end widget content -->
    
                    </div>
                    <!-- end widget div -->
    
                </div>


        </section>
<!-- end widget grid -->

@stop

@section('custom_scripts_container')
	<script src="js/appCustom_{{ $aViewData['resource'] }}.js" ></script>
    <script src="js/plugin/cropit/jquery.cropit.js"></script>
    @include('mailling'.'.'.$aViewData['resource'].'.'.$aViewData['resource'].'Scripts')
    <script type="text/javascript" src="js/plugin/canvasjs-2.2/canvasjs.min.js"></script>

@stop
