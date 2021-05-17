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
				<div class="box-authentication interna-title">
					<h4>Ingresar</h4> <br>
					@if(isset($data))
						@if($data!='')
							<div class="alert alert-danger alert-dismissable col-xs-11">
									<span class="alert-icon"><i class="fa fa-warning"></i></span>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>¡Atención!</strong> {!!$data['msg']!!}
							</div>
						@endif
					@endif
					
					
					<form method="POST" id="login">			
						<div class="col-sm-12">
							<label for="emmail_login"> E-mail <span class="required">*</span></label>
							<input id="emmail_login" name="email" type="email" class="form-control" required="required">
						</div>
						<div class="col-sm-12">
							<label for="password_login"> Contraseña <span class="required">*</span></label>
							<input id="password_login" name="password" type="password" class="form-control" required="required">
						</div>
						<div class="col-sm-6">
							<label></label>
							{{-- <div class="g-recaptcha" id="captcha_form" data-sitekey="6LdnR1cUAAAAADi_KTy4LrzowhQ-abPEm0fKu9rv"></div> --}}
							<div class="g-recaptcha" id="captcha_form" data-sitekey="6Lcd6-kUAAAAAFLVF0wHVbQ86_rTI3mZIzY8kB2b"></div>
							
						</div>
						<div class="col-sm-12">
							<p class="forgot-pass"><a href="recuperar_pass">¿Olvidó su contraseña?</a></p>
							<button onclick="gtagLogin()" class="button"><i class="fa fa-lock"></i>&nbsp;Ingresar</button>
						</div>
					</form>
				</div>	
				
				{{-- <div class="box-authentication">				
						
							<div class="">
								<div class="interna-title">
									<h4>Iniciar con</h4>
								</div>
							</div>
							
							<a  class="btn btn-facebook" href="{{ route('auth',['provider'=>'facebook']) }}"><span class="fa fa-facebook"></span> Ingresar con Facebook</a><br><br>
					
							<div style="margin-left: 79px;">- O -</div>	<br>	
							
							<a  class="btn btn-google" href="{{ route('auth',['provider'=>'google']) }}"> <span class="fa fa-google"></span> Ingresar con Google</a> <br> <br>
							
							
					</div> --}}
					
				</div>
			</div>
	</div>
</section>
<!-- Main Container End --> 

@stop
@section('javascript')
<script>
$('#login').submit(function() {
	var $form		= $(this);
	var $dataStatus	= $form.find('.data-status');

	var response = grecaptcha.getResponse();
	if(response.length == 0){
		$dataStatus.show().html('<div class="alert alert-danger"><strong>Por favor verifique que no es un robot</strong></div>');
		return false;
	}
});

function gtagLogin(){
	gtag('event', 'login',{ 'method': 'Sitio' });
}

</script>
@stop