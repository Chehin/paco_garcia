<script type="text/javascript">
		
    $(document).ready(function() { 
        
        //DOM Settings
        var resourceDOM = {};
        var formHTMLId = '{{ $aViewData['resource'] . 'Form' }}';
        var resourceLabel = '{{ $aViewData['resourceLabel'] }}';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.STORE.verb;
      

        resourceDOM.$form = $("form#" + formHTMLId);

        pageSetUp();
		
		//image crop
		$('.image-editor', resourceDOM.$form).cropit({
			onImageError:function(error){
				appCustom.closeModalPreloader();
				
				$('#imageFile').val('');
				
				if (1 == error.code) {
					appCustom.smallBox(
						'nok', 
						'La imagen es demasiado pequeña (debe tener al menos ' + this._previewSize.width + 'x'+ this._previewSize.height + ')',
						'',
						'NO_TIME_OUT'
					);
				}
			},
			onImageLoading:function(){
				appCustom.openModalPreloader();
			},
			onImageLoaded:function(){
				appCustom.closeModalPreloader();
				$("button#delete", resourceDOM.$form).removeClass('hide');
			}
			
		});
		
		@if(!empty(Sentinel::getUser()->image))
		$('.image-editor', resourceDOM.$form)
			.cropit('imageSrc', '{{ config('appCustom.UPLOADS_BE_USER') . Sentinel::getUser()->image }}');
		;
		@endif
		
		//save
		$("button#save", resourceDOM.$form).click(function(e){
			//create/edit?
			var mode = $(this).attr('data-mode');
			var data = '';
		
			//Image
			// Move cropped image data to hidden input
			var image = $('.image-editor', resourceDOM.$form).find('input.cropit-image-input').val();
			
			if ("" !== image) {
				var imageData = $('.image-editor', resourceDOM.$form).cropit('export');
				$('.hidden-image-data', resourceDOM.$form).val(imageData);
			}
			
			//End image

			data += resourceDOM.$form.serialize() + '&';

			appCustom.ajaxRest(
				resourceReq.store.url,
				resourceReq.store.verb,
				data,
				function(response){
					if (0 == response.status) {
						appCustom.smallBox('ok','');
					} else {
						 appCustom.smallBox(
							 'nok',
							 response.msg,
							 null, 
							 'NO_TIME_OUT'
						 );
					}
				}
			);

        });

		$("button#delete", resourceDOM.$form).click(function(e){
			cleanForm();
			$("button#delete", resourceDOM.$form).addClass('hide');
		});
		
		function cleanForm() {
			
			document.getElementById(formHTMLId).reset();
			
			$('.cropit-preview .cropit-preview-image-container img', resourceDOM.$form).attr('src', '');
			$('.image-editor', resourceDOM.$form).cropit('disable');
			$('.image-editor', resourceDOM.$form).cropit('reenable');
			$('.image-editor', resourceDOM.$form).cropit('imageSrc', '');
			
		}

});
    
</script>
	


