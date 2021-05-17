@if(isset($slider))
<div class="slider">
	<div class="tp-banner-container clearfix">
		<div class="tp-banner">
			<ul>
				@foreach($slider as $slide)
				<li data-transition="slidehorizontal" data-slotamount="5" data-masterspeed="700" data-link="{{route('listado_slide',['id' => $slide['id'],'name' => str_slug($slide['titulo']), 'page' => 1])}}">
					<img src="{{env('URL_BASE_UPLOADS').$slide['foto'][0]['imagen_file']}}" alt="{{env('URL_BASE_UPLOADS').$slide['foto'][0]['epigrafe']}}" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
					@if($slide['slider_texto'])
					<div class="tp-caption ExtraLargeTitle  skewfromrightshort fadeout" data-x="85" data-y="224" data-speed="500" data-start="1200" data-easing="Power4.easeOut">
						<h1>{{$slide['titulo']}}</h1>
						<p>{{$slide['antetitulo']}}</p>
					</div>
					@endif
				</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endif