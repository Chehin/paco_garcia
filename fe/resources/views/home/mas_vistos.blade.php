@if(count($productos['productos'])>0)
<div class="container-fluid">
	<div class="special-products">
		<div class="page-header"><h2><span class="title-prod">Productos más vistos</span></h2></div>
		<div class="special-products-pro">
			<div class="slider-items-products">
				<div id="special-products-slider" class="product-flexslider hidden-buttons">
					<div class="slider-items">
						@foreach($productos['productos'] as $producto)
						<div class="product-item">
							<div class="item-inner">
								<div class="product-thumbnail">
									@if(isset($producto['oferta']))
										@if($producto['oferta']==1)<div class="icon-sale-label sale-left">-{{$producto['precios']['descuento']}}%</div>@endif
                                    {{-- <div class="icon-sale-label sale-left">-{{$producto['precios']['oferta']}}%</div> --}}
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
												@if(isset($producto['oferta']))
												@if(isset($producto['precios']['precio_lista']) and $producto['oferta']==1)
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
													<span>Ver Detalle{{-- Agregar al Carrito --}}</span>
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