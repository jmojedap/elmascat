<style>
    h3.separador {
        border: 1px solid #AADBF2;
        background-color:#41ADE2;
        color: white;
        padding: 0.3em;
        text-align: center;
        font-size: 1em;
        text-transform: uppercase;
        margin: 2em 0px;
    }
</style>

<?php if ( ! is_null($products) ) : ?>
<div id="cotizadorApp">
    
    <div class="row">
        <div class="col-md-8">
            <!-- SELECTOR DE CIUDAD DE ENTREGA -->
            <h3 class="separador">Ciudad</h3>
            <div class="row">
                <div class="col-md-6">
                    <select name="region_id" v-model="region_id" class="form-control input-lg" required
                        v-on:change="get_cities">
                        <option v-for="(option_region, region_key) in options_region" v-bind:value="region_key">
                            {{ option_region }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="ciudad_id" v-model="ciudad_id" class="form-control input-lg" required
                        v-on:change="guardar_lugar">
                        <option v-for="(option_ciudad, ciudad_key) in options_ciudad" v-bind:value="ciudad_key">
                            {{ option_ciudad }}</option>
                    </select>
                </div>
            </div>

            <!-- TABLA DE PRODUCTOS PARA PANTALLAS GRANDES -->
            <h3 class="separador">Productos</h3>
            <?php $this->load->view('pedidos/cotizador/lg_productos_v') ?>

            <!-- TABLA DE PRODUCTOS PARA MOBILE -->
            <?php $this->load->view('pedidos/cotizador/sm_productos_v') ?>

            <!-- ASIGNACIÓN DE COMPRADOR -->
            <h3 class="separador">ASIGNAR COMPRADOR</h3>
            <form accept-charset="utf-8" method="POST" id="usersForm" @submit.prevent="getUsers">
                <fieldset v-bind:disabled="loading">
                    <input
                        name="q" type="text" class="form-control"
                        required
                        title="Buscar usuarios" placeholder="Buscar usuarios por nombre, email o documento"
                        v-model="fields.q"
                    >
                <fieldset>
            </form>
            <table class="table">
                <tbody>
                    <tr v-for="(user, user_key) in users">
                        <td>
                            <b>{{ user.display_name }}</b>
                            <br>
                            <span class="text-muted">{{ user.email }} &middot; {{ user.id_number }}</span>
                        </td>
                        <td width="100px">
                            <button class="btn btn-primary btn-sm" v-on:click="setUser(user.id)" type="button">
                                Asignar <i class="fa fa-arrow-right"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            

        </div>
        <div class="col-md-4">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Cotización para</td>
                        <td class="text-right">
                            <a v-bind:href="`<?= URL_APP . "usuarios/profile/" ?>` + order.usuario_id">
                                <b>{{ order.nombre }} {{ order.apellidos }}</b>
                            </a>
                            <br>
                            {{ order.email }}
                        </td>
                    </tr>
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
            <button class="btn btn-secondary w100p" data-toggle="modal" data-target="#cancel_modal">
                <i class="fa fa-trash"></i> &nbsp; Vaciar
            </button>
            <a class="btn btn-light w100p" v-bind:href="`<?= URL_APP ?>pedidos/info/` + order.id" title="Ver información en pedidos">Pedido</a>
            <a class="btn btn-light w100p" v-bind:href="`<?= URL_APP ?>pedidos/link_pago/` + order.cod_pedido" title="">Pago</a>
        </div>
    </div>
    <?php $this->load->view('pedidos/cotizador/modal_single_delete_v') ?>
    <?php $this->load->view('pedidos/cotizador/modal_cancel_v') ?>
</div>

<?php $this->load->view('pedidos/cotizador/vue_v') ?>

<?php else: ?>
<div class="jumbotron">
    <h1 class="display-4">Tu carrito está vacío</h1>
    <p class="lead">
        Aún no tienes productos en tu carrito.
        </p>
    <a class="btn btn-primary btn-lg" href="<?= URL_APP . 'tienda/productos' ?>" role="button">Ver productos</a>
</div>
<?php endif; ?>