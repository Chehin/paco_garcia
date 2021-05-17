@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>        
                    <li><a href="{{ route('home') }}">Inicio</a><span>»</span></li>
                    <li><a href="{{ route('cuenta') }}">Mi cuenta</a><span>»</span></li>
                    <li><strong>Mis Direcciones</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->
<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
            <h2 class="title custom"><i class="fa fa-map-marker"></i> Mis Direcciones</h2>
            <div class="row">
             @if($data['data'])
                @foreach($data['data'] as $dir)
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="panel dire panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>{!! $dir['titulo'] !!}</strong></h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li> <span> <strong>{!! $dir['direccion'] !!} {!! $dir['numero'] !!}</strong></span></li>
                                <li> <span> <strong>Provincia</strong>: {!! $dir['provincia'] !!}</span></li>
                                <li> <span> <strong>Ciudad</strong>: {!! $dir['ciudad'] !!}</span></li>
                                <li> <span><strong>Código postal</strong>: {!! $dir['cp'] !!}</span></li>
                                <li> <span> <strong>Teléfono</strong>: {!! $dir['telefono'] !!}</span></li>
                                <li> <span> {!! $dir['informacion_adicional'] !!}</span></li>
                            </ul>
                        </div>
                        <div class="panel-footer panel-footer-address">
                            <a href="{{ route('editar_direccion',['id' => $dir['id'] ]) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-edit"> </i> Editar 
                            </a>
                            <a href="{{ route('borrar_direccion',['remove' => $dir['id'] ]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Está seguro que quiere eliminar está dirección?')">
                                <i class="fa fa-minus-circle"></i> Borrar 
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="">
                    <h4>No hay direcciones cargadas</h4>
                </div>
            @endif
               
                <div class="">
                    <a class="btn btn-success" href="{{ route('agregar_direccion') }}">
                        <i class="fa fa-plus-circle"></i> Agregar Nueva Dirección
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
