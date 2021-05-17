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
            return appCustom.{{$aViewData['resource']}}.personasRelated.mainView.url(id);
		};
		
		//resourceReqOption1.edit.url
        // end settings
        
        $("button#resourceAdd")
                .attr('data-href', resourceReq.create.url);


        pageSetUp();
        var resourceTable = $('#' + resourceTableId).dataTable({
            "fixedHeader": true,
            "scrollX" : true,
			"scrollY": "400px",
            "stateSave": false, 
            "scrollCollapse": true,
			"language": {
				'thousands':'.', 
				'decimal':',', 
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
             },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f><'filtro-mas dataTables_length'><'btn-filters'>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
           "oTableTools" : {
             "aButtons" : [{
              "sExtends" : "collection",
              "sButtonText" : 'Exportar <span class="caret" />',
              "aButtons" : ["csv", "pdf"]
             }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "initComplete": function ()
            {
                $('.filtro-custom input').attr('placeholder', 'Buscar...');
            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            //"bServerSide": true,
            "bPaginate": false,
            "ordering": true,
            "order": [ 1, 'desc' ],
            "aoColumnDefs": [
                { "mData": "nombre", "aTargets":[0], "sortable":true },
                { "mData": "op_ventaTot", "aTargets":[1], "sortable":true, "sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "cnt", "aTargets":[2], "sortable":true, "className": "dt-right" },
                { "mData": "ca_ventaTot", "aTargets":[3], "sortable":true, "sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "ca_cnt", "aTargets":[4], "sortable":true,"className": "dt-right"},
                { "mData": "co_ventaTot", "aTargets":[5], "sortable":true,"sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "co_cnt", "aTargets":[6], "sortable":true,"className": "dt-right" },
                { "mData": "can_ventaTot", "aTargets":[7], "sortable":true, "sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "can_cnt", "aTargets":[8], "sortable":true,"className": "dt-right" },
                { "mData": "aa_ventaTot", "aTargets":[9], "sortable":true, "sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "aa_cnt", "aTargets":[10], "sortable":true,"className": "dt-right" },
                { "mData": "ag_ventaTot", "aTargets":[11], "sortable":true, "sWidth":"7%", "className": "dt-right", "mRender": function(value, type, full){
                        return '$ ' + appCustom.numberDecimalFormat(value);
                    } 
				},
                { "mData": "ag_cnt", "aTargets":[12], "sortable":true,"className": "dt-right" },
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
			},
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data, total, colsCnt = $(row).children().length;

				var i;
				for(i = 1;i < colsCnt; i++) {
					total = api
						.column( i )
						.data()
						.reduce( sumar, 0 );

					// Update footer
					$( api.column( i ).footer() ).html(
						(i % 2) ? '$ '+ appCustom.numberDecimalFormat(total) : total
					);
				}
				
				function sumar(total, b) {
					if (parseFloat(total) === 0) {
						total = parseFloat(b);                        
					} else {
						total = parseFloat(total) + parseFloat(b);
					};
					return total;
				}
				
			}
			
        });
		

    }); //End DOM ready
    
    
</script>

	
