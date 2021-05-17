$('#form_contacto').submit(function() {
	var $form		= $(this);
	var $dataStatus	= $form.find('.data-status');

	var response = grecaptcha.getResponse();
	if(response.length == 0){
		$dataStatus.show().html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Por favor verifique que no es un robot</strong></div>');
		return false;
	}else{
		var submitData	= $form.serialize();
		var $name		= $form.find('input[name="nombre"]');
		var $email		= $form.find('input[name="email"]');
		var $telefono		= $form.find('input[name="telefono"]');
		var $message	= $form.find('textarea[name="mensaje"]');
		var $submit		= $form.find('button[name="submit"]');
		
		$name.attr('disabled', 'disabled');
		$email.attr('disabled', 'disabled');
		$telefono.attr('disabled', 'disabled');
		$message.attr('disabled', 'disabled');
		$submit.attr('disabled', 'disabled');
		
		$dataStatus.show().html('<div class="alert alert-info"><strong>Enviando...</strong></div>');
	
		$.ajax({ // Send an offer process with AJAX
			type: 'POST',
			url: '/contacto',
			data: submitData  + '&action=add',
			dataType: 'html',
			success: function(msg){
				if (parseInt(msg, 0) !== 0) {
					var msg_split = msg.split('|');
					if (msg_split[0] === 'success') {
						$email.val('').removeAttr('disabled');
						$name.val('').removeAttr('disabled');
						$telefono.val('').removeAttr('disabled');
						$message.val('').removeAttr('disabled');
						$submit.removeAttr('disabled');
						$dataStatus.html(msg_split[1]).fadeIn();
					} else {
						$email.removeAttr('disabled');
						$name.removeAttr('disabled');
						$telefono.removeAttr('disabled');
						$message.removeAttr('disabled');
						$submit.removeAttr('disabled');
						$dataStatus.html(msg_split[1]).fadeIn();
					}
					grecaptcha.reset();
				}
			}
		});
	
		return false;
	}
});

