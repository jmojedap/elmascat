<?php if ( ! is_null($products) ) : ?>
<div id="carrito_app">
    <div class="row">
        <div class="col-md-8">
            <!-- TABLA DE PRODUCTOS PARA PANTALLAS GRANDES -->
            <?php $this->load->view('pedidos/compra/carrito/lg_productos_v') ?>

            <!-- TABLA DE PRODUCTOS PARA MOBILE -->
            <?php $this->load->view('pedidos/compra/carrito/sm_productos_v') ?>

        </div>
        <div class="col-md-4">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total productos</td>
                        <td class="text-right">{{ order.total_productos | currency }}</td>
                    </tr>
                    <tr v-if="order.total_extras == 0">
                        <td>Gastos de envío</td>
                        <td class="text-right">
                            <span class="text-info">Destino pendiente</span>
                        </td>
                    </tr>
                    <tr v-for="(extra, ek) in extras">
                        <td>
                            {{ extra.producto_id | name_extra_pedido }}
                            <span v-if="extra.producto_id == 1">({{ order.peso_total }} kg)</span>
                        </td>
                        <td class="text-right">
                            {{ extra.precio | currency }}
                        </td>
                    </tr>
                    <tr v-show="order.total_extras > 0">
                        <td>Total a pagar</td>
                        <td class="text-right">
                            <strong>
                                <span class="money money_total">
                                    {{ order.valor_total | currency }}
                                </span>
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <a class="btn-polo-lg btn-block text-center" href="<?= URL_APP . "pedidos/usuario" ?>" v-bind:disabled="loading">
                <span v-show="!loading">Continuar</span>
                <span v-show="loading"><i class="fa fa-spin fa-spinner"></i> Cargando</span>
            </a>
            <a class="btn btn-primary btn-block" href="<?= URL_APP . "productos/catalogo" ?>">
                <i class="fa fa-arrow-left"></i> Más productos
            </a>
            <br>
            <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#cancel_modal">
                <i class="fa fa-trash"></i> &nbsp; Vaciar carrito
            </button>
        </div>
    </div>
    <?php $this->load->view('pedidos/compra/carrito/modal_single_delete_v') ?>
    <?php $this->load->view('pedidos/compra/carrito/modal_cancel_v') ?>
</div>

<?php $this->load->view('pedidos/compra/carrito/vue_v') ?>

<?php else: ?>
<div class="jumbotron">
    <h1 class="display-4">Tu carrito está vacío</h1>
    <p class="lead">
        Aún no tienes productos en tu carrito.
        </p>
    <a class="btn btn-primary btn-lg" href="<?= URL_APP . 'tienda/productos' ?>" role="button">Ver productos</a>
</div>
<?php endif; ?>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>