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
                    <li><strong>Editar dirección</strong></li>
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
                           @if($data['msg']!='')
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
                            <input type="hidden" name='idE' value="{!! $data['direccion']['id'] !!}">
                            <div class="col-sm-6">
                                <label>Dirección *:</label>
                                <div class="input-text">
                                    <input type="text" name="direccion" class="form-control" value="{!! $data['direccion']['direccion'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Número *:</label>
                                <div class="input-text">
                                    <input type="text" name="numero" class="form-control" value="{!! $data['direccion']['numero'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Piso:</label>
                                <div class="input-text">
                                    <input type="text" name="piso" class="form-control" value="{!! $data['direccion']['piso'] !!}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Departamento:</label>
                                <div class="input-text">
                                    <input type="text" name="departamento" class="form-control" value="{!! $data['direccion']['departamento'] !!}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Teléfono *:</label>
                                <div class="input-text">
                                    <input type="text" name="telefono" class="form-control" value="{!! $data['direccion']['telefono'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Código postal *:</label>
                                <div class="input-text">
                                    <input type="text" name="cp" class="form-control" value="{!! $data['direccion']['cp'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Provincia *</label>                
                                <select class="form-control" name="provincia">
                                    <option value="">Seleccionar provincia</option>
                                     @foreach($data['provincias'] as $clave => $valor)
                                        <option value="{!!$clave!!}" {{ $data['direccion']['id_provincia'] == $clave ? 'selected': '' }} >{!!$valor!!}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Ciudad *:</label>
                                    <select class="form-control" id="ciudad" name="ciudad" required>
                                    @if(isset($data['direccion']['id_localidad']))
                                        @foreach($data['localidades'] as $clave => $valor)
                                            <option value="{!!$clave!!}" {{ $data['direccion']['id_localidad'] == $clave ? 'selected': '' }}>{!!$valor!!}</option>
                                        @endforeach
                                    @endif
                                    </select>
                            </div>
                            <div class="col-sm-12">
                                <label>Asigne un título de direcciones para futuras referencias *:</label>
                                <div class="input-text">
                                    <input type="text" name="titulo" class="form-control" value="{!! $data['direccion']['titulo'] !!}" required placeholder="Ej: Mi casa">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label>Información adicional:</label>
                                <div class="input-text">
                                    <textarea name="informacion_adicional" rows="3" class="form-control" placeholder="Ej: Puerta azul">{!! $data['direccion']['informacion_adicional'] !!}</textarea>
                                </div>
                            </div>
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
