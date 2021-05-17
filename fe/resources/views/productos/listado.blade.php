@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('css')
<style>
.customcheck {
    display: block;
    position: relative;
    padding-left: 30px;
    margin-bottom: 7px;
    cursor: pointer;
    font-size: 12px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
/* Hide the browser's default checkbox */
.customcheck input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}
/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    /* height: 25px;  */
    height: 21px; 
    width: 21px;
    background-color: #eee;
    border-radius: 5px;
}

/* On mouse-over, add a grey background color */
.customcheck:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.customcheck input:checked ~ .checkmark {
    background-color: #02cf32;
    border-radius: 5px;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.customcheck input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.customcheck .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>
@stop
@section('content')

<!-- Breadcrumbs -->

<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
					<li class="home"><a title="Ir al inicio" href="{{ route('home') }}">Inicio</a></li>
					@if(isset($etiqueta_array))
					<li class="menu-categoria"><span>&raquo;</span><a title="Ir a {{ $etiqueta_array['nombre'] }}" href="{{route('productos',['id_etiqueta' => $etiqueta_array['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta_array['nombre']), 'page' => 1])}}">{{ $etiqueta_array['nombre'] }}</a></li>
					@endif
					@if(isset($categorias_array['rubro']))
					<li class="menu-rubro"><span>&raquo;</span><a title="Ir a {{$categorias_array['rubro']['rubro']}}" href="{{route('productos',['id_etiqueta' => $etiqueta_array['id']?$etiqueta_array['id']:0, 'id_rubro' => $categorias_array['rubro']['id'] , 'id_subrubro' => 0, 'name' => str_slug($categorias_array['rubro']['rubro']), 'page' => 1])}}">{{$categorias_array['rubro']['rubro']}}</a></li>
					@endif
					@if(isset($categorias_array['subrubro']))
					<li class="menu-subrubro"><span>&raquo;</span><a title="Ir a {{$categorias_array['subrubro']['subrubro']}}" href="{{route('productos',['id_etiqueta' => $etiqueta_array['id']?$etiqueta_array['id']:0, 'id_rubro' => $categorias_array['rubro']['id']?$categorias_array['rubro']['id']:0 , 'id_subrubro' => $categorias_array['subrubro']['id'], 'name' => str_slug($categorias_array['subrubro']['subrubro']), 'page' => 1])}}">{{$categorias_array['subrubro']['subrubro']}}</a></li>
					@endif
					@if(isset($search))
					<li><span>&raquo;</span><strong>{{$search}}</strong></li>
					@endif
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->
<!-- Main Container -->
<div class="main-container col2-left-layout">
    <div class="container">
        <div class="row">
            <aside class="sidebar col-sm-4 col-xs-12 col-md-3 collapsed-block">
    
				<div class="shop-by-side" style="height: auto">
					<div class="shop-by-side-title-filter">
					</div>
                    <div class="sidebar-bar-title">
						<h3>Filtrar por:</h3>
						<a class="expander visible-xs" href="#filtros_list">+</a>
					</div>
                    <div class="block-content tabBlock" id="filtros_list">
                        <div class="layered-Category">
							<div class="">
								<h2 class="saider-bar-title">Categorias
									<a class="expander visible-xs "  href="#filtros_list_1" >+</a>
								</h2>
							</div>
                            <div class="container" >
								<div class="saide-bar-menu " id="filtros_list_1"  >
									<ul class="list-links list-unstyled">
									@foreach($filtros['etiquetas'] as $filtro)
										<li> 
											<label class="customcheck">
												<input type="checkbox" class="form-check-input">
												<span class="checkmark"></span>
												<input type="hidden" class="titulo" value="categorias">
												<span class="texto" >  {{ $filtro['text'] }} </span> 
											</label>
										</li>
									@endforeach
									</ul>
								</div>
							</div>
                        </div>

                        <div class="layered-Category">
							<div class="">
								<h2 class="saider-bar-title">Rubros
									<a class="expander visible-xs"  href="#filtros_list_2">+</a>
								</h2>
							</div>
							<div class="container" >
								<div class="saide-bar-menu" id="filtros_list_2">
									<ul class="list-links list-unstyled">
									@foreach($filtros['rubros'] as $filtro)
										<li>  
											<a href="{{-- {{route('productos',['id_etiqueta' => $filtro['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($filtro['text']), 'page' => 1])}} --}}"></a>
											{{ $filtro['text'] }}  
										</li>
										<ul style="padding: 5px 15px;">
												@if(isset($filtro['subrubros'][0]))
												<ul class="collapse show">
													@foreach($filtro['subrubros'] as $subfiltro)
													<li>
														<label  class="customcheck">
															<input type="checkbox">
															<input type="hidden" class="titulo" value="rubros">
															<span class="checkmark"></span>
															<span class="texto">{{$subfiltro['text']}}</span> 
															<span class="cantidad">({{$subfiltro['cantidad']}})</span>
														</label>
													</li>
													@endforeach
												</ul>
												@endif
										</ul>
									@endforeach
									</ul>
								</div>
							</div>
                        </div>

                        <div class="manufacturer-area">
							<div class="">
								<h2 class="saider-bar-title">Deportes
									<a class="expander visible-xs"  href="#filtros_list_3">+</a>
								</h2>
							</div>
							<div class="container" >
								<div class="saide-bar-menu" id="filtros_list_3">
									<ul>
										@foreach($filtros['deportes'] as $filtro)
											<li>
												<label class="customcheck">
													<input type="checkbox">
													<input type="hidden" class="titulo" value="deportes">
													<span class="texto"> {{$filtro['text']}} </span> 
													<input class="valor" hidden="hidden" value="{{$filtro['id']}}" > 
													<span class="checkmark"></span>
													<span class="cantidad">  ({{$filtro['cantidad']}}) </span>
												</label>
										</li>
										@endforeach   
									</ul>
								</div>
							</div>
                        </div>
             
                        <div class="manufacturer-area">
							<div class="">
								<h2 class="saider-bar-title">Marcas
									<a class="expander visible-xs"  href="#filtros_list_4">+</a>
								</h2>
							</div>
                            <div class="container" >
								<div class="saide-bar-menu" id="filtros_list_4">
									<ul>
										@foreach($filtros['marcas'] as $filtro)
											<li>
												<label class="customcheck">
												{{--<!-- <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($filtro['text']), 'page' => 1])}}?marca={{ $filtro['id'] }}"> --> --}}
													<input type="checkbox">
													<input type="hidden" class="titulo" value="marcas">	
													<span class="texto"> {{$filtro['text']}} </span> 
													<input class="valor" hidden="hidden" value="{{$filtro['id']}}" > 
													<span class="checkmark"></span>
													<span class="cantidad"> ({{ $filtro['cantidad'] }}) </span>
												<!-- </a> -->
												</label>
											</li>
										@endforeach   
									</ul>
								</div>
							</div>
						</div>
						
						<div class="manufacturer-area">
							<div class="">
								<h2 class="saider-bar-title">Precios
									<a class="expander visible-xs"  href="#filtros_list_5">+</a>
								</h2>
							</div>
							<div class="container" >
								<div class="saide-bar-menu" id="filtros_list_5">
									<ul>
										@foreach($filtros['precios'] as $filtro)
											@php $data=explode(',',$filtro['text']) @endphp
											<li>
												<label class="customcheck">
												{{-- <!-- <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug('precios'), 'page' => 1])}}?precio={{ $data[1] }}"> --> --}}
												<input type="checkbox"  >
												<input type="hidden" class="titulo" value="precios">
												<span class="checkmark"></span>
												<input type="hidden" class="valor" value="{{ $data[1] }}">
												<span class="texto">{{ trim($data[0]) }} </span> 
												<span class="cantidad"> ({{ $filtro['cantidad'] }}) </span>	
													<!-- </a> -->
													</label>
											</li>
										@endforeach    
									</ul>
								</div>
							</div>
						</div>
                    </div>
                </div>        
            </aside>
            <div class="col-main col-sm-8 col-xs-12 col-md-9">
				@if($header)
                <div class="category-description std">
                    <div class="slider-items-products">
                        <div id="category-desc-slider" class="product-flexslider hidden-buttons">
                            <div class="slider-items slider-width-col1 owl-carousel owl-Template">
								@foreach($header as $he)
                                <!-- Item -->
                                <div class="item">
									<img alt="{{$he['epigrafe']}}" src="{{ env('URL_BASE_UPLOADS').$he['imagen_file'] }}">
								</div>
								<!-- End Item -->
								@endforeach
                            </div>
                        </div>
                    </div>
				</div>
				@endif
				

                <div class="shop-inner">
                    <div class="page-title">
						<h2>{{ isset($categorias_array['rubro'])?$categorias_array['rubro']['rubro']:'Productos' }}{{ isset($etiqueta_array)?' - '.$etiqueta_array['nombre']:'' }}</h2>
					</div>
					@if(isset($total_reg))
						<div class="toolbar">
							<div class="sorter">
								<div class="short-by">
									<label>Ordenar por:</label>
									<!-- <select class="form-control" name="orderby" onchange="sortList(this);"> -->
									<select class="form-control" name="orderby" >
										<option value="orden" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='orden'?'selected':''):'' }}>Posición</option>

										<option value="nombre" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='nombre'?'selected':''):'' }}>Nombre</option>

										{{-- <option value="menorPrecio" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='menorPrecio'?'selected':''):'' }}>Menor Precio</option>

										<option value="mayorPrecio" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='mayorPrecio'?'selected':''):'' }}>Mayor Precio</option> --}}

										<option value="destacados" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='destacados'?'selected':''):'' }}>Destacados</option>

										<option value="ofertas" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='ofertas'?'selected':''):'' }}>Ofertas</option>
									</select>
								</div>
							</div>
						</div>
					
						<div class="product-grid-area">
							<label class="h2 nohay" style="display:none;"> No hay productos </label>
							<ul class="products-grid">
								@foreach($productos_array as $producto)
								<li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6 ">
									<div class="product-item">
										<div class="item-inner">
											<div class="product-thumbnail">
												@if($producto['oferta']==1) 
												@if(isset($producto['precios']['oferta']))
												<div class="icon-sale-label sale-left">-{{$producto['precios']['oferta']}}%</div>
												@endif
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
															@if(isset($producto['precios']['precio_lista'])  and $producto['oferta']==1 )
																<p class="old-price">
																	<span class="price-label">Precio regular:</span>
																	<span class="price">
																		{{ env('MONEDA_DEFAULT') }}{{$producto['precios']['precio_lista']}}
																	</span>
																</p>
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
								</li>
								@endforeach
							</ul>
						</div>
						
						<div class="pagintation">
						</div>

						@include('partials.paginado',['page' => $page, 'totalRegistros' => $total_reg, 'nameRoute' => 'productos', 'totalPages' => $total_pages, 'extraParams' => $extraParams])					
						
					@else
						<h3>No hay resultados</h3>
					@endif
                </div>
            </div>
        </div>
	</div>
	
</div>
<!-- Main Container End -->
@stop
@section('javascript')
@php
	$dataOrder = $extraParams['getData'];
	unset($dataOrder['sortList']);
@endphp


@foreach($extraParams['url'] as $key => $value)
	@php
	$params[$key] = $value;
	@endphp
@endforeach
<script>
function sortList(_this){
	var valor = _this.value;
	if(valor){
		window.location.href = "{{ route( 'productos', $params) }}{{ $dataOrder?'?'.http_build_query($dataOrder).'&':'?' }}sortList="+valor;
	}
}
</script>
<script>
function sortList(_this){
	var valor = _this.value;
	if(valor){
		window.location.href = "{{ route( 'productos', $params) }}{{ $dataOrder?'?'.http_build_query($dataOrder).'&':'?' }}sortList="+valor;
	}
}



$('select[name=orderby]').on("change",function(){
	orderBy();
});


function orderBy(){
	var categorias = $('.filter-value-categorias').text();
	var rubros = $('.filter-value-rubros').text();
	var deportes =$('.filter-value-deportes').text();
	var marcas = $('.filter-value-marcas').text();
	var precios =$('.filter-value-precios').text();
	var sortList= $('select[name=orderby]').val();
	$('.products-grid').empty();
	consulta(categorias,rubros,deportes,marcas,precios,sortList);
}

function check(thiz,consultar){
	var title = $(thiz).parent().find('.titulo').val();
	var texto = $.trim($(thiz).parent().find('.texto').text());
	var valor = $.trim($(thiz).parent().find('.valor').val());
	var n;
	var categorias = $('.filter-value-categorias').text();
	var rubros = $('.filter-value-rubros').text();
	var deportes =$('.filter-value-deportes').text();
	var marcas = $('.filter-value-marcas').text();
	var precios =$('.filter-value-precios').text();
	var sortList= $('select[name=orderby]').val();
	if(consultar){
		$('.products-grid').empty();
	}
	if(thiz.checked){
		if(title=="categorias"){
			$('.filter-titulo-categorias').parent().removeAttr('hidden');
			if(categorias==="") {n = texto;}else{ 
				n = texto+" ,"+categorias;
			}
			categorias = n;
			$('.filter-value-c').append(' <span class="glyphicon glyphicon-remove-circle " style="color:red;" onclick="eliminarFiltro(this)" ><span class="categoria-remove"  style="color:black;">'+texto+'</span></span>');
			$('.filter-value-categorias').text(n);
		}
		else if(title=='rubros'){
			$('.filter-titulo-rubros').parent().removeAttr('hidden');
			if(rubros==="") {n = texto;}else{ 
				n = texto+" ,"+rubros;
			}
			rubros = n;
			$('.filter-value-rubros').text(n);
			$('.filter-value-r').append(' <span class="glyphicon glyphicon-remove-circle" style="color:red;" onclick="eliminarFiltro(this)" ><span class="rubros-remove"  style="color:black;">'+texto+'</span></span>');
		}
		else if(title=="deportes"){
			$('.filter-titulo-deportes').parent().removeAttr('hidden');
			if(deportes==="") {n = texto;}else{ 
				n = texto+" ,"+deportes;
			}
			deportes = n;
			$('.filter-value-deportes').text(n);
			$('.filter-value-d').append(' <span class="glyphicon glyphicon-remove-circle" style="color:red;" onclick="eliminarFiltro(this)" ><span class="deportes-remove"  style="color:black;">'+texto+'</span></span>');
		}
		else if(title=="marcas"){
			$('.filter-titulo-marcas').parent().removeAttr('hidden');
			if(marcas==="") {n = texto;}else{ 
				n = texto+" ,"+marcas;
			}
			marcas = n;
			$('.filter-value-marcas').text(n);
			$('.filter-value-m').append(' <span class="glyphicon glyphicon-remove-circle" style="color:red;" onclick="eliminarFiltro(this)" ><span class="marcas-remove"  style="color:black;">'+texto+'</span></span>');
		}
		else if(title=="precios"){
			$('.filter-titulo-precios').parent().removeAttr('hidden');
			$(thiz).parent().parent().parent().find(':checkbox').not(thiz).removeAttr('checked');
			n = texto;
			precios = valor;
			$('.filter-value-precios').text(valor);
			$('.filter-value-p').empty().append(' <span class="glyphicon glyphicon-remove-circle" style="color:red;" onclick="eliminarFiltro(this)" ><span class="precios-remove"  style="color:black;">'+texto+'</span></span>');
		}
	}else{
		if(title=='categorias'){
			var t = $('.filter-value-categorias').text().split(',').filter(function(i){return $.trim(i)!==texto});
			$('.filter-value-categorias').text(t);
			$('.categoria-remove').each(function(i,item){
				if($.trim($(item).text()) == texto ){
					$(item).parent().remove();
				}
			});
			if($('.filter-value-categorias').text().length >0){ categorias = t;} else { categorias = null;}
		}else if(title=='rubros'){
			var t = $('.filter-value-rubros').text().split(',').filter(function(i){return $.trim(i)!==texto});
			$('.filter-value-rubros').text(t);
			$('.rubros-remove').each(function(i,item){
				if($.trim($(item).text()) == texto ){
					$(item).parent().remove();
				}
			});
			if($('.filter-value-rubros').text().length >0){ rubros = t;} else { rubros = null;}
		}else if(title=='deportes'){
			var t = $('.filter-value-deportes').text().split(',').filter(function(i){return $.trim(i)!==texto});
			$('.filter-value-deportes').text(t);
			$('.deportes-remove').each(function(i,item){
				if($.trim($(item).text()) == texto ){
					$(item).parent().remove();
				}
			});
			if($('.filter-value-deportes').text().length >0){ deportes = t;} else { deportes = null;}
		}else if(title=='marcas'){
			var t = $('.filter-value-marcas').text().split(',').filter(function(i){return $.trim(i)!==texto});
			$('.filter-value-marcas').text(t);
			$('.marcas	-remove').each(function(i,item){
				if($.trim($(item).text()) == texto ){
					$(item).parent().remove();
				}
			});
			if($('.filter-value-marcas').text().length >0){ marcas = t;} else { marcas = null;}
		}else if(title=='precios'){
			$('.filter-value-precios').empty();
			$('.precios-remove').each(function(i,item){
				if($.trim($(item).text()) == texto ){
					$(item).parent().remove();
				}
			});
			precios =null;
		}
	}
	categorias = clean(categorias);
	rubros = clean(rubros);
	deportes = clean(deportes);
	marcas = clean(marcas);
	precios = clean(precios);
	if(consultar){
		consulta(categorias,rubros,deportes,marcas,precios,sortList);
	}
}//end check

$('input[type=checkbox]').change(function(i,item){
	agregarDiv();
	check(this,true);
}); // end change

function clean(dato){
	var temp=[];
	if(dato!==null){
		$.each((dato+"").split(','),function(i,d){ return temp[i]= $.trim(d); });
		if(temp[0].length>0){  return temp.join(',');}
	}
	return dato;
}

function eliminarFiltro(categoria){
	$('input[type="checkbox"]').each(function(i,item){
		if($.trim($(item).parent().find('.texto').text()) == $(categoria).text() ){
			$(item).click();
		}
	});
	$(categoria).parent().remove();
}

function agregarDiv(){
	if($('.tags-title-filter').length == 0){
		$('.shop-by-side-title-filter').html(
			'<div class="tags-title-filter">'
				+'<div class="sidebar-bar-title">'
					+'<h3  >Tags: </h3>'
					+'<a class="expander visible-xs " href="#filtros_list_tag">-</a> '
				+'</div>'

				+'<div class="saide-bar-menu" id="filtros_list_tag" style="display:block">'
					+'<div class="layered" style="margin-left:10px;" hidden="hidden" >'
						+'<span class="filter-titulo-categorias"><strong> Categorias: </strong></span>'
						+'<div class="filter-value-c" style="padding:3px;"> </div>'
						+'<span class="filter-value-categorias" hidden="hidden"></span>'
					+'</div>'
					+'<div class="layered" style="margin-left:10px;"  hidden="hidden" >'
						+'<span class="filter-titulo-rubros"><strong> Rubros: </strong></span>'
						+'<div class="filter-value-r" style="padding:3px;"> </div>'
						+'<span class="filter-value-rubros" hidden="hidden"></span>'
					+'</div>'
					+'<div class="layered" style="margin-left:10px;"  hidden="hidden" >'
						+'<span class="filter-titulo-deportes"><strong> Deportes: </strong></span>'
						+'<div class="filter-value-d" style="padding:3px;"> </div>'
						+'<span class="filter-value-deportes" hidden="hidden" ></span>'

					+'</div>'
					+'<div class="layered" style="margin-left:10px;"  hidden="hidden" >'
						+'<span class="filter-titulo-marcas"><strong> Marcas: </strong></span>'
						+'<div class="filter-value-m" style="padding:3px;"> </div>'
						+'<span class="filter-value-marcas" hidden="hidden"></span>'

					+'</div>'
					+'<div class="layered" style="margin-left:10px;"  hidden="hidden" >'
						+'<span class="filter-titulo-precios"><strong> Precios: </strong></span>'
						+'<div class="filter-value-p" style="padding:3px;"> </div>'
						+'<span class="filter-value-precios" hidden="hidden"></span>'
					+'</div>'
					+'<div  class="layered" style="margin-left:10px;">'
						+'<label onclick="removeTags();"> <span class="fa fa-trash"></span> Eliminar tags</label>'
					+'</div>'
				+'</div>'
			+'</div>'
		);
	}// end if

	if($('.product-grid-area').length == 0){
		$('.shop-inner').empty();
		$('.shop-inner').append(
			'<div class="toolbar">'
				+'<div class="sorter">'
					+'<div class="short-by">'
						+'<label>Ordenar por:</label>'
						+'<select class="form-control" name="orderby" onchange="orderBy()">'
							+'<option value="orden" >Posición</option>'
							+'<option value="nombre" >Nombre</option>'
							+'<option value="destacados" >Destacados</option>'
							+'<option value="ofertas" >Ofertas</option>'
						+'</select>'
					+'</div>'
				+'</div>'
			+'</div>'
			+'<div class="product-grid-area">'
				+'<label class="h2 nohay" style="display:block;" > No hay mas productos </label>'
				+'<ul class="products-grid"> </ul>'
			+'</div>'
			+'<div class="pagintation">'
			+'</div>'
		);
	}
}

function removeTags(){
	$('.shop-by-side-title-filter').empty();
	$('.products-grid').empty();
	$('input[type=checkbox]').attr('checked',false);
	consulta();
}


function consulta(categorias,rubros,deportes,marcas,precios,sortList,page){
	$.ajax({
		url: '{!!URL::route('filtroproductos')!!}',
		data :  {'categorias' :categorias ,'rubros':rubros,'deportes':deportes,'marcas':marcas, 'precios':precios ,'sortlist':sortList,'page':page},
		type : 'get',
		dataType : 'json',
		success: function(items){
			$('.shop-inner .page-title h2').text("");
			var item;
			if(items.productos.length==0){
				$('.nohay').show();
			}else{
				$('.nohay').hide();
			}

			$.each(items.productos,function(index,data){
				var oferta='';
				var fotos='';
				var precios='';
				var precios2='';
				if(data.oferta==1){
					oferta = '<div class="icon-sale-label sale-left">-'+data.precios.oferta+'%</div>';
				}
				if(data.fotos){
					fotos = '<div class="pr-img-area">'
								+'<a title="'+data.titulo+'" href="'+'{!!route("producto",["id" => "'+data.id+'","name" => "'+data.titulo_slug+'"]) !!}' +'">'
									+'<figure>'
										+'<img class="first-img" src="'+ '{!! env("URL_BASE_UPLOADS")."th_"."'+data.fotos[0].imagen_file+'" !!} "  />'
										if(data.fotos[1]){
											+'<img class="hover-img" src="' + '{!! env("URL_BASE_UPLOADS")."th_"."'+data.fotos[1].imagen_file+'" !!} "  />'
										}
									+'</figure>'
								+'</a>'
							+'</div>';
				}
				if(data.precios ){
					if(parseFloat(data.precios.precio)>0){
						precios = '<p class="special-price">'
									+'<span class="price-label">Precio Especial</span>'
									+'<span class="price">'
										+'<span>'+ data.moneda_default +'</span>'
										+'<span>'+ data.precios.precio+'</span>'
									+'</span>'
								+'</p>'
					}
				}
				if(data.precios ){
					if( data.oferta==1){
						precios2='<p class="old-price">'
									+'<span class="price-label">Precio regular:</span>'
									+'<span class="price">'
										+'<span>'+data.moneda_default+'</span>'
										+'<span>'+data.precios.precio_lista+'</span>'
									+'</span>'
								+'</p>'
					}
				}
				item =
				'<li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6 ">'
					+'<div class="product-item">'
						+'<div class="item-inner">'
							+'<div class="product-thumbnail">'
								+ oferta
								+ fotos
							+'</div>'
							+'<div class="item-info">'
								+'<div class="info-inner">'
									+'<div class="item-title">'
										+'<a title="'+ data.titulo+'" href="'+ '{!!route("producto",["id" => "'+data.id+'" ,"name" =>  "'+data.titulo_slug+'" ] )!!}'  +'">'+ data.titulo +'</a>'
									+'</div>'
									+'<div class="item-content">'
										+'<div class="item-price">'
											+'<div class="price-box">'
											+precios 
											+precios2
											+'</div>'
										+'</div>'
										+'<div class="pro-action">'
											+'<a href="'+ '{!!route("producto",["id" => "'+data.id+'","name" => "'+data.titulo_slug+'" ]) !!}' +'" class="add-to-cart">'
												+'<span>Ver Detalle</span>'
											+'</a>'
										+'</div>'
									+'</div>'
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</li>';
				$('.products-grid').append(item);
				
			});//each
			
			

			if(items.next_url!=null){
				var firts_page= items.firts_page;
				var  contenido = '<label class="btn btn-success moredata" onclick="moredata('+firts_page+');"> ver mas </label>';
				if($('.pagination-area').length){
					$('.pagination-area').empty();
					$('.pagination-area').html(contenido);
					$('.page-title').empty().html('<h2> Productos </h2>');

				}else{
					$('.page-title').empty().html('<h2> Productos </h2>');

					$('.pagintation').prepend('<div class="pagination-area">'+contenido+'</div>');
				}
			}else{
				$('.page-title').empty().html('<h2> Productos </h2>');

				$('.pagination-area').empty();
			}
			$('.page-loader.dark').fadeOut();

		},
		beforeSend: function(){
			$('.page-loader.dark').fadeIn();
			if((rubros)!= null&&(marcas == null)){
				window.top.document.title = 'Productos - '+ rubros +' - Paco Garcia';
			}else if(marcas != null&&(rubros == null)){
				window.top.document.title = 'Productos - '+ marcas +' - Paco Garcia';
			}else if((rubros != null)&&(marcas != null)){
				window.top.document.title = 'Productos - '+ rubros +' '+ marcas +' - Paco Garcia';
			}else{
				window.top.document.title = 'Productos - Paco Garcia';
			}
			
		}
	});
}//end consulta


function moredata(firts_page){
	var nextpage =  firts_page+1;
	var categorias = $('.filter-value-categorias').text();
	var rubros = $('.filter-value-rubros').text();
	var deportes = $('.filter-value-deportes').text();
	var marcas = $('.filter-value-marcas').text();
	var precios = $('.filter-value-precios').text();
	var sortList= $('select[name=orderby]').val();
	consulta(categorias,rubros,deportes,marcas,precios,sortList,nextpage);
}


$(document).ready(function(){
	agregarDiv();
	var hola = '{!! URL::full() !!}'
	var deporte  = hola.indexOf('deporte')
	var marca  = hola.indexOf('marca')
	var numerodeporte = 0;
	var numeromarca = 0;
	if(deporte != -1){
		numerodeporte =  hola.split('=');
		numerodeporte =  numerodeporte[1];
	}
	if(marca != -1){
		numeromarca =  hola.split('=');
		numeromarca =  numeromarca[1];
	}
	$('input[type="checkbox"]').each(function(i,item){
		if($('.menu-categoria a').text() == $.trim($(item).parent().find('.texto').text()) ){
			$(item).attr('checked',true);
		}
		if($('.menu-subrubro a').text() == $.trim($(item).parent().find('.texto').text()) ){
			$(item).attr('checked',true);
		}
		var titulo = $.trim( $(item).parent().find('.titulo').val() ) ;
		if(titulo === 'deportes'){
			var numero = $.trim( $(item).parent().find('.valor').val() ); 
			if(numero == numerodeporte){
				$(item).attr('checked',true);
			}
		}
		if(titulo == 'marcas'){
			var numero = $.trim( $(item).parent().find('.valor').val() ); 
			if(numero == numeromarca){
				$(item).attr('checked',true);
			}
		}
		check(this,false);
	});
});

		@php \Log::debug('productos '.print_r($productos_array,true))  @endphp


		gtag('event', 'view_item_list', {
			"items": [
					@if (isset($productos_array))
					@foreach($productos_array as $producto)
				{
					"id":  {!! $producto['id'] !!} ,
					"name": {!! "'" .$producto['titulo']."'" !!},
					"category": {!! isset($categorias_array['rubro'])?"'" .$categorias_array['rubro']['rubro']."'":"'" .'-'."'" !!},
					"list_name": {!! isset($categorias_array['rubro']['rubro'])?"'" .$categorias_array['rubro']['rubro']."'":"'" .'Productos'."'"  !!},
					"price": {!!  isset($producto['precios']['precio_db'])?$producto['precios']['precio_db']:0 !!} ,
				},
				@endforeach
				@endif
			]
		});

	</script>
@stop