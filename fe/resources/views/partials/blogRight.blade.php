<div class="blog-content">
	<div class="tr-sidebar theiaStickySidebar">
		<div class="widget-area">
			<div class="widget widget_search">
				<form role="search" id="search-form" method="get" action="{{ route('blog', ['page' => 1]) }}">
					<input type="search" class="form-control" autocomplete="off" name="q" placeholder="Buscar..." id="search-input" value="{{ $filtros['q']?$filtros['q']:'' }}">
					<button type="submit" id="search-submit" class="btn">
						<i class="fa fa-search"></i>
					</button>
				</form>
			</div>
			@if($archivos)
			<div class="widget archives">
				<h3 class="widget_title">Archivos</h3>
				<ul class="tr-list">
					@php $anio = 0; @endphp
					@foreach($archivos as $archivo)
					@if($archivo['year'] != $anio)
					@php $anio = $archivo['year']; @endphp
					<li><strong>{{ $anio }}</strong></li>
					@endif
					<li class="{{ ($filtros['m']==$archivo['month'] && $filtros['a']==$archivo['year'])?'active':'' }}">
						<a href="{{ route('blog', ['page' => 1]) }}?a={{ $archivo['year'] }}&m={{ $archivo['month'] }}">{{ $archivo['month_name'] }} ({{ $archivo['post_count'] }})</a>
					</li>
					@endforeach
				</ul>
			</div>
			@endif

            @if($etiquetas)
			<div class="widget">
				<h3 class="widget_title">Etiquetas</h3>
				<div class="tag-cloud">
                    @foreach($etiquetas as $etiqueta)
                    <a class="{{ $filtros['tag']==$etiqueta['id']?'active':'' }}" href="{{ route('blog', ['page' => 1]) }}?tag={{ $etiqueta['id'] }}">{{ $etiqueta['text'] }}</a>
                    @endforeach
				</div>
			</div>
            @endif
		</div>
	</div>
</div>