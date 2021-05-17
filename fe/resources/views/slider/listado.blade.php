@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <li class="inicio">
                        <a title="Inicio" href="{{ route('home') }}">Inicio</a><span>»</span>
                    </li>
                    <li><strong>{{ $slider['titulo'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End --> 
<!-- Main Container -->
<div class="main-container col2-right-layout">
    <div class="container-fluid">
		<div class="row">
			<div class="col-main {{$data_news?'col-sm-9':'col-sm-12'}} col-xs-12">
				<div class="shop-inner">
					@if($productos_array)
					<div class="product-grid-area">
						<ul class="products-grid">
                            @foreach($productos_array as $producto)
							<li class="item {{$data_news?'col-lg-4 col-md-4':'col-lg-3 col-md-3'}} col-sm-6 col-xs-6 ">
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
					@else
					<h3>No hay resultados</h3>
					@endif
					@include('partials.paginado',['page' => $page, 'totalRegistros' => $total_reg, 'nameRoute' => 'listado_slide', 'totalPages' => $total_pages, 'extraParams' => $extraParams])					
				</div>
			</div>
			@if($data_news)
			<aside class="left sidebar col-sm-3 col-xs-12">
				<div class="block" style="border: 1px #e8e6e2 solid;background-color: #fff;padding:10px;">
				@foreach($data_news as $nota)
				<article class="entry mb20">
							<div class="row">
								@if(isset($nota['fotos'][0]))
								<div class="col-sm-12">
									<div class="entry-thumb image-hover2">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}">
											<figure>
												<img src="{{ env('URL_BASE_UPLOADS').$nota['fotos'][0]['imagen_file'] }}" alt="{{$nota['fotos'][0]['epigrafe']}}">
											</figure>
										</a>
									</div>
								</div>
								@endif
								<div class="{{ isset($nota['fotos'][0])?'col-sm-12':'col-sm-12' }}">
									<h3 class="entry-title">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}">{{$nota['titulo']}}</a>
									</h3>
									<div class="entry-excerpt">
										<p>{{ $nota['sumario'] }}</p>
									</div>
									<div class="entry-more" style="border-bottom: 1px solid #f3f3f3;padding-bottom: 20px;">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}" class="button">
											Ver más &nbsp; 
											<i class="fa fa-angle-double-right"></i>
										</a>
									</div>
								</div>
							</div>
						</article>
			
			
			
			
			
			
				@endforeach
				</div>
			</aside>
			@endif
		</div>
	</div>
</div>
<!-- Main Container End --> 
@stop
