@php
    $idNota = $nota['id_nota']; 
    $idSeccion = $nota['id_edicion'];
@endphp
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
                    <li class=""> <a title="" href="{{route('notas',['id' => $nota['id_seccion'],'name' => str_slug($nota['seccion'])])}}">{{ $nota['seccion'] }}</a><span>»</span></li>
                    <li><strong>{{ $nota['titulo'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="blog_post">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="entry-detail">
                    <div class="page-title">
                        <h1>{{ $nota['titulo'] }}</h1>
                    </div>
                    <div class="row">
                        @if ($fotos)
                        <div class="entry-photo pull-left col-sm-5">
                            <div class="row">
                                @php $i=0; @endphp
                                @foreach($fotos as $foto)
                                <figure class="mb20 {{ $i>0?'col-sm-4':'col-sm-12' }}">
                                    <a href="{{ env('URL_BASE_UPLOADS').$foto['imagen_file'] }}" class="foto_modal">
                                        <img src="{{ env('URL_BASE_UPLOADS').$foto['imagen_file'] }}" alt="{{$foto['epigrafe']}}">
                                    </a>
                                </figure>
                                @php $i++; @endphp
                                @endforeach
                            </div>
                        </div>    
                        @endif
                        <div class="{{ ($fotos?'col-sm-7':'col-sm-12') }} col-xs-12">
                            <p>{{$nota['sumario']}}</p>
                            <p>{!!$nota['texto']!!}</p>
                            <div class="pull-right mt20 big">
                                @include('partials.share',['titulo' => $nota['titulo']])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade text-center" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div style="display:inline-block;position:relative;">
        <button style="position: absolute;top: 10px;right: 15px;opacity: .6;" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" id="imagepreview" style="padding: 10px;max-height: 100%;">
    </div>
</div>	
@stop
@section('javascript')
<script>
jQuery(document).ready(function() {
    $("a.foto_modal").on("click", function(e) {
        $('#imagepreview').attr('src', $(this).attr('href')); 
        $('#imagemodal').modal('show');
        e.preventDefault()
    });
});
</script>
@stop

@section('scriptExtra')
    @include('notas.nota_scripts')
@stop