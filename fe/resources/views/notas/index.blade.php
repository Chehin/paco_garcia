@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <li class="inicio">
                        <a title="Inicio" href="{{ route('home') }}">Inicio</a><span>»</span>
                    </li>
                    <li><strong>{{ $data['seccion']['seccion'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="blog_post">
	<div class="container"> 
		<!-- row -->
		<div class="row"> 
			<!-- Center colunm-->
			<div class="center_column col-xs-12 col-sm-12" id="center_column">
				<div class="page-title">
					<h2>{{ $data['seccion']['seccion'] }}</h2>
				</div>
				<ul class="blog-posts">
					@foreach($data['nota'] as $nota)
					<li class="post-item wow fadeInUp">
						<article class="entry">
							<div class="row">
								@if(isset($nota['fotos'][0]))
								<div class="col-sm-4">
									<div class="entry-thumb image-hover2">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}">
											<figure>
												<img src="{{ env('URL_BASE_UPLOADS').$nota['fotos'][0]['imagen_file'] }}" alt="{{$nota['fotos'][0]['epigrafe']}}">
											</figure>
										</a>
									</div>
								</div>
								@endif
								<div class="{{ isset($nota['fotos'][0])?'col-sm-8':'col-sm-12' }}">
									<h3 class="entry-title">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}">{{$nota['titulo']}}</a>
									</h3>
									<div class="entry-excerpt">
										<p>{{ $nota['sumario'] }}</p>
									</div>
									<div class="entry-more">
										<a href="{{route('nota',['id' => $nota['id'],'name' => str_slug($nota['titulo'])])}}" class="button">
											Ver más &nbsp; 
											<i class="fa fa-angle-double-right"></i>
										</a>
									</div>
								</div>
							</div>
						</article>
					</li>
					@endforeach
				</ul>
			</div>
			<!-- ./ Center colunm --> 
		</div>
		<!-- ./row--> 
	</div>
</section>
 
@stop
