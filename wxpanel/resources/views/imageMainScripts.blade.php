<script type="text/javascript">
		
    $(document).ready(function() { 
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId = 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.image.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.image.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.image.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.image.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.image.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.image.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.image.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.image.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.image.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.image.DELETE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);

        pageSetUp();
		//grid
        var resourceTable = $('#' + resourceTableId).dataTable({
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
            "order": [ 3, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
                $(row).find('.actionEditItem', $('#' + resourceTableId)).click(onEditAction);
                $(row).find('.actionDeleteItem' , $('#' + resourceTableId)).click(onDropAction);
                $(row).find('.onoffswitch[name=enable0] input', $('#' + resourceTableId)).on('change', onEnableAction);
				$(row).find('.onoffswitch[name=enable1] input', $('#' + resourceTableId)).on('change', onEnableAction1);
            },
            "aoColumnDefs": [
                { "mData": "imagen_file", "aTargets":[0], "sortable":false, "mRender": function(value, type, full){
						return '<a target="_blank" href="'+value+'" ><img src="'+value+'" width="85" /></a>';
					}
				},
                { "mData": "imagen", "aTargets":[1], "sortable":true },
				{ "mData": "epigrafe", "aTargets":[2], "sortable":true },
				{ "mData": "orden", "aTargets":[3], "sortable":true },
				@if($aViewData['resource']=='productos' && isset($aViewData['aColores']))
				{ "mData": "color", "aTargets":[4], "sortable":true },
                { "mData": "", "aTargets":[5], "sortable":false, "mRender": function(value, type, full){
				@else
                { "mData": "", "aTargets":[4], "sortable":false, "mRender": function(value, type, full){
				@endif

                                        var checked = ( 1 == full.habilitado) ? 'checked' : '';
										var checked1 = ( 1 == full.destacada) ? 'checked' : '';
true
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
										var enableOption1 = '<div class="btn-group" style="margin-right:30px">'+
                                                                '<span class="onoffswitch" name="enable1" title="Habilitar / Deshabilitar">'+
                                                                        '<input type="checkbox" name="enable1_'+ resourceTableId + '_activeItem_'+full.id+'" class="onoffswitch-checkbox" value="'+full.id+'" '+ checked1 +' id="enable1_'+ resourceTableId + '_activeItem_'+full.id+'">'+
                                                                        '<label class="onoffswitch-label" for="enable1_'+ resourceTableId + '_activeItem_'+full.id+'"> '+
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

                                        return enableOption + enableOption1 + moreOptions;


                                     }
                }
              ],
            "fnServerData":function (sSource, aoData, fnCallback){
                //Extra params to Server
                aoData.push({name:'resource_id', value:{{ $item->id }} }, {name:'resource', value:'{{ $aViewData['resource'] }}' });

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
			
			$('.toggDiv').toggle(true);
            
            appCustom.ajaxRest(
                resourceReq.edit.url(id), 
                resourceReq.edit.verb,
                null, 
                function(result) {
                    if (0 == result.status) {
                        var data = result.data;
                        
                        $('[name=name]', resourceDOM.$form).val(data.imagen);
                        $('[name=epigraph]', resourceDOM.$form).val(data.epigrafe);
						$('[name=order]', resourceDOM.$form).val(data.orden);
						$('[name=id_color]', resourceDOM.$form).val(data.id_color);
						
						$('.image-editor', resourceDOM.$form)
							.cropit('imageSrc', data.imagen_file);
						;
					
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
								//redraw image table
                                resourceTable.dataTable().fnStandingRedraw();
								//redraw resource (background) table
								$('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw();
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
						//redraw resource (background) table
						$('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw();
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
		
		var onEnableAction1 = function(e) {
            var id = $(e.target).val();
			var resource_id = $('input[name=resource_id]',resourceDOM.$form).val();
            var enable = ($(this).prop('checked')) ? 1:0;
            
            appCustom.ajaxRest(
                resourceReq.update.url(id), 
                resourceReq.update.verb,
                { justEnable1:'yes', enable:enable, resource_id:resource_id}, 
                function(result) {
                    if (0 == result.status) {
                        appCustom.smallBox('ok','');
                        resourceTable.dataTable().fnStandingRedraw();
						//redraw resource (background) table
						$('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw();
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
		$.widget("moogle.contextmenu_img", $.moogle.contextmenu, {});
        $(document).contextmenu_img({
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
                        ui.menu.zIndex(9999);
                }
		});

		//image crop
		$('.image-editor', resourceDOM.$form).cropit({
			//exportZoom:2,
			onImageError:function(error){
				appCustom.closeModalPreloader();
				
				$('#imageFile').val('');
				
				if (1 == error.code) {
					appCustom.smallBox(
						'nok', 
						'La imagen es demasiado pequeña (debe tener al menos ' + this._previewSize.width + 'x'+ this._previewSize.height + ')',
						'',
						'NO_TIME_OUT'
					);
				}
			},
			onImageLoading:function(){
				appCustom.openModalPreloader();
			},
			onImageLoaded:function(){
				appCustom.closeModalPreloader();
				$("button#delete", resourceDOM.$form).removeClass('hide');
			}
			
		});
		
		//save
		$("button#save", resourceDOM.$form).click(function(e){
			//create/edit?
			var mode = $(this).attr('data-mode');
			
			//validating
			if ($('#name', resourceDOM.$form).val() == '' || ($('.cropit-preview-image', resourceDOM.$form).attr('src') == '') && 'add' == mode)
			{
		
				appCustom.smallBox(
						'nok', 
						'Debe cargar una imagen y su nombre',
						'',
						'NO_TIME_OUT'
				);
				return false;
			}

			var data = '';
			if ('edit' === mode) {
				var id = $(this).attr('data-id');
			}

			//Image
			// Move cropped image data to hidden input
			var imageData = $('.image-editor', resourceDOM.$form).cropit('export');
			$('.hidden-image-data', resourceDOM.$form).val(imageData);
			//End image

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
						
						cleanForm();
						
						
						appCustom.smallBox('ok','');
						//redraw image table
						$('#' + resourceTableId).dataTable().fnStandingRedraw();
						//redraw resource (background) table
						$('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw();

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

        });

        $("button#cancel", resourceDOM.$form).click(function(e){
            $('#cancel', resourceDOM.$form)
                .addClass('hidden')
            ;

            $('#save', resourceDOM.$form)
                .attr('data-mode', 'add')
                .attr('data-id', '')
            ;
			
			cleanForm();
			$('.toggDiv', resourceDOM.$form).toggle(false);
        });
		
		$('#newImg', resourceDOM.$form).click(function(){
			$('.toggDiv').toggle();
		});
		$("button#delete", resourceDOM.$form).click(function(e){
			cleanForm(true);
			$("button#delete", resourceDOM.$form).addClass('hide');
		});
		
		function cleanForm(preserveData) {
			
			if (!preserveData) {
			document.getElementById(formHTMLId).reset();
			}
			
			
			
			$('.cropit-preview .cropit-preview-image-container img', resourceDOM.$form).attr('src', '');
			$('.image-editor', resourceDOM.$form).cropit('disable');
			$('.image-editor', resourceDOM.$form).cropit('reenable');
		}

});
	
	
    
    
</script>
	


