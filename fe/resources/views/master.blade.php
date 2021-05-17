<!DOCTYPE html>
<html lang="es">

	<head>
		<!-- Basic page needs -->
		<meta charset="utf-8">
		
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<![endif]-->
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>@yield('pageTitle', env('SITE_NAME'))</title>
		<base href="{{ route('home') }}/" />
		<meta name="description" content="">
		<meta name="keywords" content="" />

		<meta name="theme-color" content="#196139" />

	    <meta property="og:image" content="{{isset($fotos['0']['imagen_file'])?env('URL_BASE_UPLOADS').'th_'.$fotos['0']['imagen_file']:''}}" />
	    <meta property="og:title" content="@yield('pageTitle', env('SITE_NAME'))" />
	    <meta property="og:type" content="website" />
	    <meta property="og:url" content="{{app('url')->full()}}"/>

		<!-- Mobile specific metas  -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Favicon  -->
		<link rel="shortcut icon" type="image/png" href="favicon.png">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<!-- CSS Style -->
			<!-- <link rel="stylesheet" href="style.css"> -->
		   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" media="screen">
		   {!!Html::style('style.css', array('media' => 'screen'))!!}
		   {!!Html::style('css/style.min.css', array('media' => 'screen'))!!}
		   {!!Html::style('css/responsive.css', array('media' => 'screen'))!!}

	
		@yield('css')

		@yield('head')
	</head>

<body class="cms-index-index cms-home-page">
	<!--[if lt IE 8]>
	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="page-loader dark">
		<div class="spinner">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>
	<div id="page">

			@include('partials.header')
			@include('partials.nav')
			
			<!-- Contenido general -->
			@yield('content')

			<!-- footer an social links -->
			@include('partials.footer')
		
	</div>
@include('partials.nav_movil')
@include('partials.script')

@yield('javascript')

</body>
</html>
