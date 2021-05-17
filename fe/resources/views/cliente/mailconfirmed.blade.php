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
                    <li><strong>Confirmar cuenta</strong></li>
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
			
            
            <div class="{!!$data['class']!!}">
                <span class="alert-icon"><i class="fa fa-warning"></i></span>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{!!$data['noti']!!}</strong>{!! $data['msg'] !!}
            </div>
			
			@if($data['status']==0)
                <a href="{{ route('login') }}" class="btn btn-danger" role="button">
                                <i class="fa fa-lock"></i> Ingresar
                </a>
            @endif
			
        </div>
    </div>
</section>
<!-- Main Container End -->
@stop
