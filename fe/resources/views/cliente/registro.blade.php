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
					<li><strong>Ingresar</strong></li>
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
				<div class="col-md-10 col-lg-10 col-xs-10">
                    <form method="POST">
					
                        @if($data!='')
                        <div class="{!!$data['class']!!} col-md-9">
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

							<div class="">
								<div class="interna-title">
									<h4>Registrarse</h4>
								</div>
							</div>
							<input type="hidden" name="desdelogin" value="1">
							<div class="loguin">
								<div class="col-md-6">
									<label>Nombre <span class="required">*</span></label>
									<div class="">
										<input type="text" name="nombre" class="form-control" value="" required>
									</div>
								</div>
								<div class="col-md-6">
									<label>Apellido <span class="required">*</span></label>
									<div class="input-text">
										<input type="text" name="apellido" class="form-control" value="" required>
									</div>
								</div>
								<div class="col-md-6">
									<label>Teléfono <span class="required">*</span></label>
									<div class="input-text">
										<input type="text" name="telefono" class="form-control" value="" required>
									</div>
								</div>
								<div class="col-md-6">
									<label>E-mail <span class="required">*</span></label>
									<div class="input-text">
										<input type="email" name="email" class="form-control" value="" required>
									</div>
								</div>
								
								<div class="col-md-6">
									<label>Contraseña <span class="required">*</span></label>
									<div class="input-text">
										<input type="password" name="password" class="form-control" required>
									</div>
								</div>			
								<div class="col-md-6">
									<label>Repetir E-mail <span class="required">*</span></label>
									<div class="input-text">
										<input type="email" name="reemail" class="form-control" required>
									</div>
								</div>					
								<div class="col-md-6">
									<label>Repetir contraseña <span class="required">*</span></label>
									<div class="input-text">
										<input type="password" name="repassword" class="form-control" required>
									</div>
								</div>
							</div>
							
							<div class="col-xs-12">
								<div class="billing-checkbox">
									<label class="inline" for="politicas">
										<input type="checkbox" value="yes" id="politicas" name="politicas" required>
										He leído y acepto la <a href="{{route('nota',['id' => 412,'name' => 'politicas-de-privacidad' ])}}" target="_blank">Política de Privacidad</a>.
									</label>
								</div>
								<div class="billing-checkbox">
									<label class="inline" for="newsletter">
										<input type="checkbox" value="yes" id="newsletter" name="newsletter">
										Deseo recibir ofertas y novedades.
									</label>
								</div>
								<div class="submit-text">
									<button class="button"><i class="fa fa-user"></i>&nbsp;Registrarse</button>
								</div>
							</div>
						</div>
                    </form>
                    
                  {{--   <div class="box-authentication">				
						
                        <div class="">
                            <div class="interna-title"> <br>
                                <h4>Registrarse con</h4>
                            </div>
                        </div>
                        
                        <a  class="btn btn-facebook" href="{{ route('auth',['provider'=>'facebook']) }}"><span class="fa fa-facebook"></span> Ingresar con Facebook</a><br><br>
                
                        <div style="margin-left: 79px;">- O -</div>	<br>	
                        
                        <a  class="btn btn-google" href="{{ route('auth',['provider'=>'google']) }}"> <span class="fa fa-google"></span> Ingresar con Google</a> <br> <br>
                        
                        
                </div> --}}
				</div>
			</div>

			
		</div>
	</div>
</section>
<!-- Main Container End --> 

@stop
