<?php
    $att_campo = array(
        'id' => 'campo',
        'name' => 'campo',
        'class' => 'form-control',
        'value' => 'valor',
        'placeholder' => 'Escriba el campo'
    );

    $att_cod_pedido = array(
        'name' => 'cod_pedido',
        'class' => 'form-control',
        'placeholder' => 'Código del pedido',
        'value' => $cod_pedido
    );
    
    $att_submit = array(
        'class'  => 'btn btn-primary pull-right',
        'value'  => 'Buscar'
    );
?>

<div class="bs-caja">
        
        <h2 class="heading">Datos de entrega</h2>

        <div class="">
            <div class="row">

                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                        <dt>Código de Pedido</dt>
                        <dd>
                            <span class="resaltar">
                                <?= $row->cod_pedido ?>
                            </span>
                        </dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Estado pedido</dt>
                        <dd><?= $this->App_model->nombre_item($row->estado_pedido, 1, 7) ?></dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Nombre</dt>
                        <dd><?= $row->nombre ?> <?= $row->apellidos ?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Ciudad</dt>
                        <dd><?= $row->ciudad ?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Dirección</dt>
                        <dd><?= $row->direccion ?>, <?= $row->direccion_detalle ?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Teléfono</dt>
                        <dd><?= $row->telefono ?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Celular</dt>
                        <dd><?= $row->celular ?></dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Notas</dt>
                        <dd><?= $row->notas ?></dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Peso del pedido</dt>
                        <dd><?= $row->peso_total ?> Kg</dd>
                    </dl>
                </div>

                <div class="col-sm-4">
                    <div class="">
                        <p>Resumen de la compra</p>

                        <table class="table table-condensed total-result">
                            <tr>
                                <td>Subtotal productos</td>
                                <td>
                                    <span>
                                        <?= $this->Pcrn->moneda($row->total_productos) ?>
                                    </span>
                                </td>
                            </tr>

                            <?php foreach ($extras->result() as $row_extra) : ?>
                                <tr>
                                    <td><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?></td>
                                    <td>
                                        <span>
                                            <?= $this->Pcrn->moneda($row_extra->precio) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?= $row_registro->campo_id ?>
                            <?php endforeach ?>


                            <tr>
                                <td>Total</td>
                                <td>
                                    <span class="resaltar" style="font-size: 1.5em;">
                                        <?= $this->Pcrn->moneda($row->valor_total) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>	
                </div>
            </div>
        </div>
        
        <div class="step-one">
            <h2 class="heading">Detalle del pedido</h2>
        </div>
        
        <section id="cart_items">
            <div class="">
                <div class="table-responsive cart_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="cart_menu">
                                <td class="description"></td>
                                <td class="price">Precio</td>
                                <td class="quantity">Cantidad</td>
                                <td class="total">Total</td>
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
                                        <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                                        <p>
                                            Web ID: <?= $row_detalle->producto_id ?>
                                            | <?= $row_detalle->peso ?> g
                                        </p>
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
            </div>
        </section>
        
    </div>