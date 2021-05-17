<script type="text/javascript">
	
var $c = $("#container_{{ $aViewData['resource'] }}");
var d13 = {};
		
$(document).ready(function() {
	
		
	
		pageSetUp();
	
	/* chart colors default */
		var $chrt_border_color = "#efefef";
		var $chrt_grid_color = "#DDD"
		var $chrt_main = "#7e9d3a";
		var $chrt_second = "#cccccc";
		var $chrt_third = "#398BF7";
		var $chrt_fourth = "#BD362F";
		var $chrt_fifth = "#FFB22B";
		var $chrt_sixth = "#353D4B";
		
		var $chrt_mono = "#000";
		
		if ($("#d11").length) {
					
					var op = [
						@foreach($aData_d11['op'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					var ca = [
						@foreach($aData_d11['ca'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					var co = [
						@foreach($aData_d11['co'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					var can = [
						@foreach($aData_d11['can'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					var aa = [
						@foreach($aData_d11['aa'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					var ag = [
						@foreach($aData_d11['ag'] as $item)
						["{{ $item['mes'] }}",{{ $item['cnt'] }}],
						@endforeach
					];
					
					
					
					//console.log(pageviews)
					var plot = $.plot($("#d11"), 
						[
							{
								data : op,
								label : "Oportunidades"
							}, 
							{
								data : ca,
								label : "Carrito"
							}, 
							{
								data : co,
								label : "Concretados"
							}, 
							{
								data : can,
								label : "Cancelado, Rechazado, No concretado"
							}, 
							{
								data : aa,
								label : "A acordar, En proceso"
							},
							{
								data : ag,
								label : "A Gestionar"
							}
						], 
						{
						series : {
							lines : {
								show : true,
								lineWidth : 1,
								fill : false,
								fillColor : {
									colors : [{
										opacity : 0.1
									}, {
										opacity : 0.15
									}]
								}
							},
							points : {
								show : true
							},
							shadowSize : 0
						},
						xaxis : {
							mode: "categories",
							tickLength: 0
						},

//						yaxes : {
//							
//						},
						grid : {
							hoverable : true,
							clickable : true,
							tickColor : $chrt_border_color,
							borderWidth : 0,
							borderColor : $chrt_border_color,
						},
						tooltip : true,
						tooltipOpts : {
							content : "%s: <b>%y</b>"
						},
						colors : [$chrt_main, $chrt_second,$chrt_third,$chrt_fourth,$chrt_fifth,$chrt_sixth]
						}
					);

				

	}
	
	$('#h2dd1').html('Compras. Cantidades');
	
	/* pie chart */

	if ($('#d13').length) {

		var data_pie = [];
		
		d13 =
			$.plot('#d13', data_pie, {
				series: {
					pie: {
						show: true
					}
				},
				legend: {
					show: false
				},
				colors : [/*$chrt_main, */$chrt_second,$chrt_third,$chrt_fourth,$chrt_fifth,$chrt_sixth]
			});
		
		

	}
	
	$('#h2dd3').html('Compras. Porcentajes. <span id="pieTitle">(seleccionar un mes)</span>');
	
	
	

});

$(function(){
	
	
	
	$('.linkMonth', $c).click(function(e){
		
		var $tds = $(this).parents('tr').children();
		
		var op = $tds.siblings('[name=op]').data('val');
		var ca = $tds.siblings('[name=ca]').data('val');
		var co = $tds.siblings('[name=co]').data('val');
		var can = $tds.siblings('[name=can]').data('val');
		var aa = $tds.siblings('[name=aa]').data('val');
		var ag = $tds.siblings('[name=ag]').data('val');
		
		var data_pie = [];
		
//		data_pie[0] = {
//				label : "Oportunidad",
//				data : op
//			};
		data_pie.push({
				label : "Carrito",
				data : ca
			});
		data_pie.push({
				label : "Concretado",
				data : co
			});
		data_pie.push({
				label : "Cancelado, rechazado, no concretado",
				data : can
			});
		data_pie.push({
				label : "A acordar y en proceso",
				data : aa
			});
		data_pie.push({
				label : "A gestionar",
				data : ag
			});
		
		$('#pieTitle', $c).html($(this).html());
		
		d13.setData(data_pie);
		d13.setupGrid(); //only necessary if your new data will change the axes or grid
		d13.draw();
		
	})
});
    
    
</script>

	
