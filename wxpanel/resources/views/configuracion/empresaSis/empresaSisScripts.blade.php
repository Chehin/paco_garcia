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
		
		//resourceReqOption1.edit.url
        // end settings
        
        $("button#resourceAdd")
                .attr('data-href', resourceReq.create.url);


        pageSetUp();
		var indexCol= 0;
        var resourceTable = $('#' + resourceTableId).dataTable({
            "fixedHeader": true,
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
              "sButtonText" : 'Exportar <span class="caret" />',
              "aButtons" : ["csv", "xls", "pdf"]
             }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "initComplete": function ()
            {
                $('.filtro-custom input').attr('placeholder', 'Buscar...');
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
            },
            "aoColumnDefs": [
                { "mData": "name", "aTargets":[0], "sortable":true },                
                { "mData": "name_org", "aTargets":[1], "sortable":true },                
                { "mData": null, "aTargets":[2], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
                                                                                
                               return '('+full.imgCnt+') <a href="javascript:;" ><span data-href="'+resourceReqOption1.edit.url(full.id)+'" data-toggle="modal-custom" style="font-size: 160%;" class="glyphicon glyphicon-camera"></span></a>';
                            
                        }
                },
                { "mData": "", "aTargets":[3], "sortable":false, "mRender": function(value, type, full){


                                        var moreOptions = '<div class="btn-group">'+
                                                                '<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
                                                                        '<i class="fa fa-cog"></i></a>'+
                                                                '<ul class="dropdown-menu" style="left:auto; right:0">'+
																		@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                                                                        '<li>'+
                                                                                '<a class="actionEditItem" data-href="'+ resourceReq.edit.url(full.id) + '" data-toggle="modal-custom">Modificar</span>'+
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
				//init index column to re-numerate
				indexCol=0;
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
                '¡¿Borrar este elemento?!',
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

        //Context menu
        $(document).contextmenu({
                delegate: "#"+resourceTableId+" td",
                menu: [
                    @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                    {title: "<i class='fa fa fa-pencil'></i> Modificar", cmd: "edit"},
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
</script>

	
