@if(isset($informacion['nota'][0]))
<div class="jtv-service-area">
    <div class="container-fluid">
        <div class="row">
            @foreach($informacion['nota'] as $nota)
            <div class="col col-md-3 col-sm-6 col-xs-12 no-padding">
                <div class="block-wrapper support">
                    <div class="text-des">
                        <div class="icon-wrapper"><i class="fa {{ $nota['icono'] }}"></i></div>
                        <div class="service-wrapper">
                            <h3>{{ $nota['titulo'] }}</h3>
                            <p>{{ $nota['sumario'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif