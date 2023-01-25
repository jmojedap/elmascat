<?php
    $texts['link_profile'] = URL_APP . "pedidos/estado/{$order->id}/{$order->cod_pedido}";
    $texts['estado_pedido'] = $this->Item_model->nombre(7, $order->estado_pedido);
?>

<h2 style="<?= $styles['h2'] ?>"><?= $texts['estado_pedido'] ?> :: Compra <?= $order->cod_pedido ?></h2>

<table style="<?= $styles['table'] ?>">
    <tbody>
        <tr>
            <td style="<?= $styles['td'] ?>" width="25%">Cód Pedido</td>
            <td style="<?= $styles['td'] ?>">
                <?= $order->cod_pedido ?>
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">Estado del pago</td>
            <td style="<?= $styles['td'] ?>">
                <b style="<?= $styles['text_success'] ?>">
                    <?= $this->Item_model->nombre(10, $order->codigo_respuesta_pol) ?>
                </b>
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">Estado del pedido</td>
            <td style="<?= $styles['td'] ?>">
                <b style="<?= $styles['text_success'] ?>">
                    <?= $this->Item_model->nombre(7, $order->estado_pedido) ?>
                </b>
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">Enviado mediante</td>
            <td style="<?= $styles['td'] ?>">
                <?= $this->Item_model->name(183, $order->shipping_method_id,'item_corto'); ?>
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">Guía de envío</td>
            <td style="<?= $styles['td'] ?>">
                <?= $this->Pcrn->si_strlen($order->no_guia, '-') ?> 
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">No. factura</td>
            <td style="<?= $styles['td'] ?>">
                <?= $this->Pcrn->si_strlen($order->factura, '-') ?>
            </td>
        </tr>

        <tr>
            <td style="<?= $styles['td'] ?>">Actualizado</td>
            <td style="<?= $styles['td'] ?>">
                <?= $this->Pcrn->fecha_formato($order->editado, 'M-d h:i a') ?>
            </td>
        </tr>
    </tbody>

</table>

<br />

<a target="_blank" href="<?= base_url("pedidos/estado/?cod_pedido={$order->cod_pedido}") ?>"
    style="<?= $styles['btn'] ?>">
    Ver más
</a>