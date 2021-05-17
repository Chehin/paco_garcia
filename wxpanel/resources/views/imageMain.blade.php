<?php
	$item = isset($aViewData['item']) ? $aViewData['item'] : null;
	$itemNameField = isset($aViewData['itemNameField']) ? $aViewData['itemNameField'] : null;
?>
<style>
	/*Cropit plugin*/
	.image-editor{
		text-align: center;
	}
	.cropit-preview {
		background-color: #f8f8f8;
        background-size: cover;
        border-radius: 3px;
        margin-top: 7px;
        width: {{ $aViewData['aImageCropSize']['w'] }}px !important;
        height: {{ $aViewData['aImageCropSize']['h'] }}px !important;
		max-width: 100%;
		position: relative;
		margin: 10px auto;
		@if($aViewData['aImageCropSize']['w']>=800)
		    zoom: .5;
		@endif
	}
	.cropit-preview .smart-form{
		position: absolute;
		right: 28%;
		z-index: 100;
		width: 100%;
		top: 58%;
	}
	.cropit-preview .smart-form .input-file .button{
		@if($aViewData['aImageCropSize']['w']>=800)
		font-size: 2.4em;
		padding: 27px;
		@else
		font-size: 14px;
		padding: 6px 10px;
		@endif
		z-index: 9999;
	}
	.cropit-preview-image-container {
        cursor: move;
		background: url("img/drop_img.png") no-repeat center 30%;
		background-size: 80%;
		border: 1px solid #ccc;
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
				<i class="fa fa-picture-o fa-fw "></i> {{$aViewData['resourceLabel']}} <span>> Imágenes</span><span> > "{{ ($item ) ? App\AppCustom\Util::truncateString(($item->nombre?$item->nombre:$item->titulo), 50) : "" }}"</span>
			</h6>
        </div>
		<!-- NEW WIDGET START -->
		<article>
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					<!-- widget div-->
					<div>
							
							@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
							<div class="tab-pane fade active in">
								{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
									{{ Form::hidden('resource_id', $item->id) }}
									{{ Form::hidden('imageThumbProportion', $aViewData['imageThumbProportion']) }}
										<div>
											<section>
												<button id="newImg" type="button" class="btn btn-labeled btn-success pull-right" style="margin-bottom:10px;">
													<span class="btn-label">
													 <i class="glyphicon glyphicon-plus"></i>
													</span>Nueva Imagen
											   </button>
											   <strong>Ancho mínimo:</strong> {{ ($aViewData['aImageCropSize']['w']) }}px<br/>
											   <strong>Alto mínimo:</strong> {{ ($aViewData['aImageCropSize']['h']) }}px
											</section>
											<div class="toggDiv" style="display:none;">
												<div>
													<fieldset class="scheduler-border">
														<legend class="scheduler-border">Carga de Imagen</legend>
														<section>
															<div class="image-editor">																
																<div class="cropit-preview">
																	<div class="smart-form">
																		<div class="input input-file"><span class="button"><input  id="imageFile" name="file" type="file" style="height: 75px;" class="cropit-image-input" onchange="this.parentNode.nextSibling.value = this.value">Seleccionar imagen</span></div>
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

													<fieldset class="scheduler-border" id="myTabContent3">
														<legend class="scheduler-border">Información de la imagen</legend>
														
														@if($aViewData['resource']=='productos' && isset($aViewData['aColores']))
														<section>
															<label class="col col-md-2">Color:</label>
															<div class="col col-10">
																<label class="select">
																	<?php $toDropDownColor = $aViewData['aColores']->prepend('Seleccione Color', ''); ?>
																	{{ Form::select('id_color',$toDropDownColor,
																	'',
																	['class' => 'col col-md-12', 'id' => 'id_color'])}}
																	<i></i>
																</label>
															</div>
														</section>
														@endif
														<section>
															<label class="col col-md-2">Nombre *:</label>
															<label class="select col-md-10 row">
																{{ Form::text('name', '',['id' => 'name']) }}
															</label>
														</section>
														<section>
															<label class="col col-md-2">Epígrafe:</label>
															<label class="select col-md-10 row">
																{{ Form::textarea('epigraph','', ['style'=>'height:60px']) }}
															</label>
														</section>
														<section>
																<label class="col col-md-2">Orden:</label>
																<label class="input col col-md-10 row">
																	{{ Form::text('order') }}
																</label>
														</section>
													</fieldset>
												</div>
													
												<div class="row pull-right" style="margin-top:13px;margin-bottom: 13px;">
													<div class="col-md-12">
														@if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
														<button type="button" id="save" data-mode="add" class="btn btn-primary">
																<i class="fa fa-save"></i> Guardar 
															</button>
														<button type="button" id="cancel" class="btn btn-danger hidden"><i class="fa fa-times none"></i> Cancelar</button>
														@endif
													</div>
												</div>
												
											</div>
										</div>
									

							{{ Form::close() }}
							</div>
							@endif
							
							<br clear="all" />
							
							 <div style="margin-top:2%;border-top: 2px solid #e6e6e6;" class="widget-body no-padding">
								<div class="widget-body-toolbar">

								</div>
								<!-- end widget div -->
								<table id="sub1_{{ $aViewData['resource'] }}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
									<thead>
										<tr>
											<th></th>
											<th>Nombre</th>
											<th>Epígrafe</th>
											<th>Orden</th>
											@if($aViewData['resource']=='productos' && isset($aViewData['aColores']))
											<th>Color</th>
											@endif
											<th>Habilitada&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destacada</th>
										</tr>
									</thead>
								</table>
							</div>
							
					</div>
				   
					<!-- end widget content -->
			</div>
			<!-- end widget -->
		</div>
</div>
@include('imageMainScripts')