@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>                
                    <li><a href="{{route('home')}}">Inicio</a><span>»</span></li>
                    <li><strong>Checkout</strong></li>
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
        	<div class="bg-{!!$estado_color!!} text-center" style="width: 44px;margin: 0 auto;height: 44px;border-radius: 30px;padding: 11px 0;border:1px solid #ccc;">
			<i class="fa {!!$icon!!} fa-2x"></i>
			</div>
			<h1 class="title mb15 text-center" style="font-size: 40px;">{!!$estado!!}</h1>
			<h3 class=" mb15 text-center text-uppercasse">{!!$estado_detalle!!}</h3>
			<div class="text-center">
				<a href="{{route('cuenta')}}" class="btn btn-success">Mi cuenta</a>
			</div>
        </div>
    </div>
</section>

@stop
