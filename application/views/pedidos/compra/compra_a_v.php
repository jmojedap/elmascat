<?php $this->load->view('assets/chosen_jquery'); ?>

<a href="<?php echo base_url("pedidos/carrito") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<div id="compra_a_app">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <?php if ( ! ($row->ciudad > 0) ) { ?>
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="ciudad_id" class="col-md-4 control-label">Ciudad de entrega</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('ciudad_id', $options_ciudad, $row->ciudad_id, 'id="ciudad_id" class="form-control chosen-select_no" v-model="ciudad_id" v-on:change="guardar_lugar"') ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <hr>

    <?php if ( $row->ciudad_id > 0 ) { ?>
        <form method="post" accept-charset="utf-8" class="form-horizontal" id="compra_a_form" @submit.prevent="send_form">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nombre" class="col-sm-4 control-label">Nombres | Apellidos</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                id="field-nombre"
                                name="nombre"
                                required
                                class="form-control"
                                placeholder="Nombres"
                                title="Nombres"
                                v-model="form_values.nombre"
                                >
                        </div>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                id="field-apellidos"
                                name="apellidos"
                                required
                                class="form-control"
                                placeholder="Apellidos"
                                title="Apellidos"
                                v-bind:value="form_values.apellidos"
                                >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_documento" class="col-md-4 control-label">No. Documento </label>
                        <div class="col-md-4">
                            <input
                                type="text"
                                id="field-no_documento"
                                name="no_documento"
                                required
                                minlength="5"
                                class="form-control"
                                placeholder="CC o NIT"
                                title="CC o NIT"
                                v-bind:value="form_values.no_documento"
                                >
                        </div>
                        <div class="col-md-4">
                            <?php echo form_dropdown('tipo_documento_id', $options_tipo_documento, '', 'class="form-control" required v-bind:value="`0` + form_values.tipo_documento_id"') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">Correo electrónico</label>
                        <div class="col-md-8">
                            <input
                                type="email"
                                id="field-email"
                                name="email"
                                required
                                class="form-control"
                                title="Correo electrónico"
                                v-bind:value="form_values.email"
                                >
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="direccion" class="col-md-4 control-label">Dirección entrega</label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                id="field-direccion"
                                name="direccion"
                                required
                                class="form-control"
                                title="Dirección de entrega del pedido"
                                v-bind:value="form_values.direccion"
                                >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="celular" class="col-md-4 control-label">Celular</label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                id="field-celular"
                                name="celular"
                                required
                                class="form-control"
                                placeholder="Número celular"
                                title="Número celular"
                                pattern="[0-9]{10}"
                                v-bind:value="form_values.celular"
                                >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notas" class="col-md-4 control-label">Notas sobre el pedido</label>
                        <div class="col-md-8">
                            <textarea
                                rows="3"
                                id="field-notas"
                                name="notas"
                                class="form-control"
                                placeholder="Notas sobre su pedido e instrucciones de envío"
                                title="Notas sobre su pedido e instrucciones de envío"
                                v-bind:value="form_values.notas"
                                ></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php $this->load->view('pedidos/compra/totales_v'); ?>
                    <button class="btn btn-polo-lg btn-block" type="submit">
                        <i class="fa fa-check"></i>
                        Continuar
                    </button>
                </div>
            </div>
        </form>
    <?php } ?>

</div>

<script>
    new Vue({
        el: '#compra_a_app',
        created: function(){
            //this.get_list();
        },
        data: {
            cod_pedido: '<?php echo $cod_pedido ?>',
            ciudad_id: '0<?php echo $row->ciudad_id ?>',
            form_values: <?php echo json_encode($row); ?>
        },
        methods: {
            guardar_lugar: function(){
                var params = new FormData();
                params.append('ciudad_id', this.ciudad_id);
                
                axios.post(app_url + 'pedidos/guardar_lugar/', params)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = app_url + 'pedidos/compra_a/' + this.cod_pedido;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function(){
                axios.post(app_url + 'pedidos/guardar_pedido/', $('#compra_a_form').serialize())
                .then(response => {
                    if ( response.data.qty_affected >= 0) {
                        window.location = app_url + 'pedidos/compra_b'
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>