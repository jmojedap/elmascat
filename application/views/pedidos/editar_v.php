<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_factura = array(
        'id'     => 'campo-factura',
        'name'   => 'factura',
        'class'  => 'form-control',
        'value'  => $row->factura,
        'placeholder'   => 'Nó factura',
        'title'   => 'Escriba el número de la factura del pedido'
    );
    
    $att_no_guia = array(
        'id'     => 'campo-no_guia',
        'name'   => 'no_guia',
        'class'  => 'form-control',
        'value'  => $row->no_guia,
        'placeholder'   => 'Número de guía de envío',
        'title'   => 'Escriba el número guía del envío del pedido'
    );
    
    $att_notas_admin = array(
        'id'     => 'campo-notas_admin',
        'name'   => 'notas_admin',
        'class'  => 'form-control',
        'value'  => $row->notas_admin,
        'rows'  => 4,
        'placeholder'   => 'Notas internas sobre el pedido',
        'title'   => 'Notas internas sobre el pedido, no visibles por el cliente'
    );
    
    $opciones_estado = $this->Item_model->opciones('categoria_id = 7');
    
    $att_submit = array(
        'class' => 'btn btn-primary',
        'value' => 'Guardar'
    );
?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open($destino_form, $att_form) ?>
            <div class="form-group">
                <label for="estado_pedido" class="col-md-3 control-label">Estado</label>
                <div class="col-md-9">
                    <?= form_dropdown('estado_pedido', $opciones_estado, $row->estado_pedido, 'class="form-control chosen-select"') ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="factura" class="col-md-3 control-label">No. factura</label>
                <div class="col-md-9">
                    <?= form_input($att_factura); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="no_guia" class="col-md-3 control-label">No. guía</label>
                <div class="col-md-9">
                    <?= form_input($att_no_guia); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notas_admin" class="col-md-3 control-label">Notas internas</label>
                <div class="col-md-9">
                    <?= form_textarea($att_notas_admin); ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        <?= form_close('') ?>
    </div>
</div>