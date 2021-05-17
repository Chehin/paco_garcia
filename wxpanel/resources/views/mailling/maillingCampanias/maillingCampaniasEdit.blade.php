<?php 
$mode = $aViewData['mode'];
$item = (isset($aViewData['item'])) ? $aViewData['item'] : null;
?>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
            </h6>
        </div>
    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div  id="{{ $aViewData['resource'] }}_formContainer">

                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                <!--        <p>
                            Tabs inside well and pulled right
                            <code>
                                    .tabs-pull-right
                            </code>
                            (Bordered Tabs)
                    </p> -->
                    <hr class="simple">

                    <ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                         <li>
                                    <a href="#l2" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}} B</a>
                         </li>

                           <li class="pull-rigth active">
                                    <a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}} A</a>
                            </li>
                    </ul>
					
					<div id="myTabContent3" class="tab-content padding-10">
                        <div class="tab-pane fade" id="l2">
                           @include('mailling.maillingCampanias.mailsCampaignB')
                        </div>
                        <div class="tab-pane fade active in" id="l1">
                           @include('mailling.maillingCampanias.mailsCampaignA')
                        </div>

                       <!-- Buttons inside Form!!-->
									<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">
										<div style="padding:0;" class="col-md-12">
                                            <button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
                                            @if('edit' == $mode) <button type="button" id="send" class="btn btn-success"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar </button> @endif
											<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
										</div>
									</div>
					</div>
                </div>
                
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </article>
        
    </div>
</div>
<script type="text/javascript">
	$(function(){
		$('#faceBlock').remove();

		$('#faceText2').emojiInit2({
			fontSize:20,
			success : function(data){

			},
			error : function(data,msg){
			}
		});
		
		$('#faceText3').emojiInit({
			fontSize:20,
			success : function(data){

			},
			error : function(data,msg){
			}
		});
	});
</script>

  <!-- Initialize the editor. FROALA-->
  <script> 
    $('#clockpickerA').clockpicker();
        
    $('#datepickerA').datepicker({
                dateFormat : 'dd/mm/yy'
    }); 
    
    $('#clockpickerB').clockpicker();
        
    $('#datepickerB').datepicker({
                dateFormat : 'dd/mm/yy'
    });   

    $("select#plantillasA").change(function(){
        $.ajax({
                    type:'get',
                    url:'{!!URL::to('template') !!}',
                    data:{'id': this.value },
                    success:function(data){
                        for(var i=0; i<data.length; i++){
                        	templ=data[i].template;
                        }
                        $("#templateA").empty();
                        $("#templateA").append(templ);
                        $("#fillA").val(templ);
                    },
                    error:function(){
                        console.log('error');
                    }
        }); 
        
    });

     $('textarea#contentA').froalaEditor({
      heightMin: 500,
      language: 'es',
      zIndex: 8000,
      placeholderText: '',
      toolbarSticky: true,
      fontFamily: {
        "Roboto,sans-serif": 'Roboto',
        "Oswald,sans-serif": 'Oswald',
        "Montserrat,sans-serif": 'Montserrat',
        "'Open Sans Condensed',sans-serif": 'Open Sans Condensed'
      },
      fontFamilySelection: true,
      fontSizeSelection: true,
      colorsHEXInput: true,
      toolbarButtons: ['bold', 'italic', 'underline', 'fontFamily', 'fontSize', '|', 'color', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'html', '|', 'spellChecker', '|', 'insert'],
      // Establece la URL de carga de fotos.
      imageUploadURL: '{!!URL::to('froalaImage') !!}',
      // Establece la URL de carga del archivo.
      fileUploadURL: '{!!URL::to('froalaFile') !!}',
      imageAllowedTypes: ['jpeg', 'jpg', 'png'],
      imageUploadParams: {_token: $("[name='_token']").val()},
      aviaryKey: 'b0c1e5af4b074d4e85b9f82ee32be2b2',
      htmlUntouched : true
     }).on('froalaEditor.contentChanged', function (e, editor) {
         $('#fillA').val(editor.html.get());
     });

      $("select#plantillasB").change(function(){
        $.ajax({
                    type:'get',
                    url:'{!!URL::to('template') !!}',
                    data:{'id': this.value },
                    success:function(data){
                        for(var i=0; i<data.length; i++){
                        	templ=data[i].template;
                        }
                        $("#templateB").empty();
                        $("#templateB").append(templ);
                        $("#fillB").val(templ);
                    },
                    error:function(){
                        console.log('error');
                    }
        }); 
        
    });

    $("button#send").click(function(e){
        $.ajax({
                    type:'get',
                    url:'{!!URL::to('mailSendAB') !!}',
                    data:{'id': $("#id").val() }
        }); 
        
    });

     $('textarea#contentB').froalaEditor({
      heightMin: 500,
      language: 'es',
      zIndex: 8000,
      placeholderText: '',
      toolbarSticky: true,
      toolbarButtons: ['bold', 'italic', 'underline', 'fontFamily', 'fontSize', '|', 'color', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'html', '|', 'spellChecker', '|', 'insert'],
      colorsBackground: ['#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
     '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
     '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
     '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', '#3B7672', '#94AC31', 'REMOVE'],
     colorsText: ['#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
     '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
     '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
     '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', '#3B7672', '#94AC31', 'REMOVE'],
     // Establece la URL de carga de fotos.
      imageUploadURL: '{!!URL::to('froalaImage') !!}',
      // Establece la URL de carga del archivo.
      fileUploadURL: '{!!URL::to('froalaFile') !!}',
      imageAllowedTypes: ['jpeg', 'jpg', 'png'],
      imageUploadParams: {_token: $("[name='_token']").val()},
      aviaryKey: 'b0c1e5af4b074d4e85b9f82ee32be2b2',
      htmlUntouched : true
     }).on('froalaEditor.contentChanged', function (e, editor) {
         $('#fillB').val(editor.html.get());
     });
    </script>

    <script type="text/javascript">

	  
        $(function(){
            
            appCustom.ajaxRest(
            'rest/v1/ListaIds',
            'GET',
            null,
            function(result){
                
                var $element = $('select#listaIdsA');
                
                @if ('edit' == $mode) 
                var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aListaA']?>');
                for (var i = 0; i < result.length; i++) { 
                    for (var d = 0; d < data.length; d++) {
                        var item = data[d];
                        if (result[i].id == item.id) {
                            // Create the DOM option that is pre-selected by default
                            var option = new Option(item.text, item.id, true, true);                                
                            // Append it to the select
                            $element.append(option);
                            // Elimino los rubros seleccionados
                            result.splice(i,1);
                        }
                    };
                }
                for (var i = 0; i < result.length; i++) {
                    // Create the DOM option that is pre-selected by default
                    var option = new Option(result[i].text, result[i].id, false, false);
                    // Append it to the select
                    $element.append(option);
                };
                @else 
                for (var i = 0; i < result.length; i++) {
                    // Create the DOM option that is pre-selected by default
                    var option = new Option(result[i].text, result[i].id, false, false);
                    // Append it to the select
                    $element.append(option);
                };
                @endif                
                
                $element.select2({
                    placeholder: 'Seleccionar',
                    minimumInputLength: 0,
                    allowClear : true,
                    width : '100%'
                });
                
                // Update the selected options that are displayed
                $element.trigger('change');
            }, 
            'sync'
            );        
            
        });

</script>

<script type="text/javascript">

	  
        $(function(){
            
            appCustom.ajaxRest(
            'rest/v1/ListaIds',
            'GET',
            null,
            function(result){
                
                var $element = $('select#listaIdsB');
                
                @if ('edit' == $mode) 
                var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aListaB']?>');
                for (var i = 0; i < result.length; i++) { 
                    for (var d = 0; d < data.length; d++) {
                        var item = data[d];
                        if (result[i].id == item.id) {
                            // Create the DOM option that is pre-selected by default
                            var option = new Option(item.text, item.id, true, true);                                
                            // Append it to the select
                            $element.append(option);
                            // Elimino los rubros seleccionados
                            result.splice(i,1);
                        }
                    };
                }
                for (var i = 0; i < result.length; i++) {
                    // Create the DOM option that is pre-selected by default
                    var option = new Option(result[i].text, result[i].id, false, false);
                    // Append it to the select
                    $element.append(option);
                };
                @else 
                for (var i = 0; i < result.length; i++) {
                    // Create the DOM option that is pre-selected by default
                    var option = new Option(result[i].text, result[i].id, false, false);
                    // Append it to the select
                    $element.append(option);
                };
                @endif                
                
                $element.select2({
                    placeholder: 'Seleccionar',
                    minimumInputLength: 0,
                    allowClear : true,
                    width : '100%'
                });
                
                // Update the selected options that are displayed
                $element.trigger('change');
            }, 
            'sync'
            );        
            
        });

</script>

@include('mailling.maillingCampanias.maillingCampaniasEditScripts')