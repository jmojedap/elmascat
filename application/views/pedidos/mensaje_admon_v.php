<?php
    $link_estado = base_url() . "pedidos/estado/?cod_pedido={$row_pedido->cod_pedido}";
    
    $style['body'] = "font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; background-color: #fafafa;";
    $style['h1'] = 'color: #00b0f0;';
    $style['a'] = 'color: #00b0f0';
    
    $style['table'] = 'width: 100%; border-collapse: collapse; background-color: #FFF;';
    $style['thead'] = '';
    $style['td'] = 'border: 1px solid #ddd; padding: 5px;';
    
    $style['btn'] = 'background: #fdd922; padding: 10px; color: white; text-decoration: none; border-radius: 5px;';
    $style['resaltar'] = 'font-weight: bold; color: #da4631;';
    $style['suave'] = 'color: #aaa;';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pedido <?= $row_pedido->cod_pedido ?></title>
    </head>
    <body>
        <div style="<?= $style['body'] ?>">
            
            <table>
                <tr>
                    <td style="width: 50%;">
                        <h1 style="<?= $style['h1'] ?>"><?= $row_pedido->nombre . ' ' . $row_pedido->apellidos ?></h1>
                    </td>
                    <td style="text-align: left;">
                        <h3 style="<?= $style['resaltar'] ?>">Valor: <?= $this->Pcrn->moneda($row_pedido->valor_total) ?></h3>
                        <h4><?= $this->App_model->nombre_item($row_pedido->estado_pedido, 1, 7) ?></h4>
                    </td>
                    <td style="text-align: right;">
                        <?= anchor($link_estado, 'Ver pedido', 'style="' . $style['btn'] . '" title="Ver pedido en la página"') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span style="<?= $style['suave']?>">
                            Ciudad:
                        </span>
                        <span style="<?= $style['resaltar'] ?>">
                            <?= $row_pedido->ciudad ?>
                        </span>
                        <span style="<?= $style['suave']?>">
                            |
                        </span>

                        <span style="<?= $style['suave']?>">
                            Cód. Pedido:
                        </span>
                        <span style="<?= $style['resaltar'] ?>">
                            <?= $row_pedido->cod_pedido ?>
                        </span>
                        <span style="<?= $style['suave']?>">
                            |
                        </span>

                        <span style="<?= $style['suave']?>">
                            Peso:
                        </span>
                        <span style="<?= $style['resaltar'] ?>">
                            <?= $row_pedido->peso_total ?> kg
                        </span>
                        <span style="<?= $style['suave']?>">
                            |
                        </span>

                        <span style="<?= $style['suave']?>">
                            Actualizado:
                        </span>
                        <span style="<?= $style['resaltar'] ?>">
                            <?= $this->Pcrn->fecha_formato($row_pedido->editado, 'Y-M-d H:i') ?>
                        </span>
                        <span style="<?= $style['suave']?>">
                            |
                        </span>
                    </td>
                </tr>
            </table>

            <hr/>

            <h2>Detalle del pedido</h2>

            <table style="<?= $style['table'] ?>">
                <thead style="<?= $style['thead'] ?>">
                    <tr style="">
                        <td style="<?= $style['td'] ?>">Producto</td>
                        <td style="<?= $style['td'] ?>">Precio</td>
                        <td style="<?= $style['td'] ?>">Cantidad</td>
                        <td style="<?= $style['td'] ?>">
                            <?= $this->Pcrn->moneda($row_pedido->total_productos) ?>
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
                            <td style="<?= $style['td'] ?>">
                                <?= $row_detalle->nombre_producto ?>
                            </td>
                            <td style="<?= $style['td'] ?>">
                                <p>
                                    <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                                </p>
                            </td>
                            <td style="<?= $style['td'] ?>">
                                <p>
                                    <?= $row_detalle->cantidad ?>
                                </p>
                            </td>
                            <td style="<?= $style['td'] ?>">
                                <?= $this->Pcrn->moneda($precio_detalle) ?>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <h2>Datos de entrega</h2>

            <p>
                <span style="<?= $style['suave'] ?>">
                    Cliente
                </span>
                <span style="<?= $style['resaltar'] ?>">
                    <?= $row_pedido->nombre . ' ' . $row_pedido->apellidos ?>
                </span>

                &middot;
                <span style="<?= $style['suave'] ?>">
                    No. documento
                </span>
                <span style="<?= $style['resaltar'] ?>">
                    <?= $row_pedido->no_documento ?>
                </span>

                <span style="<?= $style['suave'] ?>">E-mail</span>
                <span style="<?= $style['resaltar'] ?>"><?= $row_pedido->email ?></span>

                <span style="<?= $style['suave'] ?>">Dirección</span>
                <span style="<?= $style['resaltar'] ?>">
                    <?= $row_pedido->direccion ?>
                </span>

                <?php if ( strlen($row_pedido->direccion_detalle) ){ ?>
                    <span style="<?= $style['suave'] ?>">|</span>
                    <span style="<?= $style['resaltar'] ?>">
                        <?= $row_pedido->direccion_detalle ?>
                    </span>
                <?php } ?>

                <span style="<?= $style['suave'] ?>">
                    Ciudad
                </span>
                <span style="<?= $style['resaltar'] ?>">
                    <?= $row_pedido->ciudad ?>
                </span>

                <span style="<?= $style['suave'] ?>">
                    Teléfonos
                </span>
                <span style="<?= $style['resaltar'] ?>">
                    <?= $row_pedido->telefono ?> - <?= $row_pedido->celular ?>
                </span>

                <span style="<?= $style['suave'] ?>"></span>
                <span style="<?= $style['resaltar'] ?>"></span>

                <span style="<?= $style['suave'] ?>"></span>
                <span style="<?= $style['resaltar'] ?>"></span>

                <span style="<?= $style['suave'] ?>"></span>
                <span style="<?= $style['resaltar'] ?>"></span>
                
            </p>
        </div>
    </body>
</html>

