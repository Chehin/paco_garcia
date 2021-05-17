{{-- @if(count($marcas)>0)
<br><br>
<div class="mt-20">
	<div class="container-fluid">
    	<div class="slider-items-products">
        	<div class="hidden-buttons">
            	<div class="slider-items row">
					@foreach($marcas as $marca)
                    <div class="item col-lg-1-10 col-sm-2 col-xs-4">
                    	<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($marca['nombre']), 'page' => 1])}}?marca={{ $marca['id'] }}" data-toggle="tooltip" title="{{ $marca['nombre'] }}">
							<img src="{{(isset($marca['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').$marca['fotos'][0]['imagen_file']:'')}}" alt="{{ $marca['nombre'] }}" alt="{{ $marca['nombre'] }}" class="grayscale">
						</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
<br><br>
@endif --}}

@if(isset($marcas))
<div class="container">
		<div class="row">
			<div class="col-xs-11 col-md-11 col-centered">
	
				<div id="carousel_marcas" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="5000">
					<div class="carousel-inner">

					@php $i=0; @endphp
					@foreach($marcas as $marca)
					
						<div class="item {{ ($i==0)? 'active' : ''}}">
							<div class="col-md-2 col-sm-4 col-xs-12 text-center">
									<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($marca['nombre']), 'page' => 1])}}?marca={{ $marca['id'] }}" data-toggle="tooltip" title="{{ $marca['nombre'] }}">
										<img src="{{(isset($marca['fotos'][0]['imagen_file'])?env('URL_BASE_UPLOADS').$marca['fotos'][0]['imagen_file']:'')}}" alt="{{ $marca['nombre'] }}" alt="{{ $marca['nombre'] }}" class="grayscale">
									</a>
							</div>
						</div>
					@php $i++; @endphp
					@endforeach

					</div>	
					<!-- Controls -->
					<div class="left carousel-control">
						<a href="#carousel_marcas" role="button" data-slide="prev">
							<i class="fa fa-angle-left fa-3x" aria-hidden="true"></i>
							<span class="sr-only">Previous</span>
						</a>
					</div>
					<div class="right carousel-control">
						<a href="#carousel_marcas" role="button" data-slide="next">
							<i class="fa fa-angle-right fa-3x" aria-hidden="true"></i>
							<span class="sr-only">Next</span>
						</a>
					</div>
				</div>
	
			</div>
		</div>
	</div>
	@endif