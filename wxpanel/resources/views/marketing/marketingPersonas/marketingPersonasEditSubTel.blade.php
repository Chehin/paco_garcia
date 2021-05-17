<div class="modal-body">
	<div>
		<div class="widget-body">
            {{ Form::open(array('id' => $aViewData['resource'] . 'Tel', 'name' => $aViewData['resource'] . 'Tel', 'class' => 'smart-form')) }}
            <fieldset>
                <section>
                    <label class="label"><b>Agregar Teléfonos:</b></label>
                    <table class="table table-bordered">
                        <thead>
                            <tr  style="color: #222;">
                                <th>Tipo teléfono</th>
                                <th>Número</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="listado-telefonos">
                            <tr>
                                <td>
                                    <div class="col-12">
                                        <label class="select">
                                            <?php $toDropDownTipoTel = array('' => 'Seleccionar tipo teléfono') + $aViewData['aCustomViewData']['aTipoTelefono']; ?>
                                            {{ Form::select(
                                            'tipo_telefono',
                                            $toDropDownTipoTel,
                                            '',
                                            ['class' => 'col col-md-12', 'id' => 'tipo_telefono']
                                            )
                                            }}
                                            <i></i>
                                        </label>
                                    </div>
                                </td>                                
                                <td>
                                    <div class="col-12">
                                        <label class="input">
                                            <input id="numero" name="numero" type="text" placeholder="0">
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="button" class="btn btn-primary" id="agregar-telefono" value="Agregar">
                                    <input type="button" class="btn btn-default" id="editarFila" onclick="guardarFila(this)" value="Editar" style="display: none;" data-index="">
                                    &nbsp;&nbsp;
                                    <a href="javascript:;" id="editarFilaCancel" onclick="editarFilaCancel()" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" id="telefonoAsig" name="telefonoAsig" value="" />
                </section>
            </fieldset>
            {{ Form::close() }}
        </div>
    </div>
</div>
<script>
    $(document).ready(function() { 
        var telefonoAsig = {}; // El objeto que almacenará el color, código y stock
        contadorDataJSON = 0;
		@if ('edit' == $mode) 
		var dataTelefonos = JSON.parse('<?php echo $aViewData['aCustomViewData']['aTelefonosAssigned']?>');
		for (var i = 0; i < dataTelefonos.length; i++) {
			$("#listado-telefonos").append(
				"<tr class='fila-" + contadorDataJSON + "'>"+
					"<td>" + dataTelefonos[i]['tipo_telefono'] + "</td>"+
					"<td>" + dataTelefonos[i]['numero'] + "</td>"+
					"<td style='text-align:center;'>"+
						"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
						'&nbsp&nbsp&nbsp'+
						"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
					"</td>"+
				"</tr>");
			
			// Guardo los datos
			telefonoAsig[contadorDataJSON] = {
				"id" : dataTelefonos[i]['id'],
				"id_persona" : dataTelefonos[i]['id_persona'], 
				"tipo_telefono" : dataTelefonos[i]['tipo_telefono'], 
				"numero" : dataTelefonos[i]['numero'],
			};
			$("#telefonoAsig").val('');
			$("#telefonoAsig").val('['+JSON.stringify(telefonoAsig)+']');
			contadorDataJSON++;
		}
		if(contadorDataJSON>0){
			$('input[name=stock_total]').prop('disabled', true);
			$('input[name=codigo_total]').prop('disabled', true);
		}
		@endif
        $("#agregar-telefono").on("click", function(e){
			
            var telefono = $("#numero").val();
            var tipoTelefono = $("#tipo_telefono").val() ? $("#tipo_telefono option:selected").text() : '';
			
            if("" === telefono){
                appCustom.smallBox(
				'nok', 
				"Debe ingresar un teléfono", 
				null, 
				'NO_TIME_OUT'
                );
			}else{
				var repetido_telefono = false;
				if($("#telefonoAsig").val()==''){
					telefonoAsig = {};
				}else{
					var telefonoAsig = JSON.parse($("#telefonoAsig").val());
					telefonoAsig = telefonoAsig[0];
					//buscar si el color ya fue elegido 

					$.each(telefonoAsig, function(i, v) {
						if (v.numero == telefono) {
							appCustom.smallBox(
								'nok', 
								"El teléfono ya está cargado", 
								null,
								'NO_TIME_OUT'
							);
							repetido_telefono = true;
						}
						
					});
					
				}
				if(!repetido_telefono){
					$("#listado-telefonos").append(
						"<tr class='fila-" + contadorDataJSON + "'>"+
							"<td>" + tipoTelefono + "</td>"+
							"<td>" + telefono + "</td>"+
							"<td style='text-align:center;'>"+
								"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
								'&nbsp&nbsp&nbsp'+
								"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
							"</td>"+
						"</tr>")
					;
					// Vuelvo el foco a la lista de productos
					$("#tipo_telefono").focus();
					
					// Vacio los formularios
					$("#numero").val("");
					
					// Guardo los datos
					telefonoAsig[contadorDataJSON] = {
						"tipo_telefono" : tipoTelefono, 
						"numero" : telefono
					};
					$("#telefonoAsig").val('');
					$("#telefonoAsig").val('['+JSON.stringify(telefonoAsig)+']');
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
	});
	function quitarFila(numero){
		var telefonoAsig = $("#telefonoAsig").val();
		telefonoAsig = JSON.parse(telefonoAsig);
		delete telefonoAsig[0][numero]
		$(".fila-"+numero).remove();
		$("#telefonoAsig").val('');
		var cant = Object.keys(telefonoAsig[0]).length;
		if(cant>0){
			$("#telefonoAsig").val(JSON.stringify(telefonoAsig));
		}else{
			$('input[name=stock_total]').prop('disabled', false);
			$('input[name=codigo_total]').prop('disabled', false);
		}
	}
	
	function editarFila(index){
		var arr = JSON.parse($("#telefonoAsig").val())[0][index];
		
		$("#tipo_telefono").val(arr.tipo_telefono);
		$("#numero").val(arr.numero);
		$("#editarFila").data('index',index);
		
		$("#tipo_telefono").focus();
		
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
			$("#agregar-telefono").hide();
		} else {
			$("#editarFila").hide();
			$("#editarFilaCancel").hide();
			$("#agregar-telefono").show();
			
			$("#tipo_telefono").val("");
			$("#numero").val("");
		}
		
		
	}
	
	function guardarFila(elEditar){
		var index = $(elEditar).data('index');
		
		if ("" === $("#numero").val()) {
			appCustom.smallBox(
				'nok', 
				"Debe cargar un teléfono", 
				null, 
				'NO_TIME_OUT'
			);
	
			return false;
		}
		
		var arr = JSON.parse($("#telefonoAsig").val());
		repetido_telefono = false;
		$.each(arr[0], function(i, v) {
			
			if(index != i){
			
				if (v.numero == $("#numero").val()) {
					appCustom.smallBox(
						'nok', 
						"El número de teléfono ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_telefono = true;
				}

				if ($("#codigo").val() && v.codigo == $("#codigo").val()) {
					appCustom.smallBox(
						'nok', 
						"El código ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_codigo = true;
				}
			}

		});
		
		if (repetido_telefono) {
			return false;
		}
		
		arr[0][index].tipo_telefono = $("#tipo_telefono").val();
		arr[0][index].numero = $("#numero").val();
		
		$("#telefonoAsig").val(JSON.stringify(arr));
		
		//console.log(log);
		
		console.log($('#listado-telefonos .fila-' + index + ' td'));
		
		$('#listado-telefonos .fila-' + index + ' td').eq(0).html($("#tipo_telefono").val() ? $("#tipo_telefono option:selected").text() : '');
		$('#listado-telefonos .fila-' + index + ' td').eq(1).html($("#numero").val());
		
		modeGrid('default');
		
		$('#listado-telefonos .fila-' + index + ' td').animate({ 'background-color':'#3276B1'},"fast",function(){
			$(this).css('background-color','white');
		});

		
//		console.log(arr);
	}
</script>