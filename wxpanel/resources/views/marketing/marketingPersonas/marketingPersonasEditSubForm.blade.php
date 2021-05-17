<div class="modal-body">
	<div>
		<div class="widget-body">
            {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form', 'class' => 'smart-form')) }}
            <fieldset>
                <section>
                    <div class="row">
                        <label class="label col col-3">Email *:</label>
                        <div class="col col-9">
                            <label class="input">
                                <input type="text" name="email" required="" value="{{ ('edit' == $mode) ? $item->email : '' }}" />
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Nombre *:</label>
                        <div class="col col-9">
                            <label class="input">
                                <input type="text" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Apellido*:</label>
                        <div class="col col-9">
                            <label class="input">
                                <input type="text" name="apellido" required="" value="{{ ('edit' == $mode) ? $item->apellido : '' }}" />
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Telefono:</label>
                        <div class="col col-9">
                            <label class="input">
                                <input type="text" name="telefono" value="{{ ('edit' == $mode) ? $item->telefono : '' }}" />
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">País:</label>
                        <div class="col col-9">
                            <label class="select">
                                <?php $toDropDown1 = $aViewData['aCustomViewData']['aPaises']->prepend('Seleccionar Pais', ''); ?>
                                {{ Form::select(
                                'id_pais',
                                $toDropDown1,
                                ("edit" == $mode) ? $item->id_pais : '',
                                ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_pais']
                                )
                                }}
                                <i></i>
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Provincia:</label>
                        <div class="col col-9">
                            <label class="select">
                                {{ Form::select(
                                'id_provincia',
                                $aViewData['aCustomViewData']['aProvincias'],
                                ("edit" == $mode) ? $item->id_provincia : '',
                                ['class' => 'col col-md-12','id'=> 'id_provincia']
                                )
                                }}
                                <i></i>
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Ciudad:</label>
                        <div class="col col-9">
                            <label class="input">
                                <input type="text" name="ciudad" value="{{ ('edit' == $mode) ? $item->ciudad : '' }}" />
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Empresas:</label>
                        <div class="col col-9">
                            <label class="select"> 
                                <select multiple style="width: 100%" class="select2" name="empresasIds[]" id="empresasIds">
                                </select>
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Oportunidades:</label>
                        <div class="col col-9">
                            <label class="select"> 
                                <select multiple style="width: 100%" class="select2" name="oportunidadesIds[]" id="oportunidadesIds">
                                </select>
                            </label>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <label class="label col col-3">Listas:</label>
                        <div class="col col-9">
                            <label class="select"> 
                                <select multiple style="width: 100%" class="select2" name="listasIds[]" id="listasIds">
                                </select>
                            </label>
                        </div>
                    </div>
                </section>
            </fieldset>
            {{ Form::close() }}
        </div>
    </div>
</div>