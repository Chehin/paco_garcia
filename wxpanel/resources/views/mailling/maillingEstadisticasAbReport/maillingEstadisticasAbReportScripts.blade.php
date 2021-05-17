<script>
  
            window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                zoomEnabled : true,
                title:{
                    text: ""
                },
                axisX: {
                    crosshair:{
                    enabled: true,
                    snapToDataPoint: true,
                    labelFormatter: function(e) {              
				        return CanvasJS.formatDate(e.value, "DD MM YYYY");
			        }
                    },
                    valueFormatString: "DD-MM-YY",
                    intervalType: "day",
                    interval: 4
                },
                axisY: {
                    title: "",
                    includeZero: true,
                    interval: 10,
                    suffix: " %"
                },
                legend:{
                    cursor: "pointer",
                    fontSize: 16,
                    itemclick: toggleDataSeries
                },
                toolTip:{
                    shared: true
                },
                data: [{
                    name: "Tasa de Clicks",
                    type: "spline",
                    yValueFormatString: "###,##%",
                    showInLegend: true,
                    toolTipContent: "{label}<hr/> <span style='color:#2196CC'>{name}</span>: {y}",               
                    dataPoints: [
                        @foreach($aViewData['reportAB'] as $r)
                        @php $fecha= explode("-", $r->fechaenvio);  @endphp
                         { label: "{!! $r->nombre !!}" , x: new Date({!!$fecha[0]!!},{!!$fecha[1] - 1!!},{!!$fecha[2]!!}), y: {{$r->clicks}} },
                        @endforeach
                    ]
                },
                {
                    name: "Promedio de aperturas de la industria",
                    type: "spline",
                    yValueFormatString: "##,##%",
                    showInLegend: true,
                    dataPoints: [
                        @foreach($aViewData['reportAB'] as $r)
                        @php $fecha = explode("-",  $r->fechaenvio);  @endphp
                         { x: new Date({{$fecha[0]}},{{$fecha[1] - 1}},{{$fecha[2]}}), y: 10 },
                        @endforeach
                    ]
                },
                {
                    name: "% de Aperturas",
                    type: "spline",
                    yValueFormatString: "###,##%",
                    showInLegend: true,
                    dataPoints: [
                        @foreach($aViewData['reportAB'] as $r)
                        @php $fecha = explode("-",  $r->fechaenvio);  @endphp
                         { x: new Date({{$fecha[0]}},{{$fecha[1] - 1}},{{$fecha[2]}}), y: {{$r->ratio}} },
                        @endforeach
                    ]
                }]
            });
            chart.render();
            
            function toggleDataSeries(e){
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else{
                    e.dataSeries.visible = true;
                }
                chart.render();
            }
            
            }
            </script>