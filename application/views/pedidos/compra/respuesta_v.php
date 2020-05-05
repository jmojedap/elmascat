<?php

    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $get_url = str_replace(current_url(), '', $url);
    $link_print = "pedidos/respuesta_print{$get_url}";

    $cl_respuesta = 'warning';
    $icono_respuesta = '<i class="fa fa-info-circle warning"></i>';
    if ( $arr_respuesta_pol['codigo_respuesta_pol'] == 1 )
    {
        $cl_respuesta = 'success';
        $icono_respuesta = '<i class="fa fa-check-circle text-success"></i>';
    }

?>


<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="page-title">
            <h2 class="title">Resultado transacción</h2>
        </div>
        
        <table class="table table-striped">
            <tbody>
                <tr class="<?php echo $cl_respuesta ?>">
                    <td>Respuesta PayU</td>
                    <td>
                        <?= $icono_respuesta ?>
                        <?= $this->Item_model->nombre(10, $arr_respuesta_pol['codigo_respuesta_pol']) ?>
                    </td>
                </tr>
                <tr>
                    <td>Estado transacción</td>
                    <td>
                        <?= $this->Item_model->nombre(9, $arr_respuesta_pol['estado_pol']) ?>
                    </td>
                </tr>
                <tr>
                    <td>Cód. Transacción en PayU</td>
                    <td><?= $arr_respuesta_pol['ref_pol'] ?></td>
                </tr>
                <tr>
                    <td>Valor</td>
                    <td>
                        <?= $arr_respuesta_pol['moneda'] ?>
                        <?= number_format($arr_respuesta_pol['valor'],2, ',', '.') ?>
                    </td>
                </tr>
                <tr>
                    <td>Fecha transacción</td>
                    <td><?= $arr_respuesta_pol['fecha_procesamiento'] ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="page-title">
            <h2 class="title">Datos de su compra</h2>
        </div>
        
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>Cód. Pedido</td>
                    <td><?= $row->cod_pedido ?></td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td><?= $row->nombre . ' ' . $row->apellidos ?></td>
                </tr>
                <tr>
                    <td>No. documento</td>
                    <td><?= $row->no_documento ?></td>
                </tr>
                <tr>
                    <td>E-mail</td>
                    <td><?= $row->email ?></td>
                </tr>
                <tr>
                    <td>Dirección</td>
                    <td>
                        <?= $row->direccion ?>
                        <?php if ( strlen($row->direccion_detalle) ){ ?>
                            <br/>
                            <?= $row->direccion_detalle ?>
                        <?php } ?>
                        <br/>
                        <?= $row->ciudad ?>
                    </td>
                </tr>
                <tr>
                    <td>Teléfonos</td>
                    <td>
                        <?= $row->telefono ?> - <?= $row->celular ?>
                    </td>
                </tr>
                <tr>
                    <td>Notas</td>
                    <td>
                        <?= $row->notas ?>
                    </td>
                </tr>
                <tr>
                    <td>Subtotal Productos</td>
                    <td>
                        <?= $this->Pcrn->moneda($row->total_productos) ?>
                    </td>
                </tr>
                <tr>
                    <td>Gastos de envío</td>
                    <td>
                        <?= $this->Pcrn->moneda($row->total_extras) ?>
                    </td>
                </tr>
                <tr>
                    <td>Valor total</td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->valor_total) ?>
                        </b>
                    </td>
                </tr>
                
            </tbody>
        </table>
        
        <div class="page-title">
            <h2 class="title">Detalle de la compra</h2>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr class="cart_menu">
                    <td>Producto</td>
                    <td>Precio</td>
                    <td>Cantidad</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalle->result() as $row_detalle) : ?>
                    <?php
                    $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                    $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                    ?>
                    <tr>
                        <td class="">
                            <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto, 'target="_blank"') ?>
                        </td>
                        <td>
                            <p>
                                <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                            </p>
                        </td>
                        <td class="">
                            <p>
                                <?= $row_detalle->cantidad ?>
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                <?= $this->Pcrn->moneda($precio_detalle) ?>
                            </p>
                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    
    <div class="col-md-2">
        <?= anchor($link_print, '<i class="fa fa-print"></i> Imprimir', 'class="btn btn-polo btn-block" title="Imprimir este reporte" target="_blank"') ?>
    </div>
</div>