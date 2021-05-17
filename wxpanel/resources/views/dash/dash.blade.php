<?php 
$aData_d11 = $aViewData['aData_d11'];
$aData_d12 = $aViewData['aData_d12'];
?>
@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-info fa-fw "></i> 
                            {{ $aViewData['resourceLabel'] }}
            </h1>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 text-right">
        @if(Sentinel::hasAccess('news.create'))
<!--            <button id="resourceAdd" data-href="" class="btn btn-sm btn-success" data-toggle="modal-custom" data-remote="true"> 
                <i class="fa fa-plus"></i> Agregar {{ $aViewData['resourceLabel'] }}
            </button>-->
        @endif
        
    </div>
</div>
				
                            
<!-- widget grid -->
<section id="widget-grid" class="">
	
	<div id="container_{{ $aViewData['resource'] }}">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="true">
								<!-- widget options:
								usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

								data-widget-colorbutton="false"
								data-widget-editbutton="false"
								data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false"
								data-widget-collapsed="true"
								data-widget-sortable="false"

								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
									<h2 id="h2dd1"></h2>

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

										<div id="d11" class="chart has-legend"></div>

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
		
		<div class="row">
			
<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="true">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

					data-widget-colorbutton="false"
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true"
					data-widget-sortable="false"

					-->
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Compras. Pesos ($)</h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th style="width: 4%;text-align: center;">Mes</th>
										<th style="width: 16%;text-align: center;">Oportunidad</th>
										<th style="width: 16%;text-align: center;">Carrito</th>
										<th style="width: 16%;text-align: center;">Concretado</th>
										<th style="width: 16%;text-align: center;">Cancelado, rechazado, no concretado</th>
										<th style="width: 16%;text-align: center;">A acordar y en proceso</th>
										<th style="width: 16%;text-align: center;">A Gestionar</th>
									</tr>
								</thead>
								<tbody>
									@foreach($aData_d12['data'] as $k => $item)
										<tr>
											<td align="center">
												<a class="linkMonth" href="javascript:;" data-month="{{ $k }}">
													{{ \App\AppCustom\Util::$aMonths[$k] }}
												<a>
											</td>
											<td name="op" align="right" data-val='{{ $item['op'] }}'>$ {{ number_format($item['op'],2,",",".") }}</td>
											<td name="ca" align="right" data-val='{{ $item['ca'] }}'>$ {{ number_format($item['ca'],2,",",".") }}</td>
											<td name="co" align="right" data-val='{{ $item['co'] }}'>$ {{ number_format($item['co'],2,",",".") }}</td>
											<td name="can" align="right" data-val='{{ $item['can'] }}'>$ {{ number_format($item['can'],2,",",".") }}</td>
											<td name="aa" align="right" data-val='{{ $item['aa'] }}'>$ {{ number_format($item['aa'],2,",",".") }}</td>
											<td name="ag" align="right" data-val='{{ $item['ag'] }}'>$ {{ number_format($item['ag'],2,",",".") }}</td>
										</tr>
									@endforeach
								</tbody>
								<thead>
									<tr>
										<th>
											<a class="linkMonth" href="javascript:;">
												Totales
											</a>
										</th>
										<td data-val='{{ $aData_d12['totales']['op'] }}' name="op" align="right">$ {{ number_format($aData_d12['totales']['op'],2,",",".") }}</td>
										<td data-val='{{ $aData_d12['totales']['ca'] }}' name="ca" align="right">$ {{ number_format($aData_d12['totales']['ca'],2,",",".") }}</td>
										<td data-val='{{ $aData_d12['totales']['co'] }}' name="co" align="right">$ {{ number_format($aData_d12['totales']['co'],2,",",".") }}</td>
										<td data-val='{{ $aData_d12['totales']['can'] }}' name="can" align="right">$ {{ number_format($aData_d12['totales']['can'],2,",",".") }}</td>
										<td data-val='{{ $aData_d12['totales']['aa'] }}' name="aa" align="right">$ {{ number_format($aData_d12['totales']['aa'],2,",",".") }}</td>
										<td data-val='{{ $aData_d12['totales']['ag'] }}' name="ag" align="right">$ {{ number_format($aData_d12['totales']['ag'],2,",",".") }}</td>
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
		
		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-6" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="true">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

					data-widget-colorbutton="false"
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true"
					data-widget-sortable="false"

					-->
					<header>
						<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
						<h2 id="h2dd3"></h2>

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

							<div id="d13" class="chart" style="width:400px;height:300px"></div>

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
	</div>

</section>
<!-- end widget grid -->
@stop

@section('custom_scripts_container')

<!-- JARVIS WIDGETS -->
<!--		<script src="js/smartwidgets/jarvis.widget.js"></script>-->
	
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="js/plugin/flot/jquery.flot.js"></script>
<!--		<script src="js/plugin/flot/jquery.flot.cust.js"></script>-->
		<!--<script src="js/plugin/flot/jquery.flot.resize.js"></script>-->
		<script src="js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
		<script src="js/plugin/flot/jquery.flot.orderBar.js"></script>
		<script src="js/plugin/flot/jquery.flot.pie.js"></script>
		<script src="js/plugin/flot/jquery.flot.tooltip.js"></script>
		<script src="js/plugin/flot/jquery.flot.categories.js"></script>
		
		@include($aViewData['resource'].'.'.$aViewData['resource'].'Scripts')


@stop


