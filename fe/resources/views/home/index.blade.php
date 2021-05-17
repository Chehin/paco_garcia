@extends('master')
@section('content')
    
    @include('home.slider',['slider' => $slider])
    
    <div class="separacion"></div>

    @include('home.destacados',['productos' => $productos_destacados])

    <div class="separacion"></div>
    <div></div>
    @if($banners[0] || $banners[1] || $banners[2])
    <div class="top-banner">
        <div class="container-fluid">
            <div class="">
                @if($banners[0])
                <div class="col-sm-6 col-xs-12 no-padding">
                    <div class="jtv-banner1">
                        <div class="jtv-banner1">
                            {!! $banners[0] !!}
                        </div>
                    </div>
                </div>
                @endif
                @if($banners[1])
                <div class="col-sm-6 col-xs-12 no-padding">
                    <div class="jtv-banner1">
                        {!! $banners[1] !!}
                    </div>
                </div>
                @endif
                @if($banners[2])
                <div class="col-sm-6 col-xs-12 no-padding">
                    <div class="jtv-banner1">
                        {!! $banners[2] !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="separacion"></div>

    @include('home.mas_vistos',['productos' => $productos_mas_vistos])
    
    <div class="separacion"></div>

    @include('home.marcas',['marcas' => $marcas])

    <div class="separacion"></div>

    @include('home.info',['informacion' => $informacion])

@stop

@section('javascript')
 <!-- Revolution Slider -->
 <!-- Slider Js -->
 <script type="text/javascript" src="js/revolution-slider.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function () {
    	jQuery('.tp-banner').revolution(
        {
            delay:9000,
            startwidth:1920,
            startheight:730,
            hideThumbs:10,

            fullWidth:"off",
            forceFullWidth:"on",
            /*fullScreen:"on",*/
            fullScreenOffsetContainer: "header, nav",
            
            navigationType: "bullet",
            navigationStyle: "preview1",
            
            hideArrowsOnMobile: "off",

            touchenabled: "on",
            onHoverStop: "off",
            spinner: "spinner4",
        });
    });

    $('.carousel[data-type="multi"] .item').each(function() {
	    var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
	    next.children(':first-child').clone().appendTo($(this));

        for (var i = 0; i < 4; i++) {
            next = next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });

/*    var page = "http://www.lagaceta.com.ar";

    var $dialog = $('<div></div>')
        .html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
        .dialog({
            autoOpen: false,
            modal: true,
            height: 625,
            width: 500,
            title: "Some title"
        });
    $dialog.dialog('open');*/
</script>

@stop
@section('scriptExtra')
    @include('home.home_scripts')
@stop