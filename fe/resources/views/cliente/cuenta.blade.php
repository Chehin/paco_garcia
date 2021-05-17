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
                    <li><strong>Mi cuenta</strong></li>
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
            <h2 class="title custom"><i class="fa fa-unlock-alt"></i> @if ($_SESSION) {!! $_SESSION['nombre'] !!} {!! $_SESSION['apellido'] !!} @endif - Mi cuenta</h2>
            <p><strong>BIENVENIDO A SU CUENTA. AQUÍ USTED PUEDE ADMINISTRAR TODA SU INFORMACIÓN PERSONAL Y PEDIDOS.</strong></p>
            <hr />
            <ul class="myAccountList row">
                <li class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Pedidos" href="{{ route('historial') }}">
                            <i class="fa fa-calendar v-icon"></i> Historial de Pedidos </a>
                    </div>
                </li>
                <li class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Mis direcciones" href="{{ route('direcciones') }}">
                            <i class="fa fa-map-marker v-icon"></i> Mis direcciones</a>
                    </div>
                </li>
                <li class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Agregar dirección" href="{{ route('agregar_direccion') }}"> 
                            <i class="fa fa-edit v-icon"> </i> Agregar dirección
                        </a>
                    </div>
                </li>
                <li class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Información persona" href="{{ route('perfil') }}">
                            <i class="fa fa-cog v-icon"></i> Información personal
                        </a>
                    </div>
                </li>
                <li class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Información persona" href="{{ route('tracking') }}">
                            <i class="fa fa-archive v-icon"></i> Seguimiento Mis Envios
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

@stop
