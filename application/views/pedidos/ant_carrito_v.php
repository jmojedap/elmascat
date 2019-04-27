<?php

    $pedido_id = 0;
    if ( ! is_null($this->session->userdata('pedido_id')) ) {
        $pedido_id = $this->session->userdata('pedido_id');
    }
?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var pedido_id = <?= $pedido_id ?>;
    var producto_id = 0;
    var cantidad = 1;   //Cantidad de detalle de productos
</script>

<script>
    $(document).ready(function(){
        //Aumentar unidad a la cantidad
        $('.producto_mas').click(function(){
            producto_id = $(this).data('producto_id');
            cantidad = $(this).data('cantidad') + 1;
            guardar_detalle();
        });
        
        //Restar unidad a la cantidad
        $('.producto_menos').click(function(){
            producto_id = $(this).data('producto_id');
            cantidad = $(this).data('cantidad') - 1;
            guardar_detalle();
        });
        
        $('.cart_quantity_input').change(function(){
            producto_id = $(this).data('producto_id');
            cantidad = $(this).val();
            guardar_detalle();
        });
    });
</script>

<script>
    //Ajax
    function guardar_detalle(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/guardar_detalle',
            data: {
                producto_id : producto_id,
                cantidad : cantidad
            },
            success: function(){
                window.location = base_url + 'pedidos/carrito';
            }
        });
    }
</script>

<section id="cart_items">
    <div class="">
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Producto</td>
                        <td class="description"></td>
                        <td class="price">Precio</td>
                        <td class="quantity">Cantidad</td>
                        <td class="total">Total</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle->result() as $row_detalle) : ?>
                        <?php
                            $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                            $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                            $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);
                            
                            $att_img = $this->Producto_model->att_img($row_detalle->producto_id, 125);
                        ?>
                        <tr>
                            <td class="image">
                                <a href="">
                                    <?= img($att_img) ?>
                                </a>
                            </td>
                            <td class="cart_description">
                                <h4>
                                    <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                                </h4>
                                <p>
                                    Web ID: <?= $row_detalle->producto_id ?>
                                    | <?= $row_detalle->peso ?> g
                                    <?php if ( $row_producto->cant_disponibles == $row_detalle->cantidad ){ ?>
                                        | <span class="resaltar"><?= $row_producto->cant_disponibles ?> disponibles</span>
                                    <?php } ?>
                                    
                                </p>
                                
                            </td>
                            <td class="cart_price">
                                <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                            </td>
                            <td class="cart_quantity">
                                <div class="cart_quantity_button">
                                    <span class="cambiar_cantidad producto_mas" data-producto_id="<?= $row_detalle->producto_id ?>" data-cantidad="<?= $row_detalle->cantidad ?>"> + </span>
                                    <input class="cart_quantity_input" type="text" name="quantity" value="<?= $row_detalle->cantidad ?>" autocomplete="off" size="2" data-producto_id="<?= $row_detalle->producto_id ?>">
                                    <span class="cambiar_cantidad producto_menos" data-producto_id="<?= $row_detalle->producto_id ?>" data-cantidad="<?= $row_detalle->cantidad ?>"> - </span>
                                </div>
                            </td>
                            <td class="cart_total">
                                <p class="cart_total_price">
                                    <?= $this->Pcrn->moneda($precio_detalle) ?>
                                </p>
                            </td>
                            <td class="cart_delete">
                                <?= anchor("pedidos/eliminar_detalle/{$row_detalle->id}", '<i class="fa fa-times"></i>', 'class="cart_quantity_delete" title=""') ?>
                            </td>
                            
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->


<div class="shopper-informations">
    <div class="row">
        <div class="col-sm-8">
            <?= anchor("pedidos/abandonar", '<i class="fa fa-trash-o"></i> Cancelar', 'class="btn btn-primary"') ?>
        </div>

        <div class="col-sm-4">
            <div class="order-message total_area">
                <p>Resumen de la compra</p>

                <table class="table table-condensed total-result">
                    <tr>
                        <td>Subtotal productos </td>
                        <td>
                            <span class="resaltar"><?= $this->Pcrn->moneda($row->valor_total) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Peso total</td>
                        <td><?= $row->peso_total ?> kg</td>
                    </tr>
                </table>

                
                <?= anchor("pedidos/compra_a/{$row->cod_pedido}", 'Continuar', 'class="btn btn-primary btn-block"') ?>
            </div>	
        </div>
    </div>
</div>