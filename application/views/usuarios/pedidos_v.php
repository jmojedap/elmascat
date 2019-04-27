<?php
    //Clases columnas
        $clases_col['cod'] = '';
        $clases_col['estado'] = 'hidden-xs';
        $clases_col['creado'] = 'hidden-xs hidden-sm';
        $clases_col['creado_hace'] = 'hidden-xs';
        $clases_col['valor'] = 'hidden-xs';
?>


<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="100px;" class="">CÓD</th>
            <th class="">Datos pedido</th>
            <th class="<?= $clases_col['estado'] ?>">Estado</th>
            <th class="<?= $clases_col['valor'] ?> info">Valor total</th>
            <th class="<?= $clases_col['otros'] ?>">Datos</th>
            <th class="<?= $clases_col['editado'] ?>">Actualizado</th>
            <th class="<?= $clases_col['creado'] ?>">Creado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos->result() as $row_resultado) { ?>
            <?php
                //Variables
                $nombre_pedido = $row_resultado->cod_pedido;
                $link_pedido = anchor("pedidos/estado/?cod_pedido={$row_resultado->cod_pedido}", $nombre_pedido, 'class="btn btn-info"');
                $editable = $this->Pedido_model->editable($row_resultado->id);
                $estado_pedido = $this->App_model->nombre_item($row_resultado->estado_pedido, 1, 7);
                $creado_hace = $this->Pcrn->tiempo_hace($row_resultado->creado, TRUE);
                $editado_hace = $this->Pcrn->tiempo_hace($row_resultado->editado, TRUE);

                //Checkbox
                $att_check = array(
                    'class' => 'check_registro',
                    'data-id' => $row_resultado->id,
                    'checked' => FALSE
                );
            ?>
            <tr>
                <td class=""><span class="etiqueta primario w1"><?= $link_pedido ?></span></td>
                <td class="">
                    <?php if ( $row_resultado->estado_pedido == 1 ){ ?>
                        <?= anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success" title="Retomar el pedido"') ?>
                        <br/>
                    <?php } ?>
                </td>

                <td class="<?= $clases_col['estado'] ?>">
                    <?= $estado_pedido ?>
                    <?php if ( $row_resultado->estado_pedido == 1 ){ ?>
                        <?php //anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success" title="Retomar el pedido"') ?>
                    <?php } ?>
                </td>
                
                <td class="<?= $clases_col['valor'] ?> info text-right">
                    <?= $this->Pcrn->moneda($row_resultado->valor_total); ?>
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
                    <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'Y-M-d') ?>
                    |
                    <?= $editado_hace ?>
                </td>
                <td class="<?= $clases_col['creado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d') ?>
                    |
                    <?= $creado_hace ?>
                </td>
                
            </tr>

        <?php } //foreach  ?>
    </tbody>
</table>