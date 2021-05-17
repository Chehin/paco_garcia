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
							@if("edit" == $mode)
                            <li class="pull-left"><strong>Link:</strong> {{ env('FE_URL') }}producto/{{$item->id}}/{{\str_slug( $item->nombre)}}</li>
							@endif
                            <li class="pull-right active">
								<a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
							</li>
						</ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
							{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
							<div class="tab-pane fade active in" id="l1">
								
								<fieldset class="smart-form">
									<section>
										<div class="row">
											<label class="label col col-2">Rubro *:</label>
											<div class="col col-10">
												<label class="select">
													<?php $toDropDown1 = $aViewData['aCustomViewData']['aRubros']->prepend('Seleccione Rubro', ''); ?>
													{{ Form::select(
													'id_rubro',
													$toDropDown1,
													("edit" == $mode) ? $item->id_rubro : '',
													['class' => 'col col-md-12', 'required' => '', 'id' => 'id_rubro']
                                                    )
													}}
													<i></i>
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">SubRubro:</label>
											<div class="col col-10">
												<label class="select">
													{{ Form::select(
													'id_subrubro', 
													$aViewData['aSubRubros'], 
													("edit" == $mode) ? $item->id_subrubro : 0, 
													['class' => 'col col-md-12','id'=> 'subrubros']
													) 
													}}
													<i></i>
												</label>
											</div>
										</div>
									</section>									
									<section>
										<div class="row">
											<label class="label col col-2">Nombre *:</label>
											<div class="col col-10">
												<label class="input">
													<input type="text" id="nombre" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Nombre Merc. Libre*:</label>
											<div class="col col-10">
												<label class="input">
													<input type="text" id="nombremeli" name="nombremeli" required="" value="{{ ('edit' == $mode) ? $item->nombremeli : '' }}" />
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Categoría <br/>Mercadolibre:</label>
											<div class="col col-10">
												<div id="cat_meli" style="font-weight:bold;"></div>

												<a class="btn btn-primary btn-sm" href="javascript:void(0);" role="button" id="edit_cat_meli" onclick="edit_cat_meli();">Editar</a>

												<a class="btn btn-primary btn-sm" id="buscar_cat_meli" href="javascript:void(0);" role="button" onclick="buscar_cat_meli()">Buscar Categoría</a>
												
												<input type="hidden" name="categoria_meli" id="categoria_meli" value="{{ ('edit' == $mode) ? $item->categoria_meli : '' }}" />
												<input type="hidden" name="categoria_variations" id="categoria_variations" value="{{ ('edit' == $mode) ? $item->categoria_variations : '' }}" />
											</div>
										</div>
									</section>									
									<section>
										<div class="row">
											<label class="label col col-2">Genero *:</label>
											<div class="col col-10">
												<label class="select">
													<?php $toDropDown4 = $aViewData['aCustomViewData']['aGeneros']->prepend('Seleccione Género', ''); ?>
													{{ Form::select(
													'id_genero', 
													$toDropDown4,
													("edit" == $mode) ? $item->id_genero : 0, 
													['class' => 'col col-md-12','required' => 'true']
													) 
													}}
													<i></i>
												</label>
											</div>
										</div>
									</section>									
									
									<section>
										<div class="row">
											<label class="label col col-2">Marca:</label>
											<div class="col col-4">
												<label class="select">
													<?php $toDropDown2 = $aViewData['aCustomViewData']['aMarcas']->prepend('Seleccione Marca', ''); ?>
													{{ Form::select(
													'id_marca',
													$toDropDown2,
													("edit" == $mode) ? $item->id_marca : '',
													['class' => 'col col-md-12', 'id' => 'id_marca']
                                                    )
													}}
													<i></i>
												</label>
											</div>
											<label class="label col col-2">Origen:</label>
											<div class="col col-4">
												<label class="select">
													<?php $toDropDown3 = $aViewData['aCustomViewData']['aPaises'] ?>
													{{ Form::select(
													'id_origen',
													$toDropDown3,
													("edit" == $mode) ? $item->id_origen : '1',
													['class' => 'col col-md-12', 'id' => 'id_origen']
                                                    )
													}}
													<i></i>
												</label>
											</div>
										</div>
									</section>
																	
									<section>
										<div class="row">
											<label class="label col col-2">Modelo:</label>
											<div class="col col-4">
												<label class="input">
													<input type="text" name="modelo" value="{{ ('edit' == $mode) ? $item->modelo : '' }}" />
												</label>
											</div>
											<label class="label col col-2">EAN:</label>
											<div class="col col-4">
												<label class="input">
													<input type="text" name="ean" value="{{ ('edit' == $mode) ? $item->ean : '' }}" />
												</label>
											</div>
										</div>
									</section>

									<section>
										<div class="row">
											<label class="label col col-2">Estado:</label>
											<div class="col col-10">
												<div class="inline-group">
													<label class="radio">
														<input type="radio" name="estado" value="Nuevo" {{("edit" == $mode) ? $item->estado=='Nuevo'?'checked=""':'' : 'checked=""'}}>
														<i></i>Nuevo
													</label>
													<label class="radio">
														<input type="radio" name="estado" value="Usado" {{("edit" == $mode) ? $item->estado=='Usado'?'checked=""':'' : ''}}>
														<i></i>Usado
													</label>
												</div>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Video Youtube:</label>
											<div class="col col-10">
												https://www.youtube.com/watch?v=
												<label>
													<input type="text" name="id_video" value="{{ ('edit' == $mode) ? $item->id_video : '' }}" />
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Etiquetas:</label>
											<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="etiquetasIds[]" id="etiquetasIds">
													</select>
												</label>
											</div>
										</div>
									</section>                              
									<section>
										<div class="row">
											<label class="label col col-2">Deportes:</label>
											<div class="col col-10">
												<label class="select"> 
													<select multiple style="width: 100%" class="select2" name="deportesIds[]" id="deportesIds">
													</select>
												</label>
											</div>
										</div>
									</section>                              
									<section>
										<div class="row">
											<label class="label col col-2">Alto (cm) *:</label>
											<div class="col col-4">
												<label class="input">
													<input required="required" type="text" name="alto" value="{{ ('edit' == $mode) ? $item->alto : '' }}" />
												</label>
											</div>
											<label class="label col col-2">Ancho (cm) *:</label>
											<div class="col col-4">
												<label class="input">
													<input required="required" type="text" name="ancho" value="{{ ('edit' == $mode) ? $item->ancho : '' }}" />
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Largo (cm) *:</label>
											<div class="col col-4">
												<label class="input">
													<input required="required" type="text" name="largo" value="{{ ('edit' == $mode) ? $item->largo : '' }}" />
												</label>
											</div>
											<label class="label col col-2">Peso (gr) *:</label>
											<div class="col col-4">
												<label class="input">
													<input required="required" type="text" name="peso" value="{{ ('edit' == $mode) ? $item->peso : '' }}" />
												</label>
											</div>
										</div>
									</section>
									<section>
										<div class="row">
											<label class="label col col-2">Orden:</label>
											<div class="col col-10">
												<label class="input">
													<input type="text" name="orden" value="{{ ('edit' == $mode) ? $item->orden : '' }}" />
												</label>
											</div>
										</div>
									</section>
								
									<section class="section-textarea">
										<div class="row">
											<label class="label col col-2">Sumario:</label>
											<div class="col col-10">
												<label class="textarea">
													<textarea row="3" name="sumario">{{ ('edit' == $mode) ? $item->sumario : '' }}</textarea>
												</label>
											</div>
										</div>
									</section>
								</fieldset>	
									<section class="row">
										<label class="col col-md-2">Texto:</label>
										<label class="textarea  col col-md-10 row">
											<input type="hidden" name="texto" id="texto" value="" />
											<div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
												<div id="textoBox">{!! ('edit' == $mode) ? $item->texto : '' !!}</div>	
											</div>
										</label>
									</section>
									<section>
										<label class="label"><b>ASIGNAR Color, Stock y Código</b></label>
										<table class="table table-bordered">
											<thead>
												<tr  style="color: #222;">
													<th>Color</th>
													<th>Talle</th>
													<th>Stock</th>
													<th>Código</th>
													<th></th>
												</tr>
											</thead>
											<tbody id="lista-cod-stock">
												<tr>
													<td>
														<div class="col-12">
															<label class="select">
																<?php $toDropDownColor = $aViewData['aCustomViewData']['aColores']->prepend('Seleccione Color', ''); ?>
																{{ Form::select(
																'id_color',
																$toDropDownColor,
																("edit" == $mode) ? $item->id_color : '',
																['class' => 'col col-md-12', 'id' => 'id_color']
																)
																}}
																<i></i>
															</label>
														</div>
													</td>
													<td>
														<div class="col-12">
															<label class="select">
																<?php $toDropDownTalle = $aViewData['aCustomViewData']['aTalles']->prepend('Seleccione Talle', ''); ?>
																{{ Form::select(
																'id_talle',
																$toDropDownTalle,
																("edit" == $mode) ? $item->id_talle : '',
																['class' => 'col col-md-12', 'id' => 'id_talle']
																)
																}}
																<i></i>
															</label>
														</div>
													</td>
													<td>
														<div id="stock">
															@foreach($aViewData['aCustomViewData']['aScursales'] as $sucursal)
															<div class="row">
																<div class="col-sm-8">
																	{{ $sucursal['titulo'] }}
																</div>
																<div class="col-sm-4">
																	<input name="stock[]" data-sucursal="{{$sucursal['id']}}" data-sucursaln="{{$sucursal['titulo']}}" type="number" placeholder="0" style="max-width:100%;" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
																</div>
															</div>
															@endforeach
														</div>
														<!--<div class="col-12">
															<label class="input">
																<input id="stock" name="stock" type="number" placeholder="0" style="max-width:78px;" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
															</label>
														</div>-->
													</td>
													<td>
														<div class="col-12">
															<label class="input">
																<input id="codigo" name="codigo" type="text" style="max-width:78px;">
															</label>
														</div>
													</td>
													<td>
														<input type="button" class="btn btn-primary" id="agregar-cod-stock" value="Agregar">
														<input type="button" class="btn btn-default" id="editarFila" onclick="guardarFila(this)" value="Editar" style="display: none;" data-index="">
														&nbsp;&nbsp;
														<a href="javascript:;" id="editarFilaCancel" onclick="editarFilaCancel()" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i></a>
													</td>
												</tr>
											</tbody>
										</table>
										<input type="hidden" id="stockColor" name="stockColor" value="" />
                                        
									</section>
								</fieldset>
							</div>
							
							<!-- Buttons inside Form!!-->
							<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
								<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
								<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
							</div>
							{{ Form::close() }}

							<div id="selectCatMeli" class="hide">
								<div class="close_edit">&times;</div>
								<h3>Seleccionar categoría</h3>
								<div class="cat_meli"></div>
								<table class="table table-bordered">
									<thead>
										<tr style="color: #222;">
											<th>Nombre</th>
											<th>Acción</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
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
<script src="js/appCustom_subRubros.js"></script>
<script src="js/appCustom_subsubRubros.js"></script>
<script>
    $(function(){
		
        appCustom.ajaxRest(
		'rest/v1/etiquetasIds',
		'GET',
		null,
		function(result){
			
			var $element = $('form#productosForm select#etiquetasIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aEtiquetasAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );        
        appCustom.ajaxRest(
		'rest/v1/deportesIds',
		'GET',
		null,
		function(result){
			
			var $element = $('form#productosForm select#deportesIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aDeportesAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );      
		@if ('edit' == $mode)   
		@if ($item->categoria_meli)   
        appCustom.ajaxRest(
		'rest/v1/categoriaMeli/{{ $item->categoria_meli }}/1',
		'GET',
		null,
		function(result){
			if (0 == result.status) {
				var data_cat = '';
				$.each(result.data.path_from_root, function(i, v) {
					if(i>0){
						data_cat = data_cat+' > ';
					}
					data_cat = data_cat+v['name'];
				});
				$('#categoria_meli').val(result.data.id);
				$('#cat_meli').html(data_cat);
				$('#edit_cat_meli').removeClass('disabled');
			} 
		}, 
		'sync'
        );    
		@endif    
		@endif    
		
		        
	});
	
    $(document).ready(function() { 
        var stockColor = {}; // El objeto que almacenará el color, código y stock
        contadorDataJSON = 0;
		@if ('edit' == $mode) 
		var dataColor = JSON.parse('<?php echo $aViewData['aCustomViewData']['aColoresAssigned']?>');
		for (var i = 0; i < dataColor.length; i++) {
			var stockHTML = '';
			var stock_json = [];
			var data_stock = dataColor[i]['stock'];
			for (var e = 0; e < data_stock.length; e++) {
				stockHTML = stockHTML+'<p style="margin-bottom:0;"><strong>'+data_stock[e].sucursaln+':</strong> '+data_stock[e].stock+'</p>';
				stock_json.push({'id': data_stock[e].sucursal, 'stock': data_stock[e].stock});
			};

			$("#lista-cod-stock").append(
				"<tr class='fila-" + contadorDataJSON + "'>"+
					"<td>" + dataColor[i]['nombreColor'] + "</td>"+
					"<td>" + (dataColor[i]['nombreTalle'] ? dataColor[i]['nombreTalle'] : '')  + "</td>"+
					"<td>" + stockHTML + "</td>"+
					"<td>" + dataColor[i]['codigo'] + "</td>"+
					"<td style='text-align:center;'>"+
						"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
						'&nbsp&nbsp&nbsp'+
						"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
					"</td>"+
				"</tr>");
			
			// Guardo los datos
			stockColor[contadorDataJSON] = {
				"id_color" : dataColor[i]['id_color'], 
				"id_talle" : dataColor[i]['id_talle'], 
				"stock" : stock_json, 
				"codigo" : dataColor[i]['codigo'],
				"estado_meli" : dataColor[i]['estado_meli']
			};
			$("#stockColor").val('');
			$("#stockColor").val('['+JSON.stringify(stockColor)+']');
			contadorDataJSON++;
		}
		if(contadorDataJSON>0){
			$('input[name=stock_total]').prop('disabled', true);
			$('input[name=codigo_total]').prop('disabled', true);
		}
		@endif
        $("#agregar-cod-stock").on("click", function(e){
            var color = $("#id_color").val();
            var nombreColor = $("#id_color option:selected").text();
            var talle = $("#id_talle").val();
            var nombreTalle = $("#id_talle").val() ? $("#id_talle option:selected").text() : '';
            var codigo = $("#codigo").val();
			
            if("" === color){
                appCustom.smallBox(
				'nok', 
				"Debe ingresar un color", 
				null, 
				'NO_TIME_OUT'
                );
			}else{
				var repetido_color = false;
				var repetido_codigo = false;
				if($("#stockColor").val()==''){
					stockColor = {};
				}else{
					var stockColor = JSON.parse($("#stockColor").val());
					stockColor = stockColor[0];
					//buscar si el color ya fue elegido 

					$.each(stockColor, function(i, v) {
						if (v.id_color == color && v.id_talle == talle) {
							appCustom.smallBox(
								'nok', 
								"El color y talle elegido ya está cargado", 
								null, 
								'NO_TIME_OUT'
							);
							repetido_color = true;
						}
						
						/* if (codigo && v.codigo == codigo) {
							appCustom.smallBox(
								'nok', 
								"El código ya está cargado", 
								null, 
								'NO_TIME_OUT'
							);
							repetido_codigo = true;
						} */
						
					});
					
				}
				if(!repetido_color &&  !repetido_codigo){
					var stockHTML = '';
					var stock_json = [];
					$('#stock input[name="stock[]"]').each(function() {
						stockHTML = stockHTML+'<p style="margin-bottom:0;"><strong>'+$(this).data('sucursaln')+':</strong> '+$(this).val()+'</p>';
						stock_json.push({'id': $(this).data('sucursal'), 'stock': $(this).val()});
					});


					$("#lista-cod-stock").append(
						"<tr class='fila-" + contadorDataJSON + "'>"+
							"<td>" + nombreColor + "</td>"+
							"<td>" + nombreTalle + "</td>"+
							"<td>" + stockHTML + "</td>"+
							"<td>" + codigo + "</td>"+
							"<td style='text-align:center;'>"+
								"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
								'&nbsp&nbsp&nbsp'+
								"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
							"</td>"+
						"</tr>")
					;
					// Vuelvo el foco a la lista de productos
					$("#id_color").focus();
					
					// Vacio los formularios
					//$("#id_color").val("");
					$("#id_talle").val("");
					$('#stock input[name="stock[]"]').val("");
					$("#codigo").val("");
					
					// Guardo los datos
					stockColor[contadorDataJSON] = {
						"id_color" : color, 
						"id_talle" : talle, 
						"stock" : stock_json, 
						"codigo" : codigo,
						"estado_meli" : 0
					};
					$("#stockColor").val('');
					$("#stockColor").val('['+JSON.stringify(stockColor)+']');
					contadorDataJSON++;
				}
			}
			if(contadorDataJSON>0){
				$('input[name=stock_total]').prop('disabled', true);
				$('input[name=codigo_total]').prop('disabled', true);
			}else{
				$('input[name=stock_total]').prop('disabled', false);
				$('input[name=codigo_total]').prop('disabled', false);
			}
		});
		$( "select[name=id_subrubro]" ).change(function() {
			var id_subrubro = $( this ).val();
			appCustom.ajaxRest(
            appCustom.subsubRubros.OBTENER_SUBSUBRUBROS.url, 
            appCustom.subsubRubros.OBTENER_SUBSUBRUBROS.verb,
            {id_subrubro: id_subrubro}, 
            function(result) {
                if (0 == result.status) {
                    if (result.subsubrubros) {
                        $('#subsubrubros').html('<option value="" selected="selected">Seleccione una SubSubrubro</option>');
                        for (var i = 0; i < result.subsubrubros.length ; i++) {
                            $('#subsubrubros').append('<option value="'+result.subsubrubros[i].id+'">'+result.subsubrubros[i].text+'</option>');
						}
						} else {
                        $('#subsubrubros').html('<option value="" selected="selected">No hay SubSubrubro</option>');
					};
					} else {
                    appCustom.smallBox(
					'nok', 
					result.msg, 
					null, 
					'NO_TIME_OUT'
                    )
                    ;
				}
			}
			);
		});
		$( "select[name=id_rubro]" ).change(function() {
			var id_rubro = $( this ).val();
			appCustom.ajaxRest(
            appCustom.subRubros.OBTENER_SUBRUBROS.url, 
            appCustom.subRubros.OBTENER_SUBRUBROS.verb,
            {id_rubro: id_rubro}, 
            function(result) {
                if (0 == result.status) {
                    if (result.subrubros) {
                        $('#subrubros').html('<option value="" selected="selected">Seleccione una Subrubro</option>');
                        for (var i = 0; i < result.subrubros.length ; i++) {
                            $('#subrubros').append('<option value="'+result.subrubros[i].id+'">'+result.subrubros[i].text+'</option>');
						}
						} else {
                        $('#subrubros').html('<option value="" selected="selected">No hay Subrubro</option>');
					};
					} else {
                    appCustom.smallBox(
					'nok', 
					result.msg, 
					null, 
					'NO_TIME_OUT'
                    )
                    ;
				}
			}
			);
		});
		$('.close_edit').on('click', function(){
			close_edit_cat();
		});
	});
	
	//Categoria meli
	function buscar_cat_meli(){
		var nombre = $('#nombremeli').val();
		if(nombre){
			appCustom.ajaxRest(
				'rest/v1/categoryPredict/'+nombre,
				'GET',
				null,
				function(result) {
					if (0 == result.status) {
						//console.log(result);
						var data_cat = '';
						$.each(result.data.path_from_root, function(i, v) {
							if(i>0){
								data_cat = data_cat+' > ';
							}
							data_cat = data_cat+v['name'];
						});
						$('#categoria_meli').val(result.data.id);
						$('#cat_meli').html(data_cat);
						$('#edit_cat_meli').removeClass('disabled');
						var categoria_variations = result.data.variations;
						$('#categoria_variations').val(categoria_variations?1:0);
					} else {
						appCustom.smallBox(
						'nok', 
						result.msg, 
						null, 
						'NO_TIME_OUT'
						)
						;
					}
				}
			);
		}else{
			appCustom.smallBox(
				'nok', 
				'Debe ingresar el nombre del producto', 
				null, 
				'NO_TIME_OUT'
			);
		}
	}
	function quitarFila(color){
		var stockColor = $("#stockColor").val();
		stockColor = JSON.parse(stockColor);
		delete stockColor[0][color]
		$(".fila-"+color).remove();
		$("#stockColor").val('');
		var cant = Object.keys(stockColor[0]).length;
		if(cant>0){
			$("#stockColor").val(JSON.stringify(stockColor));
		}else{
			$('input[name=stock_total]').prop('disabled', false);
			$('input[name=codigo_total]').prop('disabled', false);
		}
	}
	
	function editarFila(index){
		var arr = JSON.parse($("#stockColor").val())[0][index];
		
		$("#id_color").val(arr.id_color);
		$("#id_talle").val(arr.id_talle);
		$("#codigo").val(arr.codigo);
		$("#editarFila").data('index',index);
		$.each(arr.stock, function(i, item) {
			var data = $('#stock input[name="stock[]"]')[i];
			$(data).val(item.stock);
		});
		
		$("#id_color").focus();
		
		modeGrid('edit');
				
		//console.log(arr);
		
	}
	
	function editarFilaCancel(index){
		
				
		modeGrid('default');
				
		//console.log(arr);
		
	}
	
	function modeGrid(mode) {
		
		if ('edit' === mode) {
			$("#editarFila").show();
			$("#editarFilaCancel").show();
			$("#agregar-cod-stock").hide();
		} else {
			$("#editarFila").hide();
			$("#editarFilaCancel").hide();
			$("#agregar-cod-stock").show();
			
			$("#id_color").val("");
			$("#id_talle").val("");
			$('#stock input[name="stock[]"]').val("");
			$("#codigo").val("");
		}
		
		
	}
	
	function guardarFila(elEditar){
		var index = $(elEditar).data('index');
		
		if ("" === $("#id_color").val()) {
			appCustom.smallBox(
				'nok', 
				"Debe cargar un color", 
				null, 
				'NO_TIME_OUT'
			);
	
			return false;
		}
		
		var arr = JSON.parse($("#stockColor").val());
		repetido_color = false;
		repetido_codigo = false;
		$.each(arr[0], function(i, v) {
			
			if(index != i){
			
				if (v.id_color == $("#id_color").val() && v.id_talle == $("#id_talle").val()) {
					appCustom.smallBox(
						'nok', 
						"El color y talle elegido ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_color = true;
				}
/* 
				if ($("#codigo").val() && v.codigo == $("#codigo").val()) {
					appCustom.smallBox(
						'nok', 
						"El código ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_codigo = true;
				} */
			}

		});
		
		if (repetido_codigo || repetido_color) {
			return false;
		}
		var stockHTML = '';
		var stock_json = [];
		$('#stock input[name="stock[]"]').each(function() {
			stockHTML = stockHTML+'<p><strong>'+$(this).data('sucursaln')+':</strong> '+$(this).val()+'</p>';
			stock_json.push({'id': $(this).data('sucursal'), 'stock': $(this).val()});
		});
		
		arr[0][index].id_color = $("#id_color").val();
		arr[0][index].id_talle = $("#id_talle").val();
		arr[0][index].stock = stock_json;
		arr[0][index].codigo = $("#codigo").val();
		
		$("#stockColor").val(JSON.stringify(arr));
		
		//console.log(log);
		
		//console.log($('#lista-cod-stock .fila-' + index + ' td'));
		
		$('#lista-cod-stock .fila-' + index + ' td').eq(0).html($("#id_color option:selected").text());
		$('#lista-cod-stock .fila-' + index + ' td').eq(1).html($("#id_talle").val() ? $("#id_talle option:selected").text() : '');
		$('#lista-cod-stock .fila-' + index + ' td').eq(2).html(stockHTML);
		$('#lista-cod-stock .fila-' + index + ' td').eq(3).html($("#codigo").val());
		
		modeGrid('default');
		
		$('#lista-cod-stock .fila-' + index + ' td').animate({ 'background-color':'#3276B1'},"fast",function(){
			$(this).css('background-color','white');
		});

	}

	function edit_cat_meli(cat = false, nivel=2){
		var div = $("#selectCatMeli");
		div.find('tbody').html('');
		if(!cat){
			var cat = $('#categoria_meli').val()?$('#categoria_meli').val():-1;
		}
		appCustom.ajaxRest(
			'rest/v1/editCatMeli/'+cat+'/'+nivel,
			'GET',
			null,
			function(result) {
				div.removeClass('hide');
				var data_cat = '<a href="javascript:void(0);" onclick="edit_cat_meli(-1);">Inicio</a> >';
				if(cat!=-1){
					$.each(result.camino.path_from_root, function(i, v) {
						if(i>0){
							data_cat = data_cat+' > ';
						}
						data_cat = data_cat+'<a href="javascript:void(0);" onclick="edit_cat_meli(\''+v['id']+'\');">'+v['name']+'</a>';
					});
					div.find('.cat_meli').html(data_cat);

					var data_cat = '';
					$.each(result.categoria.children_categories, function(i, v) {
						data_cat = '<tr><td align="center">'+v['name']+'</td>';
						data_cat = data_cat+'<td align="center"><a href="javascript:void(0);" onclick="selectCategoria(\''+v['id']+'\')">Seleccionar</a></td></tr>';
						div.find('tbody').append(data_cat);
					});
				}else{
					div.find('.cat_meli').html(data_cat);
					$.each(result.camino, function(i, v) {
						data_cat = '<tr><td align="center">'+v['name']+'</td>';
						data_cat = data_cat+'<td align="center"><a href="javascript:void(0);" onclick="selectCategoria(\''+v['id']+'\')">Seleccionar</a></td></tr>';
						div.find('tbody').append(data_cat);
					});
				}
			}
		);
	}

	function close_edit_cat(){
		var div = $("#selectCatMeli");
		div.addClass('hide');
		div.find('tbody').html('');
	}

	function selectCategoria(cat){	
		close_edit_cat();
		appCustom.ajaxRest(
		'rest/v1/categoriaMeli/'+cat+'/1',
		'GET',
		null,
		function(result){
			if (0 == result.status) {
				var categoria_variations = result.data.attribute_types=='variations'?1:0;
				$('#categoria_variations').val(categoria_variations);
				if(result.data.children_categories.length>0){
					edit_cat_meli(result.data.id, 1);
				}else{
					var data_cat = '';
					$.each(result.data.path_from_root, function(i, v) {
						if(i>0){
							data_cat = data_cat+' > ';
						}
						data_cat = data_cat+v['name'];
					});
					$('#categoria_meli').val(result.data.id);
					$('#cat_meli').html(data_cat);
					$('#edit_cat_meli').removeClass('disabled');
				}
			} 
		}, 
		'sync'
        );  

		
	}

</script>
@include('pedidos.pedidos.pedidosEditScripts')
