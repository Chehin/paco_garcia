@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('head')
<script src='https://www.google.com/recaptcha/api.js'></script>
@stop
@section('content')
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <li class="inicio">
                        <a title="Inicio" href="{{ route('home') }}">Inicio</a><span>»</span>
                    </li>
                    <li><strong>Contacto</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="main">
	<div id="map"></div><!-- End #map -->
	
	<div class="mb90"></div><!-- margin -->
	<div class="container">
		<div class="row">

			<div class="col-md-8">
				<h4>Formulario de contacto</h4>
				{!! Form::open(['route' =>['contacto'],'id'=>'form_contacto']) !!}
                                <div class="form-group">
                                    <div class="row">                                   
                                        <div class="col-sm-4">
                                            <input type="hidden" value="1" id='id'>
                                            <label>Su Nombre y Apellido
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" value="" maxlength="100" class="form-control" name="nombre" id="name" required="">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Su E-mail
                                                <span class="required">*</span>
                                            </label>
                                            <input type="email" value="" maxlength="100" class="form-control" name="email" id="email1" required="">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Su tel&eacute;fono
                                                <span class="required"></span>
                                            </label>
                                            <input type="text" value="" maxlength="100" class="form-control" name="telefono" id="telefono">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <label>Mensaje
                                                <span class="required">*</span>
                                            </label>
                                            <textarea maxlength="5000" rows="10" class="form-control" name="mensaje" id="Textarea1" required=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6"> <div class="g-recaptcha" data-sitekey="6LcJvbgUAAAAAD-HDJehm5Ohcepk3Mo5OH5krNvd{{-- 6LdbsV0UAAAAALchRNKqF592uwxsdGIaD30P2-W9 --}}{{-- 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI --}}"></div></div>
                                    <div class="col-sm-6">
										<br>
                                        <button name="submit" type="submit" class="btn btn-primary btn-md  pull-right">Enviar mensaje</button>
                                    </div>
                                    <span class="col-sm-12 data-status"></span> 
                                </div>
                            {!! Form::close() !!}

			</div><!-- End .col-md-8 -->

			<div class="clearfix mb65 visible-sm visible-xs"></div><!-- margin -->

			<div class="col-md-4">
				<div class="contact-box">
					<h3>Información de contacto</h3>
					<ul>
						<li><i class="fa fa-home"></i> Congreso 102 | San Miguel de Tucumán | 0381 - 4213154 </li>
						<li><i class="fa fa-home"></i> 25 de Mayo 257 | San Miguel de Tucumán | 0381 - 4310580 </li>
						<li><i class="fa fa-home"></i> Cariola 42 (Local 1184) | Yerba Buena | 0381 4315530 - 4315531 </li>
						<li><i class="fa fa-home"></i> Mendoza 789 | San Miguel de Tucumán | 0381 - 4302551 </li>
						<li><i class="fa fa-home"></i> Maipú 343 | San Miguel de Tucumán | 381 – 4210174  <li>
						{{-- <li><strong>Email:</strong> <a href="#">madeup@gmail.com</a></li> --}}
						<li>
							<strong>Social Media:</strong>
							<div class="social-icons">
								<a href="https://es-la.facebook.com/pacogarciaweb/" class="social-icon" data-toggle="tooltip" title="Facebook"><i class="fa fa-facebook"></i></a>
							</div><!-- End .social-icons -->
						</li>
					</ul>
				</div><!-- End .contact-box -->
			</div><!-- End .col-md-4 -->
		</div><!-- End .row -->
	</div><!-- End .container -->

	<div class="mb90"></div><!-- margin -->

</div><!-- End .main -->

@stop

@section('javascript')
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDPGUWUeNkS7HfMXZO33taHOci4nYrsYXQ"></script>
<script>
// Google Map api v3 - Map for contact pages
if ( document.getElementById("map") && typeof google === "object" ) {
    	// Map pin coordinates and content of pin box
        var locations = [
			[
				'<address><strong>Av. Colón 333</strong><br/> Tel: 433 3330</address>',
				-26.829728,-65.23353320000001
			],
			[
				'<address><strong>Av. Aconquija 1460 Local 3 - Yerba buena</strong><br/> Tel: 425 2000</address>',
				-26.8139999,-65.29101170000001
			],
			[
				'<address><strong>Av. Mitre 882 - Plazoleta</strong><br/> Tel: 433 4510</address>',
				-26.8161581,-65.2157398
			]
		];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: new google.maps.LatLng(-26.8123569,-65.2518005), // Map Center coordinates
            scrollwheel: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();


        var marker, i;

        for ( i = 0; i < locations.length; i++ ) {  
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            animation: google.maps.Animation.DROP,
            icon: 'images/pin.png'
          });

          google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
        }
    }
</script>
@stop