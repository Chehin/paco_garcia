<script type="text/javascript">
	
    $(document).ready(function() {
        
        //Settings
        var resourceDOM = {};
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.create = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        resourceReq.language = {};
		var resourceReqOption1 = {};
		resourceReqOption1.edit = {};
		var resourceReqOption2 = {};
		resourceReqOption2.edit = {};
        var resourceReqOption3 = {};
        resourceReqOption3.edit = {};
        var resourceReqOption4 = {};
        resourceReqOption4.edit = {};
        var resourceReqOption5 = {};
        resourceReqOption5.store = {};
        resourceReqOption5.show = {};
        resourceReqOption5.update = {};
        resourceReqOption5.delete = {};
        var resourceReqOption6 = {};
        resourceReqOption6.edit = {};
		var resourceReqOption7 = {};
		resourceReqOption7.edit = {};
		
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
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.DELETE.verb;
        
        resourceReq.language.url = function(id){
            return appCustom.{{$aViewData['resource']}}.language.mainView.url(id)
		};
		
		resourceReqOption1.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.image.mainView.url(id);
		};
		
		resourceReqOption2.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.noteRelated.mainView.url(id);
		};
		
        resourceReqOption3.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.preciosRelated.mainView.url(id);
		};

		resourceReqOption4.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosRelated.mainView.url(id);
		};

		resourceReqOption5.store.url = function(id){
			return appCustom.{{$aViewData['resource']}}.Meli.STORE.url(id);
		};
		resourceReqOption5.store.verb = appCustom.{{$aViewData['resource']}}.Meli.STORE.verb;

		resourceReqOption5.show.url = function(id){
			return appCustom.{{$aViewData['resource']}}.Meli.SHOW.url(id);
		};
		resourceReqOption5.show.verb = appCustom.{{$aViewData['resource']}}.Meli.SHOW.verb;

		resourceReqOption5.update.url = function(id){
			return appCustom.{{$aViewData['resource']}}.Meli.UPDATE.url(id);
		};
		resourceReqOption5.update.verb = appCustom.{{$aViewData['resource']}}.Meli.UPDATE.verb;

		resourceReqOption5.delete.url = function(id){
			return appCustom.{{$aViewData['resource']}}.Meli.DELETE.url(id);	
		}
		resourceReqOption5.delete.verb = appCustom.{{$aViewData['resource']}}.Meli.DELETE.verb;

		resourceReqOption6.edit.url = function(id){
			return appCustom.{{$aViewData['resource']}}.productosPreguntas.mainView.url(id);
		};
		resourceReqOption7.edit.url = function(id){
			return appCustom.{{$aViewData['resource']}}.productosRelatedColor.mainView.url(id);
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
			"language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
			},
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas dataTables_length'><'btn-filters'><'dataTables_length btn-export'B>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
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
			    //filtro Marcas
				var $select = $('<select id="marcas"><option value="NULL" seleted="true">Todos las Marcas</option></select>');
				var options = JSON.parse('{!! $aViewData['aMarcas']->toJson() !!}');
				$('.filtro-custom input').attr('placeholder', 'Buscar...');
				
				$("#marcas").empty();
				if (options.length) {
					options.forEach(function(item){
						$select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
					});
				}
				$('.filtro-mas').append($select);
				
				$select.change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),5);
				});
				//end filtro Marcas

                //filtro Rubros
				var $select = $('<select id="rubros"><option value="NULL" seleted="true">Todos los Rubros</option></select>');
				var options = JSON.parse('{!! $aViewData['aCustomViewData']['rubros']->toJson() !!}');
				$('.filtro-custom input').attr('placeholder', 'Buscar...');
				
				$("#rubros").empty();
				if (options.length) {
					options.forEach(function(item){
						$select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
					});
				}
				$('.filtro-mas').append($select);
				
				$select.change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),1);
					var id_rubro=$(this).val();
					var optionsSub='';
					var div=$(this).parent();

					if(id_rubro=='NULL'){
						$('#fsubrubros').attr('disabled', 'disabled');
					}else{
						$('#fsubrubros').removeAttr('disabled');
					}

					$.ajax({
                    type:'get',
                    url:'{!!URL::to('filtroSubrubros') !!}',
                    data:{'id':id_rubro},
                    success:function(data){
                    	$("#fsubrubros").empty();
                        optionsSub+='<option value="NULL" seleted="true">Elegir Subrubros</option>';
                        for(var i=0; i<data.length; i++){
                        	optionsSub+='<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
                        }
                        div.find('#fsubrubros').html(" ");
                        div.find('#fsubrubros').append(optionsSub);
                    },
                    error:function(){
                        console.log('error');
                    }
               		}); 
				});
				//end filtro rubros

				
				//filtro subrubros
				var $selectSub = $('<select id="fsubrubros" disabled="disabled"><option value="NULL" seleted="true">Todos los Subrubros</option></select>');
				 
				$('.filtro-mas').append($selectSub);
					
				$selectSub.change(function(){
						resourceTable.dataTable().fnFilter($(this).val(),2);
				});
				//end filtro subrubros 


				//filtro etiquetas
				$("#tag").change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),3);
				});
				//end filtro etiquetas 


				//filtro Stock
				var $select = $('<select><option value="NULL" seleted="true">Stock</option><option value="1">Con Stock</option><option value="0">Sin Stock</option></select>');
				
				$('.filtro-mas').append($select);
				
				$select.change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),4);
				});
				//end filtro Stock
				//filtro mas
				var $selectMas = $('<select><option value="NULL" seleted="true">Filtrar</option><option value="1">Con Foto</option><option value="2">Sin Foto</option><option value="3">Destacados</option><option value="4">Ofertas</option><option value="5">En Mercado Libre</option></select>');
				
				$('.filtro-mas').append($selectMas);
				$selectMas.change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),6);
				});

				//filtro etiquetas
				var $select = $('<select id="tags"><option value="NULL" seleted="true">Etiquetas</option></select>');
				var options = JSON.parse('{!! $aViewData['aEtiquetas']->toJson() !!}');

				$("#tags").empty();
				if (options.length) {
					options.forEach(function(item){
						$select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
					});
				}
				$('.filtro-mas').append($select);

				$("#tags").change(function(){
					resourceTable.dataTable().fnFilter($(this).val(),7);
				});
				//end filtro etiquetas 

				//La búsqueda de los filtros de texto se lanzan solamente con ENTER o en Blur.
				$('.dataTables_filter input')
				.unbind()
				.bind('keypress', function(e) {
					if (13 == e.keyCode) {
						handleFiltersText($(this).val());
					}
				})
				.bind('blur', function(){
					handleFiltersText($(this).val());
				});

			},
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 1, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
                $(row).find('.actionDeleteItem' , $('#' + resourceTableId)).click(onDropAction);
                $(row).find('.onoffswitch[name=enable0] input', $('#' + resourceTableId)).on('change', onEnableAction);
                $(row).find('.onoffswitch[name=enable1] input', $('#' + resourceTableId)).on('change', onEnableAction1);
                $(row).find('.onoffswitch[name=enable2] input', $('#' + resourceTableId)).on('change', onEnableAction2);
                $(row).find('.onoffswitch[name=enable3] input', $('#' + resourceTableId)).on('change', onEnableAction3);
                $(row).find('.publicarItem', $('#' + resourceTableId)).click(onPublicaAction);
                $(row).find('.verPublicacion', $('#' + resourceTableId)).click(onVerPublicacion);
                $(row).find('.actualizarPublicacion', $('#' + resourceTableId)).click(onActualizarPublicacion);
                $(row).find('.editPublicacion', $('#' + resourceTableId)).click(onEditPublicacion);
				$(row).find('.deletePublicacion', $('#' + resourceTableId)).click(onDeletePublicacion);
				$(row).find('.checkItem', $('#' + resourceTableId)).on('change', addItem);
			},
            "aoColumnDefs": [
			{ "mData": "", "aTargets":[0], "sortable":false, "mRender": function(value, type, full){
			var checked = '';
			if (itemIds.indexOf(full.id) >= 0) {
				checked = 'checked';
			}
			return '<input '+checked+' data-id="'+full.id+'" class="checkItem" type="checkbox" value="'+full.id+'">';
			}
			},
			{ "mData": "codigo", "aTargets":[1], "sortable":true },
			{ "mData": null, "aTargets":[2], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
				return '<a href="javascript:;"><img data-href="'+ resourceReqOption1.edit.url(full.id)+ '" data-toggle="modal-custom" width="50" src="'+full.foto+'" /></a>';
			}
			},
			{ "mData": "nombre", "aTargets":[3], "sortable":true },
			{ "mData": "rubro", "aTargets":[4], "sortable":true },
			{ "mData": null, "aTargets":[5], "sortable":false, "sClass": "center", "mRender": function(value, type, full){                                                                                
				return '<a href="javascript:;" data-href="'+resourceReqOption3.edit.url(full.id)+'" data-toggle="modal-custom" >'+full.precio+' <span data-href="'+resourceReqOption3.edit.url(full.id)+'" data-toggle="modal-custom" class="glyphicon glyphicon-edit"></span></a>';
			}                    
			},
			{ "mData": "orden", "aTargets":[6], "sortable":true },
			{ "mData": "habilitado", "aTargets":[7], "sortable":false, "mRender": function(value, type, full){
				
				var checked = ( 1 == full.habilitado) ? 'checked' : '';
				
				var enableOption = '<div class="btn-group" style="margin-right:30px">'+
				'<span class="onoffswitch" name="enable0" title="Habilitar / Deshabilitar">'+
				'<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
				'<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
				'<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
				'<span class="onoffswitch-switch"></span>' +
				'</label>'+
				'</span>'+
				'</div>'
				;
				return enableOption;
			}
			},
			{ "mData": "destacado", "aTargets":[8], "sortable":false, "mRender": function(value, type, full){
				var prefix = '1';
				var resourceTableId = prefix + '_' + resourceTableId;
				var checked = ( 1 == full.destacado) ? 'checked' : '';
				
				var enableOption = '<div class="btn-group" style="margin-right:30px">'+
				'<span class="onoffswitch" name="enable1" title="Habilitar / Deshabilitar">'+
				'<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
				'<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
				'<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
				'<span class="onoffswitch-switch"></span>' +
				'</label>'+
				'</span>'+
				'</div>'
				;
				return enableOption;
			}
			},	
			
			{ "mData": "destacado", "aTargets":[9], "sortable":false, "mRender": function(value, type, full){
				var prefix = '2';
				var resourceTableId = prefix + '_' + resourceTableId;
				var checked = ( 1 == full.oferta) ? 'checked' : '';
				
				var enableOption = '<div class="btn-group" style="margin-right:30px">'+
				'<span class="onoffswitch" name="enable2" title="Habilitar / Deshabilitar">'+
				'<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
				'<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
				'<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
				'<span class="onoffswitch-switch"></span>' +
				'</label>'+
				'</span>'+
				'</div>'
				;
				return enableOption;
			}
			},		
			{ "mData": null, "aTargets":[10], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
				
				return '<a href="javascript:;" ><span data-href="'+ resourceReqOption4.edit.url(full.id) + '" data-toggle="modal-custom" style="font-size: 160%;" class="glyphicon glyphicon-th-list"></span></a>';
				
			}			
			},
			{ "mData": null, "aTargets":[11], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
				
				return '<a href="javascript:;" ><span data-href="'+ resourceReqOption7.edit.url(full.id) + '" data-toggle="modal-custom" style="font-size: 160%;" class="glyphicon glyphicon-th-list"></span></a>';
				
			}			
			},
			{ "mData": null, "aTargets":[12], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
				
				return '('+full.imgCnt+') <a href="javascript:;" ><span data-href="'+resourceReqOption1.edit.url(full.id)+'" data-toggle="modal-custom" style="font-size: 160%;" class="glyphicon glyphicon-camera"></span></a>';
				
			}
			},
			{ "mData": "estado_meli", "aTargets":[13], "sortable": true, "mRender": function(value, type, full){
				var prefix = '3';
				var resourceTableId = prefix + '_' + resourceTableId;
				var checked = ( 1 == full.estado_meli) ? 'checked' : '';
				
				var enableOption = '<div class="btn-group" style="margin-right:30px">'+
				'<span class="onoffswitch" name="enable3" title="Habilitar / Deshabilitar">'+
				'<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
				'<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
				'<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
				'<span class="onoffswitch-switch"></span>' +
				'</label>'+
				'</span>'+
				'</div>'
				;
				return enableOption;
			}
			},
			{ "mData": "id_meli", "visible": true, "aTargets":[14], "sortable":false, "mRender": function(value, type, full){
				var meli = '';
				var estado_meli = ( 1 == full.estado_meli) ? 'Pausar' : 'Activar';
				var value_meli = ( 1 == full.estado_meli) ? 0 : 1;
				if(value) {
					meli += '<div class="btn-group">'+
					'<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
					'<i class="fa fa-cog"></i></a>'+
					'<ul class="dropdown-menu" style="left:auto; right:0">'+
					@if(Sentinel::hasAccess($aViewData['resource'] . '.view'))
					'<li>'+
					'<a class="verPublicacion" data-id="'+full.id+'">Ver publicación</span>'+
					'</li>'+
					@endif

					@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
					'<li>'+
					'<a class="actualizarPublicacion" data-id="'+full.id+'">Actualizar publicación</span>'+
					'</li>'+
					@endif

					@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
					'<li>'+
					'<a class="editPublicacion" data-id="'+full.id+'" data-value_meli="'+value_meli+'">'+ estado_meli +
					'</span>'+
					'</li>'+
					@endif
					
					@if(Sentinel::hasAccess($aViewData['resource'] . '.delete'))
					'<li>'+
					'<a class="deletePublicacion" data-id="'+full.id+'">Eliminar</a>'+
					'</li>'+
					@endif
					'</ul>'+
					'</div>'
					;
				} else {
					meli += '<a class="btn btn-primary btn-xs publicarItem" data-id="'+full.id+'">Publicar</a>';
				}
				return meli;
			} 
			},
			
			{ "mData": "", "aTargets":[15], "sortable": true, "mRender": function(value, type, full){
				
				var moreOptions = '<div class="btn-group">'+
				'<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
				'<i class="fa fa-cog"></i></a>'+
				'<ul class="dropdown-menu" style="left:auto; right:0">'+
				@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
				'<li>'+
				'<a class="actionEditItem" data-href="'+ resourceReq.edit.url(full.id) + '" data-toggle="modal-custom">Modificar</a>'+
				'</li>'+
				@endif
				
				@if(Sentinel::hasAccess($aViewData['resource'] . '.delete'))
				'<li>'+
				'<a class="actionDeleteItem" data-id="'+full.id+'">Eliminar</a>'+
				'</li>'+
				@endif
				'</ul>'+
				'</div>'
				;
				
				return moreOptions;
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
        
        var onDropAction = function(e) {
            appCustom.confirmAction(
			'Borrar este elemento?!',
			function(){
				var id = e.target.dataset.id;
				appCustom.ajaxRest(
				resourceReq.delete.url(id), 
				resourceReq.delete.verb,
				null, 
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
			}
            );
			
		};

		//Manejador de los filtros de texto
		var filterTxtLast = '';
		function handleFiltersText(filterTxt) {
			
			filterTxt = filterTxt.slice(0, -1);

			if (filterTxt != filterTxtLast) {
				filterTxtLast = filterTxt;

				resourceTable.fnFilter(filterTxt);

			}
		}

		/****************************************************************************/
		/* SCRIPTS PARA INTERACTUAR CON MERCADO LIBRE*/
		// Publica un producto en Mercado Libre
		var onPublicaAction = function(e) {
            appCustom.confirmAction(
			'Publicar este producto en Mercado Libre?!',
			function(){
				var id = e.target.dataset.id;
				appCustom.ajaxRest(
				resourceReqOption5.store.url(id),
				resourceReqOption5.store.verb,
				null, 
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
			}
            );
		};
		// Obtengo el link de la publicación de Mercado Libre
		var onVerPublicacion = function(e) {
			e.preventDefault();
			var id = e.target.dataset.id;
            
            appCustom.ajaxRest(
			resourceReqOption5.show.url(id), 
			resourceReqOption5.show.verb,
			null,
			function(result) {
				if (0 == result.status) {
					resourceTable.dataTable().fnStandingRedraw();
					window.open(result.data, '_blank');
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
		}
		// Actualiza una publicación
		var onActualizarPublicacion = function(e) {

			var id = e.target.dataset.id;
            
            appCustom.ajaxRest(
			resourceReqOption5.update.url(id), 
			resourceReqOption5.update.verb,
			null,
			function(result) {
				if (0 == result.status) {
					appCustom.smallBox('ok','');
					resourceTable.dataTable().fnStandingRedraw();
                } else {
                	resourceTable.dataTable().fnStandingRedraw();
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
		}
		// Activar o Pausar una publicación
		var onEditPublicacion = function(e) {

			var id = e.target.dataset.id;
			var enable = e.target.dataset.value_meli;
            
            appCustom.ajaxRest(
			resourceReqOption5.update.url(id), 
			resourceReqOption5.update.verb,
			{ justEnable:'yes', enable:enable},
			function(result) {
				if (0 == result.status) {
					appCustom.smallBox('ok','');
					resourceTable.dataTable().fnStandingRedraw();
                } else {
                	resourceTable.dataTable().fnStandingRedraw();
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
		}
		// Elimina una publicación de Mercado Libre
		var onDeletePublicacion = function(e) {
			appCustom.confirmAction(
			'Borrar la publicación en Mercado Libre?!',
			function(){
				var id = e.target.dataset.id;
				appCustom.ajaxRest(
				resourceReqOption5.delete.url(id),
				resourceReqOption5.delete.verb,
				null,
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
			}
            );
		}
		/* FIN SCRIPTS MERCADO LIBRE*/
		/*************************************************************************/

        // Habilitado
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
					resourceTable.dataTable().fnStandingRedraw();
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
        // Destacado
        var onEnableAction1 = function(e) {
            
            var id = $(e.target).val();
            var enable = ($(this).prop('checked')) ? 1:0;
            
            appCustom.ajaxRest(
			resourceReq.update.url(id), 
			resourceReq.update.verb,
			{ justEnable1:'yes', enable:enable}, 
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
        // Oferta
        var onEnableAction2 = function(e) {
            
            var id = $(e.target).val();
            var enable = ($(this).prop('checked')) ? 1:0;
            
            appCustom.ajaxRest(
			resourceReq.update.url(id), 
			resourceReq.update.verb,
			{ justEnable2:'yes', enable:enable}, 
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
        // estado_meli
        var onEnableAction3 = function(e) {
            
            var id = $(e.target).val();
            var enable = ($(this).prop('checked')) ? 1:0;
            
            appCustom.ajaxRest(
			resourceReq.update.url(id), 
			resourceReq.update.verb,
			{ justEnable3:'yes', enable:enable}, 
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
		
        //Context menu
        $(document).contextmenu({
			delegate: "#"+resourceTableId+" td",
			menu: [
			@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
			{title: "<i class='fa fa fa-pencil'></i> Modificar", cmd: "edit"},
			@endif
			
			@if(Sentinel::hasAccess($aViewData['resource'] . '.delete'))
			{title: "<i class='fa fa-times'></i> Eliminar", cmd: "drop"}
			@endif
			],
			select: function(event, ui) {
				switch(ui.cmd) {
					case "edit":
					$(ui.target)
					.parents('tr')
					.find('.actionEditItem')
					.trigger('click');
					
					break;
					case "drop":
					$(ui.target)
					.parents('tr')
					.find(".actionDeleteItem")
					.trigger('click');
					
					break;
					
				}
			},
			beforeOpen: function(event, ui) {
				var $menu = ui.menu,
				$target = ui.target,
				extraData = ui.extraData;
				ui.menu.zIndex(99999);
			}
		});
		
		
		
	}); //End DOM ready
	var itemIds = [];
	function addItem() {
		var id = $(this).data('id');
		if ($(this).is(':checked')) {
			itemIds.push(id);
		} else {
			itemIds.splice(itemIds.indexOf(id), 1);
		}
	}

	function onSetEtiquetas(){
		var data = {ids:JSON.stringify(itemIds)};			
		appCustom.showModalRest(appCustom.setEtiquetas.CREATE.url("{{$aViewData['resource']}}"), null, data);
		$("#select_all").prop('checked', false);
	} 
    
    
</script>

<script type="text/javascript">
	$(document).ready(function () {
		 
		  $('#tag').select2({
		   placeholder: "Filtrar por Etiqueta",
		   minimumInputLength: 0,
		   allowClear : false,
		   width : '100%',
		   ajax: {
			   url: '{!!URL::to('filtroTag') !!}',
			   dataType: 'json',
			   data: function (params) {
				   return {
					   q: params.term
				   };
			   },
			   processResults: function (data) {
				   return {
					   results: data
				   };
			   }
		   }
		});
		$('#select_all').click(function() {
			if(this.checked){
				$('.checkItem').each(function(){
					$(".checkItem").prop('checked', true);
					itemIds.push($(this).val());
			   })
		   }else{
			   $('.checkItem').each(function(){
				   $(".checkItem").prop('checked', false);
				   itemIds.splice(itemIds.indexOf($(this).val()), 1);
			   })
		   }
	   });
   
   });
   </script>


