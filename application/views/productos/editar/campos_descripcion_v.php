<?php

    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_referencia = array(
        'id'     => 'referencia',
        'name'   => 'referencia',
        'class'  => 'form-control',
        'value'  => $row->referencia,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del producto'
    );
    
    $att_nombre_producto = array(
        'id'     => 'nombre_producto',
        'name'   => 'nombre_producto',
        'class'  => 'form-control',
        'value'  => $row->nombre_producto,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del producto'
    );
    
    $att_descripcion = array(
        'id'     => 'descripcion',
        'name'   => 'descripcion',
        'class'  => 'form-control',
        'value'  => $row->descripcion,
        'required' => TRUE,
        'placeholder'   => 'Escriba la descripción del producto',
        'rows'   => 7
    );
    
    $att_palabras_clave = array(
        'id'     => 'palabras_clave',
        'name'   => 'palabras_clave',
        'class'  => 'form-control',
        'autofocus'   => TRUE,
        'value'  => $row->palabras_clave,
        'required' => TRUE,
        'placeholder'   => 'Palabras clave del producto',
        'title'   => 'Escriba las palabras claves relacionados con el producto, útiles para la búsqueda del producto'
    );
    
    $att_puntaje = array(
        'id'     => 'campo-puntaje',
        'name'   => 'puntaje',
        'type'   => 'text',
        'value'  => $row->puntaje
    );
    
    //Opciones
        $opciones_fabricante = $this->Item_model->opciones_id('categoria_id = 5');
        $opciones_estado = $this->Item_model->opciones('categoria_id = 8');
        $opciones_categoria = $this->Item_model->opciones('categoria_id = 25', 'Categoría de producto');
    
    $att_submit = array(
        'class'  => 'btn btn-success btn-block',
        'value'  => 'Actualizar'
    );
    
?>

<?php $this->load->view('productos/editar/jquery_scripts_v') ?>

<div class="panel panel-default">
    <div class="panel-body">
        <form accept-charset="utf-8" method="POST" id="producto_form" class="form-horizontal">
        
            <div class="form-group">
                <label class="col-md-4 control-label" for=""></label>
                <div class="col-md-8">
                    <?php echo form_submit($att_submit) ?>
                </div>
            </div>
        
            <div class="form-group" title="Puntaje para mejorar resultado en búsquedas">
                <label class="col-md-4 control-label" for="nombre_producto">Puntaje (0-100)</label>
                <div class="col-md-8">
                    <?php //form_input($att_puntaje) ?>
                    <input id="campo-puntaje" type="text" name="puntaje" value="0;100" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="estado">Estado</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('estado', $opciones_estado, $row->estado, 'class="form-control chosen-select"') ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="nombre_producto">Título *</label>
                <div class="col-md-8">
                    <?php echo form_input($att_nombre_producto) ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="referencia">Referencia *</label>
                <div class="col-md-8">
                    <?php echo form_input($att_referencia) ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="descripcion">Descripción *</label>
                <div class="col-md-8">
                    <?php echo form_textarea($att_descripcion) ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="descripcion">Palabras clave *</label>
                <div class="col-md-8">
                    <?php echo form_input($att_palabras_clave) ?>
                </div>
            </div>

            <div class="form-group row" id="form-categoria_id">
                <label for="categoria_id" class="col-md-4 control-label">Categoría *</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('categoria_id', $opciones_categoria, '0' . $row->categoria_id, 'id="campo-tipo_id" class="form-control" required') ?>
                </div>
            </div>

            <div class="form-group" id="group-tags">
                <label class="control-label col-md-4" for="tags">Etiquetas *</label>

                <div class="col-md-8">
                    
                    <select name="tags[]" id="tags" class="form-control chosen-select" multiple required>
                        <?php foreach ($tags_activas->result() as $row_tag) : ?>
                            <?php
                                $selected = '';
                                if ( $row_tag->activo > 0 ) { $selected = 'selected'; }

                                $repeticiones_nivel = 4 * ($row_tag->nivel - 1);
                            ?>
                            <option value="0<?php echo $row_tag->id ?>" <?php echo $selected ?>>
                                <?php echo str_repeat('&nbsp;', $repeticiones_nivel) ?>
                                <?php echo $row_tag->nombre_tag ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <span class="help-block" id="alerta-tags" style="display: none;">
                        <i class="fa fa-warning"></i>
                        Debe elegir al menos una categoría
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="fabricante_id">Fabricante - Marca</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('fabricante_id', $opciones_fabricante, $row->fabricante_id, 'class="form-control chosen-select"') ?>
                </div>
            </div>
        </form>
    </div>
</div>
