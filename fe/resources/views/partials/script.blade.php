
    <!-- JS -->

    <!-- jquery js 
    <script type="text/javascript" src="js/jquery.min.js"></script>
    -->
    {!!Html::script('js/jquery.min.js')!!}

    <!-- bootstrap js 
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    -->
    {!!Html::script('js/bootstrap.min.js')!!}

    <!-- owl.carousel.min js 
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
   -->
    <!-- owl.carousel.min js -->
   {!!Html::script('js/owl.carousel.min.js')!!}


    <!-- bxslider js 
    <script type="text/javascript" src="js/jquery.bxslider.js"></script>
   -->
    <!-- bxslider js -->
   {!!Html::script('js/jquery.bxslider.js')!!}

   <!-- Slider Js 
    <script type="text/javascript" src="js/revolution-slider.js"></script>
-->
     <!-- revolution slider js -->
   {!!Html::script('js/revolution-slider.js')!!}
   

    <!-- megamenu js -->
   {!!Html::script('js/megamenu.js')!!}
    

    <script type="text/javascript">
        /* <![CDATA[ */
        var mega_menu = '0';

  /* ]]> */
    </script>

    <!-- jquery.mobile-menu js -->
    <script type="text/javascript" src="js/mobile-menu.js"></script>
  
 <!-- mobile Menu JS -->
{{--  {!!Html::script('js/jtv-mobile-Menu.js')!!} --}}
   

    <!--jquery-ui.min js 
    <script type="text/javascript" src="js/jquery-ui.js"></script>
-->
    <!--jquery-ui.min js -->
{!!Html::script('js/jquery-ui.js')!!}


    <!-- main js 
    <script type="text/javascript" src="js/main.js"></script>
-->
 <!-- main js -->
 {!!Html::script('js/main.js')!!}

    <!-- countdown js 
    <script type="text/javascript" src="js/countdown.js"></script>
    -->
    {!!Html::script('js/countdown.js')!!}

    {!!Html::script('js/envio.js')!!}

    {!!Html::script('js/contact.js')!!}

    {!!Html::script('js/jquery.validate.js')!!}

    {!!Html::script('js/custom.js')!!}

    <script>
      $( function() {
        $( "#q" ).autocomplete({
          source: "search",
          minLength: 2,
          select: function( event, ui ) {
            $('.page-loader').fadeIn();
                  window.location.replace('producto/'+ui.item.id+'/'+ui.item.label);
          },
          html: true, 
          open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 5000);
          }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
        };
        @if(Carbon\Carbon::now('America/Argentina/Buenos_Aires') < Carbon\Carbon::create(2019, 11, 04, 0, 0, 0, 'America/Argentina/Buenos_Aires'))
        //contador
        var dthen1 = new Date("11/04/19 00:00:00 AM");
        //var dthen1 = new Date();
        start = new Date();
        start_date = Date.parse(start);
        var dnow1 = new Date(start_date);
        if(CountStepper>0)
        ddiff= new Date((dnow1)-(dthen1));
        else
        ddiff = new Date((dthen1)-(dnow1));
        gsecs1 = Math.floor(ddiff.valueOf()/1000);
        var iid1 = "countbox_1";
        CountBack_slider(gsecs1,"countbox_1", 1);
        @endif
      });
    </script>

@yield('scriptExtra')