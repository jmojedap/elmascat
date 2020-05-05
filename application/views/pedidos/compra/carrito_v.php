<script>
    //Variables
    var base_url = '<?php echo base_url() ?>';
    var pedido_id = <?php echo $pedido_id ?>;
    var producto_id = 0;
    var cantidad = 1;   //Cantidad de detalle de productos

    $(document).ready(function(){
        $('.cant_producto').change(function(){
            producto_id = $(this).data('producto_id');
            cantidad = $(this).val();
            guardar_detalle();
        });
    });



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

<div class="row">
    <div class="col col-md-8">
        <div class="cart wow bounceInUp animated">
            <div class="table-responsive">
                <!--<form method="post">-->
                    <input type="hidden" value="Vwww7itR3zQFe86m" name="form_key">
                    <fieldset>
                        <table class="data-table cart-table" id="shopping-cart-table">
                            <thead>
                                <tr class="first last">
                                    <th rowspan="1">&nbsp;</th>
                                    <th rowspan="1"><span class="nobr">Producto</span></th>
                                    <th class="a-center" rowspan="1">Cantidad</th>
                                    <th colspan="1" class="a-center">Subtotal</th>
                                    <th class="a-center" rowspan="1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="first last">
                                    <td class="a-right last" colspan="7">
                                        <?php echo anchor("productos/catalogo", '<i class="fa fa-arrow-left"></i><span><span> Catálogo</span></span>', 'class="btn btn-polo" title="Volver al catálogo"') ?>
                                        <?php echo anchor("pedidos/abandonar", '<i class="fa fa-times"></i> Vaciar carrito', 'class="btn btn-polo pull-right" title="Cancelar el pedido"') ?>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php foreach ($detalle->result() as $row_detalle) : ?>
                                    <?php
                                        $precio_especial = FALSE;
                                        if ( $row_detalle->precio_nominal > $row_detalle->precio ) { $precio_especial = TRUE; }
                                        $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                                        $ahorro = $row_detalle->cantidad * ($row_detalle->precio_nominal - $row_detalle->precio);
                                        $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                                        $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                                        $pct_descuento = 100 - $this->Pcrn->int_percent($row_detalle->precio, $row_detalle->precio_nominal);

                                        $att_img = $this->Producto_model->att_img($row_detalle->producto_id, 500);
                                        $att_img['width'] = 75;
                                        $att_imt['alt'] = $row_detalle->nombre_producto;
                                    ?>
                                    <tr class="last even">
                                        <td class="image">
                                            <?php echo anchor("productos/detalle/{$row_detalle->producto_id}", img($att_img), 'class="product-image" title="' . $row_detalle->nombre_producto . '"') ?>
                                        </td>
                                        <td>
                                            <h2 class="product-name">
                                                <b>
                                                    <?php echo anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>                                                
                                                </b>
                                            </h2>
                                            <p>
                                                <span class="money" style="font-size: 125%;">
                                                    <?php echo $this->Pcrn->moneda($row_detalle->precio) ?>
                                                </span> 

                                                <?php if ( $precio_especial ) { ?>
                                                    <span class="correcto">
                                                        <i class="fa fa-caret-right"></i>
                                                        <?php echo $arr_tipos_precio[$row_detalle->promocion_id] ?>
                                                    </span>
                                                <?php } ?>
                                            </p>
                                            <?php if ( $precio_especial ) { ?>
                                                <p>
                                                    <span class="suave">
                                                        Precio normal 
                                                    </span>
                                                    <span class="suave">
                                                        <?php echo $this->Pcrn->moneda($row_detalle->precio_nominal) ?>
                                                    </span>

                                                    <span class="label label-success">
                                                         -<?php echo $pct_descuento ?>%
                                                    </span>
                                                </p>
                                            <?php } ?>
                                        </td>

                                        <td class="a-center movewishlist">
                                            <?php if ( $row_detalle->peso > 0 ) { ?>    
                                                <!-- Se puede modificar solo si tiene peso y debe enviarse físicamente -->
                                                <input maxlength="12" type="number" min="1" max="100" class="input-polo cant_producto" title="Cantidad" size="4" value="<?php echo $row_detalle->cantidad ?>" data-producto_id="<?php echo $row_detalle->producto_id ?>">
                                            <?php } else { ?>
                                                1
                                            <?php } ?>
                                        </td>
                                        <td class="a-right movewishlist">
                                            <span class="cart-price">
                                                <span class="money">
                                                    <?php echo $this->Pcrn->moneda($precio_detalle) ?>
                                                </span>
                                            </span>
                                            <?php if ( $precio_especial ) { ?>
                                                <br/>
                                                <span class="suave">Ahorro -<?php echo $this->Pcrn->moneda($ahorro) ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="a-center last">
                                            <?php echo anchor("pedidos/eliminar_detalle/{$row_detalle->id}", '<span><span>Remove item</span></span>', 'class="button remove-item" title="Quitar producto del carrito"') ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </fieldset>
                <!--</form>-->
            </div>
            
            <table class="table table-default bg-blanco hidden">
                <thead>
                    <th class="<?php echo $clases_col['promocion'] ?>">Descuentos</th>
                    <th class="<?php echo $clases_col['sum_descuento'] ?>">Valor del descuento</th>
                </thead>

                <tbody>
                    <?php foreach ($descuentos->result() as $row_descuento) : ?>
                        <tr>
                            <td class="<?php echo $clases_col['nombre_elemento'] ?>">
                                <?php echo $arr_tipos_precio[$row_descuento->promocion_id] ?>
                            </td>
                            <td class="<?php echo $clases_col['nombre_elemento'] ?>">
                                <?php echo $this->Pcrn->moneda($row_descuento->sum_descuento) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col col-md-4">
        
        <?php $this->load->view('pedidos/compra/totales_v'); ?>
        
        <a type="button" title="Continuar con la compra" class="btn-polo-lg btn-block text-center" href="<?php echo base_url("pedidos/usuario") ?>">
            <i class="fa fa-check"></i>
            Continuar
        </a>
        
        
        
    </div>
</div>