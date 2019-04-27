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
        <title>Pedido <?php echo $row_pedido->cod_pedido ?></title>
    </head>
    <body>
        <div style="<?php echo $style['body'] ?>">
            
            <table>
                <tr>
                    <td style="width: 50%;">
                        <h1 style="<?php echo $style['h1'] ?>"><?php echo $row_pedido->nombre . ' ' . $row_pedido->apellidos ?></h1>
                    </td>
                    <td style="text-align: left;">
                        <h3 style="<?php echo $style['resaltar'] ?>">Valor: <?php echo $this->Pcrn->moneda($row_pedido->valor_total) ?></h3>
                        <h4><?php echo $this->App_model->nombre_item($row_pedido->estado_pedido, 1, 7) ?></h4>
                    </td>
                    <td style="text-align: right;">
                        <?php echo anchor($link_estado, 'Ver pedido', 'style="' . $style['btn'] . '" title="Ver pedido en la página"') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span style="<?php echo $style['suave']?>">
                            Ciudad:
                        </span>
                        <span style="<?php echo $style['resaltar'] ?>">
                            <?php echo $row_pedido->ciudad ?>
                        </span>
                        <span style="<?php echo $style['suave']?>">
                            |
                        </span>

                        <span style="<?php echo $style['suave']?>">
                            Cód. Pedido:
                        </span>
                        <span style="<?php echo $style['resaltar'] ?>">
                            <?php echo $row_pedido->cod_pedido ?>
                        </span>
                        <span style="<?php echo $style['suave']?>">
                            |
                        </span>

                        <span style="<?php echo $style['suave']?>">
                            Peso:
                        </span>
                        <span style="<?php echo $style['resaltar'] ?>">
                            <?php echo $row_pedido->peso_total ?> kg
                        </span>
                        <span style="<?php echo $style['suave']?>">
                            |
                        </span>

                        <span style="<?php echo $style['suave']?>">
                            Actualizado:
                        </span>
                        <span style="<?php echo $style['resaltar'] ?>">
                            <?php echo $this->Pcrn->fecha_formato($row_pedido->editado, 'Y-M-d H:i') ?>
                        </span>
                        <span style="<?php echo $style['suave']?>">
                            |
                        </span>
                    </td>
                </tr>
            </table>

            <hr/>

            <h2>Detalle del pedido</h2>

            <table style="<?php echo $style['table'] ?>">
                <thead style="<?php echo $style['thead'] ?>">
                    <tr style="">
                        <td style="<?php echo $style['td'] ?>">Producto</td>
                        <td style="<?php echo $style['td'] ?>">Precio</td>
                        <td style="<?php echo $style['td'] ?>">Cantidad</td>
                        <td style="<?php echo $style['td'] ?>">
                            <?php echo $this->Pcrn->moneda($row_pedido->total_productos) ?>
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
                            <td style="<?php echo $style['td'] ?>">
                                <?php echo $row_detalle->nombre_producto ?>
                            </td>
                            <td style="<?php echo $style['td'] ?>">
                                <p>
                                    <?php echo $this->Pcrn->moneda($row_detalle->precio) ?>
                                </p>
                            </td>
                            <td style="<?php echo $style['td'] ?>">
                                <p>
                                    <?php echo $row_detalle->cantidad ?>
                                </p>
                            </td>
                            <td style="<?php echo $style['td'] ?>">
                                <?php echo $this->Pcrn->moneda($precio_detalle) ?>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <h2>Datos de entrega</h2>

            <p>
                <span style="<?php echo $style['suave'] ?>">
                    Cliente
                </span>
                <span style="<?php echo $style['resaltar'] ?>">
                    <?php echo $row_pedido->nombre . ' ' . $row_pedido->apellidos ?>
                </span>

                &middot;
                <span style="<?php echo $style['suave'] ?>">
                    No. documento
                </span>
                <span style="<?php echo $style['resaltar'] ?>">
                    <?php echo $row_pedido->no_documento ?>
                </span>

                <span style="<?php echo $style['suave'] ?>">E-mail</span>
                <span style="<?php echo $style['resaltar'] ?>"><?php echo $row_pedido->email ?></span>

                <span style="<?php echo $style['suave'] ?>">Dirección</span>
                <span style="<?php echo $style['resaltar'] ?>">
                    <?php echo $row_pedido->direccion ?>
                </span>

                <?php if ( strlen($row_pedido->direccion_detalle) ){ ?>
                    <span style="<?php echo $style['suave'] ?>">|</span>
                    <span style="<?php echo $style['resaltar'] ?>">
                        <?php echo $row_pedido->direccion_detalle ?>
                    </span>
                <?php } ?>

                <span style="<?php echo $style['suave'] ?>">
                    Ciudad
                </span>
                <span style="<?php echo $style['resaltar'] ?>">
                    <?php echo $row_pedido->ciudad ?>
                </span>

                <span style="<?php echo $style['suave'] ?>">
                    Teléfonos
                </span>
                <span style="<?php echo $style['resaltar'] ?>">
                    <?php echo $row_pedido->telefono ?> - <?php echo $row_pedido->celular ?>
                </span>

                <span style="<?php echo $style['suave'] ?>"></span>
                <span style="<?php echo $style['resaltar'] ?>"></span>

                <span style="<?php echo $style['suave'] ?>"></span>
                <span style="<?php echo $style['resaltar'] ?>"></span>

                <span style="<?php echo $style['suave'] ?>"></span>
                <span style="<?php echo $style['resaltar'] ?>"></span>
                
            </p>
        </div>
    </body>
</html>

