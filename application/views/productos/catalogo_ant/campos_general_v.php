<?php
    $campos_general = array(
        'precio_base' => 'Precio base',
        'iva_porcentaje' => '% IVA',
        'iva' => 'Valor IVA',
        'precio' => 'Precio',
        'cant_disponibles' => 'Cantidad disponibles',
        'peso' => 'Peso (Gramos)',
        'ancho' => 'Ancho (cm)',
        'alto' => 'Alto (cm)'
    );
    
    $campos_requeridos = array(
        'precio_base',
        'iva_porcentaje',
        'iva',
        'precio',
        'cant_disponibles',
        'peso'
    );
?>

<?php foreach ($campos_general as $nombre_campo => $titulo_campo) : ?>
    <?php

        $label = $titulo_campo;

        $att_campo = array(
            'id'     => 'field-' . $nombre_campo,
            'name'   => $nombre_campo,
            'class'  => 'form-control',
            'value'  => $row->$nombre_campo,
            'placeholder'   => $titulo_campo
        );

        if ( in_array($nombre_campo, $campos_requeridos) ) {
            $att_campo['required'] = TRUE;
            $label = $titulo_campo . ' *';
        }
    ?>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="<?= $nombre_campo ?>"><?= $label ?></label>
        <div class="col-sm-9">
            <?= form_input($att_campo) ?>
        </div>
    </div>
<?php endforeach ?>