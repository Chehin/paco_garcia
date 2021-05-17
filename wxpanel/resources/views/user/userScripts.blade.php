<script type="text/javascript">
		
    $(document).ready(function() {
		
	var resourceTableId = 'datatable_tabletools';

		$("button#userAdd")
			.attr('data-href', appCustom.user.CREATE.url);

		pageSetUp();

		resourceReqOption1 = {};
		resourceReqOption1.edit = {};

		resourceReqOption1.edit.url = function(id){
			return appCustom.roleAssign.EDIT.url(id);
		};
		resourceReqOption1.edit.verb = appCustom.roleAssign.EDIT.verb;

            
		var userTable = $('#datatable_tabletools').dataTable({
                "scrollX" : true,
		"fixedHeader": true,
                "scrollCollapse": true,
                 "language": {
                    "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
                   },
                "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
               "oTableTools" : {
                 "aButtons" : [{
                  "sExtends" : "collection",
                  "sButtonText" : 'Exportar <span class="caret" />',
                  "aButtons" : ["csv", "xls", "pdf"]
                 }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
                },
                "initComplete": function ()
                {
			$('.filtro-custom input').attr('placeholder', 'Buscar Usuarios');
					
			var select = $('<select><option value="not_deleted">No eliminados</option><option value="deleted">Eliminados</option></select>');
			$('.filtro-mas').append( select );
			$('.filtro-mas select').on('change', onChangeFiltroMas);
                },
                "bProcessing" : false,
                "sAjaxSource":  appCustom.user.INDEX.url,
                "bServerSide": true,
                "bPaginate": true,
                "ordering": true,
                "order": [ 3, 'desc' ],
                "fnCreatedRow": function ( row, data, index ) {
			$(row).find('.actionDeleteItem').click(function(e){

				appCustom.confirmAction(
					'Borrar este usuario?!',
					function(){
						var id = e.target.dataset.id;
						appCustom.ajaxRest(
							appCustom.user.DELETE.url(id), 
							appCustom.user.DELETE.verb,
							null, 
							function(result) {
								if (0 == result.status) {
									appCustom.smallBox('ok','');
									userTable.dataTable().fnStandingRedraw();
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



			});
					
			$(row).find('.onoffswitch[name=enable0] input', $('#' + resourceTableId)).on('change', onEnableAction);


			$(row).find('.actionUndeleteItem').click(onUndeleteAction);
					
					

                },
                "aoColumnDefs": [
                    { "mData": "nombre", "aTargets":[0], "sortable":true },
                    { "mData": "telefono", "aTargets":[1], "sortable":true},
                    { "mData": "mail", "aTargets":[2], "sortable":true },
                    { "mData": "f_ingreso", "aTargets":[3], "sortable":true, "mRender": function(value, type, full){
									var ret = ''
										if (value) {
											ret = moment(value, "YYYY-MM-DD H:mm").format("DD/MM/YYYY H:mm");
										}

									return ret;
							}
					},
                    { "mData": "", "aTargets":[4], "sortable":false, "mRender": function(value, type, full){
							
								if (!full.deleted_at) {
							
									var checked = ( 1 == full.habilitado) ? 'checked' : '';

									var enableOption = '<div class="btn-group" style="margin-right:30px">'+
                                                                '<span class="onoffswitch" name="enable0" title="Habilitar / Deshabilitar">'+
                                                                        '<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id_usuario+'" class="onoffswitch-checkbox" value="'+full.id_usuario+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id_usuario+'">'+
                                                                        '<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id_usuario+'"> '+
                                                                                '<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
                                                                                '<span class="onoffswitch-switch"></span>' +
                                                                        '</label>'+
                                                                '</span>'+
                                                            '</div>'
                                                            ;
									var moreOptions =  '<div class="btn-group">'+
                                                            '<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
                                                                    '<i class="fa fa-cog"></i></a>'+
                                                            '<ul class="dropdown-menu" style="left:auto; right:0">'+
									@if (\Sentinel::hasAccess([$aViewData['resource']  . '.update', $aViewData['resource']  . '.create']))
                                                                        '<li>'+
                                                                                '<a class="actionOption1Item" data-href="'+ resourceReqOption1.edit.url(full.id_usuario) + '" data-toggle="modal-custom"><i class="fa fa fa-group"></i> Perfiles</span>'+
                                                                        '</li>'+
                                                                        @endif
                                                                    @if(Sentinel::hasAccess('user.update'))
                                                                    '<li>'+
                                                                            '<a class="editItem" data-href="'+ appCustom.user.EDIT.url(full.id_usuario) + '" data-toggle="modal-custom"><i class="fa fa fa-pencil"></i> Modificar</span>'+
                                                                    '</li>'+
                                                                    @endif
															
                                                                    @if(Sentinel::hasAccess('user.delete'))
                                                                    '<li>'+
                                                                            '<a class="actionDeleteItem" data-id="'+full.id_usuario+'"><i class="fa fa fa-times"></i> Eliminar</a>'+
                                                                    '</li>'+
                                                                    @endif
                                                            '</ul>'+
                                                    '</div>';
									return enableOption + moreOptions; 
								} else {
									var undelete = ''
									@if(Sentinel::hasAccess('user.update'))
										undelete =  '<input class="actionUndeleteItem" type="button" value="Restaurar" data-id="'+full.id_usuario+'">';
									@endif
									
									return undelete;
									
								}
								
								
							}
									
									
                    }
                  ],
                "fnServerData":function (sSource, aoData, fnCallback){
                    appCustom.ajaxRest(
                        sSource, 
                        appCustom.user.INDEX.verb,
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
			
	var onEnableAction = function(e) {

		var id = $(e.target).val();
		var enable = ($(this).prop('checked')) ? 1:0;

		appCustom.ajaxRest(
			appCustom.user.UPDATE.url(id), 
			appCustom.user.UPDATE.verb,
			{ justEnable:'yes', enable:enable}, 
			function(result) {
				if (0 == result.status) {
					appCustom.smallBox('ok','');
					$('#' + resourceTableId).dataTable().fnStandingRedraw();
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
			
	var onUndeleteAction = function(e) {

		var id = $(e.target).data('id');

		appCustom.ajaxRest(
			appCustom.user.UPDATE.url(id), 
			appCustom.user.UPDATE.verb,
			{ undelete:'yes'}, 
			function(result) {
				if (0 == result.status) {
					appCustom.smallBox('ok','');
					$('#' + resourceTableId).dataTable().fnStandingRedraw();
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

	var onChangeFiltroMas = function(){
		$('#' + resourceTableId).dataTable().fnFilter($(this).val() ,0);
	}
	//Context menu
	$(document).contextmenu({
            delegate: ".dataTable td",
            menu: [
		@if (\Sentinel::hasAccess([$aViewData['resource']  . '.update', $aViewData['resource']  . '.create']))
                    {title: "<i class='fa fa fa-group'></i> Perfiles", cmd: "role"},
		@endif
		
		@if(Sentinel::hasAccess('user.update'))
		{title: "<i class='fa fa fa-pencil'></i> Modificar", cmd: "edit"},
		@endif
	  
		@if(Sentinel::hasAccess('user.delete'))
		{title: "<i class='fa fa-times'></i> Eliminar", cmd: "drop"}
		@endif
                ],
                select: function(event, ui) {
                    switch(ui.cmd) {
			case "role":
                                $(ui.target)
                                    .parents('tr')
                                    .find('.actionOption1Item')
                                    .trigger('click');
				break;
                        case "edit":
                            $(ui.target)
                                .parents('tr')
                                .find('.editItem')
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



    });
</script>
	
