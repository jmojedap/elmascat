<?php 
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    $campos_valores = array(
        'precio' => 'PRECIO DE VENTA',
        'iva_porcentaje' => '% IVA',
        'costo' => 'Costo',
        'iva' => 'Valor IVA',
        'precio_base' => 'Precio base',
        'cant_disponibles' => 'Cantidad disponibles',
        'peso' => 'Peso (Gramos)',
        'ancho' => 'Ancho (cm)',
        'alto' => 'Alto (cm)'
    );
    
    $campos_requeridos = array(
        'costo',
        'iva_porcentaje',
        'iva',
        'precio',
        'cant_disponibles',
        'peso',
        'precio_base'
    );
    
    $campos_enteros = array(
        'peso',
        'cant_disponibles'
    );

    $opciones_promociones = $this->App_model->opciones_post('tipo_id = 31001', 'n', 'Sin promoción');
    
    $att_submit = array(
        'class'  => 'btn btn-success btn-block',
        'value'  => 'Actualizar'
    );
?>

<?php $this->load->view('productos/editar/jquery_scripts_v') ?>

<div class="row">
    <div class="col-md-8 form-horizontal">
        <div class="panel panel-default">
            <div class="panel-body">
                <form accept-charset="utf-8" method="POST" id="producto_form" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for=""></label>
                        <div class="col-md-8">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="promocion_id">Promoción aplicada</label>
                        <div class="col-md-8">
                            <?= form_dropdown('promocion_id', $opciones_promociones, $row->promocion_id, 'class="form-control chosen-select"') ?>
                        </div>
                    </div>
                    
                    <?php foreach ($campos_valores as $nombre_campo => $titulo_campo) : ?>
                        <?php

                            $label = $titulo_campo;

                            $att_campo = array(
                                'id'     => 'field-' . $nombre_campo,
                                'name'   => $nombre_campo,
                                'class'  => 'form-control',
                                'value'  => $row->$nombre_campo,
                                'placeholder'   => $titulo_campo
                            );

                            if ( in_array($nombre_campo, $campos_requeridos) ) 
                            {
                                $att_campo['required'] = TRUE;
                                $label = $titulo_campo . ' *';
                            }
                            
                            if ( in_array($nombre_campo, $campos_enteros) ) 
                            {
                                $att_campo['type'] = 'number';
                                $att_campo['min'] = 0;
                            }
                        ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="<?= $nombre_campo ?>"><?= $label ?></label>
                            <div class="col-md-8">
                                <?= form_input($att_campo) ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">        
        <?php $this->load->view('productos/editar/tabla_precios_v'); ?>
    </div>
    
</div>

