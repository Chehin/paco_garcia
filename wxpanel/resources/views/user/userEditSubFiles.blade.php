 <div>
    <div class="row">
        
        <article class="col-sm-12">
            
            @if("edit" == $mode)
                <label class="col-sm-2">Imagen del usuario:</label>
                <ul class="demo-btns">
                    @foreach($aViewData['files'] as $aFile)
                        <li>
                            <div class="btn-group serviceSubFilesAttachBox">
                                <button title="Dwonload this file" name="downloadfile" data-id="{{ $aFile['name'] }}" class="btn btn-default">
                                    {{ $aFile['nameDecoded'] }}
                                </button>
                                <button title="Drop this file" name="dropfile" data-id="{{ $aFile['name'] }}" class="btn btn-default dropdown-toggle">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>
       <article class="col-sm-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-0" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<form action="upload/service" class="dropzone" id="userFilesToDrop"></form>
                                                
                                                <form id="userSubFiles">
                                                    <input type="hidden" name="files" id="files"></input>
                                                    <input type="hidden" name="filesDeleted" id="filesDeleted"></input>
                                                </form>

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
    
    
    $(document).ready(function() {
            pageSetUp();
            Dropzone.autoDiscover = false;
            $("#newServiceSubFilesDrop").dropzone({
                    maxFiles: 1,
                    init: function() {
                        this.on("success", function(file, responseText) {
                            var val = $("form#newServiceSubFiles input#files").val();
                            if (val) {
                                $("form#newServiceSubFiles input#files").val(val + ',' + responseText);
                            } else {
                                $("form#newServiceSubFiles input#files").val(responseText);
                            }
                            file.serverId = responseText;
                        });
                    },
                    addRemoveLinks : true,
                    maxFilesize: 5,
                    dictResponseError: 'Error uploading file!',
                    removedfile: function(file) {
                        var val = $("form#newServiceSubFiles input#files").val();
                        var valClean = val.replace(file.serverId, '');
                        $("form#newServiceSubFiles input#files").val(valClean);
                        
                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                      }
            });
        
        
            

            $(".serviceSubFilesAttachBox [name=downloadfile]").click(function(e){
                location.href = 'download/serviceAttach/'  + e.target.dataset.id;
            });
            
            $(".serviceSubFilesAttachBox [name=dropfile]").click(function(e){
                var fileName = this.dataset.id;
                var val = $("form#newServiceSubFiles input#filesDeleted").val();
                if (val) {
                    $("form#newServiceSubFiles input#filesDeleted").val(val + ',' + fileName);
                } else {
                    $("form#newServiceSubFiles input#filesDeleted").val(fileName);
                }
                $(this).parent().remove();
            });
            
            
    });
    
</script>

