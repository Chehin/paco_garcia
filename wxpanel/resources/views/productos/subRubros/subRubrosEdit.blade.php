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
                                    <a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
                            </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
                        <div class="tab-pane fade active in" id="l1">
                            
                            <fieldset>
								<section>
                                    <div class="row">
										<label class="label col col-2">Nombre *:</label>
                                        <div class="col col-10">
											<label class="input">
												<input type="text" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
											</label>
                                        </div>
                                    </div>
								</section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Rubro:</label>
                                        <div class="col col-10">
                                            <label class="select">
                                                <?php $toDropDown1 = $aViewData['aCustomViewData']['aRubros']->prepend('Seleccione Rubro', ''); ?>
                                                {{ Form::select(
                                                        'id_rubro',
                                                        $toDropDown1,
                                                        ("edit" == $mode) ? $item->id_rubro : '',
                                                        ['class' => 'col col-md-12', 'id' => 'id_rubro']
                                                    )
                                                }}
                                                <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Orden:</label>
                                        <div class="col col-10">
                                            <label class="input">
                                                <input type="text" name="orden" value="{{ ('edit' == $mode) ? $item->orden : '' }}" />
                                            </label>
                                        </div>
                                    </div>
                                </section>
								<section class="section-textarea">
                                    <div class="row">
										<label class="label col col-2">Descripción:</label>
                                        <div class="col col-10">
											<label class="textarea">
												<textarea row="3" name="descripcion">{{ ('edit' == $mode) ? $item->descripcion : '' }}</textarea>
											</label>
                                        </div>
                                    </div>
								</section>                                
                            </fieldset>

                            
                            <section>
                                <label class="label"><b>Equivalencias de talles</b></label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr  style="color: #222;">
                                            <th>
                                                Genero
                                            </th>
                                            <th>Marca</th>
                                            <th>Imagen</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsList">
                                        <tr>
                                            <td>
                                                <div class="col col-12">
                                                        {{ Form::select(
                                                            'id_genero', 
                                                            $aViewData['aCustomViewData']['aGeneros']->lists('genero', 'id')->prepend('Seleccionar Genero...',''), 
                                                            ("edit" == $mode) ? $item->id_genero : '', 
                                                            ['class' => 'col col-md-12', 'id' => 'id_genero']
                                                            ) 
                                                        }}
                                                </div>
                                            </td>
                                            <td>
                                               
                                                <div class="col-md-12">
                                                        {{-- <div class="col col-md-10">
                                                                <label class="select">
                                                                     <select name="" id="" class="col col-md-12">
                                                                         <option value="1">Masculino</option>
                                                                         <option value="2">Femenino</option>
                                                                         <option value="3">Juvenil</option>
                                                                         <option value="4">Ninos</option>
                                                                         <option value="5">Bebe</option>
                                                                     </select>
                                                                    <i></i>
                                                                </label>
                                                        </div> --}}
                                                        {{-- @php
                                                            echo $aViewData['aCustomViewData']['equivalenciasSubrubros']->lists('genero');
                                                        @endphp --}}

                                                        {{ Form::select(
                                                            'id_marca', 
                                                            $aViewData['aCustomViewData']['aMarcas']->lists('nombre', 'id')->prepend('Seleccionar Marca...',''), 
                                                            ("edit" == $mode) ? $item->id_marca : '', 
                                                            ['class' => 'col col-md-12', 'id' => 'id_marca']
                                                            ) 
                                                        }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-md-12">
                                                <input type="file" name="imagen"  id="imagen" value=""  accept="image/*" />
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <input type="button" class="btn btn-primary" id="itemAdd" value="Agregar">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" id="itemsStore" name="itemsStore" value="" />
                                <div id="base"></div>
                            </section>







                        </div>
						<!--	Google map-->
						<div class="row">
							<div class="col-md-12">
								<div id='map_canvas'></div>
								
							</div>
						</div>
						<!--	Google map End-->
                
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
<script>
</script>
@include('genericEditScripts')
<script>
$(function(){
    var $form = $('#{{$aViewData['resource'] . 'Form'}}');
    // console.log($form->getValues());

	var itemsStore = {}; // El objeto que almacenará
	var contadorDataJSON = 0;

	$("#itemAdd",$form).on("click", function(e){
            // marca
            var id_marca = $("#id_marca",$form).val();
            var marca = $("#id_marca  option:selected",$form).text();
            
            // genero
            var id_genero = $("#id_genero",$form).val();
            var genero = $("#id_genero  option:selected",$form).text();
            

            if(!id_marca){
                appCustom.smallBox(
					'nok', 
					"Debe seleccionar una Marca", 
					null, 
					'NO_TIME_OUT'
				);
			}else if(!$("#imagen").val()){
				appCustom.smallBox(
					'nok', 
					"Debe seleccionar una Imagen", 
					null, 
					'NO_TIME_OUT'
				);
			}else{
				readURL(document.getElementById("imagen",$form),contadorDataJSON);
				var imagen = document.getElementById("imagen",$form).files[0].name;
				var imagen_base = 0;

				var alredy = false;
				if($("#itemsStore",$form).val()==''){
					itemsStore = {};
				}else{
					var itemsStore = JSON.parse($("#itemsStore",$form).val());
					itemsStore = itemsStore[0];
					//buscar la marca ya fue elegido 

					$.each(itemsStore, function(i, v) {
						if (v.id_genero == id_genero && v.id_marca == id_marca) {
							appCustom.smallBox(
								'nok', 
								"El Genero y Marca elegida ya está cargado", 
								null, 
								'NO_TIME_OUT'
							);
							alredy = true;
						}
						
					});
				}
				if(!alredy){
					itemAddHtml([
						{id_genero:id_genero,genero:genero,marca:marca,id_marca:id_marca, imagen:imagen, imagen_base:imagen_base}
					]);
				}
			}
		});


        function itemAddHtml(a) {
			
			$.each(a, function(){
				$("#itemsList",$form).append(
					"<tr class='fila-" + contadorDataJSON + "'>"+
						"<td>" + this.genero + "</td>"+
						"<td>" + this.marca + "</td>"+
						"<td>" + (this.link?"<a style='font-size: 22px;' target='_blank' href='" + this.link + "'><i class='fa fa-picture-o'></i></a>":this.imagen) + "</td>"+
						"<td style='text-align:center;'>"+
							"<a class='itemRm' data-i='" + contadorDataJSON + "'><i class='fa fa-trash fa-lg'></i></a>"+
						"</td>"+
					"</tr>")
				;
				// Vuelvo el foco a la lista de productos
				$("#id_marca",$form).focus();
				// $("#id_genero",$form).focus();
				// Vacio los formularios
				$("#id_marca",$form).val("");
				$("#id_genero",$form).val("");
				$("#imagen",$form).val("");
				$("#imagen_base",$form).val("");

				// Guardo los datos
				itemsStore[contadorDataJSON] = {
					"id_marca" : this.id_marca,
					"id_genero" : this.id_genero,
					"imagen" : this.imagen,
					"imagen_base" : this.imagen_base,
				};
				$("#itemsStore",$form).val('');
				$("#itemsStore",$form).val('['+JSON.stringify(itemsStore)+']');

				contadorDataJSON++;
			});
            
            
            var itemsStores = $("#itemsStore",$form).val();
		}
		
		
        @if('edit' == $mode)
		
            itemAddHtml([
                @foreach($aViewData['aCustomViewData']['equivalenciasSubrubros'] as $i)
                {id_genero:"{{ $i->id_genero }}",genero:"{{ $i->genero }}",id_marca:"{{ $i->id_marca }}",marca:"{{ $i->marca }}", imagen:"{{ $i->imagen }}", link:"{{ $i->link }}"},
                @endforeach
            ]);
        
        @endif
        
        $($form).on("click",".itemRm" ,function(e){
            var itemsStore = $("#itemsStore",$form).val();
            var i = $(this).data('i');
            itemsStore = JSON.parse(itemsStore);
            delete itemsStore[0][i];
            $(".fila-"+i,$form).remove();
            $("#itemsStore",$form).val('');
            var cant = Object.keys(itemsStore[0]).length;
            if(cant>0){
                $("#itemsStore",$form).val(JSON.stringify(itemsStore));
            }
        });

        function readURL(input, contadorDataJSON) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#base').val(e.target.result);
                    itemsStore[contadorDataJSON]['imagen_base'] = e.target.result;
                    $("#itemsStore",$form).val('');
                    $("#itemsStore",$form).val('['+JSON.stringify(itemsStore)+']');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


})
</script>