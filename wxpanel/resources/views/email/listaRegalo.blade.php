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
						<h3>¡Hola!, {{$user}}!</h3>

						<p>Se ha creado su lista de regalos en <b>{{ \config('appCustom.clientName') }}</b></p>
						
						<p>Para visualizar su lista de regalos <a href="{{$link}}">haga clic aquí</a></p>
								
					</td>
				</tr>
			</table>
			</div><!-- /content -->
									
		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->
@stop