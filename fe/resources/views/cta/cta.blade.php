<div class="page-loader-cta">
</div>
<div class="modal-dialog">
	<div class="modal-body">
		<button type="button" class="close myclose" data-dismiss="modal"><i class="fa fa-times-circle"></i></button>
		<div class="product-view-area item-inner" style="overflow:hidden">

			<?php echo $aCtaData['texto'] ?>
			
			@if($aCtaData['tipo']!= 9)
			<hr>
			<div class="viewForm">
				<div class="formCta col-sm-12 col-xs-12">
					<?php echo $aCtaData['form'] ?>
				</div>
				<div class="col-sm-12 col-xs-12">
					<button class="button pro-add-to-cart ctaEnviar" title="" type="button" style="margin-bottom: 15px;">
						<span><?php echo $aCtaData['label_submit'] ? $aCtaData['label_submit'] : 'Enviar'  ?></span>
					</button>
				</div>
				<p style="font-size: 11px;text-align: center;">Puede darse de baja en cualquier momento. Por favor, consulte nuestras políticas de privacidad.</p>
			</div>
			<div class="viewTks">
				¡Muchas gracias!
			</div>
			@endif
		</div>
	</div>
</div>
@include('cta.cta_scripts');
