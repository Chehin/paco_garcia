<style>
	/*Cropit plugin*/
	.image-editor{
		text-align: center;
	}
	.cropit-preview {
		background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 140px;
        height: 120px;
		max-width: 100%;
		position: relative;
		margin: 10px auto;
	}
	.cropit-preview .smart-form{
		position: absolute;
		right: 4%;
		z-index: 100;
		width: 91%;
		top: 35%;
	}
	.cropit-preview .smart-form .input-file .button{
		font-size: 14px;
		padding: 6px 10px;
	}
	.cropit-preview-image-container {
        cursor: move;
		/* background: url("img/drop_img.png") no-repeat center 30%;*/
		background-size: 80%;
	}
	.cropit-preview-image-container img{
		position: relative;
		z-index: 101;
	}
	input[type="range"] {
		display: block;
		width: 25%;
	}
	.toggDiv{
		background: #fff;
		padding: 10px 20px;
		float: left;
		width: 100%;
		border: 2px solid #e6e6e6;
	}
	input[type="file" i]#imageFile::-webkit-file-upload-button {
		cursor: pointer !important;
	}
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                    &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
				<i class="fa fa-picture-o fa-fw "></i> {{$aViewData['resourceLabel']}} 
			</h6>
        </div>
		<!-- NEW WIDGET START -->
		<article>
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					<!-- widget div-->
					<div>
							
							<div class="tab-pane fade active in">
								{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
										<div>
											<div class="toggDiv">
												<div>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border">Carga de Imagen</legend>
														<section>
															<span id="selectMsg" style="font-style:italic;">Arroje su imagen al cuadro o selecciónela desde el botón</span>
														</section>
														<section>
															<div class="image-editor">																
																<div class="cropit-preview">
																	<div class="smart-form">
																		<div class="input input-file"><span class="button"><input  id="imageFile" name="file" type="file" class="cropit-image-input" onchange="this.parentNode.nextSibling.value = this.value">Seleccionar imagen</span></div>
																	</div>
																</div>
																<button type="button" id="delete" class="btn btn-danger hide"><i class="fa fa-trash none"></i> Eliminar</button>
																
																<div class="row">
																	<div class="col-md-7" style="margin: 10px auto;float: none;">
																	  <div class="row">
																		  <span class="fa fa-picture-o col-md-1 "></span>	
																		<span class="col-md-10 no-padding">
																		  <input type="range" style="width:100%;" step="0.01" max="1" min="0" class="cropit-image-zoom-input ignore">
																		</span>
																		<span class="fa fa-picture-o fa-2x col-md-1"></span>
																	  </div> 
																	</div>
																  </div>
																<input type="hidden" name="image-data" class="hidden-image-data" />
														  </div>
														</section>
													</fieldset>
												</div>
													
												<div class="row pull-right" style="margin-top:13px;margin-bottom: 13px;">
													<div class="col-md-12">
														<button type="button" id="save" data-mode="add" class="btn btn-primary">
																<i class="fa fa-save"></i> Guardar 
															</button>
														<button type="button" id="cancel" class="btn btn-danger hidden"><i class="fa fa-times none"></i> Cancelar</button>
													</div>
												</div>
												
											</div>
										</div>
									

							{{ Form::close() }}
							</div>
							
					</div>
				   
					<!-- end widget content -->
			</div>
			<!-- end widget -->
		</div>
</div>
<script src="js/plugin/cropit/jquery.cropit.js"></script>
@include('user.userImageScripts')
