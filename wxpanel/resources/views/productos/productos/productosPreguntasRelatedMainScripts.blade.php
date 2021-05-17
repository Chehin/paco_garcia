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
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.productosPreguntas.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.productosPreguntas.INDEX.verb;
        
        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosPreguntas.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.productosPreguntas.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosPreguntas.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.productosPreguntas.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.productosPreguntas.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.productosPreguntas.DELETE.verb;
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
                $(row).find('.actionResponder', $('#' + resourceTableId)).click(onEditAction);
            },
            "aoColumnDefs": [
                { "mData": "nickname_meli", "aTargets":[0], "sortable":true },
                { "mData": "pregunta_meli", "aTargets":[1], "sortable":true },
                { "mData": "fecha_pregunta", "aTargets":[2], "sortable":true, "mRender": function(value, type, full){
                    return moment(value, "YYYY-MM-DD").format("DD/MM/YYYY");
                } 
                },
                { "mData": "estado", "aTargets":[3], "sortable":true, "mRender": function(value, type, full){
                    var estado_pregunta = (1 == full.estado) ? 'CONTESTADA' : 'SIN RESPUESTA';
                    return estado_pregunta;
                } 
                },
                { "mData": "estado", "aTargets":[4], "sortable":false, "mRender": function(value, type, full){
                    var respusta = '';
                    if (full.estado == 1) {
                        respuesta = '<a class="actionResponder" data-id="'+full.id+'">Ver</a>';
                    } else {
                        respuesta = '<a class="actionResponder" data-id="'+full.id+'">Responder</a>';
                    }
                    return respuesta;
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
                        
                        $('[name=pregunta]', resourceDOM.$form).val(data.pregunta_meli);
                        $('[name=respuesta]', resourceDOM.$form).val(data.respuesta_meli);
                        
                        $('#cancelRespuesta', resourceDOM.$form)
                            .removeClass('hidden')
                        ;
                        if (data.estado == 1) {
                            $('#saveRespuesta', resourceDOM.$form)
                                .addClass('hidden')
                            ;
                        }
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

        var preguntasRelatedForm = preguntasRelatedForm || {};
        var $formContainer = $("#{{ $aViewData['resource'] }}" + '_formContainer');
        
        preguntasRelatedForm.$form = $("form#productosForm", $formContainer);

        preguntasRelatedForm.formValidate = preguntasRelatedForm.$form.validate();

        $("button#saveRespuesta", resourceDOM.$form).click(function(e){
            var mode = $(this).attr('data-mode');
            if ('edit' === mode) {
                var id = $(this).attr('data-id');
            }
            if (preguntasRelatedForm.formValidate.form()) {
                var data = '';                
                
                data += resourceDOM.$form.serialize() + '&';

                appCustom.ajaxRest(
                    resourceReq.update.url(id),
                    resourceReq.update.verb,
                    data,
                    function(response){
                        if (0 == response.status) {

                            cleanForm();

                            appCustom.smallBox('ok','');
                                
                            $('#' + resourceTableId).dataTable().fnStandingRedraw();
                            //redraw resource (background) table
                            $('#' + resourceTableId.replace('sub1_','')).dataTable().fnStandingRedraw()
                            
                            $('.toggDiv').toggle();

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

        $("button#cancelRespuesta", resourceDOM.$form).click(function(e){
            $('#cancelPrecio', resourceDOM.$form)
                .addClass('hidden')
            ;
            
            cleanForm();

            $('.toggDiv').toggle();
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