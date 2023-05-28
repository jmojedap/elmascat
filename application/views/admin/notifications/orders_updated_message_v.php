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

        <?php if ( $order->shipping_method_id == 98 ) : ?>
        <tr>
            <td style="<?= $styles['td'] ?>">Método de entrega</td>
            <td style="<?= $styles['td'] ?>">
                <b style="<?= $styles['text_success'] ?>">
                    <?= $this->Item_model->name(183, $order->shipping_method_id,'item_corto'); ?>
                </b>
            </td>
        </tr>
        <tr>
            <td style="<?= $styles['td'] ?>">Dirección para recoger</td>
            <td style="<?= $styles['td'] ?>">
                <a href="https://www.google.com/maps/place/Cl.+72+%2383-96,+Bogot%C3%A1/@4.6949995,-74.107477,17z/" target="_blank">
                    Av Calle 72 # 83-96
                </a>
                <br>
                <span class="text-muted">Barrio Almería - Frente ETB Santa Helenita</span>
                
                <br>
                Bogotá D.C. - Colombia
                <br>
                <span class="text-muted">Horario de entrega</span>
                <br>
                Lunes a Sábado 10am a 4pm
                <?php if ( $order->estado_pedido != 9 ) : ?>
                    <br>
                    <strong>
                        Le enviaremos un E-mail cuando su compra esté lista para ser recogida
                    </strong>
                <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>

        <?php if ( $order->shipping_method_id != 98 ) : ?>
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
        <?php endif; ?>


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