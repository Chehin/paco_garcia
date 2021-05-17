@if($relacionados)
@foreach($relacionados as $rel)
<article class="entry entry-grid col-md-4">
		<h4>Notas Relacionadas</h4> <br>
		<div class="entry-media">
			@if(isset($nota['fotosRel'][0]['imagen_file']))
			<figure>
				<a href="{{ route('blog_nota' ,['id_nota' => $rel['id_nota'],'name' => str_slug($rel['titulo'])]) }}"><img src="{{env('URL_BASE_UPLOADS').'th_'.$nota['fotosRel'][0]['imagen_file']}}" alt="{{ isset($nota['fotosRel'][0]['epigrafe'])?$nota['fotosRel'][0]['epigrafe']:''}}" class="img-responsive"></a>
			</figure>
			@endif
			<div class="entry-meta">
				<span><i class="fa fa-calendar"></i>{{$nota['fecha']['dia']}} {{$nota['fecha']['mes']}}, {{$nota['fecha']['anio']}}</span>
			</div><!-- End .entry-media -->
		</div><!-- End .entry-media -->
		<h4 class=""><a href="{{ route('blog_nota' ,['id_nota' => $rel['id_nota'],'name' => str_slug($rel['titulo'])]) }}">{{ $rel['titulo'] }}</a></h4>
		
		<div class="entry-content">
			{{$rel['sumario']}}
			<a href="{{ route('blog_nota' ,['id_nota' => $rel['id_nota'],'name' => str_slug($rel['titulo'])]) }}" class="readmore">Leer Más<i class="fa fa-angle-right"></i></a>
		</div><!-- End .entry-content -->
	</article>
@endforeach
@endif