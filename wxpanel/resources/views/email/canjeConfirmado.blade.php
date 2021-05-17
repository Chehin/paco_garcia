@extends('email.base')

@section('main_container')

<!-- BODY -->
<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">

			<div class="content">
			<table>
				<tr>
					<td>
						<h3>¡Hola!, {{$cliente->nombre}}!, se ha confirmado el canje de su premio <b>{{ $premio->titulo }}</b> (cantidad: {{$cantidad}})  </h3>
						
						<p>Para continuar con el proceso:</p>
						
						<p>Suspendisse potenti. Nam suscipit in eros id auctor. Ut ac erat ligula. Pellentesque pharetra massa eu auctor efficitur. Nulla malesuada cursus quam, ut vestibulum risus accumsan quis. Vestibulum non nibh quis leo pellentesque tincidunt et vitae felis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec eget porttitor enim, id fringilla orci. Donec id pharetra urna. Integer id massa sed eros vulputate semper. Praesent viverra aliquet pretium. Pellentesque tincidunt dignissim nunc ac vestibulum. Curabitur neque ante, blandit eget pellentesque quis; lacinia ut magna. Proin eget est non tellus condimentum tempus nec vitae magna. Sed consequat varius augue condimentum efficitur. Sed mattis nibh in sagittis sollicitudin.</p>
								
						@include('email.social')
						
					</td>
				</tr>
			</table>
			</div><!-- /content -->
									
		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->
@stop

