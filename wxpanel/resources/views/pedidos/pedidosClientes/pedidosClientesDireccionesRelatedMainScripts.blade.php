<script type="text/javascript">
        
    $(document).ready(function() { 
        
        //DOM Settings
        var resourceDOM = {};
        var resourceTableId = 'sub1_{{ $aViewData['resource'] }}_datatable_tabletools';
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        var dtWrapper = '#' + resourceTableId + '_wrapper';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.direccionesRelated.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.direccionesRelated.INDEX.verb;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.direccionesRelated.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.direccionesRelated.STORE.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.direccionesRelated.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.direccionesRelated.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.direccionesRelated.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.direccionesRelated.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.direccionesRelated.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.direccionesRelated.DELETE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();

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
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {
                $(row).find('.actionEditItem', $('#' + resourceTableId)).click(onEditAction);
                $(row).find('.actionDeleteItem' , $('#' + resourceTableId)).click(onDropAction);
                // $(row).find('.onoffswitch[name=enable0] input', $('#' + resourceTableId)).on('change', onEnableAction);
                // $(row).find('.onoffswitch[name=enable1] input', $('#' + resourceTableId)).on('change', onEnableAction1);
            },
            "aoColumnDefs": [
                { "mData": "titulo", "aTargets":[0], "sortable":true },
                { "mData": "", "aTargets":[1], "sortable":true, "mRender": function(value, type, full){
                    return full.direccion+' '+full.numero;
                }
                },
                { "mData": "provincia", "aTargets":[2], "sortable":true },
                { "mData": "ciudad", "aTargets":[3], "sortable":true },
                { "mData": "cp", "aTargets":[4], "sortable":true },
                { "mData": "informacion_adicional", "aTargets":[5], "sortable":true },
                { "mData": "telefono", "aTargets":[6], "sortable":true },
                { "mData": "", "aTargets":[7], "sortable":false, "mRender": function(value, type, full){

                                        var checked = ( 1 == full.habilitado) ? 'checked' : '';
                                        var checked1 = ( 1 == full.destacada) ? 'checked' : '';

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

                                        return moreOptions;


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
                        
                        $('[name=direccion]', resourceDOM.$form).val(data.direccion);
                        $('[name=numero]', resourceDOM.$form).val(data.numero);
                        $('[name=piso]', resourceDOM.$form).val(data.piso);
                        $('[name=departamento]', resourceDOM.$form).val(data.departamento);
                        $('[name=id_provincia]', resourceDOM.$form).val(data.id_provincia);
                        $('[name=ciudad]', resourceDOM.$form).val(data.ciudad);
                        $('[name=cp]', resourceDOM.$form).val(data.cp);
                        $('[name=telefono]', resourceDOM.$form).val(data.telefono);
                        $('[name=titulo]', resourceDOM.$form).val(data.titulo);
                        $('[name=informacion_adicional]', resourceDOM.$form).val(data.informacion_adicional);
                        
                        $('#cancelDir', resourceDOM.$form)
                            .removeClass('hidden')
                        ;

                        $('#saveDir', resourceDOM.$form)
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
                                $('#' + resourceTableId).dataTable().fnStandingRedraw();
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

        //Context menu
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

        var direccionesRelatedForm = direccionesRelatedForm || {};
        var $formContainer = $("#{{ $aViewData['resource'] }}" + '_formContainer');
        
        direccionesRelatedForm.$form = $("form#pedidosClientesForm", $formContainer);

        direccionesRelatedForm.formValidate = direccionesRelatedForm.$form.validate();

        $("button#saveDir", resourceDOM.$form).click(function(e){
            var mode = $(this).attr('data-mode');
            if ('edit' === mode) {
                var id = $(this).attr('data-id');
            }
            if (direccionesRelatedForm.formValidate.form()) {
                var data = '';                
                
                data += resourceDOM.$form.serialize() + '&';

                appCustom.ajaxRest(
                    ('add' === mode) ? resourceReq.store.url : resourceReq.update.url(id),
                    ('add' === mode) ? resourceReq.store.verb : resourceReq.update.verb,
                    data,
                    function(response){
                        if (0 == response.status) {

                            cleanForm();

                            appCustom.smallBox('ok','');
                                
                            $('#' + resourceTableId).dataTable().fnStandingRedraw();
                            //redraw resource (background) table
                            $('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw();

                            $('#saveDir', resourceDOM.$form)
                                .attr('data-mode', 'add')
                                .attr('data-id', '')
                            ;
                            
                        } else {
                            var type = 'nok';
                            if (2 == response.status) {
                                type = 'warn';
                            }
                            
                             appCustom.smallBox(
                                 type,
                                 response.msg,
                                 null, 
                                 'NO_TIME_OUT'
                             );
                        }
                    }
                );
            }

        });

        $("button#cancelDir", resourceDOM.$form).click(function(e){
            $('#cancelDir', resourceDOM.$form)
                .addClass('hidden')
            ;

            $('#saveDir', resourceDOM.$form)
                .attr('data-mode', 'add')
                .attr('data-id', '')
            ;
            
            cleanForm();
            $('.toggDiv', resourceDOM.$form).toggle(false);
        });
        
        $('#newDir').click(function(){
            $('.toggDiv').toggle();
        });

        function cleanForm(preserveData) {
            
            if (!preserveData) {
            document.getElementById(formHTMLId).reset();
            }
            
        }

});
    
</script>