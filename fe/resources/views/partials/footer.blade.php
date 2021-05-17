<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-12 col-lg-3">
                <div class="footer-logo" style="padding-right: 70px;">
                    <a title="{{env('SITE_NAME')}}" href="{{ route('home') }}">
                        <img alt="{{env('SITE_NAME')}}" src="images/Paco-garcia-logo-header.png">
                    </a>
                </div>
                <br>
                <br>
                <div class="social">
                    <ul class="inline-mode pull-left">
                        <li class="social-network fb">
                            <a title="Connect us on Facebook" target="_blank" href="https://www.facebook.com/pacogarciaweb/">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="social-network instagram">
                            <a title="Connect us on Instagram" target="_blank" href="https://www.instagram.com/paco_garcia_deportes/">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                    </ul>

                    <br><br><br>
                </div>
                
                <div class="pull-left" style="margin-left: 8px;">
                    <a href="http://qr.afip.gob.ar/?qr=ymoPZavuXn42z3poh-2OwQ,," target="_F960AFIPInfo"><img src="https://www.afip.gob.ar/images/f960/DATAWEB.jpg" border="0" style="width:19%;margin-right: 20px;"></a>
                
                    <a href="https://www.cybermonday.com.ar/" target="_blank"><img src="images/cybermonday.png"></a>
                </div>
            </div>

            @if(isset($menu_footer))
            @if(isset($menu_footer['institucional']['nota']))
            <div class="col-sm-6 col-md-3 col-xs-12 col-lg-3 collapsed-block">
                <div class="footer-links">
                    <h3 class="links-title">{{$menu_footer['institucional']['seccion']['seccion']}} <a class="expander visible-xs"
                            href="#TabBlock-3">+</a></h3>
                    <div class="tabBlock" id="TabBlock-3">
                        <ul class="list-links list-unstyled">
                            @foreach($menu_footer['institucional']['nota'] as $empresa)
                            <li><a href="{{route('nota',['id' => $empresa['id'],'name' => str_slug($empresa['titulo'])])}}">{{
                                    $empresa['titulo'] }}</a></li>
                            @endforeach
                            <li><a href="{{ route('contacto') }}">Contacto</a></li>
                            {{-- <li><a href="{{ route('blog', ['page' => 1]) }}">Blog</a></li>    --}}         
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($menu_footer['ayuda']['nota']))
            <div class="col-sm-6 col-md-3 col-xs-12 col-lg-3 collapsed-block">
                <div class="footer-links">
                    <h3 class="links-title">{{$menu_footer['ayuda']['seccion']['seccion']}} <a class="expander visible-xs"
                            href="#TabBlock-3">+</a></h3>
                    <div class="tabBlock" id="TabBlock-3">
                        <ul class="list-links list-unstyled">
                            @foreach($menu_footer['ayuda']['nota'] as $empresa)
                            <li><a href="{{route('nota',['id' => $empresa['id'],'name' => str_slug($empresa['titulo'])])}}">{{
                                    $empresa['titulo'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
			@endif
			@if(isset($menu_footer['sucursales']['nota']))
            <div class="col-sm-6 col-md-3 col-xs-12 col-lg-3 collapsed-block">
                <div class="footer-links">
                    <h3 class="links-title">Sucursales<a class="expander visible-xs" href="#TabBlock-1">+</a></h3>
                    <div class="tabBlock" id="TabBlock-1">
                        <ul class="list-links list-unstyled">
							@foreach($menu_footer['sucursales']['nota'] as $empresa)
							<li><a href="{{route('notaSuc',['id' => $empresa['id'],'name' => str_slug($empresa['titulo'])])}}">{{$empresa['sumario']}}, {{$empresa['ciudad']}}</a></li>
							@endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
		@endif
    </div>
    <div class="footer-coppyright">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-xs-12 coppyright text-left">
                    Todos los derechos reservados - Paco Garcia 2018
                </div>
		<div class="col-sm-6 col-xs-12 coppyright text-right">
                    Desarrollado por <a href="https://webexport.com.ar" target="_blank">WebExport</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<a href="javascript:void(0);" id="back-to-top" title="Volver a arriba"><i class="fa fa-angle-up"></i></a>