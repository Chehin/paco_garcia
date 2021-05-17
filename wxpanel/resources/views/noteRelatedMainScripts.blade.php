<script type="text/javascript">
//Notes related
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
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.noteRelated.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.noteRelated.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.noteRelated.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.noteRelated.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.noteRelated.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.noteRelated.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.noteRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.noteRelated.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.noteRelated.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.noteRelated.DELETE.verb;
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
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas sub2 dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
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
                { "mData": "titulo", "aTargets":[0], "sortable":true},
                { "mData": "seccion", "aTargets":[1], "sortable":false },
				{ "mData": "", "aTargets":[2], "sortable":false , "mRender":function(value, type, full){
											return '<a data-id="'+ full.id +'" class="btn btn-xs btn-danger actionOption1" href="javascript:void(0);">Quitar Relación</a>';
						
										}
				
				}
              ],
            "fnServerData":function (sSource, aoData, fnCallback){
                //Extra params to Server
                aoData.push(
					{
					name:'parent_id', value:{{ $item->id_nota }}
					},
					{
					name:'parent_resource', value:"{{ $aViewData['resource'] }}"
					}
				);

                appCustom.ajaxRest(
                    sSource, 
                    resourceReq.index.verb,
                    aoData, 
                    fnCallback
                );
            }
        });
		var onActionOption1 = function(e) {
            
            var id = e.target.dataset.id;
            
            appCustom.ajaxRest(
                resourceReq.delete.url(id), 
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


//Notes
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
        resourceReq.index.url = appCustom.{{ $aViewData['resource'] }}.note.INDEX.url;
        resourceReq.index.verb = appCustom.{{ $aViewData['resource'] }}.note.INDEX.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.noteRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{ $aViewData['resource'] }}.noteRelated.UPDATE.verb;

        pageSetUp();
		//grid
        var resourceTable = $('#' + resourceTableId).dataTable({
            "scrollY": "200px",
            "scrollX" : true,
            "scrollCollapse": true,
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas sub3 dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
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

                var select = $('<select><option value="">Sección</option><option value="Etiquetas">Etiquetas</option><option value="Productos">Productos</option><option value="Notas">Notas</option></select>');
                $('.sub3.filtro-mas').append( select );
                $('.sub3.filtro-mas select').on('change', onChangeFiltroMas1);
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
                { "mData": "titulo", "aTargets":[0], "sortable":true},
                { "mData": "seccion", "aTargets":[1], "sortable":false },
				{ "mData": "", "aTargets":[2], "sortable":false , "mRender":function(value, type, full){
											return '<a data-id1="{{ $item->id_nota }}" data-id2="'+full.id+'" data-resource="'+full.resource+'" class="btn btn-xs btn-warning actionOption1" href="javascript:void(0);">Relacionar</a>';
						
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
		
		var onChangeFiltroMas1 = function(){
            $('#' + resourceTableId).dataTable().fnFilter($(this).val() ,0);
        }
		var onActionOption1 = function(e) {
            
            var id1 = e.target.dataset.id1;
			var id2 = e.target.dataset.id2;
			var related_resource = e.target.dataset.resource;
            
            appCustom.ajaxRest(
                resourceReq.update.url(id1), 
                resourceReq.update.verb,
                {parent_resource:'{{ $aViewData['resource'] }}',related_id:id2,related_resource:related_resource}, 
                function(result) {
                    if (0 == result.status) {
                       appCustom.smallBox('ok', '');
						$('#' + 'sub2_{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
						$('#' + '{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
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
	


