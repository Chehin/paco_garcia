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
						<h3>¡Hola, {{ $userPc->first_name .','. $userPc->last_name }}!, <b>Eclon</b> le ha enviado este mensaje:</h3>
						
						<p>{{$mensaje}}</p>

						
						
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

