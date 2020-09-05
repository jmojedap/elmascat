<?php
    //Clases columnas
        $cl_col['cod'] = '';
        $cl_col['proceso'] = '';
        $cl_col['estado'] = 'hidden-xs';
        $cl_col['creado'] = 'hidden-xs hidden-sm';
        $cl_col['creado_hace'] = 'hidden-xs';
        $cl_col['valor'] = 'hidden-xs';
?>


<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th class="<?= $cl_col['cod'] ?>">Ref. Venta</th>
            <th class="<?= $cl_col['proceso'] ?>"></th>
            <th class="<?= $cl_col['estado'] ?>">Estado</th>
            <th class="<?= $cl_col['valor'] ?> info">Valor total</th>
            <th class="<?= $cl_col['otros'] ?>">Datos</th>
            <th class="<?= $cl_col['editado'] ?>">Actualizado</th>
            <th class="<?= $cl_col['creado'] ?>">Creado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos->result() as $row_resultado) { ?>
            <?php
                //Variables
                $nombre_pedido = $row_resultado->cod_pedido;

                //$editable = $this->Pedido_model->editable($row_resultado->id);
                $editable = true;
                $estado_pedido = $this->App_model->nombre_item($row_resultado->estado_pedido, 1, 7);
                $creado_hace = $this->Pcrn->tiempo_hace($row_resultado->creado, TRUE);
                $editado_hace = $this->Pcrn->tiempo_hace($row_resultado->editado, TRUE);

                $link_pedido = anchor("pedidos/estado/?cod_pedido={$row_resultado->cod_pedido}", 'Estado', 'class="btn btn-info w4"');

                if ( $row_resultado->estado_pedido == 1) {
                    $link_pedido = anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success w4"');
                }

                //Checkbox
                $att_check = array(
                    'class' => 'check_registro',
                    'data-id' => $row_resultado->id,
                    'checked' => FALSE
                );
            ?>

            
            <tr>
                <td class="<?= $cl_col['cod'] ?>">
                    <?php if ( $this->session->userdata('role') < 10 ) { ?>
                        <a href="<?= base_url("pedidos/ver/{$row_resultado->id}") ?>" class="">
                            <?= $row_resultado->cod_pedido; ?>
                        </a>
                    <?php } else { ?>
                        <?= $nombre_pedido ?>
                    <?php } ?>
                </td>

                <td class="<?= $cl_col['proceso'] ?>">
                    <?= $link_pedido ?>
                </td>

                <td class="<?= $cl_col['estado'] ?>">
                    <?= $estado_pedido ?>
                    <?php if ( $row_resultado->estado_pedido == 1 ){ ?>
                        <?php anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success" title="Retomar el pedido"') ?>
                    <?php } ?>
                </td>
                
                <td class="<?= $cl_col['valor'] ?> info text-right">
                    <?= $this->Pcrn->moneda($row_resultado->valor_total); ?>
                </td>
                
                <td class="<?= $cl_col['otros'] ?>">
                    <?php if ( strlen($row_resultado->factura) > 0 ) { ?>
                        <span class="suave">Factura:</span>
                        <?= $row_resultado->factura ?>
                        <span class="suave"> &middot; </span>
                    <?php } ?>
                    <?php if ( strlen($row_resultado->no_guia) > 0 ) { ?>
                        <span class="suave">Guía:</span>
                        <?= $row_resultado->no_guia ?>
                    <?php } ?>
                </td>
                
                <td class="<?= $cl_col['editado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'Y-M-d') ?>
                    &middot;
                    <?= $editado_hace ?>
                </td>
                <td class="<?= $cl_col['creado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d') ?>
                    &middot;
                    <?= $creado_hace ?>
                </td>
                
            </tr>

        <?php } //foreach  ?>
    </tbody>
</table>