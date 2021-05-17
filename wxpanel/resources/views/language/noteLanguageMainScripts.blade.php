<script type="text/javascript">
		
    $(document).ready(function() {
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId = '{{ $aViewData['resource'] }}_lang_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'FormLang' }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.index.url = appCustom.{{ $aViewData['resource'] }}.language.INDEX.url;
        resourceReq.index.verb = appCustom.{{ $aViewData['resource'] }}.language.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{ $aViewData['resource'] }}.language.STORE.url;
        resourceReq.store.verb = appCustom.{{ $aViewData['resource'] }}.language.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.language.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{ $aViewData['resource'] }}.language.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.language.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{ $aViewData['resource'] }}.language.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{ $aViewData['resource'] }}.language.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{ $aViewData['resource'] }}.language.DELETE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();

        $("button#save", resourceDOM.$form).click(function(e){
            if (resourceDOM.formValidate.form()) {
                var mode = $(this).attr('data-mode');
                var data = '';
                if ('edit' === mode) {
                    var id = $(this).attr('data-id');
                }
				
				//summernote
                $('#texto', resourceDOM.$form).val($('#textoBox', resourceDOM.$form).code());

                data += resourceDOM.$form.serialize() + '&';

                appCustom.ajaxRest(
                    ('add' === mode) ?  resourceReq.store.url : resourceReq.update.url(id),
                    ('add' === mode) ? resourceReq.store.verb : resourceReq.update.verb,
                    data,
                    function(response){
                        if (0 == response.status) {
                             $('#cancel', resourceDOM.$form)
                                .addClass('hidden')
                            ;

                            $('#save', resourceDOM.$form)
                                .attr('data-mode', 'add')
                                .attr('data-id', '')
                            ;

                            appCustom.smallBox('ok','');
                             $('#' + resourceTableId).dataTable().fnStandingRedraw();
							 
							 clearForm();
                        } else {
                             appCustom.smallBox(
                                 'nok',
                                 response.msg,
                                 null, 
                                 'NO_TIME_OUT'
                             );
                        }
                    }
                );
            }

        });

        $("button#cancel", resourceDOM.$form).click(function(e){
            $('#cancel', resourceDOM.$form)
                .addClass('hidden')
            ;

            $('#save', resourceDOM.$form)
                .attr('data-mode', 'add')
                .attr('data-id', '')
            ;
			clearForm();
			
        });
		
		//summernote
		$('#textoBox', resourceDOM.$form).summernote({
			height: 200,
			focus: false,
			tabsize: 2
		});

        pageSetUp();
        var resourceTable = $('#' + resourceTableId).dataTable({
            "scrollY": "400px",
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
                $(row).find('.actionEditItem', $('#' + resourceTableId)).click(onEditAction);
                $(row).find('.actionDeleteItem' , $('#' + resourceTableId)).click(onDropAction);
                $(row).find('.onoffswitch input', $('#' + resourceTableId)).on('change', onEnableAction);
            },
            "aoColumnDefs": [
                { "mData": "idioma", "aTargets":[0], "sortable":true },
                { "mData": "titulo", "aTargets":[1], "sortable":true },
				{ "mData": "sumario", "aTargets":[2], "sortable":true },
				{ "mData": "updated_at", "aTargets":[3], "sortable":true },
                { "mData": "", "aTargets":[4], "width": "25%", "sortable":false, "mRender": function(value, type, full){

                                        var checked = ( 1 == full.habilitado) ? 'checked' : '';

                                        var enableOption = '<div class="btn-group" style="margin-right:30px">'+
                                                                '<span class="onoffswitch" title="Habilitar / Deshabilitar">'+
                                                                        '<input type="checkbox" name="'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked +' id="'+ resourceTableId + '_activeItem_'+full.id+'">'+
                                                                        '<label class="onoffswitch-label" for="'+ resourceTableId + '_activeItem_'+full.id+'"> '+
                                                                                '<span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>'+ 
                                                                                '<span class="onoffswitch-switch"></span>' +
                                                                        '</label>'+
                                                                '</span>'+
                                                            '</div>'
                                                            ;

                                        var moreOptions = '<div class="btn-group">'+
                                                                '<a class="btn btn-xs btn-default" title="Edit" data-toggle="dropdown"  href="javascript:void(0);">'+
                                                                        '<i class="fa fa-cog"></i></a>'+
                                                                '<ul class="dropdown-menu" style="left:auto; right:0">'+
                                                                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                                                                        '<li>'+
                                                                                '<a class="actionEditItem" data-id="'+full.id+'">Modificar</span>'+
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

                                        return enableOption + moreOptions;


                                     }
                }
              ],
            "fnServerData":function (sSource, aoData, fnCallback){
                //Extra params to Server
                aoData.push({name:'id', value:{{ $item->id_nota }} });

                appCustom.ajaxRest(
                    sSource, 
                    resourceReq.index.verb,
                    aoData, 
                    fnCallback
                );
            }
        });
            
            
            
        var onEditAction = function(e) {
            
            var id = e.target.dataset.id;
            
            appCustom.ajaxRest(
                resourceReq.edit.url(id), 
                resourceReq.edit.verb,
                null, 
                function(result) {
                    if (0 == result.status) {
                        var data = result.data;
                        
                        $('[name=id_idioma]', resourceDOM.$form).val(data.id_idioma);
                        $('[name=titulo]', resourceDOM.$form).val(data.titulo);
						$('[name=sumario]', resourceDOM.$form).val(data.sumario);
						$('[name=keyword]', resourceDOM.$form).val(data.keyword);
						$('#textoBox', resourceDOM.$form).code(data.texto);
                        
                        $('#cancel', resourceDOM.$form)
                            .removeClass('hidden')
                        ;
                        
                        $('#save', resourceDOM.$form)
                            .attr('data-mode', 'edit')
                            .attr('data-id', data.id)
                        ;
                        
                        
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
		//Needs to create another object!
		//https://github.com/mar10/jquery-ui-contextmenu/wiki#howto-bind-different-contextmenus-to-the-same-dom-element
		$.widget("moogle.contextmenu_lang", $.moogle.contextmenu, {});
        $(document).contextmenu_lang({
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
                        ui.menu.zIndex(999999);
                }
          });
		  
		  
		function clearForm() {
			document.getElementById(formHTMLId).reset();
			$('#textoBox', resourceDOM.$form).code('');
		}



    });
    
    
</script>
	


