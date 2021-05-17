<?php $company = \App\AppCustom\Util::getCompanyDataByUrl(\URL::to('/')); ?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> {{ $company['company']->name_org }} </title>
		<meta name="description" content="">
		<meta name="author" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.css">	
		
		<!-- SmartAdmin RTL Support is under construction
			<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->
		
		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/demo.css">
		<!-- page related CSS -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/lockscreen.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico?v=1" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico?v=1" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">


	</head>
	<body>
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<form action="{{ URL::to('login') }}" method="post" id="login-form" class="smart-form lockscreen animated flipInY client-form">
			<div class="logo text-center">
				<img src="{{ $company['logos']['logo'] }}" alt="{{ $company['company']->name_org }}" style="width:302px"/>
			</div>
			<div>
				<div>
					<h4>Iniciar sesi&oacute;n</h4>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<?php 
					$route = \Request::route()->getName();
					?>
					<input type="hidden" name="returnTo" value="{{ !empty($route) && $route != 'login' ? $route : ''  }}">
					<fieldset>
						@if($errors->any())
						<div class="alert alert-danger">
							<button class="close" data-dismiss="alert">×</button>
							@foreach ($errors->all() as $error)
								<p><i class="fa-fw fa fa-times"></i> {{ $error }}</p>
							@endforeach
						</div>
						@endif
						<section>
							<label class="label">E-mail</label>
							<label class="input"> 
								<i class="icon-append fa fa-user"></i>
								<input type="text" name="email">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Por favor ingrese su E-mail
								</b>
							</label>
						</section>
						<section>
							<label class="label">Contrase&ntilde;a</label>
							<label class="input"> 
								<i class="icon-append fa fa-lock"></i>
								<input type="password" name="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Ingrese su contrase&ntilde;a
								</b> 
							</label>
							<div class="note">
								<a href="{{ route('passwordForgot') }}">&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
							</div>
						</section>
						<section>
							<label class="checkbox">
								<input type="checkbox" name="remember" checked="">
								<i></i>No cerrar sesi&oacute;n
							</label>
						</section>
					</fieldset>
					<footer>
						<button type="submit" class="btn btn-primary">Iniciar sesi&oacute;n</button>
					</footer>
				</div>
			</div>
			<p class="font-xs margin-top-5">Desarrollado por <a href="http://www.webexport.com.ar" target="_blank">WebExport</p>
		</form>

		
		<!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script src="js/plugin/pace/pace.min.js"></script>

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->		
		<script src="js/bootstrap/bootstrap.min.js"></script>

		
		<!-- JQUERY VALIDATE -->
		<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
		
		<!-- browser msie issue fix -->
		<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		
		<!-- FastClick: For mobile devices -->
		<script src="js/plugin/fastclick/fastclick.js"></script>
		
		<!--[if IE 7]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="js/app.js"></script>

		<script type="text/javascript">
			runAllForms();

			$(function() {
				// Validation
				$("#login-form").validate({
					// Rules for form validation
					rules : {
						email : {
							required : true,
							email : false
						},
						password : {
							required : true,
							minlength : 3,
							maxlength : 20
						}
					},

					// Messages for form validation
					messages : {
						email : {
							required : 'Por favor ingrese su E-mail',
							email : 'Por favor ingrese un E-mail v&aacute;lido'
						},
						password : {
							required : 'Por favor ingrese su contrase&ntilde;a'
						}
					},

					// Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});
			});
		</script>

	</body>
</html>

