<?php
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );

    //Clases columna
        $clases_col['cliente'] = 'hidden-xs';
        $clases_col['estado'] = 'hidden-xs';
        $clases_col['valor_total'] = '';
        $clases_col['respuesta_pol'] = 'hidden-xs hidden-sm';
        $clases_col['otros'] = 'hidden-xs hidden-sm';
        $clases_col['creado'] = 'hidden-xs hidden-sm';
        $clases_col['editar'] = 'hidden-xs';
        
        
        if ( $this->session->userdata('rol_id') >= 3 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
        if ( $this->session->userdata('rol_id') <= 2 )
        {
            $clases_col['selector'] = '';
        }
        
    //Arrays con valores para contenido en lista
        $clases_estado = $this->Pedido_model->clases_estado();
        $arr_respuestas_pol = $this->Item_model->arr_item(10);

?>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="10px;"><?= form_checkbox($att_check_todos) ?></th>
        <th width="45px;" class="warning">ID</th>
        <th>Cód. Pedido</th>
        <th class="<?= $clases_col['cliente'] ?>">Cliente</th>
        <th class="<?= $clases_col['estado'] ?>">Estado</th>
        <th class="<?= $clases_col['valor_total'] ?>">Valor</th>
        <th class="<?= $clases_col['respuesta_pol'] ?>">Estado pago</th>
        <th class="<?= $clases_col['otros'] ?>">Datos</th>
        <th class="<?= $clases_col['editado'] ?>">Editado</th>
        <th class="<?= $clases_col['creado'] ?>">Creado</th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_pedido = $row_resultado->cod_pedido;
                    $link_pedido = anchor("pedidos/ver/$row_resultado->id", $nombre_pedido);
                    $editable = $this->Pedido_model->editable($row_resultado->id);

                //Datos POL
                    $condicion = "dato_id = 3005 AND elemento_id = {$row_resultado->id}";
                    $cant_reg_pol = $this->Pcrn->num_registros('meta', $condicion);

                //Checkbox
                    $att_check = array(
                        'class' =>  'check_registro',
                        'data-id' => $row_resultado->id,
                        'checked' => FALSE
                    );

                //Clase fila
                    $clase_fila = '';
                    if ( $cant_reg_pol > 0 && $row_resultado->estado_pedido <= 1 ) {
                        $clase_fila = 'danger';
                    }

                //Respuesta pol
                    $indice = '0' . $row_resultado->codigo_respuesta_pol;
                    $nombre_cliente = $row_resultado->nombre . ' ' . $row_resultado->apellidos;
            ?>
            <tr class="<?= $clase_fila ?>">
                <?php if ( $this->session->userdata('rol_id') <= 2  ) { ?>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                <?php } ?>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td><?= $link_pedido ?></td>
                <td class="<?= $clases_col['cliente'] ?>">
                        <?php if ( $row_resultado->usuario_id > 0 ) { ?>
                            <?= anchor("usuarios/pedidos/{$row_resultado->usuario_id}", $nombre_cliente, 'title="Ver pedidos del cliente"') ?>
                        <?php } else { ?>
                            <?= $nombre_cliente ?>
                        <?php } ?>
                </td>
                <td class="<?= $clases_col['estado'] ?> <?= $clases_estado[$row_resultado->estado_pedido] ?>"><?= $this->App_model->nombre_item($row_resultado->estado_pedido, 1, 7) ?></td>
                <td class="<?= $clases_col['valor_total'] ?> text-right">
                    <?= number_format($row_resultado->valor_total, 0, ',', '.'); ?>
                </td>
                <td class="<?= $clases_col['respuesta_pol'] ?>">
                    <?php if ( $cant_reg_pol > 0 ){ ?>
                        <?= anchor("pedidos/pol/{$row_resultado->id}", '<i class="fa fa-credit-card"></i>', 'class="btn btn-warning btn-xs" title="Datos de transación en Pagos On Line"') ?>
                    <?php } ?>
                    <?= $arr_respuestas_pol[$indice] ?>
                </td>
                <td class="<?= $clases_col['otros'] ?>">
                    <?php if ( strlen($row_resultado->factura) > 0 ) { ?>
                        <span class="suave">Factura:</span>
                        <?= $row_resultado->factura ?>
                        <span class="suave"> | </span>
                    <?php } ?>
                    <?php if ( strlen($row_resultado->no_guia) > 0 ) { ?>
                        <span class="suave">Guía:</span>
                        <?= $row_resultado->no_guia ?>
                    <?php } ?>
                </td>
                <td class="<?= $clases_col['editado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'M-d'); ?>
                    <span class="suave">
                        (<?= $this->Pcrn->tiempo_hace($row_resultado->editado); ?>)
                    </span>
                </td>
                <td class="<?= $clases_col['creado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->creado, 'M-d'); ?>
                    <span class="suave">
                        (<?= $this->Pcrn->tiempo_hace($row_resultado->creado); ?>)
                    </span>
                </td>
            </tr>

        <?php } //foreach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>