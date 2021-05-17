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
						<h3>¡Se realizó la compra de un regalo para la lista {{ $titulo }}!</h3>

						<p>El invitado <b>{{ $invitado }}</b> ha comprado el producto: {{ $producto }}</p>
						
						<p>Para visualizar la lista de regalos <a href="{{$link}}">haga clic aquí</a></p>
								
					</td>
				</tr>
			</table>
			</div><!-- /content -->
									
		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->
@stop