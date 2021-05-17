<nav>
    <div class="container hidden-xs">
        <div class="row">
            <div class="col-md-2 col-sm-3">
                <div class="mega-container visible-lg visible-md visible-sm">
                    <div class="navleft-container">
                        <div class="mega-menu-title menu-hover">
                                <div class="logo-stick">
                                        <a title="{{env('SITE_NAME')}}" href="{{ route('home') }}">
                                            <img alt="{{env('SITE_NAME')}}" src="images/logo-sticky.png">
                                        </a>
                                    </div>
                            <h3>Categorias <i class="fa fa-bars" aria-hidden="true" style="margin-left: 27px;"></i></h3> 
                            
                        </div>
                        <div class="mega-menu-category">
                            <ul class="nav">
                            @if(isset($menu_web['etiquetas']))
                                @foreach($menu_web['etiquetas'] as $menu)
                                <li>
									<a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($menu['nombre']), 'page' => 1])}}">{{ $menu['nombre'] }}</a>
                                    @if(isset($menu['rubros']))
                                    <div class="wrap-popup">
                                        <div class="popup">
                                            <div class="row">
                                                @php $i=0; @endphp
                                                @foreach($menu['rubros'] as $rubros)
                                                @php $i++; @endphp
                                                <div class="col-md-4 col-sm-6 {{($i>1?'has-sep':'')}}">
                                                    <h3><a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => $rubros['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubros['nombre']), 'page' => 1])}}">{{ $rubros['nombre'] }}</a></h3>
                                                    <ul class="nav">
													@if(isset($rubros['subrubros']))
														@foreach($rubros['subrubros'] as $subrubros)
														<li><a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => $rubros['id'] , 'id_subrubro' => $subrubros['id'], 'name' => str_slug($subrubros['nombre']), 'page' => 1])}}">{{ $subrubros['nombre'] }}</a></li>
														@endforeach
													@endif
                                                        <li><a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => $rubros['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubros['nombre']), 'page' => 1])}}">> Ver Todos</a></li>
                                                    </ul>
												</div>
												@endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                            @endif
                            @if(isset($menu_web['deportes']))
                                @php $i=0; $cant=round((count($menu_web['deportes']))/2);@endphp
                                <li>
                                    <a href="javascript:void(0);">Deportes</a>
                                    <div class="wrap-popup column2">
                                        <div class="popup">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <ul class="nav">
                                                        @foreach($menu_web['deportes'] as $deporte)
                                                        @php $i++; @endphp
                                                        <li>
                                                            <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($deporte['nombre']), 'page' => 1])}}?deporte={{ $deporte['id'] }}">
                                                            {{$deporte['nombre'] }}
                                                            </a>
                                                        </li>
                                                        @if($i==$cant)
                                                        @php $i=0; @endphp
                                                        </ul></div><div class="col-md-6 col-sm-6 has-sep"><ul class="nav">
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if(isset($menu_web['marcas']))
                                @php $i=0; $cant=round((count($menu_web['marcas']))/2);@endphp
                                <li>
                                    <a href="javascript:void(0);">Marcas</a>
                                    <div class="wrap-popup column2">
                                        <div class="popup">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <ul class="nav">
                                                        @foreach($menu_web['marcas'] as $marcas)
                                                        @php $i++; @endphp
                                                        <li>
                                                            <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($marcas['nombre']), 'page' => 1])}}?marca={{ $marcas['id'] }}">
                                                            {{$marcas['nombre'] }}
                                                            </a>
                                                        </li>
                                                        @if($i==$cant)
                                                        @php $i=0; @endphp
                                                        </ul></div><div class="col-md-6 col-sm-6 has-sep"><ul class="nav">
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
							</ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 col-sm-9 margin-left-menu">
                <div class="mtmegamenu">
                    <ul>
					@if(isset($menu_web['etiquetas_destacadas']))
						@foreach($menu_web['etiquetas_destacadas'] as $etiqueta_dest)
                        <li class="mt-root menu-{{$etiqueta_dest['color']}}">
                            <div class="mt-root-item">
                                <a href="{{route('productos',['id_etiqueta' => $etiqueta_dest['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta_dest['nombre']), 'page' => 1])}}">
                                    <div class="title title_font">
                                        <span class="title-text">{{$etiqueta_dest['nombre']}}</span>
                                    </div>
                                </a>
                            </div>
						</li>
						@endforeach
                    @endif
                    @if(isset($menu_web['ofertas']))
                        <li class="mt-root menu-hover-outlet">
                            <div class="mt-root-item">
                                <a href="javascript:void(0);">
                                    <div class="title title_font">
                                        <span class="title-text">Outlet</span>
                                        <i class="fa fa-angle-down"></i>
                                    </div>
                                </a>
                            </div>
                            <ul class="menu-items col-xs-12">
                                @foreach($menu_web['ofertas'] as $producto)
                                <li class="menu-item depth-1 product menucol-1-4 withimage">
                                    <div class="product-item">
                                        <div class="item-inner">
                                            <div class="product-thumbnail">
                                                @if(isset($producto['precios']['oferta']))
                                                <div class="icon-sale-label sale-left">-{{$producto['precios']['oferta']}}%</div>
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
															@if(isset($producto['precios']['precio_lista']))
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
                        </li>
                    @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>