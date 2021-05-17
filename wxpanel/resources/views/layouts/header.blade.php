<?php 
	$aLogos = App\AppCustom\Util::getLogosByCompanyId(Sentinel::getUser()->id_company); 
	
?>
<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
		<!-- HEADER -->
		<header id="header" class="fh-fixedHeader">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <img src="{{ $aLogos['logo'] }}" alt="{{ config('appCustom.clientName') }}"> </span>
				<!-- END LOGO PLACEHOLDER -->
			</div>

			<!-- pulled right: nav area -->
			<div class="pull-right">

				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" title="Ocultar menú"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="logout" title="Cerrar sesión" data-logout-options="Sí|No" data-logout-msg="Usted puede mejorar aún más su seguridad  cerrando el navegador luego de cerrar la sesión"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" onclick="launchFullscreen()" title="Pantalla completa"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<div class="btn-header transparent pull-right">
					<span> <a href="{{ \env('FE_URL') }}" title="Ir al sitio" target="_blank"><i class="fa fa-globe"></i></a> </span>
				</div>


			</div>
			<!-- end pulled right: nav area -->

		</header>
		<!-- END HEADER -->