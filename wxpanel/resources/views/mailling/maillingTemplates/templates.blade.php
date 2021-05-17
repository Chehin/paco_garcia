{{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
<div class="tab-pane fade active in" id="l1">
    
<fieldset class="smart-form">
                                      
<section>
    <div class="row">
      <label class="label col col-2">Nombre *:</label>
      <label class="input col col-10"> 
      <input type="text" name="nombre" placeholder="Nombre" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['template']->nombre : '' }}" require>
     
      </label>
    </div>
</section>

    <section>
        <div class="row">
          <label class="label col col-2">Categoria *:</label>
          <label class="input col col-10"> 
          <input type="text" name="tipo" placeholder="Categoria" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['template']->tipo : '' }}" require>
         
          </label>
        </div>
    </section>

</fieldset>



<fieldset>

<label class="label  col-2">Mensaje:</label><br>
<div class="col-lg-12">  
<textarea id="content">
    
    {{ ('edit' == $mode) ? '<div id="template">'.$aViewData['aCustomViewData']['template']->template.'</div>' : '<div id="template"></div>' }}
</textarea>

<input type="hidden" id="fill" name="template" value="{{ ('edit' == $mode) ? $aViewData['aCustomViewData']['template']->template : ''  }}">                   
</div>

</fieldset>
</div>


<!-- Buttons inside Form!!-->
<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">											
<div style="padding:0;" class="col-md-12">
    <button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
    <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>											
</div>
</div>
{{ Form::close() }}