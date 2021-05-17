<div id="mobile-menu">
    <ul>
        <li><a href="{{ route('home') }}">Inicio</a></li>
        @if(isset($menu_web['etiquetas']))
        @foreach($menu_web['etiquetas'] as $menu)
        <li>
            <a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($menu['nombre']), 'page' => 1])}}">{{ $menu['nombre'] }}</a>
            @if(isset($menu['rubros']))
            <ul>
                @foreach($menu['rubros'] as $rubros)
                <li>
                    <a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => $rubros['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubros['nombre']), 'page' => 1])}}" class="">{{ $rubros['nombre'] }}</a>
                    @if(isset($rubros['subrubros']))
                    <ul>
                        @foreach($rubros['subrubros'] as $subrubros)
                        <li><a href="{{route('productos',['id_etiqueta' => $menu['id'], 'id_rubro' => $rubros['id'] , 'id_subrubro' => $subrubros['id'], 'name' => str_slug($subrubros['nombre']), 'page' => 1])}}">{{ $subrubros['nombre'] }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
        @endif
        @if(isset($menu_web['marcas']))
        <li>
            <a href="javascript:void(0);">Marcas</a>
            <ul>
                @foreach($menu_web['marcas'] as $marca)
                <li><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($marca['nombre']), 'page' => 1])}}?marca={{ $marca['id'] }}">{{ $marca['nombre'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
        @if(isset($menu_web['deportes']))
        <li>
            <a href="javascript:void(0);">Deportes</a>
            <ul>
                @foreach($menu_web['deportes'] as $deporte)
                <li><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($deporte['nombre']), 'page' => 1])}}?deporte={{ $deporte['id'] }}">{{ $deporte['nombre'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
        @if(isset($menu_web['etiquetas_destacadas']))
        @foreach($menu_web['etiquetas_destacadas'] as $etiqueta_dest)
        <li class="menu-{{$etiqueta_dest['color']}}">
            <a href="{{route('productos',['id_etiqueta' => $etiqueta_dest['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta_dest['nombre']), 'page' => 1])}}">{{$etiqueta_dest['nombre']}}</a>
        </li>
        @endforeach
        @endif
    </ul>
</div>