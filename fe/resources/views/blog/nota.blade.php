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
                    <li><strong><a href="{{ route('blog', ['page' => 1]) }}">Blog</a></strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-9"><br><br>
            <article class="entry">
                
                @if($nota['fotos'])
                <div class="entry-media">
                    <figure>
                            <a href="{{ route('blog_nota' ,['id_nota' => $nota['id'],'name' => str_slug($nota['titulo'])]) }}"><img src="{{env('URL_BASE_UPLOADS').$nota['fotos'][0]['imagen_file']}}" alt="{{ isset($nota['fotos'][0]['epigrafe'])?$nota['fotos'][0]['epigrafe']:'' }}"></a>
                    </figure>
                </div><!-- End .enty-media -->
                @endif

                <h2 class="entry-title">{{ $nota['titulo'] }}</h2>
                <div class="entry-meta">
                    <span><i class="fa fa-calendar"></i>{{$nota['fecha']['dia']}} {{$nota['fecha']['mes']}}, {{$nota['fecha']['anio']}}</span> <br>
                </div><!-- End .entry-meta -->
                
                <div class="entry-content"><br>
                    {{ $nota['sumario'] }} <br><br>
                    
                    
                    {!! $nota['texto'] !!}
                </div><!-- End .entry-content -->

                <div class="text-right">
                        @if($etiquetas)
                        <div class="sidebar">
                                <div class="widget">
                                        <div class="tagcloud">
                                            @foreach($etiquetas as $etiqueta)
                                            <a class="{{ $filtros['tag']==$etiqueta['id']?'active':'' }}" href="{{ route('blog', ['page' => 1]) }}?tag={{ $etiqueta['id'] }}">{{ $etiqueta['text'] }}</a>
                                            @endforeach
                                        </div><!-- End .tagcloud -->
                                </div><!-- End .widget -->
                        </div>                        
                       @endif

                       @include('partials.share')
                </div>
            </article>

            @include('partials.relacionados')            
        </div><!-- End .col-md-9 -->

        <aside class="col-md-3 sidebar"> <br><br>
            <div class="widget search-widget">
                <form action="{{ route('blog', ['page' => 1]) }}">
                    <input type="search" class="form-control" placeholder="Buscar..." required>
                    <button type="submit" class="btn btn-link"><i class="fa fa-search"></i></button>
                </form>
            </div><!-- End .widget -->

            @if($archivos)
            <div class="widget">
                <h3 class="widget-title">Archivos</h3>
                <ul class="fa-ul">
                    @php $anio = 0; @endphp
                    @foreach($archivos as $archivo)
                    @if($archivo['year'] != $anio)
                    @php $anio = $archivo['year']; @endphp
                        <li><b>{{ $anio }}</b></li>
                    @endif
                        <li><i class="fa-li fa fa-chain"></i> <a href="{{ route('blog', ['page' => 1]) }}?a={{ $archivo['year'] }}&m={{ $archivo['month'] }}">{{ $archivo['month_name'] }} ({{ $archivo['post_count'] }})</a></li>
                    @endforeach
                </ul>
            </div><!-- end .widget -->
            @endif
           
            @if($etiquetas_all)
            <div class="widget">
                <h3 class="widget-title">Etiquetas</h3>
                <div class="tagcloud">
                    @foreach($etiquetas_all as $etiqueta)
                    <a class="{{ $filtros['tag']==$etiqueta['id']?'active':'' }}" href="{{ route('blog', ['page' => 1]) }}?tag={{ $etiqueta['id'] }}">{{ $etiqueta['text'] }}</a>
                    @endforeach
                </div><!-- End .tagcloud -->
            </div><!-- End .widget -->
           @endif
         
        </aside>
    </div><!-- end .row -->
</div><!-- End .container -->
 
@stop
