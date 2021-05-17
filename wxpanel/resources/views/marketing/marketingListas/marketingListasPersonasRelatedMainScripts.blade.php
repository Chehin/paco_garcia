<script type="text/javascript">
        
    $(document).ready(function() { 
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId_1 = 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        var dtWrapper = '#' + resourceTableId_1 + '_wrapper';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.delete = {};
        resourceReq.quitarPersonas = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.personasRelated.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.personasRelated.INDEX.verb;       
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.personasRelated.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.personasRelated.DELETE.verb;
        
        resourceReq.quitarPersonas.url = appCustom.{{$aViewData['resource']}}.personasRelated.QUITARPERSONAS.url;
        resourceReq.quitarPersonas.verb = appCustom.{{$aViewData['resource']}}.personasRelated.QUITARPERSONAS.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();       

        pageSetUp();
        //grid
        var resourceTable = $('#' + resourceTableId_1).dataTable({
            "scrollX" : true,
            "scrollCollapse": true,
            "lengthMenu": [ 100, 125, 150, 175, 200 ],
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'l <'filtro-custom'f><'filtro-mas dataTables_length'><'btn_wr_1 dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
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
                
                // agregar contacto a lista					
                var $img = $('<button id="quitarLista" disabled class="btn btn-primary" title="Quitar de la lista"><i class="fa fa-arrow-down"></i> Quitar de la lista</button>');
                $img.on('click', onQuitarLista);

                $('.btn_wr_1')
                    .css('left', function(i, v) {
                        return (parseFloat(v) - 210) + 'px';
                        }
                    )
                    .css('width','50px')
                ;
                $('.btn_wr_1').append($img);
				// fin agregar contacto a lista
            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
                $(row).find('.actionOption1', $('#' + resourceTableId_1)).click(onActionOption1);
                $(row).find('.checkItem_1', $('#' + resourceTableId_1)).on('change', addItem_1);
            },
            "aoColumnDefs": [
                { "mData": "", "aTargets":[0], "sortable":false, "mRender": function(value, type, full){
                        var checked = '';

                        if (itemIds_1.indexOf(full.id) >= 0) {
                            checked = 'checked';
                        }

                        return '<input '+checked+' data-id="'+full.id+'" class="checkItem_1" type="checkbox">';
                    }
				},
                { "mData": "nombre", "aTargets":[1], "sortable":true },
                { "mData": "email", "aTargets":[2], "sortable":true },
                { "mData": "created_at", "aTargets":[3], "sortable":true, "mRender": function(value, type, full){
                        if(value){
                            return moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
                            }else{
                            return '';
                        }
                    }
                },
                { "mData": "", "aTargets":[4], "sortable":false , "mRender":function(value, type, full){
                        return '<a data-id1="'+ full.id_lista +'" data-id2="'+full.id_persona+'" class="btn btn-xs btn-danger actionOption1" href="javascript:void(0);">Quitar</a>';

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
						$('#' + resourceTableId_1).dataTable().fnStandingRedraw();
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
        
        //checkbox handling
		var itemIds_1 = [];
		function addItem_1() {
			var id = $(this).data('id');
			if ($(this).is(':checked')) {
				itemIds_1.push(id);
                $('#quitarLista').prop('disabled',false);
			} else {
				itemIds_1.splice(itemIds_1.indexOf(id), 1);
                $('#checkAll_1').prop('checked', false);
			}            			
			if ($('.checkItem_1:checked', $('#' + resourceTableId_1)).length == 0) {
                $('#quitarLista').prop('disabled',true);                
            }
		}
        
        $('table#'+resourceTableId_1+' #checkAll_1').change(function(){
			var check = true;
            
            if (!this.checked) {
                check = false;
                $('#quitarLista').prop('disabled',true);
            } else {
                $('#quitarLista').prop('disabled',false);
            }
			
			$('.checkItem_1', $('#' + resourceTableId_1)).each(function(){
				var id = $(this).data('id');
				if (check) {
                    if (($(this).is(':checked')) == false) {
                        itemIds_1.push(id);
                        $(this).prop('checked', check);
                    }
				} else {
                    $(this).prop('checked', check);
					itemIds_1.splice(itemIds_1.indexOf(id), 1);
				}
			});
		});
        
        function checkCheckAll() {
			var check = true;
            if ($('.checkItem_1', $('#' + resourceTableId_1)).length !== $('.checkItem_1:checked', $('#' + resourceTableId_1)).length) {
                check = false;                
            }            
		}
        
        function onQuitarLista(){			
			var data = {id:{{ $item->id }}, ids:JSON.stringify(itemIds_1)};
			console.log(data);
			appCustom.ajaxRest(
                resourceReq.quitarPersonas.url, 
                resourceReq.quitarPersonas.verb,
                data, 
                function(result) {
                    if (0 == result.status) {                        
                        appCustom.smallBox('ok', '');
                        $('#checkAll_1').prop('checked', false);
                        $('.checkItem_1', $('#' + resourceTableId_1)).prop('checked', false);
                        if ($('.checkItem_1:checked', $('#' + resourceTableId_1)).length == 0) {
                            $('#quitarLista').prop('disabled',true);
                            itemIds_1 = [];
                        }
						$('#' + 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
                    } else {
                        appCustom.smallBox(
                            'nok', 
                            result.msg, 
                            null, 
                            'NO_TIME_OUT'
                        );
                    }
                }
            );
		}
    });
    
    // Personas
    $(document).ready(function() {
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId_2 = 'sub2_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
		resourceReq.update = {};
        resourceReq.index.url = appCustom.marketingPersonas.INDEX.url;
        resourceReq.index.verb = appCustom.marketingPersonas.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.personasRelated.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.personasRelated.STORE.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.personasRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{ $aViewData['resource'] }}.personasRelated.UPDATE.verb;                

        pageSetUp();
		//grid
        var resourceTable = $('#' + resourceTableId_2).dataTable({
            "scrollY": "200px",
            "scrollX" : true,
            "scrollCollapse": true,
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'l<'filtro-custom'f><'filtro-mas dataTables_length'><'btn_wr_2 dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
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
                
                // agregar contacto a lista					
                var $img = $('<button id="agregarLista" disabled class="btn btn-primary" title="Agregar a la lista"><i class="fa fa-arrow-up"></i> Agregar a la lista</button>');
                $img.on('click', onAgregarLista);

                $('.btn_wr_2')
                    .css('left', function(i, v) {
                        return (parseFloat(v) - 210) + 'px';
                        }
                    )
                    .css('width','50px')
                ;
                $('.btn_wr_2').append($img);
				// fin agregar contacto a lista
            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
				$(row).find('.actionOption1', $('#' + resourceTableId_2)).click(onActionOption1);
                $(row).find('.checkItem_2', $('#' + resourceTableId_2)).on('change', addItem_2);
            },
            "aoColumnDefs": [
                { "mData": "", "aTargets":[0], "sortable":false, "mRender": function(value, type, full){
                        var checked = '';

                        if (itemIds_2.indexOf(full.id) >= 0) {
                            checked = 'checked';
                        }

                        return '<input '+checked+' data-id="'+full.id+'" class="checkItem_2" type="checkbox">';
                    }
				},
                { "mData": "contacto", "aTargets":[1], "sortable":true},
                { "mData": "email", "aTargets":[2], "sortable":false },
                { "mData": "created_at", "aTargets":[3], "sortable":true, "mRender": function(value, type, full){
                        if(value){
                            return moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
                            }else{
                            return '';
                        }
                    }
                },
				{ "mData": "", "aTargets":[4], "sortable":false , "mRender":function(value, type, full){
                        return '<a data-id1="{{ $item->id }}" data-id2="'+full.id+'" class="btn btn-xs btn-warning actionOption1" href="javascript:void(0);">Agregar</a>';
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
						$('#' + 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
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
        
        //checkbox handling
		var itemIds_2 = [];
		function addItem_2() {
			var id = $(this).data('id');
			if ($(this).is(':checked')) {
				itemIds_2.push(id);
                $('#agregarLista').prop('disabled',false);
			} else {
				itemIds_2.splice(itemIds_2.indexOf(id), 1);
                $('#checkAll_2').prop('checked', false);
			}            			
			if ($('.checkItem_2:checked', $('#' + resourceTableId_2)).length == 0) {
                $('#agregarLista').prop('disabled',true);                
            }
		}
        
        $('table#'+resourceTableId_2+' #checkAll_2').change(function(){
			var check = true;
            
            if (!this.checked) {
                check = false;
                $('#agregarLista').prop('disabled',true);
            } else {
                $('#agregarLista').prop('disabled',false);
            }
			
			$('.checkItem_2', $('#' + resourceTableId_2)).each(function(){
				var id = $(this).data('id');
				if (check) {
                    if (($(this).is(':checked')) == false) {
                        itemIds_2.push(id);
                        $(this).prop('checked', check);
                    }
				} else {
                    $(this).prop('checked', check);
					itemIds_2.splice(itemIds_2.indexOf(id), 1);
				}
			});
		});
        
        function checkCheckAll() {
			var check = true;
            if ($('.checkItem_2', $('#' + resourceTableId_2)).length !== $('.checkItem_2:checked', $('#' + resourceTableId_2)).length) {
                check = false;                
            }            
		}
        
        function onAgregarLista(){
			
			var data = {id:{{ $item->id }}, ids:JSON.stringify(itemIds_2)};
			console.log(data);
			appCustom.ajaxRest(
                resourceReq.store.url, 
                resourceReq.store.verb,
                data, 
                function(result) {
                    if (0 == result.status) {                        
                        appCustom.smallBox('ok', '');
                        $('#checkAll_2').prop('checked', false);
                        $('.checkItem_2', $('#' + resourceTableId_2)).prop('checked', false);
                        if ($('.checkItem_2:checked', $('#' + resourceTableId_2)).length == 0) {
                            $('#agregarLista').prop('disabled',true);
                            itemIds_2 = [];
                        }
						$('#' + 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools').dataTable().fnStandingRedraw();
                    } else {
                        appCustom.smallBox(
                            'nok', 
                            result.msg, 
                            null, 
                            'NO_TIME_OUT'
                        );
                    }
                }
            );
		}
    });
    
</script>