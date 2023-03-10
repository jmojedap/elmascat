<div style="margin-bottom: 20px;" id="link_pago_app">
    <div class="row wow bounceInUp animated">
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
                                
                                <tbody>
                                    <?php foreach ($detalle->result() as $row_detalle) : ?>
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
            
            <table class="data-table cart-table" id="shopping-cart-table">
                <thead>
                    <tr class="first last">
                        <th colspan="2">
                            <span class="nobr">
                                Contacto y Direcci??n
                            </span>
                        </th>
                    </tr>
                </thead>
                
                <?php if ( $this->session->userdata('logged') && $this->session->userdata('role') <= 1 ) { ?>
                    <tfoot>
                        <tr class="first last">
                            <td class="a-right last" colspan="2">
                                <a href="<?= base_url("pedidos/editar/edit/{$row->id}") ?>" class="btn btn-polo w3" title="Editar pedido">
                                    <i class="fa fa-edit"></i><span><span> Editar</span></span>
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                <?php } ?>

                <tbody>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-user fa-2x"></i>
                        </td>
                        <td>
                            <span class="resaltar">
                                <?= $row->nombre ?> <?= $row->apellidos ?><br/>
                            </span>
                            CC <?= $row->no_documento ?><br/>
                            <?= $row->email ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-map-marker fa-2x"></i>
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
                    <tr>
                        <td width="25px">
                            <i class="fa fa-info fa-2x"></i>
                        </td>
                        <td>
                            <?= $row->notas ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="col col-md-4">
            <div class="page-title">
                <h2>Valores</h2>
            </div>
            <?php $this->load->view('pedidos/compra/totales_v'); ?>
            <ul class="checkout">
                <?= form_open($destino_form) ?>

                    <?php foreach ($form_data as $key => $valor) : ?>
                        <?= form_hidden($key, $valor) ?>
                    <?php endforeach ?>

                    <li>
                        <?php if ( count($missing_data) == 0 ) { ?>
                            <button class="btn-polo-lg" type="submit">
                                IR A PAGAR
                            </button>
                        <?php } else { ?>
                            <div class="alert alert-warning">
                                <p> 
                                    <i class="fa fa-info-circle fa-2x"></i>
                                </p>
                                <p>
                                    <?= count($missing_data) ?> datos faltantes: <?= implode(', ', $missing_data); ?>.
                                </p>
                            </div>
                        <?php } ?>
                    </li>
                <?= form_close('') ?>
            </ul>
            
            <?php if ( $row->pais_id != 51 ) { ?>
                <a class="btn btn-info btn-block" href="<?= base_url("pedidos/verificar_usd/{$row->cod_pedido}") ?>">
                    Pagar en D??lares (USD)
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
var link_pago_app = new Vue({
    el: '#link_pago_app',
    data: {
        order: <?= json_encode($row) ?>,
        extras: <?= json_encode($extras->result()) ?>,
        loading: false,
    },
    methods: {}
});
</script>