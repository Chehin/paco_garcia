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
			<i class="fa fa-{!!$estado_ico!!} fa-2x"></i>
			</div>
			<h1 class="title mb15 text-center" style="font-size: 40px;">{!!$estado!!}</h1>
			<h3 class=" mb15 text-center text-uppercasse">{!!$estado_detalle!!}</h3>
			<div class="text-center">
				<a href="{{route('cuenta')}}" class="btn btn-success">Mi cuenta</a>
			</div>
        </div>
    </div>
</section>


<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
        	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28481.556154774607!2d-65.23374861418172!3d-26.833764722329395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94225c0fe48142b1%3A0xc6f92c1719a80e57!2sPaco+Garcia+S.A.+Sucursal+Congreso!5e0!3m2!1ses!2sar!4v1562155269044!5m2!1ses!2sar" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe>
        </div>
    </div>
</section>


@stop
@section('javascript')
    @php //\Log::debug('precios '.print_r($carritoPago,true))  @endphp
    <script>
        @if (isset($carritoPago))
        gtag('event', 'purchase', {
            "transaction_id": {!! $carritoPago['id_pedido']!!},
            "affiliation": "Paco Garcia",
            "value":{!!$carritoPago['total']['precio_db']!!},
            "currency": "ARS",
            "shipping": {{$carritoPago['envio']['precio_db']}},
            "items": [
                    @if (isset($carritoPago['carrito']))
                    @foreach($carritoPago['carrito'] as $Producto)
                {
                    "id": {!!  $Producto['id_producto']!!} ,
                    "name": {!! "'" .$Producto['titulo']."'" !!},
                    "list_name": "Checkout",
                    "quantity": {!! $Producto['cantidad']!!} ,
                    "price": {!! $Producto['precio']['precio_db'] !!} ,
                },
                @endforeach
                @endif
            ]
        });
        @endif
    </script>
@stop
