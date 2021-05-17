<script type="text/javascript">
		
    $(document).ready(function() {
        
        //Settings
        var resourceDOM = {};
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
		var dtWrapper = '#' + resourceTableId + '_wrapper ';

        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.create = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        resourceReq.metodopago = {};
        resourceReq.estadopago = {};
        resourceReq.estadoenvio = {};
        resourceReq.productos = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.INDEX.verb;
        
        resourceReq.create.url = appCustom.{{$aViewData['resource']}}.CREATE.url;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.UPDATE.verb;
		
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.DELETE.url(id);
        };
        resourceReq.metodopago.url = function(id){
            return appCustom.{{$aViewData['resource']}}.metodopago.EDIT.url(id);
        };
        resourceReq.estadopago.url = function(id){
            return appCustom.{{$aViewData['resource']}}.estadopago.EDIT.url(id);
        };
        resourceReq.estadoenvio.url = function(id){
            return appCustom.{{$aViewData['resource']}}.estadoenvio.EDIT.url(id);
        };
        resourceReq.productos.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productos.EDIT.url(id);
        };
        
		
		//resourceReqOption1.edit.url
        // end settings
        
        $("button#resourceAdd")
                .attr('data-href', resourceReq.create.url);


        pageSetUp();
        var resourceTable = $('#' + resourceTableId).dataTable({
            "fixedHeader": true,
            "scrollX" : true,
            "stateSave": false, 
			"scrollCollapse": true,
			"lengthMenu": [ 100, 125, 150, 175, 200, 300 ],
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas filtro-all dataTables_length2'><'filtro-mas filtro-meli dataTables_length2'><'btn-filters'><'dataTables_length btn-export'B>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
			buttons: [
				{
					extend: 'collection',
					text: 'Exportar',
					buttons: [
						{
							extend: 'excelHtml5',
							text: 'Excel',
							filename: '{{$aViewData['resource']}}_*'
						},
						{
							extend: 'pdfHtml5',
							text: 'PDF',
							filename: '{{$aViewData['resource']}}_*',
							orientation: 'landscape',
							title: 'Reporte de {{$aViewData['resource']}} - *'
						},
						'csvHtml5'
					]
				}
			],

            "initComplete": function ()
            {
                $(dtWrapper + '.filtro-custom input').attr('placeholder', 'Buscar Cliente o Producto...');
				
				//filter 1
				var $fecha_a = $('<input style="width:86px;margin-left:4px;" id="fecha_a" class="datepicker" placeholder="F Desde" data-dateformat="dd/mm/yy" name="fecha_a" type="text" value="">');	
				
				$(dtWrapper + '.filtro-all').append($fecha_a);
				$fecha_a.css('height','33px');
				
				$fecha_a.datepicker({
					dateFormat : $fecha_a.attr('data-dateformat') || 'dd/mm/yy',
					language: "es",
					prevText : '<i class="fa fa-chevron-left"></i>',
					nextText : '<i class="fa fa-chevron-right"></i>'
				});
				$fecha_a.on('change', onChangeFiltroMas1);
				
				//filter 2
				var $fecha_a2 = $('<input style="width:85px;margin-left:4px;" id="fecha_a2" class="datepicker" placeholder="F Hasta" data-dateformat="dd/mm/yy" name="fecha_a2" type="text" value="">');	
				
				$(dtWrapper + '.filtro-all').append($fecha_a2);
				$fecha_a2.css('height','30px');
				
				$fecha_a2.datepicker({
					dateFormat : $fecha_a2.attr('data-dateformat') || 'dd/mm/yy',
					language: "es",
					prevText : '<i class="fa fa-chevron-left"></i>',
					nextText : '<i class="fa fa-chevron-right"></i>'
				});
				$fecha_a2.on('change', onChangeFiltroMas2);
				
				//filter 3
				@php
				$selectName = 'metPago';
				@endphp
				
				var selectName = "{{ $selectName }}";
				var select = '{{ Form::select(
					$selectName, 
					$aViewData['aCustomViewData']['aMetPago'],
					null,
					['style' => 'width:128px;']
					) 
				}}';
							
				$(dtWrapper + '.filtro-all').append(select);
				$(dtWrapper + 'select[name='+ selectName +']').prepend('<option selected value="">Met Pago (Todos)</option>');
				$(dtWrapper + '.filtro-all select[name='+ selectName +']').on('change', onChangeFiltroMas3);
				
				//filter 4
				@php
				$selectName = 'estadoPago';
				@endphp
				
				var selectName = "{{ $selectName }}";
				var select = '{{ Form::select(
					$selectName, 
					[
						"acordar"			=> "Envios a acordar",
						"approved"          => "Pago realizado con &eacute;xito",
						"cash_on_delivery"  => "Pago contra reembolso",
						"payment_in_branch" => "Pago en sucursal",
						"pending"           => "Pago en proceso",
						"proceso"           => "Carrito",
						"in_process"        => "El pago está siendo revisado",
						"rejected"          => "El pago fue rechazado",
						"cancelled"         => "El pago fue cancelado",
						"refunded"          => "La compra no se concretó",
						"in_mediation"      => "En disputa del pago",
					],
					null,
					['style' => 'width:122px;']
					) 
				}}';
							
				$(dtWrapper + '.filtro-all').append(select);
				$(dtWrapper + 'select[name='+ selectName +']').prepend('<option selected value="">Est Pago (Todos)</option>');
				$(dtWrapper + '.filtro-all select[name='+ selectName +']').on('change', onChangeFiltroMas4);
				
				//filter 5
				@php
				$selectName = 'estadoEnv';
				@endphp
				
				var selectName = "{{ $selectName }}";
				var select = '{{ Form::select(
					$selectName, 
					[
						"sin_estado"          => "Sin Estado",
						"pending"          => "Pendiente",
						"ready_to_ship"  => "Listo para enviar",
						"shipped" => "Enviado",
						"delivered"           => "Entregado",
						"proceso"           => "Carrito",
						"not_delivered"        => "No entregado",
						"cancelled"          => "Cancelado",
						"en_sucursal"         => "Retiro en sucursal",
					],
					null,
					['style' => 'width:124px;']
					) 
				}}';
							
				$(dtWrapper + '.filtro-all').append(select);
				$(dtWrapper + 'select[name='+ selectName +']').prepend('<option selected value="">Est Envío (Todos)</option>');
				$(dtWrapper + '.filtro-all select[name='+ selectName +']').on('change', onChangeFiltroMas5);
				
				$("#filtro_envio_acordar").on('click', onChangeFiltroMas6);

				//filter 7
				@php
				$selectName = 'Recurrente';
				@endphp
				
				var selectName = "{{ $selectName }}";
				var select = '{{ Form::select(
					$selectName, 
					[
						"1"          => "Si",
						"0"          => "No",
					],
					null,
					['style' => 'width:124px;']
					) 
				}}';
							
				$(dtWrapper + '.filtro-all').append(select);
				$(dtWrapper + 'select[name='+ selectName +']').prepend('<option selected value="">Recurrente (Todos)</option>');
				$(dtWrapper + '.filtro-all select[name='+ selectName +']').on('change', onChangeFiltroMas7);				
				
            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
			"order": [[ 1, "desc" ]],
            "fnCreatedRow": function ( row, data, index ) {
				$(row).find('.onoffswitch[name=enable0] input', $('#' + resourceTableId)).on('change', onEnableAction);
				if(data.acordar_envio==1){
					$(row).addClass('info');
				}
				$(row).find('.checkItem', $('#' + resourceTableId)).on('change', addItem);
				$(row).find('.publicarItem', $('#' + resourceTableId)).click(onPublicaAction);
				$(row).find('.facturaItem', $('#' + resourceTableId)).click(onVerFactura);
            },
            "aoColumnDefs": [
				
				{ "mData": "comprado_desde", "aTargets":[0], "sortable":true , "mRender": 
					function(value, type, full){
						var retorno = '<img src="img/mercado_libre.png"/>';
						return full.comprado_desde ? retorno : '';
					}
				},
				{ "mData": "created_at", "aTargets":[1], "sortable":true },
				{ "mData": "updated_at", "aTargets":[2], "sortable":true },
				{ "mData": "cliente", "aTargets":[3], "sortable":false },
				{ "mData": "metodo_pago", "aTargets":[4], "sortable":false, "mRender": 
					function(value, type, full){
						var metodo_pago = full['metodo_pago']?full['metodo_pago']:'Sin Asignar';
						metodo_pago=metodo_pago+' <a href="javascript:;" ><span data-href="'+resourceReq.metodopago.url(full.id_pedido)+'" data-toggle="modal-custom" class="fa fa-edit"></span></a>';
						
						return metodo_pago;
					}
				},
				{ "mData": "estado", "aTargets":[5], "sortable":false, "mRender": 
					function(value, type, full){
						var estado=full.estado+' <a href="javascript:;" ><span data-href="'+resourceReq.estadopago.url(full.id_pedido)+'" data-toggle="modal-custom" class="fa fa-edit"></span></a>';
						return estado;
					}
				},
				{ "mData": "estado_envio", "aTargets":[6], "sortable":false, "mRender": 
					function(value, type, full){
						var estado=' <a href="javascript:;" ><span data-href="'+resourceReq.estadoenvio.url(full.id_pedido)+'" data-toggle="modal-custom" class="fa fa-edit"></span></a>';
						return full['estado_envio']+estado;
					}
				},				
				{ "mData": "productos", "aTargets":[7], "sortable":false, "mRender": 
					function(value, type, full){
						var estado='<a href="javascript:;" ><span data-href="'+resourceReq.productos.url(full.id_pedido)+'" data-toggle="modal-custom" class="fa fa-shopping-cart"></span></a> ';
						return estado+'('+full['productos']+')';
					}
				},
				{ "mData": "collection_id", "aTargets":[8], "sortable":false },
				{ "mData": "payment_id", "aTargets":[9], "sortable":false },
				{ "mData": "tracking_number", "aTargets":[10], "sortable":false },
				{ "mData": "facturado", "visible": false,  "aTargets":[11], "sortable":false, "mRender": 
					function(value, type, full){	
						if(full['estado']=='Pago realizado con &eacute;xito!'){
							( 1 == full.facturado) ? factura = '<a href="'+window.location.origin+'/rest/v1/facturacion?id='+full.id_pedido+'" title="Ver Factura" class="facturaItem" data-id="'+full.id_pedido+'" target="_blank"><span class="fa fa-file-pdf-o fa-2x"></span></a>' : factura = '<a title="Emitir Factura" class="publicarItem" data-id="'+full.id_pedido+'"><span class="fa fa-file-o fa-2x"></span></a>';			
							return factura;
						}else{
							return '';
						}

							
					}
				},
				{ "mData": "precio_venta", "aTargets":[12], "sortable":false, "visible":false },
				{ "mData": "stock_reserva", "aTargets":[13], "sortable":false, "mRender": 
					function(value, type, full){
						var checked = ( 1 == full.stock_reserva) ? 'checked' : '';
						var enableOption = '<div class="btn-group" style="margin-right:30px">'+'<span class="onoffswitch" name="enable0" title="Liberar stock">'+'<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id_pedido+'" class="onoffswitch-checkbox" value="'+full.id_pedido+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id_pedido+'">'+'<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id_pedido+'"> '+'<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+'<span class="onoffswitch-switch"></span>' +'</label>'+'</span>'+'</div>';
					
						return enableOption;
					}
				}
              ],
            "fnServerData":function (sSource, aoData, fnCallback){

                appCustom.ajaxRest(
                    sSource, 
                    resourceReq.index.verb,
                    aoData, 
                    fnCallback
                );
            },
			"drawCallback": function () {
				$(".dataTables_paginate li a").on('click', function(){
					$('html, body').animate({
						scrollTop: $("body").offset().top
					}, 500);
				});
			}
        });
		
		// Esto se hace para evitar Requests repetidos
		var auxFiltrosFecha = {fecha_a:'', fecha_a2:''};

		function auxRequestValida(param, valor) {
			if ('' == auxFiltrosFecha[param]) {
				auxFiltrosFecha[param] = valor;
			} else if (auxFiltrosFecha[param] == valor) {
				return false;
			}

			auxFiltrosFecha[param] = valor;

			return true;
		}
		
		var onChangeFiltroMas1 = function(){
			if (auxRequestValida('fecha_a', this.value)) {
				$('#' + resourceTableId).dataTable().fnFilter(this.value ,1);
			}
		}
		
		var onChangeFiltroMas2 = function(){
			if (auxRequestValida('fecha_a2', this.value)) {
				$('#' + resourceTableId).dataTable().fnFilter(this.value ,2);
			}
		}
		
		var onChangeFiltroMas3 = function(){
			$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,3);
		}
		
		var onChangeFiltroMas4 = function(){
			$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,4);
		}
		
		var onChangeFiltroMas5 = function(){
			$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,5);
		}
		//envio a acordar
		var onChangeFiltroMas6 = function(){
			$('#' + resourceTableId).dataTable().fnFilter(($(this).is(':checked')?1:0) ,6);
		}
        var onChangeFiltroMas7 = function(){
			$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,7);
		}
        var onChangeFiltroMas8 = function(){
			$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,8);
		}
        
        var onEnableAction = function(e) {
            
            var id = $(e.target).val();
            var enable = ($(this).prop('checked')) ? 1:0;
            
            appCustom.ajaxRest(
                resourceReq.update.url(id), 
                resourceReq.update.verb,
                { justEnable:'yes', enable:enable}, 
                function(result) {
                    if (0 == result.status) {
                        appCustom.smallBox('ok','');
                        resourceTable.dataTable().fnStandingRedraw();
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
        };

				/* SCRIPTS PARA INTERACTUAR CON FACTURA ELECTRONICA*/
		var onPublicaAction = function(e) {
			$('#' + resourceTableId).dataTable().fnStandingRedraw();
            appCustom.confirmAction(
			'Desear generar la factura?!',
				function(){
					var id = e.currentTarget.dataset.id;
					$.ajax({ // Send an offer process with AJAX
                        method: 'POST',
                        url: '/rest/v1/fe',
                        data:  {  id : id },
                        dataType: 'json',
                        success: function(msg){
							if(msg.status!=1){
								console.log('pasa');
								$('#' + resourceTableId).dataTable().fnStandingRedraw();
								appCustom.smallBox('ok', 'La operación finalizó exitosamente');								
							}else{
								$('#' + resourceTableId).dataTable().fnStandingRedraw();
								appCustom.smallBox(
										'nok', 
										msg.msg, 
										null, 
										'NO_TIME_OUT'
										)
										;
							}
							
                        },
                        error: function(e) {
							console.log(e);
							appCustom.smallBox('Error', e.msg);
                        }
                    });
				}
            );
		};


		//VER FACTURA
		var onVerFactura = function(e) {
            var id = e.currentTarget.dataset.id;
			
			$.ajax({ // Send an offer process with AJAX
                        method: 'POST',
                        url: '/rest/v1/facturacion',
                        data:  {  id : id },
                        dataType: 'json',
                        success: function(msg){
                            if(msg.msg=='ok'){                
                                if (!e.target.hasAttribute("target")) {
									e.target.setAttribute("target", "_blank");
								}
                            }else{                    
                                console.log('nok');
                            }
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
        };

    }); //End DOM ready
	
	//checkbox handling
		var itemIds = [];
		function addItem() {
			var id = $(this).data('id');
			if ($(this).is(':checked')) {
				itemIds.push(id);
			} else {
				itemIds.splice(itemIds.indexOf(id), 1);
			}
		}
	
	function onSendMsg(){
			
			var data = {ids:JSON.stringify(itemIds)};
			
			appCustom.showModalRest(appCustom.sendMsg.CREATE.url("{{$aViewData['resource']}}"), null, data);
		}

</script>

	
