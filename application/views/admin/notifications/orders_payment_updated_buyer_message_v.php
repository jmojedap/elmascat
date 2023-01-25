<div style="<?= $styles['body'] ?>">

    <table>
        <tr>
            <td style="text-align: left; width: 50%; padding-bottom: 15px;">
                <span style="<?= $styles['text_main'] ?>"><?= $this->Pcrn->moneda($order->valor_total) ?></span>
                &middot;
                <span><?= $this->App_model->nombre_item($order->estado_pedido, 1, 7) ?></span>
            </td>
            <td style="text-align: right; padding-bottom: 15px;">
                <a href="<?= URL_APP . "pedidos/estado/?cod_pedido={$order->cod_pedido}"; ?>" title="Ver compra en la página" style="<?= $styles['btn'] ?>">
                    Ver más
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="<?= $styles['text_muted']?>">
                    Ciudad:
                </span>
                <span style="<?= $styles['text_main'] ?>">
                    <?= $order->ciudad ?>
                </span>
                <span style="<?= $styles['text_muted']?>">
                    &middot;
                </span>

                <span style="<?= $styles['text_muted']?>">
                    Cód. Pedido:
                </span>
                <span style="<?= $styles['text_main'] ?>">
                    <?= $order->cod_pedido ?>
                </span>
                <span style="<?= $styles['text_muted']?>">
                    &middot;
                </span>

                <span style="<?= $styles['text_muted']?>">
                    Peso:
                </span>
                <span style="<?= $styles['text_main'] ?>">
                    <?= $order->peso_total ?> kg
                </span>
                <span style="<?= $styles['text_muted']?>">
                    &middot;
                </span>

                <span style="<?= $styles['text_muted']?>">
                    Actualizado:
                </span>
                <span style="<?= $styles['text_main'] ?>">
                    <?= $this->Pcrn->fecha_formato($order->editado, 'Y-M-d H:i') ?>
                </span>
                <span style="<?= $styles['text_muted']?>">
                    &middot;
                </span>
            </td>
        </tr>
    </table>

    <hr />

    <h2 style="<?= $styles['h2'] ?>">Detalle del pedido</h2>

    <table style="<?= $styles['table'] ?>">
        <thead style="<?= $styles['thead'] ?>">
            <tr style="">
                <td style="<?= $styles['td'] ?>">Producto</td>
                <td style="<?= $styles['td'] ?>">Precio</td>
                <td style="<?= $styles['td'] ?>">Cantidad</td>
                <td style="<?= $styles['td'] ?>">
                    <?= $this->Pcrn->moneda($order->total_productos) ?>
                </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle->result() as $row_detalle) : ?>
            <?php
                        $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                        $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                        ?>
            <tr>
                <td style="<?= $styles['td'] ?>">
                    <?= $row_detalle->nombre_producto ?>
                </td>
                <td style="<?= $styles['td'] ?>">
                    <p>
                        <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                    </p>
                </td>
                <td style="<?= $styles['td'] ?>">
                    <p>
                        <?= $row_detalle->cantidad ?>
                    </p>
                </td>
                <td style="<?= $styles['td'] ?>">
                    <?= $this->Pcrn->moneda($precio_detalle) ?>
                </td>

            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <h2 style="<?= $styles['h2'] ?>">Datos de entrega</h2>

    <p>
        <span style="<?= $styles['text_muted'] ?>">
            Cliente
        </span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->nombre . ' ' . $order->apellidos ?>
        </span>

        &middot;
        <span style="<?= $styles['text_muted'] ?>">
            No. documento
        </span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->no_documento ?>
        </span>

        <span style="<?= $styles['text_muted'] ?>">E-mail</span>
        <span style="<?= $styles['text_main'] ?>"><?= $order->email ?></span>

        <span style="<?= $styles['text_muted'] ?>">Dirección</span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->direccion ?>
        </span>

        <?php if ( strlen($order->direccion_detalle) ){ ?>
        <span style="<?= $styles['text_muted'] ?>">&middot;</span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->direccion_detalle ?>
        </span>
        <?php } ?>

        <span style="<?= $styles['text_muted'] ?>">
            Ciudad
        </span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->ciudad ?>
        </span>

        <span style="<?= $styles['text_muted'] ?>">
            Teléfonos
        </span>
        <span style="<?= $styles['text_main'] ?>">
            <?= $order->telefono ?> - <?= $order->celular ?>
        </span>

        <span style="<?= $styles['text_muted'] ?>"></span>
        <span style="<?= $styles['text_main'] ?>"></span>

        <span style="<?= $styles['text_muted'] ?>"></span>
        <span style="<?= $styles['text_main'] ?>"></span>

        <span style="<?= $styles['text_muted'] ?>"></span>
        <span style="<?= $styles['text_main'] ?>"></span>

    </p>
</div>