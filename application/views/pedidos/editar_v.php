<?php
    $show_missing_data = false;
    if ( strlen($row->no_documento) == 0 ) $show_missing_data = true;
    if ( strlen($row->direccion) <= 3 ) $show_missing_data = true;
    if ( strlen($row->celular) < 10 ) $show_missing_data = true;
?>

<div id="edit_app">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="order_form" @submit.prevent="send_form">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <?php if ( $row->usuario_id > 0 ) : ?>
                    <?php if ( strlen($row->no_documento) == 0 ) : ?>
                        <div class="form-group row">
                            <label for="no_documento" class="col-md-4 col-form-label text-right">No. documento *</label>
                            <div class="col-md-8">
                                <input
                                    name="no_documento" type="text" class="form-control" required title="No. documento"
                                    v-model="form_values.no_documento"
                                >
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( strlen($row->direccion) <= 3 ) : ?>
                        <div class="form-group row">
                            <label for="direccion" class="col-md-4 col-form-label text-right">Dirección entrega *</label>
                            <div class="col-md-8">
                                <input
                                    name="direccion" type="text" class="form-control" required
                                    title="Dirección de entrega" v-model="form_values.direccion"
                                >
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( strlen($row->celular) < 10 ) : ?>
                        <div class="form-group row">
                            <label for="celular" class="col-md-4 col-form-label text-right">No. celular *</label>
                            <div class="col-md-8">
                                <input
                                    name="celular" type="text" class="form-control" required
                                    title="Número de celular" v-model="form_values.celular"
                                >
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $show_missing_data ) : ?>
                        <hr>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="form-group row">
                    <label for="estado_pedido" class="col-md-4 col-form-label text-right">Estado</label>
                    <div class="col-md-8">
                        <select name="estado_pedido" v-model="form_values.estado_pedido" class="form-control" required>
                            <option v-for="(option_estado_pedido, key_estado_pedido) in options_estado_pedido" v-bind:value="key_estado_pedido">{{ option_estado_pedido }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="factura" class="col-md-4 col-form-label text-right">Número factura</label>
                    <div class="col-md-8">
                        <input name="factura" type="text" class="form-control" title="Número factura" value="<?= $row->factura ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="shipping_method_id" class="col-md-4 col-form-label text-right">Sistema de envío</label>
                    <div class="col-md-8">
                        <select name="shipping_method_id" v-model="form_values.shipping_method_id" class="form-control">
                            <option v-for="(option_shipping_method, key_shipping_method) in options_shipping_method" v-bind:value="key_shipping_method">{{ option_shipping_method }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="no_guia" class="col-md-4 col-form-label text-right">Número guía</label>
                    <div class="col-md-8">
                        <input name="no_guia" id="field-no_guia" type="text" class="form-control" v-model="order.no_guia">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="notas_admin" class="col-md-4 col-form-label text-right">Notas internas</label>
                    <div class="col-md-8">
                        <textarea
                            name="notas_admin" id="field-notas_admin" type="text" class="form-control"
                            title="Notas internas sobre el pedido" placeholder="Notas internas sobre el pedido" rows="4"
                            v-model="order.notas_admin"
                        ></textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var order = <?= json_encode($row) ?>;
order.payment_channel = '0<?= $row->payment_channel ?>';
order.estado_pedido = '0<?= $row->estado_pedido ?>';
order.shipping_method_id = '0<?= $row->shipping_method_id ?>';

// VueApp
//-----------------------------------------------------------------------------
var edit_app = new Vue({
    el: '#edit_app',
    data: {
        order_id: '<?= $row->id ?>',
        form_values: order,
        options_payment_channel: <?= json_encode($options_payment_channel) ?>,
        options_estado_pedido: <?= json_encode($options_estado_pedido) ?>,
        options_shipping_method: <?= json_encode($options_shipping_method) ?>,
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'pedidos/guardar_admin/', $('#order_form').serialize())
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success'](response.data.message)
                } else {
                    toastr['info'](response.data.message)
                }
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>