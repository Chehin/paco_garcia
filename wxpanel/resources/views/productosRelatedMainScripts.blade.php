<script type="text/javascript">
//Productos related
$(document).ready(function() {
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId = 'sub2_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.productosRelated.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.productosRelated.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.productosRelated.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.productosRelated.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosRelated.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.productosRelated.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.productosRelated.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosRelated.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.productosRelated.DELETE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);

        pageSetUp();
		//grid
        var resourceTable = $('#' + resourceTableId).dataTable({
            "scrollY": "200px",
            "scrollX" : true,
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
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
				$(row).find('.actionOption1', $('#' + resourceTableId)).click(onActionOption1);
            },
            "aoColumnDefs": [
                { "mData": "nombre", "aTargets":[0], "sortable":true },
                { "mData": "rubro", "aTargets":[1], "sortable":true },

				{ "mData": "", "aTargets":[2], "sortable":false , "mRender":function(value, type, full){
											return '<a data-id1="'+ full.id_principal +'" data-id2="'+full.id_secundaria+'" class="btn btn-xs btn-danger actionOption1" href="javascript:void(0);">Quitar Relación</a>';
						
										}
				
				}
              ],
            "fnServerData":function (sSource, aoData, fnCallback){
                //Extra params to Server
                aoData.push({name:'id', value:{{ $item->id }} });

                appCustom.ajaxRest(
                    sSource, 
                    resourceReq.index.verb,
                    aoData, 
                    fnCallback
                );
            }
        });
		
		var onActionOption1 = function(e) {
            
            var id1 = e.target.dataset.id1;
			var id2 = e.target.dataset.id2;
            
            appCustom.ajaxRest(
                resourceReq.delete.url(id1 + '_' + id2), 
                resourceReq.delete.verb,
                null, 
                function(result) {
                    if (0 == result.status) {
                       appCustom.smallBox('ok', '');
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
});


//Productos
$(document).ready(function() {
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId = 'sub3_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
		resourceReq.update = {};
        resourceReq.index.url = appCustom.{{ $aViewData['resource'] }}.productos.INDEX.url;
        resourceReq.index.verb = appCustom.{{ $aViewData['resource'] }}.productos.INDEX.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.productosRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{ $aViewData['resource'] }}.productosRelated.UPDATE.verb;

        pageSetUp();
		//grid
        var resourceTable = $('#' + resourceTableId).dataTable({
            "scrollY": "200px",
            "scrollX" : true,
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
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
				$(row).find('.actionOption1', $('#' + resourceTableId)).click(onActionOption1);
		
            },
            "aoColumnDefs": [
                { "mData": "nombre", "aTargets":[0], "sortable":true },
                { "mData": "rubro", "aTargets":[1], "sortable":true },

				{ "mData": "", "aTargets":[2], "sortable":false , "mRender":function(value, type, full){
											return '<a data-id1="{{ $item->id }}" data-id2="'+full.id+'" class="btn btn-xs btn-warning actionOption1" href="javascript:void(0);">Relacionar</a>';
						
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
		
		var onActionOption1 = function(e) {
            
            var id1 = e.target.dataset.id1;
			var id2 = e.target.dataset.id2;
            
            appCustom.ajaxRest(
                resourceReq.update.url(id1 + '_' + id2), 
                resourceReq.update.verb,
                null, 
                function(result) {
                    if (0 == result.status) {
                       appCustom.smallBox('ok', '');
						$('#' + 'sub2_{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
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
		
});
    
</script>