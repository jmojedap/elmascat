<?php

    $att_q = array(
        'class' =>  'form-control',
        'name' => 'q',
        'autofocus' => TRUE,
        'placeholder' => 'Buscar por código, cliente o factura...',
        'value' => $busqueda['q']
    );

    $att_submit = array(
        'class' =>  'btn btn-primary btn-block',
        'value' =>  'Buscar'
    );

    //Opciones de dropdowns
        $opciones_estado = $this->Item_model->opciones('categoria_id = 7', 'Filtrar por estado');

        $opciones_peso = array(
            '' => 'Todos',
            '01' => 'Con peso',
            '02' => 'Sin peso',
        );
        
    //Clases filtros
        $arr_filtros = array('est', 'fe1');
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("app/buscar/{$controlador}/explorar", $att_form) ?>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-9">
                <div class="input-group">
                    <?= form_input($att_q) ?>
                    <span class="input-group-btn" title="Mostrar búsqueda avanzada">
                        <button class="btn btn-info" id="alternar_avanzada" type="button">
                            <i class="fa fa-caret-down b_avanzada_si"></i>
                            <i class="fa fa-caret-up b_avanzada_no"></i>
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
                <?= form_dropdown('est', $opciones_estado, $busqueda['est'], 'class="form-control" title="Filtrar estado del pedido"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['fe1'] ?>">
            <label for="fe1" class="col-sm-3 control-label">Tipo peso</label>
            <div class="col-sm-6">
                <?= form_dropdown('fe1', $opciones_peso, $busqueda['fe1'], 'class="form-control" title="Filtrar por peso"'); ?>
            </div>
        </div>

    </div>

<?= form_close() ?>