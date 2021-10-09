<div id="compra_b_app">
    <div class="row">
        <div class="col col-md-4">
            <div class="cart ">
                <div class="page-title">
                    <h2>Productos</h2>
                </div>
                <div class="table-responsive">
                    <!--<form method="post">-->
                        <input type="hidden" value="Vwww7itR3zQFe86m" name="form_key">
                        <fieldset>
                            <table class="data-table cart-table" id="shopping-cart-table">
                                <thead>
                                    <tr class="first last">
                                        <th rowspan="1">
                                            <span class="nobr">Productos y precios</span>
                                        </th>
                                        
                                    </tr>
                                </thead>
                                
                                <tfoot>
                                    <tr class="first last">
                                        <td class="a-right last">
                                            <?= anchor("pedidos/carrito/", '<i class="fa fa-shopping-cart"></i><span><span> Editar</span></span>', 'class="btn btn-polo w3" title="Editar productos"') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                                
                                <tbody>
                                    <?php foreach ($products->result() as $row_detalle) : ?>
                                        <?php
                                            $precio_especial = FALSE;
                                            if ( $row_detalle->precio_nominal > $row_detalle->precio ) { $precio_especial = TRUE; }
                                            $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                                            $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                                            $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                                            $pct_descuento = 100 - $this->Pcrn->int_percent($row_detalle->precio, $row_detalle->precio_nominal);

                                            
                                        ?>
                                        <tr class="last even">
                                            <td>
                                                <h2 class="product-name">
                                                    <b>
                                                        <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                                                    </b>
                                                </h2>
                                                <p>
                                                    <b>
                                                        <?= $row_detalle->cantidad ?>
                                                    </b>
                                                    <span class="suave">
                                                        x
                                                    </span>
                                                    <span class="suave"><?= $this->Pcrn->moneda($row_detalle->precio) ?></span>
                                                    
                                                    <span class="suave">
                                                        <i class="fa fa-caret-right"></i>
                                                    </span>
                                                    <span class="cart-price"><span class="money">
                                                        <?= $this->Pcrn->moneda($precio_detalle) ?>
                                                    </span></span>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            
                            <br/>
                            
                        </fieldset>
                    <!--</form>-->
                </div>
                
                

            </div>
        </div>
        
        <div class="col col-md-4">
            <div class="page-title">
                <h2>Datos de entrega</h2>
            </div>
            
            <table class="data-table cart-table mb-2" id="shopping-cart-table">
                <thead>
                    <tr class="first last">
                        <th colspan="2">
                            <span class="nobr">
                                Contacto y Dirección
                            </span>
                        </th>
                    </tr>
                </thead>
                
                <tfoot>
                    <tr class="first last">
                        <td class="a-right last" colspan="2">
                            <?= anchor("pedidos/compra_a/{$row->cod_pedido}", '<i class="fa fa-edit"></i><span><span> Editar</span></span>', 'class="btn btn-polo w3" title="Editar datos de compra"') ?>
                        </td>
                    </tr>
                </tfoot>

                <tbody>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-user fa-2x text-primary"></i>
                        </td>
                        <td>
                            <span class="resaltar">
                                <?= $row->nombre ?> <?= $row->apellidos ?><br/>
                            </span>
                            <span class="text-muted"><?= $this->Item_model->nombre(53, $row->tipo_documento_id) ?></span>
                            <?= $row->no_documento ?><br/>
                            <?= $row->email ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-map-marker fa-2x text-primary"></i>
                        </td>
                        <td>
                            <?= $row->direccion ?><br>
                            <span class="suave">
                                Celular
                            </span>
                            <?= $row->celular ?><br/>
                            <?= $row->ciudad ?>
                        </td>
                    </tr>
                    <?php if ( strlen($row->notas) > 0 ) : ?>
                        <tr>
                            <td width="25px">
                                <i class="fa fa-info-circle fa-2x text-primary"></i>
                            </td>
                            <td>
                                <?= $row->notas ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ( $row->is_gift ) : ?>
                        <tr>
                            <td width="25px">
                                <i class="fa fa-gift fa-2x text-primary"></i>
                            </td>
                            <td>
                                <span class="resaltar">Datos para regalo</span>
                                
                                <p>
                                    <strong class="text-primary">De:</strong>
                                    <?= $arr_meta['regalo']['de'] ?>
                                    &middot;
                                    <strong class="text-primary">Para:</strong>
                                    <?= $arr_meta['regalo']['para'] ?>
                                    &middot;
                                    <strong class="text-primary">Mensaje:</strong>
                                    <?= $arr_meta['regalo']['mensaje'] ?>
                                </p>
                                <p>
                                    <a href="<?= base_url("pedidos/datos_regalo") ?>" class="btn btn-xs btn-primary">
                                        Editar
                                    </a>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="col col-md-4">
            <div class="page-title">
                <h2>Valores</h2>
            </div>
            
            <?php $this->load->view('pedidos/compra/totales_v'); ?>


            <ul class="checkout">
                <form accept-charset="utf-8" method="POST" action="<?= $destino_form ?>">

                    <?php foreach ($form_data as $key => $valor) : ?>
                        <?= form_hidden($key, $valor) ?>
                    <?php endforeach ?>

                    <?php if ( $validacion['status'] == 1 ) : ?>
                        <button class="btn-polo-lg" type="submit">IR A PAGAR</button>
                    <?php else : ?>
                        <?php if ( $validacion['existencias']['status'] == 0 ) : ?>
                            <div class="alert alert-warning">
                                <?= $validacion['existencias']['error'] ?>
                                <br>
                                <a href="<?= base_url("pedidos/carrito") ?>">Volver al carrito</a>
                            </div>
                        <?php endif; ?>

                        <?php if ( $validacion['datos_completos']['status'] == 0 ) : ?>
                            <div class="alert alert-warning">
                                <?= $validacion['datos_completos']['error'] ?>
                                <br>
                                <a href="<?= base_url("pedidos/compra_a") ?>">Completar</a>
                            </div>
                        <?php endif; ?>

                        <?php if ( $validacion['flete']['status'] == 0 ) : ?>
                            <div class="alert alert-warning">
                                <?= $validacion['flete']['error'] ?>
                                <br>
                                <a href="<?= base_url("pedidos/compra_a") ?>">Volver</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </form>
            </ul>

            <div class="alert alert-info text-center">
                <p>
                    <i class="fa fa-info-circle"></i> <strong>Recuerda</strong>
                </p>
                <p>
                    Para pagos en Efectivo con Códigos de pago: 
                    Algunos productos de tu pedido podrían dejar de estar disponibles si pasa mucho tiempo entre la creación de tu pedido y el momento del pago.
                </p>
            </div>
            
            <?php if ( $row->pais_id != 51 ) { ?>
                <a class="btn btn-info btn-block" href="<?= base_url("pedidos/compra_b_usd/{$row->cod_pedido}") ?>">
                    Pagar en Dólares (USD)
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var arr_extras_pedidos = <?= json_encode($arr_extras_pedidos) ?>;

// Filters
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return ''
    value = '$' + new Intl.NumberFormat().format(value)
    return value
})

Vue.filter('name_extra_pedido', function (value) {
    if (!value) return ''
    value = arr_extras_pedidos[value]
    return value
})

// VueApp
//-----------------------------------------------------------------------------
var compra_b_app = new Vue({
    el: '#compra_b_app',
    data: {
        order: <?= json_encode($row) ?>,
        extras: <?= json_encode($extras->result()) ?>,
        loading: false,
    },
    methods: {}
});
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>