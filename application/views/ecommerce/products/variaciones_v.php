<?php
    //Valores de campos
    $consecutivo_auto = $productos->num_rows() + 1;
    
    $valores['referencia'] = $row->referencia . '-' . $consecutivo_auto;
    $valores['cant_disponibles'] = 30;
    $valores['talla'] = '';
    $valores['orden'] = $consecutivo_auto;
    
    $clase_td_form = 'table-info';
    $texto_id_form = 'Agregar';

    if ( ! is_null($row_variacion) ) {
        $valores['referencia'] = $row_variacion->referencia;
        $valores['cant_disponibles'] = $row_variacion->cant_disponibles;
        $valores['talla'] = $row_variacion->talla;
        $valores['orden'] = $row_variacion->puntaje;
        
        $clase_td_form = 'success';
        $texto_id_form = $row_variacion->id;
    }
        
    
    //Atributos de campos
        $att_referencia = array(
            'id'     => 'campo-referencia',
            'name'   => 'referencia',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores['referencia'],
            'placeholder'   => 'Escriba la referencia',
            'title'   => 'Escriba la referencia del producto'
        );

        $att_cant_disponibles = array(
            'id'     => 'campo-cant_disponibles',
            'name'   => 'cant_disponibles',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores['cant_disponibles'],
            'placeholder'   => 'Disponibles',
            'title'   => 'Escriba la cantidad de unidades disponibles del producto'
        );
        
        $att_talla = array(
            'id'     => 'campo-talla',
            'name'   => 'talla',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores['talla'],
            'placeholder'   => 'Escriba la talla',
            'title'   => 'Escriba la talla del producto',
            'autofocus'   => TRUE
        );
        
        $att_orden = array(
            'id'     => 'campo-orden',
            'name'   => 'puntaje',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores['orden'],
            'placeholder'   => 'Escriba el orden',
            'title'   => 'Escriba el número del orden en el que se debe mostrar esta variación de producto',
            'autofocus'   => TRUE
        );

        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Guardar'
        );
?>

<?php if ( $this->session->userdata('resultado') ){ ?>
    <?php $this->load->view('app/resultado_proceso_v'); ?>
<?php } ?>

<table class="table table-hover bg-white">
    <thead>
        <th width="45px;" class="table-warning">ID</th>
        <th>Referencia</th>
        <th>Disponibles</th>
        <th>Talla</th>
        <th>Orden</th>
        <th width="70px"></th>
    </thead>
    <tbody>
        <tr class="<?= $clase_td_form ?>">
            <form accept-charset="utf-8" method="POST" action="<?= base_url($destino_form) ?>">
                <td><?= $texto_id_form ?></td>
                <td><?= form_input($att_referencia); ?></td>
                <td><?= form_input($att_cant_disponibles); ?></td>
                <td><?= form_input($att_talla); ?></td>
                <td><?= form_input($att_orden); ?></td>
                <td><?= form_submit($att_submit) ?></td>
            </form>
        </tr>
        <?php foreach ($productos->result() as $row_producto) : ?>
            <?php
                $clase_fila = '';
                if ( $variacion_id == $row_producto->id  ) { $clase_fila = 'success'; }
            ?>
            <tr class="<?= $clase_fila ?>">
                <td class="table-warning"><span class="etiqueta primario w1"><?= $row_producto->id ?></span></td>
                <td><?= $row_producto->referencia ?></td>
                <td><?= $row_producto->cant_disponibles ?></td>
                <td><?= $row_producto->talla ?></td>
                <td><?= $row_producto->puntaje ?></td>
                <td>
                    <?= anchor("productos/variaciones/{$row_producto->padre_id}/{$row_producto->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title="Editar registro"') ?>
                    <?= anchor("productos/eliminar_variacion/{$row_producto->padre_id}/{$row_producto->id}", '<i class="fa fa-trash"></i>', 'class="a4" title="Eliminar registro"') ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>