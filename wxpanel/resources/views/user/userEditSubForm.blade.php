<div class="modal-body">
	<div>
		<div class="widget-body">
			{{ Form::open(array('id'=>'userForm', 'name' => 'userForm')) }}
			<fieldset>
				<div class="row">
					<section class="row">
						<label class="col col-md-2">Estado:</label>
						<label class="select col-md-10 row">
                                                        {{ Form::select(
                                                            'habilitado', 
                                                            [
                                                            '1' => 'Habilitado', 
                                                            '0' => 'Deshabilitado', 
                                                            ], 
                                                            ("edit" == $mode) ? $aItem['enabled'] : 1, 
                                                            ['class' => 'col col-md-12']
                                                            ) 
                                                        }}
                                                </label>
					</section>
					<section class="row">
						<label class="col col-md-2">Apellido *:</label>
						<label class="input col col-md-10 row">
                                                    <input class="col col-md-12" type="text" name="apellido" required="" value="{{ ('edit' == $mode) ? $aItem['last_name'] : '' }}" />
						</label>
					</section>
					<section class="row">
						<label class="col col-md-2">Nombre *:</label>
						<label class="input col col-md-10 row">
                                                    <input class="col col-md-12" type="text" name="nombre" required="" value="{{ ('edit' == $mode) ? $aItem['first_name'] : '' }}" />
						</label>
					</section>
					<section class="row">
						<label class="col col-md-2">Teléfono:</label>
						<label class="input col col-md-10 row">
                                                    <input class="col col-md-12" type="text" name="telefono" value="{{ ('edit' == $mode) ? $aItem['phone'] : '' }}" />
						</label>
					</section>
					<section class="row">
						<label class="col col-md-2">Email *:</label>
						<label class="input col col-md-10 row">
                                                    <input class="col col-md-12" type="text" name="mail" id="email" required="" value="{{ ('edit' == $mode) ? $aItem['email'] : '' }}" />
						</label>
					</section>
                                        <section class="row">
						<label class="col col-md-2">Contraseña *:</label>
						<label class="input col col-md-10 row">
							<input class="col col-md-12" type="password" name="password" {{ ("add" == $mode) ? 'required=""' : '' }} value="" />
						</label>
					</section>
					<section class="row">
						<label class="col col-md-2">Repetir Contraseña *:</label>
						<label class="input col col-md-10 row">
							<input class="col col-md-12" type="password" name="password_confirmation" {{ ("add" == $mode) ? 'required=""' : '' }} value="" />
						</label>
					</section>
				</div>
			<fieldset>
			{{ Form::close() }}
		</div>
	</div>
</div>
<script>

$(function(){

   $('form#userForm input[type=password]').val('');
       
    
});


</script>