<!DOCTYPE html>
<html lang="es">
    @include('layouts.head')
    <body class="smart-style-1 fixed-navigation fixed-header">
        @include('layouts.header')
        @include('layouts.leftSide')
        <!-- MAIN PANEL -->
		<div id="main" role="main">
			<!-- MAIN CONTENT -->
			<div id="content">
				@yield('main_container')
			</div>
			<!-- Ajax Preloader -->
				<div id="fadePreloader"></div>
				<div id="modalPreloader">
					<img id="modalPreloaderImg" src="img/preloader.gif" />
				</div>
			<!-- End Preloader -->
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		@include('layouts.scripts_bottom')
		@yield('custom_scripts_container')
    </body>
</html>