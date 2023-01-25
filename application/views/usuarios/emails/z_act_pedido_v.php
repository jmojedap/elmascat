<?php
    $texto_estado = $this->Item_model->nombre(7, $row_pedido->estado_pedido);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $row_pedido->nombre ?></title>
    </head>
    <body>
        <div style="<?= $style['body'] ?>">
            <h1 style="<?= $style['h1'] ?>">
                Pedido <?= $row_pedido->cod_pedido ?>: <?= $texto_estado ?>
            </h1>
            <h4 style="<?= $style['suave'] ?>"><?= "{$row_pedido->nombre} {$row_pedido->apellidos}" ?></h4>
            <p>
                Aquí está la información más actualizada sobre el estado de su compra.
            </p>
            <table style="<?= $style['table'] ?>">
                <tbody>
                    <tr>
                        <td style="<?= $style['td'] ?>" width="25%">Cód Pedido</td>
                        <td style="<?= $style['td'] ?>">
                            <?= $row_pedido->cod_pedido ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?= $style['td'] ?>">Estado del pago</td>
                        <td style="<?= $style['td'] ?>">
                            <span style="<?= $style['resaltar'] ?>">
                                <?= $this->Item_model->nombre(10, $row_pedido->codigo_respuesta_pol) ?>                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?= $style['td'] ?>">Estado del pedido</td>
                        <td style="<?= $style['td'] ?>">
                            <span style="<?= $style['resaltar'] ?>">
                                <?= $this->Item_model->nombre(7, $row_pedido->estado_pedido) ?>                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?= $style['td'] ?>">No. guía</td>
                        <td style="<?= $style['td'] ?>">
                            <?= $this->Pcrn->si_strlen($row_pedido->no_guia, '-') ?>                                
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?= $style['td'] ?>">No. factura</td>
                        <td style="<?= $style['td'] ?>">
                            <?= $this->Pcrn->si_strlen($row_pedido->factura, '-') ?>                                
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?= $style['td'] ?>">Actualizado</td>
                        <td style="<?= $style['td'] ?>">
                            <?= $this->Pcrn->fecha_formato($row_pedido->editado, 'M-d h:i a') ?>
                        </td>
                    </tr>
                </tbody>
                
            </table>
            
            <br/>
            
            <a target="_blank" href="<?= base_url("pedidos/estado/?cod_pedido={$row_pedido->cod_pedido}") ?>" style="<?= $style['btn'] ?>">
                Ver más
            </a>
        </div>
    </body>
    
</html>

