<?php
    $texto_estado = $this->Item_model->nombre(7, $row_pedido->estado_pedido);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $row_pedido->nombre ?></title>
    </head>
    <body>
        <div style="<?php echo $style['body'] ?>">
            <h1 style="<?php echo $style['h1'] ?>">
                Pedido <?php echo $row_pedido->cod_pedido ?>: <?php echo $texto_estado ?>
            </h1>
            <h4 style="<?php echo $style['suave'] ?>"><?php echo "{$row_pedido->nombre} {$row_pedido->apellidos}" ?></h4>
            <p>
                Aquí está la información más actualizada sobre el estado de su compra.
            </p>
            <table style="<?php echo $style['table'] ?>">
                <tbody>
                    <tr>
                        <td style="<?php echo $style['td'] ?>" width="25%">Cód Pedido</td>
                        <td style="<?php echo $style['td'] ?>">
                            <?php echo $row_pedido->cod_pedido ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?php echo $style['td'] ?>">Estado del pago</td>
                        <td style="<?php echo $style['td'] ?>">
                            <span style="<?php echo $style['resaltar'] ?>">
                                <?php echo $this->Item_model->nombre(10, $row_pedido->codigo_respuesta_pol) ?>                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?php echo $style['td'] ?>">Estado del pedido</td>
                        <td style="<?php echo $style['td'] ?>">
                            <span style="<?php echo $style['resaltar'] ?>">
                                <?php echo $this->Item_model->nombre(7, $row_pedido->estado_pedido) ?>                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?php echo $style['td'] ?>">No. guía</td>
                        <td style="<?php echo $style['td'] ?>">
                            <?php echo $this->Pcrn->si_strlen($row_pedido->no_guia, '-') ?>                                
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?php echo $style['td'] ?>">No. factura</td>
                        <td style="<?php echo $style['td'] ?>">
                            <?php echo $this->Pcrn->si_strlen($row_pedido->factura, '-') ?>                                
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="<?php echo $style['td'] ?>">Actualizado</td>
                        <td style="<?php echo $style['td'] ?>">
                            <?php echo $this->Pcrn->fecha_formato($row_pedido->editado, 'M-d h:i a') ?>
                        </td>
                    </tr>
                </tbody>
                
            </table>
            
            <br/>
            
            <a target="_blank" href="<?php echo base_url("pedidos/estado/?cod_pedido={$row_pedido->cod_pedido}") ?>" style="<?php echo $style['btn'] ?>">
                Ver más
            </a>
        </div>
    </body>
    
</html>

