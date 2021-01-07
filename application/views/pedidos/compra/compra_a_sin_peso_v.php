<?php $this->load->view('assets/chosen_jquery'); ?>

<a href="<?= base_url("pedidos/carrito") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<style>
    label{
        color: #37a4d9;
    }
</style>

<div id="compra_a_app">
    <form method="post" accept-charset="utf-8" class="form-horizontal" id="compra_a_form" @submit.prevent="send_form">
        <div class="row">
            <div class="col-md-8">
                <?php if ( ! ($row->ciudad > 0) ) { ?>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="ciudad_id" class="col-md-4 control-label">País residencia</label>
                            <div class="col-md-8">
                                <?= form_dropdown('pais_id', $options_pais, $row->pais_id, 'id="field-pais_id" class="form-control input-lg chosen-select_no" v-model="pais_id" v-on:change="cambiar_pais"') ?>
                            </div>
                        </div>
                        <div class="form-group" v-show="pais_id == '051'">
                            <label for="region_id" class="col-md-4 control-label">Departamento</label>
                            <div class="col-md-8">
                                <select name="region_id" v-model="region_id" class="form-control input-lg" required v-on:change="get_cities">
                                    <option v-for="(option_region, region_key) in options_region" v-bind:value="region_key">{{ option_region }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" v-show="pais_id == '051'">
                            <label for="ciudad_id" class="col-md-4 control-label">Ciudad</label>
                            <div class="col-md-8">
                                <select name="ciudad_id" v-model="ciudad_id" class="form-control input-lg" required v-on:change="guardar_lugar">
                                    <option v-for="(option_ciudad, ciudad_key) in options_ciudad" v-bind:value="ciudad_key">{{ option_ciudad }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" v-show="pais_id != '051'">
                            <label for="ciudad" class="col-md-4 control-label">Ciudad residencia</label>
                            <div class="col-md-8">
                                <input
                                    type="text"
                                    id="field-ciudad"
                                    name="ciudad"
                                    required
                                    class="form-control input-lg"
                                    placeholder="Ciudad de residencia"
                                    title="Ciudad de residencia"
                                    v-model="ciudad"
                                    >
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <hr>

                <div class="form-group">
                    <label for="nombre" class="col-sm-4 control-label">Nombres &middot; Apellidos</label>
                    <div class="col-sm-4">
                        <input
                            type="text"
                            id="field-nombre"
                            name="nombre"
                            required
                            class="form-control input-lg"
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
                            class="form-control input-lg"
                            placeholder="Apellidos"
                            title="Apellidos"
                            v-model="form_values.apellidos"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_documento" class="col-md-4 control-label">Número de documento</label>
                    <div class="col-md-4">
                        <input
                            type="text"
                            id="field-no_documento"
                            name="no_documento"
                            required
                            minlength="5"
                            class="form-control input-lg"
                            placeholder="CC o NIT"
                            title="CC o NIT"
                            v-model="form_values.no_documento"
                            >
                    </div>
                    <div class="col-md-4">
                        <?= form_dropdown('tipo_documento_id', $options_tipo_documento, '', 'class="form-control input-lg" required v-bind:value="`0` + form_values.tipo_documento_id"') ?>
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
                            class="form-control input-lg"
                            title="Correo electrónico"
                            v-model="form_values.email"
                            >
                            <span id="helpBlock" class="help-block hidden">Si usa un correo de <b class="text-danger">hotmail.com</b>, el mensaje para la activación de su cuenta podría llegar a la carpeta de spam o correo no deseado.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion" class="col-md-4 control-label">Dirección residencia</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-direccion"
                            name="direccion"
                            required
                            class="form-control input-lg"
                            title="Dirección de entrega del pedido"
                            v-model="form_values.direccion"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="celular" class="col-md-4 control-label">Número celular</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-celular"
                            name="celular"
                            required
                            class="form-control input-lg"
                            placeholder="Número celular"
                            title="Número teléfono celular sin espacios, solo números"
                            pattern="[0-9]{5,}"
                            v-model="form_values.celular"
                            >
                    </div>
                </div>

                <hr>

                <?php if ( is_null($row_usuario->fecha_nacimiento) )  { ?>
                    <div class="form-group row">
                        <label for="fecha_nacimiento" class="col-xs-12 col-md-4 control-label">Fecha de nacimiento</label>
                        <div class="col-xs-4 col-md-2">
                            <?= form_dropdown('day', $options_day, '', 'class="form-control input-lg" required v-model="day"') ?>
                        </div>
                        <div class="col-xs-4 col-md-4">
                            <?= form_dropdown('month', $options_month, '', 'class="form-control input-lg" required v-model="month"') ?>
                        </div>
                        <div class="col-xs-4 col-md-2">
                            <?= form_dropdown('year', $options_year, '', 'class="form-control input-lg" required v-model="year"') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( is_null($row_usuario->sexo) )  { ?>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <input type="radio" name="sexo" value="1" required> Mujer
                            <input type="radio" name="sexo" value="2"> Hombre
                        </div>
                    </div>
                <?php } ?>
                
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
</div>

<script>
    new Vue({
        el: '#compra_a_app',
        created: function(){
            //this.get_list();
        },
        data: {
            cod_pedido: '<?= $cod_pedido ?>',
            pais_id: '0<?= $row->pais_id ?>',
            region_id: '0<?= $row->region_id ?>',
            ciudad_id: '0<?= $row->ciudad_id ?>',
            form_values: <?= json_encode($row); ?>,
            ciudad: '<?= $row->ciudad ?>',
            year: '01985',
            month: '006',
            day: '015',
            options_region: <?= json_encode($options_region) ?>,
            options_ciudad: <?= json_encode($options_ciudad) ?>
        },
        methods: {
            cambiar_ciudad: function(){
                var e = document.getElementById("field-ciudad_id");
                this.ciudad = e.options[e.selectedIndex].text;
                console.log(this.ciudad_id);
                console.log('cambiando');
            },
            cambiar_pais: function(){
                if ( this.pais_id != '051' )    //No es Colombia
                {
                    console.log('No es Colombia');
                    this.ciudad = '';
                    this.ciudad_id = '01'
                } else {
                    console.log('Es Colombia');
                    this.ciudad = '';
                    this.ciudad_id = ''
                }
            },
            send_form: function(){
                axios.post(url_app + 'pedidos/guardar_pedido/', $('#compra_a_form').serialize())
                .then(response => {
                    if ( response.data.qty_affected >= 0) {
                        window.location = url_app + 'pedidos/compra_b'
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardar_lugar: function(){
                var params = new FormData();
                params.append('ciudad_id', this.ciudad_id);
                
                axios.post(url_app + 'pedidos/guardar_lugar/', params)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = url_app + 'pedidos/compra_a/' + this.cod_pedido;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            get_cities: function(){
                form_data = new FormData
                form_data.append('value_field', 'nombre_lugar')
                form_data.append('empty_text', 'Seleccione la ciudad')
                form_data.append('type', '4')
                form_data.append('region_id', this.region_id)
                axios.post(url_api + 'app/get_places/', form_data)
                .then(response => {
                    this.ciudad_id = ''
                    this.options_ciudad = response.data.list
                    //this.guardar_ciudad()
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
        }
    });
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>