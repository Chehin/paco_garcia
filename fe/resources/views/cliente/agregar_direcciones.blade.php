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
                    <li><strong>Agregar dirección</strong></li>
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
            <div class="account-login">
                <div class="new-customer-box Account Page ">
                    <form method="POST" action="{{ route('agregar_direccion') }}">
                        <div class="row">
                        @if($data!='')
                            <div class="{!!$data['class']!!}">
                                    <span class="alert-icon"><i class="fa fa-warning"></i></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{!!$data['noti']!!}</strong> 
                                    @if(is_array( $data['msg']) )
                                        <ul class="list">
                                        @foreach($data['msg'] as $err)
                                            @foreach($err as $valor)
                                                <li>{!!$valor!!}</li>
                                            @endforeach
                                        @endforeach
                                        </ul>
                                    @else
                                        {!! $data['msg'] !!}                                                                             
                                    @endif
                            </div>
                        @endif

                       

                            <div class="col-xs-12">
                                <div class="check-title">
                                    <h4>Agregar dirección</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Dirección *:</label>
                                <div class="input-text">
                                    <input type="text" name="direccion" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Número *:</label>
                                <div class="input-text">
                                    <input type="text" name="numero" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Piso:</label>
                                <div class="input-text">
                                    <input type="text" name="piso" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Departamento:</label>
                                <div class="input-text">
                                    <input type="text" name="departamento" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Teléfono *:</label>
                                <div class="input-text">
                                    <input type="text" name="telefono" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Código postal *:</label>
                                <div class="input-text">
                                    <input type="text" name="cp" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Provincia *</label>                
                                <select class="form-control" name="provincia">
                                    <option value="">Seleccionar provincia</option>
                                     @foreach($direcciones['provincias'] as $clave => $valor)
                                        <option value="{!!$clave!!}">{!!$valor!!}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Ciudad *:</label>
                                <div class="input-text">
                                    <select class="form-control" id="ciudad" name="ciudad" style="display: none;"></select>
                                    <input type="text" id="ciudad_text" name="ciudad_text" class="form-control" value="" required>
                                </div>
                            </div>
                
                            <div class="col-sm-12">
                                <label>Asigne un título de direcciones para futuras referencias *:</label>
                                <div class="input-text">
                                    <input type="text" name="titulo" class="form-control" value="" required placeholder="Ej: Mi casa">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Información adicional:</label>
                                <div class="input-text">
                                    <textarea name="informacion_adicional" rows="3" class="form-control" placeholder="Ej: Puerta azul"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name='idE' value="">
                            <input type="hidden" name="returnTo" value="{{ (isset($_GET['returnTo'])) ? $_GET['returnTo'] : '' }}">
                            <input type="hidden" name="id" value="{{ (isset($_GET['id'])) ? $_GET['id'] : '' }}">
                            <div class="col-xs-12">
                                <div class="submit-text">
                                    <button class="button"><i class="fa fa-user"></i>&nbsp; <span>Guardar</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main Container End -->

@stop
