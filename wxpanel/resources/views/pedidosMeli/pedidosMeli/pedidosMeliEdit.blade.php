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
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
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
						<hr class="simple">
						
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
								<a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
							</li>
						</ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
							{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
							<div class="tab-pane fade active in" id="l1">                            
								<fieldset>
									<section>
										<div class="row">
											<label class="label col col-2">Fecha del Pedido:</label>
											<div class="col col-10">
												<label class="input">
													<i class="icon-append fa fa-calendar"></i>
													<input type="text" name="fecha_pedido" id="fecha_pedido" value="{{ ('edit' == $mode && $item->fecha_pedido) ? $item->fecha_pedido->format('d/m/Y') : '' }}" />
												</label>
											</div>
										</div>
									</section>									
									<section>
										<div class="row">
											<label class="label col col-2">Cliente:</label>
											<div class="col col-10">
												<select id="id_cliente" style="width:100%"></select>
											</div>
										</div>
									</section>
								</fieldset>
								<fieldset id="ficha_p">
									<input type="hidden" name="id_cliente" value="0"/>
									 <section class="col col-6">
										<div class="row">
											<label class="label col col-2">Apellido *:</label>
											<div class="col col-10">
												<label class="input">
													<input type="text" name="apellido" required="" class="form-control"/>
												</label>
											</div>
										</div>
									</section>
									<section class="col col-6">
										<div class="row">
											<label class="label col col-3">Nombre *:</label>
											<div class="col col-9">
												<label class="input">
													<input type="text" name="nombre" required="" class="form-control"/>
												</label>
											</div>
										</div>
									</section>                              
									<section class="col col-6">
										<div class="row">
											<label class="label col col-2">E-mail *:</label>
											<div class="col col-10">
												<label class="input">
													<input type="text" name="mail" required="" class="form-control"/>
												</label>
											</div>
										</div>
									</section>
									<section class="col col-6">
										<div class="row">
											<label class="label col col-3">Contraseña *:</label>
											<div class="col col-9">
												<label class="input">
													<input class="form-control" type="password" name="password" value="" required="" />
												</label>
											</div>
										</div>
									</section>
								</fieldset>
							
								<fieldset>
									<section>
										<div class="row">
											<label class="label col col-2">Seleccionar Productos:</label>
											<div class="col col-10">
												<select id="productos" name="productos[]" style="width:100%"></select>
											</div>
										</div>
									</section>
									<div id="cantidades"></div>
								</fieldset>
							</div>
							
							<!-- Buttons inside Form!!-->
							<div class="pull-right" style="margin-top:22px;margin-bottom: 13px;">											
								<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
								<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
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
<script src="js/appCustom_pedidosClientes.js" ></script>
<script>
	$(document).ready(function() {
		
        pageSetUp();
		// FECHA DE NACIMIENTO
        $('#fecha_pedido').datepicker({
        	changeMonth: true,
      		changeYear: true,
      		yearRange: "-100:+0",
        	dateFormat : 'dd/mm/yy',
            prevText : '<i class="fa fa-chevron-left"></i>',
            nextText : '<i class="fa fa-chevron-right"></i>',
		});
		
        @if ('edit' != $mode)
        $('#fecha_pedido').datepicker("setDate", new Date());
        @endif
		
		
		
		$("input[name=apellido]").prop('disabled', true);
		$("input[name=nombre]").prop('disabled', true);
		$("input[name=mail]").prop('disabled', true);
		$("input[name=password]").prop('disabled', true);
		$('#id_cliente').select2({
			placeholder: 'Seleccionar Cliente',
			minimumInputLength: 2,
			ajax: {
				url: "pedidosClientes/selectCliente",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page_limit: 10
					};
				},
				processResults: function (data, params) {
					params.page = 10;
					return {
						results: data.data,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			}
		}).on("select2:selecting", function(e) {
			if(e.params.args.data.id > 0){
				$("input[name=id_cliente]").val(e.params.args.data.id);//id_cliente
				$("input[name=apellido]").val(e.params.args.data.apellido);//apellido
				$("input[name=nombre]").val(e.params.args.data.nombre);//nombre
				$("input[name=mail]").val(e.params.args.data.email);//email
				$("input[name=password]").val('-------');//password
				
				$("input[name=apellido]").prop('disabled', true);
				$("input[name=nombre]").prop('disabled', true);
				$("input[name=mail]").prop('disabled', true);
				$("input[name=password]").prop('disabled', true);
			}else{
				$("input[name=id_cliente]").val(0);//id_cliente
				$("input[name=apellido]").val('');//apellido
				$("input[name=nombre]").val('');//nombre
				$("input[name=mail]").val('');//email
				$("input[name=password]").val('');//email
				
				$("input[name=apellido]").prop('disabled', false);
				$("input[name=nombre]").prop('disabled', false);
				$("input[name=mail]").prop('disabled', false);
				$("input[name=password]").prop('disabled', false);
			}
			$('#s2id_cliente .select2-choice').attr('style','');
		});
		
		$('#productos').select2({
			placeholder: 'Seleccionar Productos',
			minimumInputLength: 2,
			multiple: true,
			ajax: {
				url: "pedidos/selectProducto",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page_limit: 10
					};
				},
				processResults: function (data, params) {
					params.page = 10;
					return {
						results: data.data,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			}
		}).on("select2:selecting", function(e) {
            //al seleccionar una practica agrego el campo cantidad
            var id      = e.params.args.data.id;
            var texto   = e.params.args.data.text;
            var stock   = e.params.args.data.stock;
            var html    =
			'<section class="col-md-12" id="cantidad_'+id+'">' +
				'<label class="col-md-12">Cantidad '+texto+'. <strong>Stock '+stock+'</strong></label>' +
				'<div class="col-md-12">' +
					'<label class="input">' +
						'<input placeholder="Cantidad" class="form-control cantidades" id="cantidad[]" name="cantidad[]" type="text" value="1" min="1" >' +
					'</label>' +
                '</div>' +
            '</section>';

            $("#cantidades").append(html);
        }).on("select2:unselect", function(e) {
			var id = e.params.data.id;
            $('#cantidad_'+id).remove();
        })
	});
</script>
@include('genericEditScripts')
