<script src="js/appCustomUser.js"></script>

<script type="text/javascript">
		
    $(document).ready(function() {
        
        //Settings
        var resourceDOM = {};
        var resourceTableId = '{{ $aViewData["resource"] }}_datatable_tabletools';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.create = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
		resourceReqOption1 = {};
		resourceReqOption1.edit = {};
        
        resourceReq.index.url = appCustom.role.INDEX.url;
        resourceReq.index.verb = appCustom.role.INDEX.verb;
        
        resourceReq.create.url = appCustom.role.CREATE.url;
        
        resourceReq.store.url = appCustom.role.STORE.url;
        resourceReq.store.verb = appCustom.role.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.role.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.role.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.role.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.role.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.role.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.role.DELETE.verb;
        
		
		resourceReqOption1.edit.url = function(id){
            return appCustom.permission.EDIT.url(id);
        };
        resourceReqOption1.edit.verb = appCustom.permission.EDIT.verb;
        // end settings
        
        $("button#resourceAdd")
                .attr('data-href', resourceReq.create.url);


        pageSetUp();
        var resourceTable = $('#' + resourceTableId).dataTable({
            "scrollY": "400px",
            "scrollX" : true,
            "stateSave": true, 
            "scrollCollapse": true,
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
           "oTableTools" : {
             "aButtons" : [{
              "sExtends" : "collection",
              "sButtonText" : 'Export <span class="caret" />',
              "aButtons" : ["csv", "xls", "pdf"]
             }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "initComplete": function ()
            {
                $('.filtro-custom input').attr('placeholder', 'Buscar...');
            },
            "bProcessing" : true,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
                //$(row).find('.actionEditItem', $('#' + resourceTableId)).click(onEditAction);
                $(row).find('.actionDeleteItem' , $('#' + resourceTableId)).click(onDropAction);
                $(row).find('.onoffswitch input', $('#' + resourceTableId)).on('change', onEnableAction);
            },
            "aoColumnDefs": [
                { "mData": "name", "aTargets":[0], "sortable":true },
		{ "mData": "updated_at", "aTargets":[1], "sortable":true, "mRender": function(value, type, full){
															var ret = ''
															if (value) {
																ret = moment(value, "YYYY-MM-DD H:mm").format("DD/MM/YYYY H:mm");
															}

															return ret;
											} 
		},
                { "mData": null, "aTargets":[2], "sortable":false, "mRender": function(value, type, full){

                                        var checked = ( 1 == full.enabled) ? 'checked' : '';

                                        /*var enableOption = '<div class="btn-group" style="margin-right:30px">'+
                                                                '<span class="onoffswitch" title="Habilitar / Deshabilitar">'+
                                                                        '<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
                                                                        '<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
                                                                                '<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
                                                                                '<span class="onoffswitch-switch"></span>' +
                                                                        '</label>'+
                                                                '</span>'+
                                                            '</div>'
                                                            ;
										*/
                                        var moreOptions = '<div class="btn-group">'+
                                                                '<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
                                                                        '<i class="fa fa-cog"></i></a>'+
                                                                '<ul class="dropdown-menu" style="left:auto; right:0">'+
                                                                        @if (\Sentinel::hasAccess($aViewData['resource']  . '.update'))
                                                                        '<li>'+
                                                                                '<a class="actionEditItem" data-href="'+ resourceReq.edit.url(full.id) + '" data-toggle="modal-custom">Editar</span>'+
                                                                        '</li>'+
																		'<li>'+
                                                                                '<a class="actionOption1Item" data-href="'+ resourceReqOption1.edit.url(full.id) + '" data-toggle="modal-custom">Permisos</span>'+
                                                                        '</li>'+
                                                                        @endif
                                                                        @if (\Sentinel::hasAccess($aViewData['resource']  . '.delete'))
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
                                resourceTable
									.dataTable()
									.fnStandingRedraw();
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
                        resourceTable
							.dataTable()
							.fnStandingRedraw();
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
					@if (\Sentinel::hasAccess($aViewData['resource']  . '.update'))
                    {title: "<i class='fa fa fa-lock'></i> Permisos", cmd: "permission"},
                    @endif
                    @if (\Sentinel::hasAccess($aViewData['resource']  . '.update'))
                    {title: "<i class='fa fa fa-pencil'></i> Modificar", cmd: "edit"},
                    @endif
					@if (\Sentinel::hasAccess($aViewData['resource']  . '.delete'))
                    {title: "<i class='fa fa-times'></i> Eliminar", cmd: "drop"}
                    @endif
                    ],
                    select: function(event, ui) {
                        switch(ui.cmd) {
							case "permission":
                                $(ui.target)
                                    .parents('tr')
                                    .find('.actionOption1Item')
                                    .trigger('click');

                                break;
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
                        ui.menu.zIndex(9999);
                }
          });



    });
    
    
</script>

	
