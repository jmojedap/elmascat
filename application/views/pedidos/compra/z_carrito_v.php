<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var pedido_id = <?= $pedido_id ?>;
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
            success: function(response){
                console.log(response);
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
                                        <?= anchor("productos/catalogo", '<i class="fa fa-arrow-left"></i><span><span> Catálogo</span></span>', 'class="btn btn-polo" title="Volver al catálogo"') ?>
                                        <?= anchor("pedidos/abandonar", '<i class="fa fa-times"></i> Vaciar carrito', 'class="btn btn-polo pull-right" title="Cancelar el pedido"') ?>
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
                                    ?>
                                    <tr class="last even">
                                        <td class="image">
                                            <a href="<?= URL_APP . "productos/detalle/{$row_detalle->producto_id}" ?>">
                                                <img src="<?= $row_detalle->url_thumbnail ?>" alt="Imagen producto en carrito" class="product-image" width="75">
                                            </a>
                                        </td>
                                        <td>
                                            <h2 class="product-name">
                                                <b>
                                                    <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>                                                
                                                </b>
                                            </h2>
                                            <p>
                                                <span class="money" style="font-size: 125%;">
                                                    <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                                                </span> 

                                                <?php if ( $precio_especial ) { ?>
                                                    <span class="correcto">
                                                        <i class="fa fa-caret-right"></i>
                                                        <?= $arr_tipos_precio[$row_detalle->promocion_id] ?>
                                                    </span>
                                                <?php } ?>
                                            </p>
                                            <?php if ( $precio_especial ) { ?>
                                                <p>
                                                    <span class="suave">
                                                        Precio normal 
                                                    </span>
                                                    <span class="suave">
                                                        <?= $this->Pcrn->moneda($row_detalle->precio_nominal) ?>
                                                    </span>

                                                    <span class="label label-success">
                                                         -<?= $pct_descuento ?>%
                                                    </span>
                                                </p>
                                            <?php } ?>
                                        </td>

                                        <td class="a-center movewishlist">
                                            <?php if ( $row_detalle->peso > 0 ) { ?>    
                                                <!-- Se puede modificar solo si tiene peso y debe enviarse físicamente -->
                                                <input maxlength="12" type="number" min="1" max="100" class="input-polo cant_producto" title="Cantidad" size="4" value="<?= $row_detalle->cantidad ?>" data-producto_id="<?= $row_detalle->producto_id ?>">
                                            <?php } else { ?>
                                                1
                                            <?php } ?>
                                        </td>
                                        <td class="a-right movewishlist">
                                            <span class="cart-price">
                                                <span class="money">
                                                    <?= $this->Pcrn->moneda($precio_detalle) ?>
                                                </span>
                                            </span>
                                            <?php if ( $precio_especial ) { ?>
                                                <br/>
                                                <span class="suave">Ahorro -<?= $this->Pcrn->moneda($ahorro) ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="a-center last">
                                            <a href="<?= base_url("pedidos/eliminar_detalle/{$row_detalle->id}") ?>" class="button remove-item" title="Quitar el producto del carrito">
                                                <i class="fa fa-trash-alt"></i>
                                            </a>
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
                    <th class="<?= $clases_col['promocion'] ?>">Descuentos</th>
                    <th class="<?= $clases_col['sum_descuento'] ?>">Valor del descuento</th>
                </thead>

                <tbody>
                    <?php foreach ($descuentos->result() as $row_descuento) : ?>
                        <tr>
                            <td class="<?= $clases_col['nombre_elemento'] ?>">
                                <?= $arr_tipos_precio[$row_descuento->promocion_id] ?>
                            </td>
                            <td class="<?= $clases_col['nombre_elemento'] ?>">
                                <?= $this->Pcrn->moneda($row_descuento->sum_descuento) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col col-md-4">
        
        <?php $this->load->view('pedidos/compra/totales_v'); ?>
        
        <a type="button" title="Continuar con la compra" class="btn-polo-lg btn-block text-center" href="<?= base_url("pedidos/usuario") ?>">
            <i class="fa fa-check"></i>
            Continuar
        </a>
        
        
        
    </div>
</div>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>