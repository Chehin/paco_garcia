<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">
	
    <!-- User info -->
    <div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
			
			<a href="javascript:void(0);" id="show-shortcut">
				<?php
					$user = Sentinel::getUser();
				?>
				<img data-href="{{ route('user/image') }}" data-toggle="modal-custom" src="{{ (trim($user->image)) ? config('appCustom.UPLOADS_BE_USER') .  $user->image : 'img/user-x.svg' }}" alt="me" class="online" title="Clic para cambiar la imagen de perfil"/> 
				<span id="show-shortcut">
					{{ $user->first_name }}.{{ $user->last_name }} 
				</span>
				<i class="fa fa-angle-down"></i>
			</a> 
			
		</span>
	</div>
    <!-- end user info -->
	
    <!-- NAVIGATION : This navigation is also responsive
		
		To make this navigation dynamic please make sure to link the node
		(the reference to the nav > ul) after page load. Or the navigation
		will not initialize.
	-->
    <nav>
			@if(Sentinel::hasAccess('dash.view') || Sentinel::hasAccess('dash2.view') || Sentinel::hasAccess('dash3.view'))		
			<ul>
				<li class="{{ Request::is('dash') }}">
					<a href="#"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
					<ul>
						@if(Sentinel::hasAccess('dash.view'))
						<li class="{{ Request::is('dash') ? 'active' : '' }}">
							<a href="{{ route('dash') }}"><i class="fa fa-bar-chart-o"></i> Dash. Por Mes</a>
						</li>
						@endif
						
						@if(Sentinel::hasAccess('dash2.view'))
						<li class="{{ Request::is('dash2') ? 'active' : '' }}">
							<a href="{{ route('dash2') }}"><i class="fa fa-bar-chart-o"></i> Dash. Por Producto</a>
						</li>
						@endif
						
						@if(Sentinel::hasAccess('dash3.view'))
						<li class="{{ Request::is('dash3') ? 'active' : '' }}">
							<a href="{{ route('dash3') }}"><i class="fa fa-bar-chart-o"></i> Dash. Por Rubro</a>
						</li>
						@endif
					</ul>
				</li>
			</ul>
			@endif
		<!-- NOTE: Notice the gaps after each icon usage <i></i>..
            Please note that these links work a bit different than
            traditional hre="" links. See documentation for details.
		-->
		@if(Sentinel::hasAccess('user.view') || Sentinel::hasAccess('role.view'))		
		<ul>
			<li class="{{ Request::is('user') || Request::is('role') ? 'active' : '' }}">
				<a href="#"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">Usuarios</span></a>
				<ul>
					@if(Sentinel::hasAccess('user.view'))
					<li class="{{ Request::is('user') ? 'active' : '' }}">
						<a href="{{ route('user') }}"><i class="fa fa-user"></i> Usuarios</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('role.view'))
					<li class="{{ Request::is('role') ? 'active' : '' }}">
						<a href="{{ route('role') }}"><i class="fa fa-group"></i> Perfiles</a>
					</li>
					@endif
					
				</ul>
			</li>
		</ul>
		@endif
		
		@if(Sentinel::hasAnyAccess(['empresaSis.view', 'marcas.view', 'colores.view', 'talles.view', 'monedas.view', 'general.view', 'comprobante.view']))
		<ul>
			<li class="{{ Request::is('empresaSis') || Request::is('marcas') || Request::is('colores') || Request::is('talles') || Request::is('monedas') ? 'active' : '' || Request::is('comprobante')? 'comprobante' : '' }}">
				<a href="#"><i class="fa fa-wrench"></i> <span class="menu-item-parent">Configuracion</span></a>
				<ul>
					@if(Sentinel::hasAccess('empresaSis.view'))
					<li class="{{ Request::is('empresaSis') ? 'active' : '' }}">
						<a href="{{ route('empresaSis') }}"><i class="fa fa-cog"></i> La Empresa</a>
					</li>
					@endif

					@if(Sentinel::hasAccess('marcas.view'))
					<li class="{{ Request::is('marcas') ? 'active' : '' }}">
						<a href="{{ route('configuracion/marcas') }}"><i class="fa fa-cog"></i> Marcas</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('colores.view'))
					<li class="{{ Request::is('colores') ? 'active' : '' }}">
						<a href="{{ route('configuracion/colores') }}"><i class="fa fa-cog"></i> Colores</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('talles.view'))
					<li class="{{ Request::is('talles') ? 'active' : '' }}">
						<a href="{{ route('configuracion/talles') }}"><i class="fa fa-cog"></i> Talles</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('monedas.view'))
					<li class="{{ Request::is('monedas') ? 'active' : '' }}">
						<a href="{{ route('configuracion/monedas') }}"><i class="fa fa-cog"></i> Monedas</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('general.view'))
					<li class="{{ Request::is('general') ? 'active' : '' }}">
						<a href="{{ route('configuracion/general') }}"><i class="fa fa-cog"></i> General</a>
					</li>
					@endif

					@if(Sentinel::hasAccess('comprobante.view'))
					<li class="{{ Request::is('comprobante') ? 'active' : '' }}">
						<a href="{{ route('comprobante') }}"><i class="fa fa-cog"></i>Comprobantes</a>
					</li>
					@endif
					
					
				</ul>
			</li>
		</ul>
		@endif
		
		
		@if(Sentinel::hasAnyAccess(['rubros.view', 'etiquetas.view', 'productos.view', 'subRubros.view']))
		<ul>
			<li class="{{ Request::is('rubros') || Request::is('etiquetas') || Request::is('productos') || Request('subRubros') ? 'active' : '' }}">
				<a href="#"><i class="fa fa-briefcase"></i> <span class="menu-item-parent">Productos</span></a>
				<ul>
					
					@if(Sentinel::hasAccess('rubros.view'))
					<li class="{{ Request::is('rubros') ? 'active' : '' }}">
						<a href="{{ route('productos/rubros') }}"><i class="fa fa-cog"></i> Rubros</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('subRubros.view'))
					<li class="{{ Request::is('subRubros') ? 'active' : '' }}">
						<a href="{{ route('productos/subRubros') }}"><i class="fa fa-cog"></i> Sub Rubros</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('subsubRubros.view'))
					<li class="{{ Request::is('subsubRubros') ? 'active' : '' }}">
						<a href="{{ route('productos/subsubRubros') }}"><i class="fa fa-cog"></i> Sub Sub Rubros</a>
					</li>
					@endif					
					
					@if(Sentinel::hasAccess('deportes.view'))
					<li class="{{ Request::is('deportes') ? 'active' : '' }}">
						<a href="{{ route('productos/deportes') }}"><i class="fa fa-tag"></i> Deportes</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('etiquetas.view'))
					<li class="{{ Request::is('etiquetas') ? 'active' : '' }}">
						<a href="{{ route('productos/etiquetas') }}"><i class="fa fa-tag"></i> Etiquetas</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('productos.view'))
					<li class="{{ Request::is('productos') ? 'active' : '' }}">
						<a href="{{ route('productos/productos') }}"><i class="fa fa-cog"></i> Productos</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('importarProductos.view'))
					<li class="{{ Request::is('importarProductos') ? 'active' : '' }}">
						<a href="{{ route('productos/importarProductos') }}"><i class="fa fa-cog"></i> Importar Productos</a>
					</li>
					@endif
					

				</ul>
			</li>
		</ul>
		@endif

		@if(
			Sentinel::hasAnyAccess(['pedidos.view']) || 
			Sentinel::hasAnyAccess(['pedidos1.view']) ||
			Sentinel::hasAnyAccess(['pedidos2.view']) ||
			Sentinel::hasAnyAccess(['pedidos3.view']) ||
			Sentinel::hasAnyAccess(['pedidosMeli.view']) 
		)
		<ul>
			<li>
				<a href="#"><i class="fa fa-shopping-cart"></i> <span class="menu-item-parent">Pedidos</span></a>
				<ul>
					@if(Sentinel::hasAccess('pedidos1.view'))
					<li class="{{ Request::is('pedidos1') ? 'active' : '' }}">
						<a href="{{ route('pedidos1') }}"><i class="fa fa-shopping-cart"></i> A Gestionar</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('pedidos2.view'))
					<li class="{{ Request::is('pedidos2') ? 'active' : '' }}">
						<a href="{{ route('pedidos2') }}"><i class="fa fa-shopping-cart"></i>En Carrito</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('pedidos3.view'))
					<li class="{{ Request::is('pedidos3') ? 'active' : '' }}">
						<a href="{{ route('pedidos3') }}"><i class="fa fa-shopping-cart"></i>A acordar</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('pedidos.view'))
					<li class="{{ Request::is('pedidos') ? 'active' : '' }}">
						<a href="{{ route('pedidos/pedidos') }}"><i class="fa fa-shopping-cart"></i> Todos</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('pedidosMeli.view'))
					<li class="{{ Request::is('pedidosMeli') ? 'active' : '' }}">
						<a href="{{ route('pedidosMeli/pedidosMeli') }}"><i class="fa fa-shopping-cart"></i>  Meli</a>
					</li>
					@endif
				</ul>
			</li>
		</ul>
		@endif
		@if(Sentinel::hasAccess('pedidosClientes.view'))
		<ul>
			<li class="{{ Request::is('pedidosClientes') ? 'active' : '' }}">
				<a href="{{ route('pedidos/pedidosClientes') }}"><i class="fa fa-lg fa-fw fa-group"></i> <span class="menu-item-parent">Clientes</span></a>
			</li>
		</ul>
		@endif

		
		@if(Sentinel::hasAccess('slider.view'))
		<ul>
			<li class="{{ Request::is('slider') ? 'active' : '' }}">
				<a href="{{ route('news/slider') }}"><i class="fa fa-th-large"></i> <span class="menu-item-parent">Slider</span></a>
				
			</li>
		</ul>
		@endif
		
		@if(Sentinel::hasAccess('news.view'))
		<ul>
			<li class="{{ Request::is('news') ? 'active' : '' }}">
				<a href="{{ URL::to('news') }}"><i class="fa fa-file-text-o"></i> <span class="menu-item-parent">Contenidos</span></a>
			</li>
		</ul>
		@endif
		@if(Sentinel::hasAccess('sucursales.view'))
		<ul>
			<li class="{{ Request::is('sucursales') ? 'active' : '' }}">
				<a href="{{ URL::to('sucursales') }}">
					<i class="fa fa-lg fa-fw fa-building-o"></i> 
					<span class="menu-item-parent">Sucursales</span>
					<span class="badge pull-right inbox-badge"></span>
				</a>
			</li>
			
		</ul>
		@endif
		
		@if(Sentinel::hasAnyAccess(['bannersClientes.view', 'bannersPosiciones.view', 'bannersTipos.view', 'banners.view']))
		<ul>
			<li class="{{ Request::is('bannersClientes') || Request::is('bannersPosiciones') || Request::is('bannersTipos') || Request::is('banners') ? 'active' : '' }}">
				<a href="#"><i class="fa fa-file-image-o"></i> <span class="menu-item-parent">Banners</span></a>
				<ul>
					
					@if(Sentinel::hasAccess('bannersClientes.view'))
					<li class="{{ Request::is('bannersClientes') ? 'active' : '' }}">
						<a href="{{ route('banners/bannersClientes') }}"><i class="fa fa-cog"></i> Clientes</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('bannersPosiciones.view'))
					<li class="{{ Request::is('bannersPosiciones') ? 'active' : '' }}">
						<a href="{{ route('banners/bannersPosiciones') }}"><i class="fa fa-cog"></i> Posiciones</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('bannersTipos.view'))
					<li class="{{ Request::is('bannersTipos') ? 'active' : '' }}">
						<a href="{{ route('banners/bannersTipos') }}"><i class="fa fa-cog"></i> Tipos de Banners</a>
					</li>
					@endif
					
					@if(Sentinel::hasAccess('banners.view'))
					<li class="{{ Request::is('banners') ? 'active' : '' }}">
						<a href="{{ route('banners/banners') }}"><i class="fa fa-cog"></i> Banners</a>
					</li>
					@endif
					
				</ul>
			</li>
		</ul>
		@endif
		@if(Sentinel::hasAccess('newsletter.view'))
            <ul>
                <li class="{{ Request::is('newsletter') ? 'active' : '' }}">
                    <a href="{{ URL::to('newsletter') }}">
                        <i class="fa fa-lg fa-fw fa-user"></i> 
                        <span class="menu-item-parent">newsletter</span>
                        <span class="badge pull-right inbox-badge"></span>
                    </a>
                </li>

            </ul>
            @endif
		
		{{-- @if(Sentinel::hasAccess('blog.view') || Sentinel::hasAccess('etiquetasBlog.view'))
			<ul>
				<li class="{{ Request::is('blog')|| Request::is('etiquetasBlog') ? 'active' : '' }}">
					<a href="#"> <i class="fa fa-lg fa-fw fa-rss"></i><span class="menu-item-parent">Blog</span></a>
					<ul>
						@if(Sentinel::hasAccess('blog.view'))
						<li class="{{ Request::is('blog') ? 'active' : '' }}">
							<a href="{{ URL::to('blog') }}">Notas</a>
						</li>
						@endif
						
						@if(Sentinel::hasAccess('etiquetasBlog.view'))
						<li class="{{ Request::is('etiquetasBlog') ? 'active' : '' }}">
							<a href="{{ URL::to('etiquetasBlog') }}">Etiquetas Blog</a>
						</li>
						@endif
						
					</ul>
				</li>
				
			</ul>
		@endif --}}

		@if(Sentinel::hasAccess('marketingPersonas.view'))
            <ul>
                <li class="{{ Request::is('margetingPersonas') }}">
                    <a href="#"><i class="fa fa-lg fa-fw fa-circle"></i> <span class="menu-item-parent">Marketing</span></a>
                    <ul>

                        @if(Sentinel::hasAccess('importarClientes.view'))
                     
                            <li class="{{ Request::is('importarClientes') ? 'active' : '' }}">
                                <a href="{{ URL::to('importarClientes') }}">
                                    <i class="fa fa-lg fa-fw fa-download"></i> 
                                    <span class="menu-item-parent">Importar Clientes</span>
                                </a>
                            </li>

                    
						@endif
						
                        @if(Sentinel::hasAccess('marketingListas.view'))
                        <li class="{{ Request::is('marketingListas') ? 'active' : '' }}">
                            <a href="{{ URL::to('marketingListas') }}">
                                <i class="fa fa-lg fa-fw fa-list-alt"></i>
                                <span>Listas</span>
                            </a>
                        </li>
                        @endif
						
						@if(Sentinel::hasAnyAccess(['banners2.view', 'banners2Posiciones.view', 'banners2Tipos.view']))
						<li class="{{ Request::is('banners2') || Request::is('banners2Posiciones') || Request::is('banners2Tipos') || Request::is('banners2') ? 'active' : '' }}">
							<a href="#"><i class="fa fa-credit-card"></i> <span class="menu-item-parent">Call to Action</span></a>
							<ul>

								@if(Sentinel::hasAccess('banners2.view'))
								<li class="{{ Request::is('banners2') ? 'active' : '' }}">
									<a href="{{ route('banners2/banners2') }}"><i class="fa fa-credit-card"></i> CTA config</a>
								</li>
								@endif


								@if(Sentinel::hasAccess('banners2Tipos.view'))
								<li class="{{ Request::is('banners2Tipos') ? 'active' : '' }}">
									<a href="{{ route('banners2/banners2Tipos') }}"><i class="fa fa-cog"></i> CTA Tipos</a>
								</li>
								@endif

								@if(Sentinel::hasAccess('banners2Posiciones.view'))
								<li class="{{ Request::is('banners2Posiciones') ? 'active' : '' }}">
									<a href="{{ route('banners2/banners2Posiciones') }}"><i class="fa fa-cog"></i> CTA Posiciones</a>
								</li>
								@endif

							</ul>
						</li>
						@endif

						@if(Sentinel::hasAnyAccess(['maillingDiagramador.view', 'maillingTipos.view', 'maillingCampanias.view', 'maillingEstadisticas.view','maillingTemplates.view']))
							<li class="{{ Request::is('maillingDiagramador') || Request::is('maillingTipos') || Request::is('maillingCampanias') || Request::is('maillingEstadisticas') || Request::is('maillingTemplates') ? 'active' : '' }}">
								<a href="#"><i class="fa fa-envelope"></i> <span class="menu-item-parent">Mailling</span></a>
								<ul>

									@if(Sentinel::hasAccess('maillingTemplates.view'))
										<li class="{{ Request::is('maillingTemplates') ? 'active' : '' }}">
											<a href="{{ route('mailling/maillingTemplates') }}"><i class="fa fa-text-height"></i> Templates</a>
										</li>
									@endif
									
									{{-- @if(Sentinel::hasAccess('maillingTipos.view'))
									<li class="{{ Request::is('maillingTipos') ? 'active' : '' }}">
										<a href="{{ route('mailling/maillingTipos') }}"><i class="fa fa-cog"></i> Automaticos</a>
									</li>
									@endif --}}
									
									@if(Sentinel::hasAccess('maillingDiagramador.view'))
									<li class="{{ Request::is('maillingDiagramador') ? 'active' : '' }}">
										<a href="{{ route('mailling/maillingDiagramador') }}"><i class="fa fa-object-group"></i> Campañas</a>
									</li>
									@endif
									
									
									@if(Sentinel::hasAccess('maillingCampanias.view'))
									<li class="{{ Request::is('maillingCampanias') ? 'active' : '' }}">
										<a href="{{ route('mailling/maillingCampanias') }}"><i class="fa fa-magic"></i> Campañas A/B testing</a>
									</li>
									@endif

									@if(Sentinel::hasAnyAccess(['maillingEstadisticas.view', 'maillingEstadisticasSimples.view', 'maillingEstadisticasReport.view']))
										<li class="{{ Request::is('maillingEstadisticas') || Request::is('maillingEstadisticasSimples') || Request::is('maillingEstadisticasReport')  ? 'active' : '' }}">
											<a href="#"><i class="fa fa-bar-chart"></i> <span class="menu-item-parent">Estadisticas</span></a>
											<ul>

												{{-- @if(Sentinel::hasAccess('maillingEstadisticasSimples.view'))
												<li class="{{ Request::is('maillingEstadisticasSimples') ? 'active' : '' }}">
													<a href="{{ route('mailling/maillingEstadisticasSimples') }}"><i class="fa fa-bar-chart"></i> Campañas</a>
												</li>
												@endif --}}

												@if(Sentinel::hasAccess('maillingEstadisticasReport.view'))
												<li class="{{ Request::is('maillingEstadisticasReport') ? 'active' : '' }}">
													<a href="{{ route('mailling/maillingEstadisticasReport') }}"><i class="fa fa-bar-chart"></i> Campañas</a>
												</li>
												@endif

												@if(Sentinel::hasAccess('maillingEstadisticasAbReport.view'))
												<li class="{{ Request::is('maillingEstadisticasAbReport') ? 'active' : '' }}">
													<a href="{{ route('mailling/maillingEstadisticasAbReport') }}"><i class="fa fa-bar-chart"></i> Campañas AB</a>
												</li>
												@endif


												{{-- @if(Sentinel::hasAccess('maillingEstadisticas.view'))
												<li class="{{ Request::is('maillingEstadisticas') ? 'active' : '' }}">
													<a href="{{ route('mailling/maillingEstadisticas') }}"><i class="fa fa-bar-chart"></i> Campañas AB </a>
												</li>
												@endif --}}

											</ul>
										</li>
									@endif								
								</ul>
							</li>
						@endif
						
                    </ul>
                </li>
            </ul>
		@endif
		
	</nav>
    <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
<!-- END NAVIGATION -->