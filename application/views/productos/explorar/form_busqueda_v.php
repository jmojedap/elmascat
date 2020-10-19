<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    //Elementos formulario
        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'autofocus' => TRUE,
            'placeholder' => 'Buscar por nombre o descripción...',
            'value' => $busqueda['q']
        );

        $att_submit = array(
            'class' =>  'btn btn-primary btn-block',
            'value' =>  'Buscar'
        );
        
    //Clases filtros
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'filtro';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("app/buscar/{$controlador}/{$funcion}", $att_form) ?>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-9">
                <div class="input-group">
                    <?= form_input($att_q) ?>
                    <span class="input-group-btn" title="Mostrar búsqueda avanzada">
                        <button class="btn btn-info" id="alternar_avanzada" type="button">
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-sm-3">
                <?= form_submit($att_submit) ?>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['est'] ?>">
            <label for="est" class="col-sm-3 control-label">Estado</label>
            <div class="col-sm-6">
                <?= form_dropdown('est', $opciones_estado, $busqueda['est'], 'class="form-control" title="Filtrar estado del producto"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['cat'] ?>">
            <label for="cat" class="col-sm-3 control-label">Categoría</label>
            <div class="col-sm-6">
                <?= form_dropdown('cat', $opciones_categoria, $busqueda['cat'], 'class="form-control" title="Filtrar por categoría"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['tag'] ?>">
            <label for="cat" class="col-sm-3 control-label">Etiquetas</label>
            <div class="col-sm-6">
                <?= form_dropdown('tag', $opciones_tag, $busqueda['tag'], 'class="form-control chosen-select" title="Filtrar por etiqueta"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['fab'] ?>">
            <label for="fab" class="col-sm-3 control-label">Fabricante</label>
            <div class="col-sm-6">
                <?= form_dropdown('fab', $opciones_fabricante, $busqueda['fab'], 'class="form-control chosen-select" title="Filtrar por fabricante del producto"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['dcto'] ?>">
            <label for="dcto" class="col-sm-3 control-label">Promoción</label>
            <div class="col-sm-6">
                <?= form_dropdown('dcto', $opciones_promocion, $busqueda['dcto'], 'class="form-control chosen-select" title="Filtrar por promoción activa"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['dcto'] ?>">
            <label for="dcto" class="col-sm-3 control-label">Peso máximo (g)</label>
            <div class="col-sm-6">
                <input name="fe1" type="text" class="form-control" value="<?= $busqueda['fe1'] ?>">
            </div>
        </div>

    </div>

<?= form_close() ?>