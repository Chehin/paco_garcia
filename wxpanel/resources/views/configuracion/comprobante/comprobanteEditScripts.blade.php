<script>
  
   $(function() {
	   
	   var $form = $('#{{$resource . 'Form'}}');
	   
	   $('[name=id_categoria]', $form).change((function() {
			if ($(this).val() == 1) {
				$('[name=fiscal]', $form).val(1);
				$('[name=fiscal]', $form).attr('disabled',true);
			} else {
				$('[name=fiscal]', $form).val('');
				$('[name=fiscal]', $form).attr('disabled', false);
			}
	   }));
	   
	   @if("edit"== $mode && 1==$item->id_categoria)
		   $('[name=fiscal]', $form).attr('disabled',true);
	   @endif
	   
       
        
   });
   
   
	
   
</script> 
