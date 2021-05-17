<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['item'])) ? $aViewData['item'] : null;
?>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
				&times;
			</button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> Actualizar productos Mercado Libre  
			</h6>
		</div>
		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						<!--        <p>
                            Tabs inside well and pulled right
                            <code>
							.tabs-pull-right
                            </code>
                            (Bordered Tabs)
						</p> -->
						<hr class="simple">
						
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
								<a href="#l1" data-toggle="tab">Productos disponibles</a>
							</li>
						</ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
                            {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}

							<div class="tab-pane fade active in" id="l1">
                                <div style="padding:10px 15px;">
				<div class="row">
                                    <div class="col-md-7">
                                        <h2>Seleccione los productos que desea actualizar</h2>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="pull-right">
                                            <button type="button" id="actualizar_meli" class="btn btn-primary"><i class="fa fa-upload"></i> Actualizar </button>
                                            <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
                                        </div>
                                    </div>
                                    
                                </div>
                                </div>
                                <table id="sinc_meli" class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="checkbox-inline" id="selectAll">
                                                    <i></i> 
                                                </label>
                                            </th>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item as $producto)
                                        <tr id="fila_{{$producto['id']}}">
                                            <td>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="prod[]" value="{{ $producto['id'] }}">
                                                    <i></i> 
                                                </label>
                                            </td>
                                            <td>{{ $producto['codigo'] }}</td>
                                            <td>{{ $producto['nombre'] }}</td>
                                            <td class="detail">
                                                <div class="text-primary">Disponible para actualizar</div>
                                            </td>
                                        </tr>
                                        @endforeach                                        
                                    </tbody>
                                </table>
                                
							</div>
							{{ Form::close() }}
						</div>
					</div>
					
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
        
	</div>
</div>
<script>
    $(function(){
        /* * Click on select all checkbox */
        $('#selectAll').click(function(e) {
            $('[name="prod[]"]').prop('checked', this.checked);
        });
        /* * Click on another checkbox can affect the select all checkbox */
        $('[name="prod[]"]').click(function(e) {
            if ($('[name="prod[]"]:checked').length == $('[name="prod[]"]').length || !this.checked)
            $('#selectAll').prop('checked', this.checked);
        });

        //sincronizar
        var resourceDOM = {};
        var formHTMLId = "{{$aViewData['resource'] . 'Form'}}";
        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        $("button#actualizar_meli", resourceDOM.$form).click(function(e){
            if (resourceDOM.formValidate.form()) {
                var data = $('[name="prod[]"]:checked');
                if(data.length==0){
                    appCustom.smallBox(
						'nok',
						'Debe seleccionar al menos un producto',
						null, 
						'NO_TIME_OUT'
					)	;
			
					return false;
                }
                data.each(function() {
                    updateMeli($(this).val());
                });
            }

        });
    });
    function updateMeli(id){
        var div_detail = $('tr#fila_'+id).find('td.detail');
        div_detail.html('<i class="fa fa-gear fa-1x fa-spin"></i> Actualizando...');
        appCustom.ajaxRest(
            'updatePublicacion/'+id,
            'GET',
            [],
            function(response){
                if (0 == response.status) {
                    div_detail.html('<div class="text-success"><i class="fa fa-check"></i> Producto actualizado con éxito</div>');
                    $('tr#fila_'+id).find('label.checkbox').addClass('state-disabled').find(('[name="prod[]"]')).prop('checked', false).prop('disabled', true);
                } else {
                    div_detail.html('<div class="text-danger"><i class="fa fa-exclamation"></i> Error al actualizar este producto</div>');
                }
            }
        );
    }
</script>