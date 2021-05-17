<script type="text/javascript">
		
    $(document).ready(function() {
		
					
			@if($aProcessed)
				@if(0 == $aProcessed['status'])
					appCustom.smallBox('ok', 'La importación finalizó exitosamente');
				@endif
				
				@if(1 == $aProcessed['status'])
					appCustom.smallBox('nok', '{{ $aProcessed['msg'] }}', null, 'NO_TIME_OUT');
				@endif
				
				@if(2 == $aProcessed['status'])
					appCustom.smallBox('warn', '{{ $aProcessed['msg'] }}' );
				@endif
				
			@endif
			
			
	}); //End DOM ready
    
    
</script>

	
