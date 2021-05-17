<header>
    <div class="header-container">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-md-5 hidden-xs"></div>
                    <!-- top links -->
                    <div class="headerlinkmenu col-lg-8 col-md-7 col-sm-8 col-xs-12">
                        <ul class="links">
                            <li>
                                <a title="Mandanos tu consulta" href="https://api.whatsapp.com/send?phone=5493815855512" target="_blank">
                                    <span>
                                        <i class="fa fa-whatsapp"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a title="Seguinos en Facebook" href="https://www.facebook.com/pacogarciaweb/" target="_blank">
                                    <span>
                                        <i class="fa fa-facebook"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a title="Seguinos en Instagram" href="https://www.instagram.com/paco_garcia_deportes/" target="_blank">
                                    <span>
                                        <i class="fa fa-instagram"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="hidden-xs">
                                <a title="Correo contacto" href="{{ route('contacto') }}" target="_blank">
                                    <span>
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </a>
                            </li>
                            {{-- <li><a title="Comprar" href="#"><span>Comprar</span></a></li> --}}
                            @if (isset($_SESSION['email']))
                            <li>
                                <div class="dropdown">
                                    <a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:void(0);">
                                        <span>Mi Cuenta</span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('cuenta') }}">Perfil</a></li>
                                        <li class="divider"></li>
                                        <li><a href="{{ route('logout') }}">Salir</a></li>
                                    </ul>
                                </div>
                            </li>
                            @else
                            <li><a title="Ingresar" href="{{ route('login') }}"><span>Ingresar <i class="fa fa-user-o hidden-xs" aria-hidden="true"></i></span></a></li>
                            <li><a title="Ingresar" href="{{ route('registro') }}"><span>Registrarse </span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3 col-xs-12">
                    <!-- Header Logo -->
                    <div class="logo">
                        <a title="{{env('SITE_NAME')}}" href="{{ route('home') }}">
                            <img alt="{{env('SITE_NAME')}}" src="images/Paco-garcia-logo-header.png">
                        </a>
                    </div>
                    <!-- End Header Logo -->
                </div>
                <div class="col-sm-3 col-md-5 col-lg-1 col-md-1"></div>
                <div class="col-xs-2 visible-xs-block visible-sm-block">
                    <div class="mm-toggle-wrap">
                        <div class="mm-toggle"> <i class="fa fa-align-justify"></i> </div>
                    </div>
                </div>
                <div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 top-search">
                    @if(Carbon\Carbon::now('America/Argentina/Buenos_Aires') < Carbon\Carbon::create(2019, 11, 04, 0, 0, 0, 'America/Argentina/Buenos_Aires'))
                    <div class="header_cybermonday">
                        <img src="images/cybermonday_logo.png" alt="" width="70" class="cmimg">
                        <div class="cont_cm">
                            <h3>Faltan:</h3> 
                            <div class="jtv-box-timer">
                                <div class="countbox_1 jtv-timer-grid"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Search -->
                    <div id="search" class="{{ (Carbon\Carbon::now('America/Argentina/Buenos_Aires') < Carbon\Carbon::create(2019, 11, 04, 0, 0, 0, 'America/Argentina/Buenos_Aires'))?'hidden-xs':'' }}">
                        {{-- <div class="ui-widget"> --}}
                            <form id="search_bar" action="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug('productos'), 'page' => 1])}}">
                                <div class="input-group">
                                    <input type="text"  class="form-control" placeholder="Ingresa lo que estás buscando" name="q" id="q" value="{{isset($search)?($search?urldecode($search):''):''}}">
                                    <button class="btn-search" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        {{-- </div> --}}
                    </div>

                    <!-- End Search -->
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"></div>
                <div class="col-lg-1 col-xs-2 col-sm-1 top-cart cart_box">
                    <div class="top-cart-contain">
                        <div class="mini-cart">
                            <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle">
                                <a href="javascript:void(0);">
                                    <div class="cart-icon">
                                        <i class="icon-basket-loaded icons"  id="cart-icon"></i>
                                        <span class="cart-total badge">@if(isset($_SESSION['carrito']['carrito'])) {!! count($_SESSION['carrito']['carrito']) !!}  @else 0 @endif</span>
                                    </div>
                                </a>
                            </div>
                            <div>
                                <div class="top-cart-content">
                                        <div class="page-loader l-cart">
                                                <div class="spinner">
                                                  <div class="dot1"></div>
                                                  <div class="dot2"></div>
                                                </div>
                                        </div>
                                    <div class="block-subtitle hidden-xs">Productos en Carrito</div>
                                    
                                        <ul id="cart-product-list" class="cart-sidebar mini-products-list">                       
                                        </ul>
                                
                                    <div class="top-subtotal">
                                        Subtotal: <span class="cart-total"><span class="total"></span></span>
                                    </div>
                                    <div class="actions">
                                        <a class="btn-checkout" href="{{ route('procesar_pedido',['id' => 1 ]) }}">
                                            <i class="fa fa-check"></i>
                                            <span>Comprar</span>
                                        </a>
                                        <form action="{{route('cart')}}" method="POST">
                                            <button class="view-cart" type="submit">
                                                 <i class="fa fa-shopping-cart"></i>
                                                 Ver Carrito
                                            </button>
                                            <input type="hidden" id="idProd" name="idProd">
                                           {{--  <a class="view-cart" href="">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span>Ver Carrito</span>
                                            </a> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>