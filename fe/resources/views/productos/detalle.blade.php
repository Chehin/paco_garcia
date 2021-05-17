@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
	@php \Log::debug('$producto '.print_r($producto,true))  @endphp
	@php
		$nombreGT ='-';
        $marcaGT='-';
        $precioGT=0;
        $idGT=0;
		$categoriaGT='-';
	@endphp
	{{-- Para Analytics --}}
	@php
		if ($producto['id'])
             $idGT = $producto['id'];
        else
                     $id='0';

        if (isset($producto['nombre']))  $nombreGT = $producto['nombre'];
        if ($producto['marca'])  $marcaGT = $producto['marca'];
        if (isset($precios['precio_venta']) & $precios['precio_venta'])  $precioGT = $precios['precio_venta'];
        if ($categoria['rubro'])  $categoriaGT = $categoria['rubro']['rubro'];
 \Log::debug('$nombreGT '.$nombreGT.' $marcaGT'.$marcaGT)
	@endphp

<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
				<ul>
					<li class="home"><a title="Ir al inicio" href="{{ route('home') }}">Inicio</a><span>&raquo;</span></li>
                    @if($categoria['rubro'])
					<li class=""><a title="Ir a {{$categoria['rubro']['rubro']}}" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => 0, 'name' => $categoria['rubro']['rubro'], 'page' => 1])}}">{{$categoria['rubro']['rubro']}}</a><span>&raquo;</span></li>
                    @endif
					@if($categoria['subrubro'])
					<li class=""><a title="Ir a {{$categoria['subrubro']['subrubro']}}" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => $categoria['subrubro']['id'], 'name' => $categoria['subrubro']['subrubro'], 'page' => 1])}}">{{$categoria['subrubro']['subrubro']}}</a><span>&raquo;</span></li>
                    @endif
					@if($producto['marca'])
					<li class=""><a title="Ir a {{$producto['marca']}}" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => $producto['marca'], 'page' => 1])}}?marca={{$producto['id_marca']}}">{{$producto['marca']}}</a><span>&raquo;</span></li>
                    @endif
					<li><strong>{{ $producto['nombre'] }}</strong></li>
				</ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->
<!-- Main Container -->
<div class="main-container col1-layout" id="produto_detalle">
    <div class="container">
        <div class="row">
            <div class="col-main">
                <div class="product-view-area item-producto">
                    <div class="product-big-image col-xs-12 col-sm-5 col-lg-5 col-md-5">
						@if(isset($producto['oferta']) && isset($precios['oferta']))
							@if($producto['oferta']==1)<div class="icon-sale-label sale-left">-{{$precios['oferta']}}%</div>@endif
						@endif
                        <div class="large-image"> 
							@if(isset($fotos['0']['imagen_file']))
                            <a href="{{env('URL_BASE_UPLOADS').$fotos[0]['imagen_file']}}" class="cloud-zoom" id="magni_img" data-big="" data-overlay="images/magnifying_glass.png" rel="useWrapper: false, adjustY:0, adjustX:20">
								<img  class="zoom-img" src="{{env('URL_BASE_UPLOADS').$fotos[0]['imagen_file']}}" alt="{{$fotos[0]['epigrafe']}}"/>
							</a> 
							@else
								<img  class="zoom-img" src="{{env('URL_BASE').'images/img_default/th_producto.jpg'}}" />
							@endif
						</div>
					   
						<div class="flexslider flexslider-thumb">
								<ul class="previews-list slides">
								@if(isset($fotos['1']['imagen_file']))
									@foreach($fotos as $foto)
									<li>
										<a href="{{env('URL_BASE_UPLOADS').$foto['imagen_file']}}" class='cloud-zoom-gallery' rel="useZoom: 'magni_img', smallImage: '{{env('URL_BASE_UPLOADS').$foto['imagen_file']}}' ">
											<img src="{{env('URL_BASE_UPLOADS').'th_'.$foto['imagen_file']}}" alt = "{{$foto['epigrafe']}}"/>
										</a>
									</li>
									@endforeach
								@endif
								</ul>
						</div>
						<!-- end: more-images -->
						@if($producto['id_video'])
						<br/><br/>
						<iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ $producto['id_video'] }}?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe><br/><br/>
						@endif
					</div>
                    <div class="col-xs-12 col-sm-7 col-lg-7 col-md-7 product-details-area">
                        <div class="product-name">
							<h1>{{ $producto['nombre'] }}</h1>
                        </div>
                        <div class="price-box">
							@if(isset($precios['precio']))
							    @if($precios['precio']>0)
								    <p class="special-price"> <span class="price-label">Precio Especial</span> <span class="price"> {{ env('MONEDA_DEFAULT') }}{{$precios['precio']}} </span> </p>
								@endif
							@endif
							
							@if(isset($precios['precio_lista']) and isset($producto['oferta']))
							  @if($producto['oferta']==1)
							    @if($precios['precio_lista']>0)
								    <p class="old-price"> <span class="price-label">Precio Regular:</span> <span class="price"> {{ env('MONEDA_DEFAULT') }}{{$precios['precio_lista']}} </span> </p>
								@endif
							  @endif
							@endif
                        </div>
                        <div class="ratings">
							<p class="rating-links"> 
								Código: 
								<span id="codigo_prod">
								{{ isset($stockColor[0]['talles'][0]['codigo'])? $stockColor[0]['talles'][0]['codigo'] : $stockColor[0]['codigo']}}
								</span>
							</p>
							@if(isset($stockColor[0]['stock_total']))
							<p class="availability in-stock pull-right"><span>Con Stock</span></p>
							<input type="hidden" name="stock_prod" value="{{ isset($stockColor[0]['talles']['0']['stock'])?$stockColor[0]['talles']['0']['stock']:$stockColor[0]['stock_total'] }}" class="stock_prod" />
                            @else
                            <p class="availability in-stock out-of-stock pull-right"><span>Sin Stock</span></p>
                            @endif
                        </div>
                        <div class="short-description">
							<p>{{ $producto['sumario'] }}</p>
						</div>
						
						<div class="product-color-size-area">
						@if(count($relacionadosColor['productos'])>0)
                            <div class="color-area">
                                <h2 class="saider-bar-title">Colores</h2>
                                <div class="color">
									<ul id="coloresProducto">
										@foreach($relacionadosColor['productos'] as $productoC)
										<li data-color="{{ $productoC['id'] }}">
											<a title="{{$productoC['titulo']}}" href="{{route('producto',['id' => $productoC['id'],'name' => str_slug($productoC['titulo'])])}}">
												<img src="{{(isset($productoC['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').'th_'.$productoC['fotos'][0]['imagen_file']:'images/img_default/th_producto.jpg') }}" alt="{{(isset($productoC['fotos'][0]['epigrafe'])?$productoC['fotos'][0]['epigrafe']:$productoC['titulo']) }}" />
											</a>
										</li>
										@endforeach
                                    </ul>
								</div>
							</div>
						@endif

                            <div class="size-area" style="display: none;">
                                <h2 class="saider-bar-title">Talles disponibles</h2>
                                <div class="size" id="talles_div">
                                    <ul>
                                    </ul>
                                </div>
							</div>

						</div>
						

                        <div class="product-variation">
                            <form action="#" method="post">
								<div class="cart-plus-minus">
									<label for="qty">Cantidad:</label>
									<div class="numbers-row">
										<div onclick="restar_stock('#produto_detalle');" class="dec qtybutton"><i class="fa fa-minus">&nbsp;</i></div>
											
										<input type="text" name="cantidad" class="qty" id="qty" placeholder="1" value="1" onchange="cambiar_stock('#produto_detalle');">
											
										<div onclick="sumar_stock('#produto_detalle')" class="inc qtybutton"><i class="fa fa-plus">&nbsp;</i></div>
									</div>
								</div>		
								<input type="hidden" name="id" id="id_producto" value="{{ $producto['id'] }}"/>

								<input type="hidden" name="idProd" value="{{ $producto['id'] }}"/>

								<input type="hidden" name="id_marca" value="{{ $producto['id_marca'] }}"/>

								<input type="hidden" name="id_genero" value="{{ $producto['id_genero'] }}"/>
						
								<input type="hidden" name="id_color" value="0" class="color_prod" />
							
								<input type="hidden" name="id_talle" value="0" class="talle_prod" />
								
								<input type="hidden" id="nombre" name="nombre" value="{{ $producto['nombre'] }}" />
                                
                                <div class="col-xs-12 visible-xs-block"><br></div>

								<button class="button pro-add-to-cart add-to-cart" title="Agregar al carrito" type="button" onclick="addcart({!! "'".$idGT."'" !!},{!! "'".$nombreGT."'" !!},{!! "'".$marcaGT."'" !!},{!! "'". $categoriaGT."'" !!},{!!$precioGT!!})">
									<span><i class="icon-basket-loaded icons">
										</i> Agregar al carrito</span></button>

								<a class="btn-checkout1 add-to-cart" href="{{ route('procesar_pedido',['id' => 1 ]) }}">
									<i class="fa fa-check"></i>
									<span>Comprar</span>
								</a>
							</form>
						</div>



						@if(count($etiquetas)>0)
                        <div class="pro-tags">
							<div class="pro-tags-title">Tags:</div>
							@foreach($etiquetas as $etiqueta)
							<a href="{{route('productos',['id_etiqueta' => $etiqueta['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['text']), 'page' => 1])}}">{{$etiqueta['text']}}</a>,
							@endforeach
							<a class="pull-right btn btn-default" title="Calcular envío" href="#" data-toggle="modal" data-target="#modal-envio" style="margin-top: -10px;"><span><i class="fa fa-truck"></i> Calcular envío</span></a>
						</div>
						@endif

							
					
						@if(count($subRubroGeneroMarca)>0)
						<hr>
						<div class="pro-tags" >
							<div class="pro-tags-title">Tabla de detalles:</div>
							<a class="pull-right btn btn-default" title="Tabla de detalle" href="#" data-toggle="modal" data-target="#modal-tabla-detalle" style="margin-top: -10px;"><span><i class="fa fa-tags"></i> Mostrar tabla de detalle</span></a>
						</div>
						@endif

						@if(isset($producto['marca']))
						<hr>
						<div class="pro-tags" >
							<div class="pro-tags-title">Marca:
								</div>
								<a> {{$producto['marca']}} </a>
						</div>
						@endif

                        <div class="share-box">
                            <div class="title">Compartir </div>
                            @include('partials.share',['titulo' => $producto['nombre']])
                        </div>


                    </div>
                </div>
			</div>
			@if($producto['texto'])
            <div class="product-overview-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="product-tab-inner">
                                <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
                                    <li class="active"> 
										<a href="#description" data-toggle="tab"> Descripción </a> 
									</li>
                                </ul>
                                <div id="productTabContent" class="tab-content">
                                    <div class="tab-pane fade in active" id="description">
                                        <div class="std">
                                            {!! $producto['texto'] !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			@endif
        </div>
    </div>
</div>

<!-- Main Container End -->
<div class="separacion"></div>
<!-- Related Product Slider -->
@if(count($relacionados['productos'])>0)
<div class="container">
	<div class="special-products">
		<div class="page-header"><h2><span class="title-prod"> Productos relacionados </span></h2></div>
		<div class="special-products-pro">
			<div class="slider-items-products">
				<div id="special-products-slider" class="product-flexslider hidden-buttons">
					<div class="slider-items">
						@foreach($relacionados['productos'] as $producto)
						<div class="product-item">
							<div class="item-inner">
								<div class="product-thumbnail">
									@if(isset($producto['oferta']) && isset($producto['precios']['oferta']))
                                    	@if($producto['oferta']==1) <div class="icon-sale-label sale-left">-{{$producto['precios']['oferta']}}%</div> @endif
                                    @endif
									@if(isset($producto['fotos']))
									<div class="pr-img-area">
										<a title="{{$producto['titulo']}}" href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}">
										<figure>
											<img class="first-img" src="{{(isset($producto['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').'th_'.$producto['fotos'][0]['imagen_file']:'images/img_default/th_producto.jpg') }}" alt="{{(isset($producto['fotos'][0]['epigrafe'])?$producto['fotos'][0]['epigrafe']:$producto['titulo']) }}" />
											@if(isset($producto['fotos'][1]['imagen_file']))
											<img class="hover-img" src="{{ env('URL_BASE_UPLOADS').'th_'.$producto['fotos'][1]['imagen_file'] }}" alt="{{(isset($producto['fotos'][1]['epigrafe'])?$producto['fotos'][1]['epigrafe']:$producto['titulo']) }}" />
											@endif
										</figure>
										</a>
									</div>
									@endif
								</div>
								<div class="item-info">
									<div class="info-inner">
										<div class="item-title">
											<a title="{{$producto['titulo']}}" href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}">{{$producto['titulo']}}</a>
										</div>
										<div class="item-content">
											<div class="item-price">
												<div class="price-box">
												@if(isset($producto['precios']['precio']))
													@if($producto['precios']['precio']>0)
													<p class="special-price">
														<span class="price-label">Precio Especial</span>
														<span class="price">
															{{ env('MONEDA_DEFAULT') }}{{$producto['precios']['precio']}}
														</span>
													</p>
													@endif
												@endif
												@if(isset($producto['precios']['precio_lista']) and isset($producto['oferta']))
												@if($producto['oferta']==1)
													<p class="old-price">
														<span class="price-label">Precio regular:</span>
														<span class="price">
															{{ env('MONEDA_DEFAULT') }}{{$producto['precios']['precio_lista']}}
														</span>
													</p>
												@endif
												@endif
												</div>
											</div>
											<div class="pro-action">
												<a href="{{route('producto',['id' => $producto['id'],'name' => str_slug($producto['titulo'])])}}" class="add-to-cart">
													{{-- <i class="fa fa-shopping-cart"></i> --}}
													<span>Ver Detalle</span>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

<div class="separacion"></div>

<div id="modal-envio" class="modal fade" role="dialog" style="margin-top: 153px;">
		<form role="form" id="consulta_form" class="form-horizontal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close myclose" data-dismiss="modal">×</button>
						<h1 class="modal-title"><span>CONSULTAR COSTO DE ENVIO</span></h1>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<label for="nombre" class="control-label col-sm-2">Código postal:</label>
								<div class="col-sm-7">
									<input type="text" class="form-control form-consulta" id="codigo_postal" name="codigo_postal" placeholder="Ej.: 4000" autofocus required>
									{{-- <input type="hidden" id="id_producto" name="id_producto" value="{{ $producto['id'] }}"/> --}}
								</div>
								<div class="col-sm-2" style="margin-left: 17px;">
									<button type="button" value="Aceptar" id="calcular_envio" name="calcular_envio" class="button button-compare pull-right"><span>Calcular</span></button>
								</div>
								<div class="col-sm-12">	<br>	</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12" id="opciones_envio">			                                            		
							</div>
						</div>
						<div class="hidden" id="costos_envio">													
						</div>
						<div id="ajaxPreloader" class="center-block" style="display:none;">
							<img id="ajaxPreloaderImg" src="images/ajax-loader.gif" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="button button-clear" data-dismiss="modal"><span>Cerrar</span></button>
						<button type="button" value="Aceptar" id="confirma_envio" class="button button-compare" style="display:none;"><span>Aceptar</span></button>
					</div>
				</div>
			</div>
		</form>
	</div>




	@if(count($subRubroGeneroMarca)>0)

	<div id="modal-tabla-detalle" class="modal fade" role="dialog" >
		<form role="form" id="consulta_form" class="form-horizontal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close myclose" data-dismiss="modal">×</button>
						<h1 class="modal-title"><span>TABLA DE DETALLES</span></h1>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 "  style="padding-bottom:15px" >
								<select name="modal-genero" class="form-control" id="modal-genero">
									@foreach($subRubroGeneroMarca as $key=>$genero)
									<option value="{{$key}}">{{$genero['genero']}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="row text-center" style="margin-left:3px;margin-right:3px;">
							@foreach($subRubroGeneroMarca as $key=>$imagen)
								@if($key==0)
									<img  src="{{env('URL_BASE_UPLOADS').$imagen['imagen']}}" alt="" id="imgen-{{$key}}" class="im1" > 		                                            		
								@endif
									<img  src="{{env('URL_BASE_UPLOADS').$imagen['imagen']}}" alt="" id="imgen-{{$key}}" class="im1" hidden="hidden">
							@endforeach
						</div>
						
						<div id="ajaxPreloader" class="center-block" style="display:none;">
							<img id="ajaxPreloaderImg" src="images/ajax-loader.gif" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="button button-clear" data-dismiss="modal"><span>Cerrar</span></button>
						<button type="button" value="Aceptar" id="confirma_envio" class="button button-compare" style="display:none;"><span>Aceptar</span></button>
					</div>
				</div>
			</div>
		</form>
	</div>

@endif


@stop
@section('javascript')

<!--cloud-zoom js --> 
<script type="text/javascript" src="js/cloud-zoom.js"></script>
<script type="text/javascript" src="js/jquery.magnifying-zoom.js"></script>

<script type="text/javascript" src="js/jquery.flexslider.js"></script> 
<script>
	

	function sumar_stock(content){
		var stock = parseInt($(content).find('.stock_prod').val());
		var qty = parseInt($(content).find('#qty').val()); 
		if( !isNaN( qty ) & qty < stock) $(content).find('#qty').val(qty+1);
		return false;
	}
	function restar_stock(content){
		var qty = parseInt($(content).find('#qty').val()); 
		if( !isNaN( qty ) & qty > 1 ) $(content).find('#qty').val(qty-1);
		return false;
	}
	function cambiar_stock(content){
		var stock = parseInt($(content).find('.stock_prod').val());
		var _this = parseInt($(content).find('#qty').val());
		if(_this>stock){ 
			$(content).find('#qty').val(stock); 
			}else{
			if(_this<1 || !$.isNumeric(_this)){ 
				$(content).find('#qty').val(1);
				}else{
				$(content).find('#qty').val(_this);
			}
		}
	}
	function buscarCambioColor(id_producto, id_color, marca, genero){
		$(document).ready(function() {
			$('.page-loader.dark').fadeIn();
			productoReady();
			$("#talles_div li:first").click();

			$.ajax({
				dataType: 'json',
				type: "GET",
				url: '{!!URL::route('ajax/cambioColor')!!}',
				data: {'id_producto': id_producto, 'id_color': id_color, 'id_marca': marca, 'id_genero': genero}
			})
			.done(function( items ) {
				$('.page-loader.dark').fadeOut();
				var talles = items.talles[0].talles?items.talles[0].talles:'';
				var fotos = items.fotos;
				$('#talles_div ul').html('');
				//talles
				jQuery.each( talles, function( i, val ) {
					var li = $('<li/>')
					.data('cantidad', val['cantidad'])
					.data('talle', val['id_talle'])
					.data('stock', val['stock'])
					.data('codigo', val['codigo'])
					.appendTo('#talles_div ul');
					
					$('<a/>')
					.attr('href', 'javascript:void(0)')
					.text(val['nombre'])
					.appendTo(li);

					$('.size-area').show();
				});
				//fotos
				$('.flexslider-thumb').hide();
				$('.previews-list.slides').html('');
				if(fotos.length == 0){
					var div = $('.large-image');
					div.find('a').attr('href','images/img_default/th_producto.jpg').find('img').attr('src','images/img_default/th_producto.jpg')
					
					jQuery("#magni_img").attr("data-big", 'images/img_default/th_producto.jpg');  
					jQuery("#mlens_target_0").css('background-image','url(images/img_default/th_producto.jpg)');
				}
				jQuery.each( fotos, function( i, val ) {
					var galimg='{{env("URL_BASE_UPLOADS")}}'+val['imagen_file'];
					var galimg_th='{{env("URL_BASE_UPLOADS")}}th_'+val['imagen_file'];
					if(i==0){
						var div = $('.large-image');
						div.find('a').attr('href',galimg).find('img').attr('src',galimg)
						
						jQuery("#magni_img").attr("data-big", galimg);  
						jQuery("#mlens_target_0").css('background-image','url('+galimg+')'); 
					}
					if(fotos.length > 1){
						var li = $('<li/>')
						.appendTo('.previews-list.slides');

						var ali = $('<a/>')
						.attr('href', galimg)
						.attr('rel', "useZoom: 'magni_img', smallImage: '"+galimg_th+"'")
						.addClass('cloud-zoom-gallery')
						.appendTo(li);
						
						$('<img/>')
						.attr('src', galimg_th)
						.appendTo(ali);
					}
				});
				jQuery(".cloud-zoom, .cloud-zoom-gallery").CloudZoom();
				jQuery(".cloud-zoom-gallery").click(clickThumb);
				if(fotos.length > 1){
					$('.flexslider-thumb').data('flexslider');
					$('.flexslider-thumb').show();
				}
				productoReady();
				$("#talles_div li:first").click();
			});
		});
	}
	function productoReady(){
		//selecciona talle
		$("#talles_div li").on('click',function(){
			$('input[name=cantidad]').val(1);
			$('.stock_prod').val($(this).data('stock'));
			$('input[name=talle_prod]').val($(this).data('talle'));
			$('input[name=color_prod]').val($(this).data('color'));
			$('input[name=id_talle]').val($(this).data('talle'));
			$('#codigo_prod').text($(this).data('codigo'));
			$("#talles_div li").removeClass('active');
			$(this).addClass('active');
		});
	}
	$(document).ready(function() {
		var id_producto = $('input[name=id]').val();
		var id_color = $('input[name=id_color]').val();
		var marca = $('input[name=id_marca]').val();
		var genero = $('input[name=id_genero]').val();
		//selecciona color
		$("#coloresProducto li").on('click',function(e){
			$('input[name=cantidad]').val(1);
			$('input[name=color_prod]').val($(this).data('color'));
			$('input[name=id_color]').val($(this).data('color'));
			$("#coloresProducto li a").removeClass('active');
			$(this).addClass('active');			
		});
		productoReady();
		$("#coloresProducto li:first-child").click();
		buscarCambioColor(id_producto, id_color, marca, genero);

		
	});

	$(document).on('change','#modal-genero',function(){
		var id =$('#modal-genero option:selected').val();
		$('.im1').attr('hidden','hidden')
		$('#imgen-'+id).removeAttr('hidden');
		
	});

	function addcart(id,nombre,marca,categoria,precio) {
		gtag('event', 'add_to_cart', {
			"items": [
				{
					"id": id,
					"name": nombre,
					"list_name": "Detalle Producto",
					"brand": marca,
					"category":categoria,
					"quantity": parseInt(jQuery(document).find('#qty').val()),
					"price": precio,

				}
			]
		});

	}

	gtag("event",  "view_item",  {
		"items": [{
			"id": {!! ($idGT)?$idGT:'-' !!},
			"name": {!!"'" .$nombreGT."'"!!},
			"list_name": {!!"'" .'Detalle de Producto'."'"!!},
			"brand": {!!isset($marcaGT)?"'" .$marcaGT."'":"'" .'-'."'"!!},
			"category":{!! ($categoriaGT)?"'".$categoriaGT."'":"'" .$categoriaGT."'"!!},
			"quantity": parseInt(jQuery(document).find('#qty').val()),
			"price": {!!isset($precioGT)?$precioGT:0!!},
		}]
	});
	console.table(dataLayer);
</script>
@stop