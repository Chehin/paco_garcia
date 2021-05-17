<script>
	$(function(){
		$('.page-loader-cta').fadeIn();
		var $modal = $('#ctaModal');
		
		$("form", $modal).submit(function(e){
			e.preventDefault();
		});
		
		$('form', $modal)
			.append('<input type="hidden" name="params" value=\'<?php echo $params?>\' />')
			.append('<input type="hidden" name="ctaId" value="<?php echo filter_var($ctaId, FILTER_VALIDATE_INT) ?>" />')
		;

		setTimeout(function() { $('.formCta input', $modal).first().focus(); }, 1000);

		$('.ctaEnviar', $modal).click(function(e){
			var valid = true;

			$('input', $modal).each(function(index){

				var attr = $(this).attr('required');

				// For some browsers, `attr` is undefined; for others,
				// `attr` is false.  Check for both.
				if (typeof attr !== typeof undefined && attr !== false) {

					if ($.trim($(this).val()) ===  "") {
						alert("Debe completar el campo " + $(this).attr('placeholder'));
						valid = false;

						return false;
					}
				}
			});

			if (valid) {
				submit();
			}
		});

		function submit(){

			var data = 'cta1=1&';

			data += $('form', $modal).serialize();

			$.ajax({
				dataType: 'json', 
				type: "POST",
				url: "ctaAjx",
				data,
				beforeSend: function(){

				},
				complete: function( jqXHR,textStatus){

					}
				})
				.success(function(response) {
					//console.log(response);
					if (0 == response.status) {
						$('.viewForm', $modal).hide();
						$('.viewTks', $modal).show();

						setTimeout(function() { $('#ctaModal').modal('toggle'); }, 800);
					} else {
						alert(response.msg);
					}
				})
				.error(function(jqXHR, textStatus, errorThrown) {
					alert('Se ha producido un error');
				})
			;
		}

		$('input', $modal).keyup(function(e){
			var code = e.which; // recommended to use e.which, it's normalized across browsers

			if(code===13) {
				$('.ctaEnviar', $modal).trigger('click');
				return false;
			}
		});

	});
</script>	
