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
                    <li><strong>Blog</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="blog_post">
	<div class="container"> 
		<!-- row -->
		<div class="row"> 
			<!-- Center colunm-->
			<div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            @if($data['nota'])
                            @foreach($data['nota'] as $nota)
                                <article class="entry">
                                    
                                    <div class="entry-media">
                                    @if($nota['fotos'])
                                        <figure>
                                            <a href="{{ route('blog_nota' ,['id_nota' => $nota['id'],'name' => str_slug($nota['titulo'])]) }}"><img src="{{env('URL_BASE_UPLOADS').$nota['fotos'][0]['imagen_file']}}" alt="{{ isset($nota['fotos'][0]['epigrafe'])?$nota['fotos'][0]['epigrafe']:'' }}"></a>
                                        </figure>                                    
                                        <div class="entry-meta">
                                            <span><i class="fa fa-calendar"></i>{{$nota['fecha']['dia']}} {{$nota['fecha']['mes']}}, {{$nota['fecha']['anio']}}</span>
                                        </div><!-- End .entry-meta -->
                                    @endif
                                    </div><!-- End .entry-media -->

                                    <h4 class=""><a href="{{ route('blog_nota' ,['id_nota' => $nota['id'],'name' => str_slug($nota['titulo'])]) }}">{{ $nota['titulo'] }}</a></h4>
                                    <div class="entry-content">
                                        <p>{{ $nota['sumario'] }}</p>
                                        <a href="{{ route('blog_nota' ,['id_nota' => $nota['id'],'name' => str_slug($nota['titulo'])]) }}" class="readmore">Leer más<i class="fa fa-angle-right"></i></a>
                                    </div><!-- End .entry-content -->
                                </article>
                            @endforeach
                            @else
                                <h3>No hay notas</h3>
                            @endif
                            
                        </div><!-- End .col-md-9 -->

                        <aside class="col-md-3 sidebar">
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
                           
                            @if($etiquetas)
                            <div class="widget">
                                <h3 class="widget-title">Etiquetas</h3>
                                <div class="tagcloud">
                                    @foreach($etiquetas as $etiqueta)
                                    <a class="{{ $filtros['tag']==$etiqueta['id']?'active':'' }}" href="{{ route('blog', ['page' => 1]) }}?tag={{ $etiqueta['id'] }}">{{ $etiqueta['text'] }}</a>
                                    @endforeach
                                </div><!-- End .tagcloud -->
                            </div><!-- End .widget -->
                           @endif
                        </aside>
                    
                    </div><!-- End .row -->
                </div><!-- End .container -->
			<!-- ./ Center colunm --> 
		</div>
		<!-- ./row--> 
	</div>
</section>
 
@stop
