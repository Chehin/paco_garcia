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
            "lengthMenu": [5, 10, 25, 50, 75, 100 ],
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
                { "mData": null, "aTargets":[0], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
                    return '<a href="javascript:;"><img width="50" src="'+full.foto+'" /></a>';
                }
                },
                { "mData": "codigo", "aTargets":[1], "sortable":true },
                { "mData": "nombre", "aTargets":[2], "sortable":true },
                { "mData": "marca", "aTargets":[3], "sortable":true },
                { "mData": "rubro", "aTargets":[4], "sortable":true },

				{ "mData": "", "aTargets":[5], "sortable":false , "mRender":function(value, type, full){
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
        var resourceTableId2 = 'sub3_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
		resourceReq.update = {};
        resourceReq.index.url = appCustom.{{ $aViewData['resource'] }}.INDEX.url;
        resourceReq.index.verb = appCustom.{{ $aViewData['resource'] }}.INDEX.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.productosRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{ $aViewData['resource'] }}.productosRelated.UPDATE.verb;

        pageSetUp();
		//grid
        var resourceTable2 = $('#' + resourceTableId2).dataTable({
            "scrollY": "200px",
            "lengthMenu": [5, 10, 25, 50, 75, 100 ],
            "scrollX" : true,
            "scrollCollapse": true,
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas filtros dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
           "oTableTools" : {
             "aButtons" : [{
              "sExtends" : "collection",
              "sButtonText" : 'Exportar <span class="caret" />',
              "aButtons" : ["csv", "xls", "pdf"]
             }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "initComplete": function ()
            {
                //filtro Marcas
				var $select = $('<select id="marcasRel"><option value="NULL" seleted="true">Todos las Marcas</option></select>');
				var options = JSON.parse('{!! $aViewData['aMarcas']->toJson() !!}');
				$('.filtro-custom input').attr('placeholder', 'Buscar...');
				
				$("#marcasRel").empty();
				if (options.length) {
					options.forEach(function(item){
						$select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
					});
				}
				$('.filtros').append($select);
				
				$select.change(function(){
                    resourceTable2.dataTable().fnFilter($(this).val(),5);
				});
				//end filtro Marcas

                //filtro Rubros
				var $select = $('<select id="rubrosRel"><option value="NULL" seleted="true">Todos los Rubros</option></select>');
				var options = JSON.parse('{!! $aViewData['aCustomViewData']['rubros']->toJson() !!}');
				$('.filtro-custom input').attr('placeholder', 'Buscar...');
				
				$("#rubrosRel").empty();
				if (options.length) {
					options.forEach(function(item){
						$select.append('<option value="'+item.id +'">'+item.nombre+'</option>')
					});
				}
				$('.filtros').append($select);
				
				$select.change(function(){
					resourceTable2.dataTable().fnFilter($(this).val(),1);
					var id_rubro=$(this).val();
					var optionsSub='';
					var div=$(this).parent();

					if(id_rubro=='NULL'){
						$('#fsubrubrosRel').attr('disabled', 'disabled');
					}else{
						$('#fsubrubrosRel').removeAttr('disabled');
					}

					$.ajax({
                    type:'get',
                    url:'{!!URL::to('filtroSubrubros') !!}',
                    data:{'id':id_rubro},
                    success:function(data){
                    	$("#fsubrubrosRel").empty();
                        optionsSub+='<option value="NULL" seleted="true">Elegir Subrubros</option>';
                        for(var i=0; i<data.length; i++){
                        	optionsSub+='<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
                        }
                        div.find('#fsubrubrosRel').html(" ");
                        div.find('#fsubrubrosRel').append(optionsSub);
                    },
                    error:function(){
                        console.log('error');
                    }
               		}); 
				});
				//end filtro rubros

                //filtro subrubros
				var $selectSub = $('<select id="fsubrubrosRel" disabled="disabled"><option value="NULL" seleted="true">Todos los Subrubros</option></select>');
				 
                 $('.filtros').append($selectSub);
                     
                 $selectSub.change(function(){
                         resourceTable2.dataTable().fnFilter($(this).val(),2);
                 });
                 //end filtro subrubros 


                 //filtro Stock
				var $select = $('<select><option value="NULL" seleted="true">Stock</option><option value="1">Con Stock</option><option value="0">Sin Stock</option></select>');
				
				$('.filtros').append($select);
				
				$select.change(function(){
					resourceTable2.dataTable().fnFilter($(this).val(),4);
				});
				//end filtro Stock
				//filtro mas
				var $selectMas = $('<select><option value="NULL" seleted="true">Filtrar</option><option value="1">Con Foto</option><option value="2">Sin Foto</option><option value="3">Destacados</option><option value="4">Ofertas</option><option value="5">En Mercado Libre</option></select>');
				
				$('.filtros').append($selectMas);
				$selectMas.change(function(){
					resourceTable2.dataTable().fnFilter($(this).val(),6);
				});
                $('.filtro-custom input').attr('placeholder', 'Buscar...');
            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
				$(row).find('.actionOption1', $('#' + resourceTableId2)).click(onActionOption1);
		
            },
            "aoColumnDefs": [
                { "mData": null, "aTargets":[0], "sortable":false, "sClass": "center", "mRender": function(value, type, full){
                    return '<a href="javascript:;"><img width="50" src="'+full.foto+'" /></a>';
                }
                },
                { "mData": "codigo", "aTargets":[1], "sortable":true },
                { "mData": "nombre", "aTargets":[2], "sortable":true },
                { "mData": "marca", "aTargets":[3], "sortable":true },
                { "mData": "rubro", "aTargets":[4], "sortable":true },

				{ "mData": "", "aTargets":[5], "sortable":false , "mRender":function(value, type, full){
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
            
            console.log("principal: "+id1+ " "+ id2);
            
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

        //Manejador de los filtros de texto
		var filterTxtLast = '';
		function handleFiltersText(filterTxt) {
			
			filterTxt = filterTxt.slice(0, -1);

			if (filterTxt != filterTxtLast) {
				filterTxtLast = filterTxt;

				resourceTable2.fnFilter(filterTxt);

			}
		}
		
});
    
</script>