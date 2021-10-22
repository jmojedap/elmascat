<a href="<?= base_url("pedidos/carrito") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<div id="compra_a_app">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="region_id" class="col-md-4 control-label">Departamento</label>
                    <div class="col-md-8">
                        <select name="region_id" v-model="region_id" class="form-control input-lg" required v-on:change="get_cities">
                            <option v-for="(option_region, region_key) in options_region" v-bind:value="region_key">{{ option_region }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ciudad_id" class="col-md-4 control-label">Ciudad</label>
                    <div class="col-md-8">
                        <select name="ciudad_id" v-model="ciudad_id" class="form-control input-lg" required v-on:change="guardar_lugar">
                            <option v-for="(option_ciudad, ciudad_key) in options_ciudad" v-bind:value="ciudad_key">{{ option_ciudad }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <form method="post" accept-charset="utf-8" class="form-horizontal" id="compra_a_form" @submit.prevent="send_form" v-show="ciudad_id.length > 1">
        <input type="hidden" v-model="ciudad_id" name="ciudad_id">
        <input type="hidden" v-model="screen_width" name="screen_width">
        <input type="hidden" v-model="screen_height" name="screen_height">

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="col-sm-4 control-label">Nombres | Apellidos</label>
                    <div class="col-sm-4">
                        <input
                            type="text" name="nombre" class="form-control input-lg" required
                            placeholder="Nombres" title="Nombres"
                            v-model="form_values.nombre"
                            >
                    </div>
                    <div class="col-sm-4">
                        <input
                            type="text" name="apellidos" required class="form-control input-lg"
                            placeholder="Apellidos" title="Apellidos"
                            v-model="form_values.apellidos"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-4 control-label">Correo electrónico</label>
                    <div class="col-md-8">
                        <input type="email" name="email"
                            required
                            class="form-control input-lg"
                            v-model="form_values.email"
                            >
                        <span id="helpBlock" class="help-block">Si usa un correo de <b class="text-danger">hotmail.com</b>, los mensajes de confirmación podrían llegar a la carpeta de spam o correo no deseado.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_documento" class="col-md-4 control-label">No. Documento </label>
                    <div class="col-md-4">
                        <input type="number" name="no_documento"
                            required min="100"
                            class="form-control input-lg"
                            v-model="form_values.no_documento"
                            >
                    </div>
                    <div class="col-md-4">
                        <?= form_dropdown('tipo_documento_id', $options_tipo_documento, '', 'class="form-control input-lg" required v-model="`0` + form_values.tipo_documento_id"') ?>
                    </div>
                </div>
                

                <div class="form-group">
                    <label for="direccion" class="col-md-4 control-label">Dirección entrega</label>
                    <div class="col-md-8">
                        <input
                            type="text" name="direccion" class="form-control input-lg" required
                            title="Dirección de entrega del pedido"
                            v-model="form_values.direccion"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="celular" class="col-md-4 control-label">Celular</label>
                    <div class="col-md-8">
                        <input
                            type="text" name="celular" required class="form-control input-lg"
                            placeholder="Número celular" title="Número teléfono celular sin espacios, solo números"
                            pattern="[0-9]{5,}"
                            v-model="form_values.celular"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="celular" class="col-md-4 control-label">Empacar como regalo</label>
                    <div class="col-md-8">
                        <select name="is_gift" v-model="form_values.is_gift" class="form-control input-lg" v-bind:disabled="order.valor_total < 20000">
                            <option v-for="(option_gift, gift_key) in options_gift" v-bind:value="gift_key">{{ option_gift }}</option>
                        </select>
                        <span id="is_gift_help" class="help-block" v-show="order.valor_total < 20000">Aplica para compras mayores a $20.000 COP</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notas" class="col-md-4 control-label">Notas sobre el pedido</label>
                    <div class="col-md-8">
                        <textarea
                            rows="3" id="field-notas" name="notas"
                            class="form-control input-lg"
                            placeholder="Notas sobre su pedido e instrucciones de envío"
                            title="Notas sobre su pedido e instrucciones de envío"
                            v-model="form_values.notas"
                            ></textarea>
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
                <button class="btn btn-polo-lg btn-block" type="submit">
                    <i class="fa fa-check"></i> Continuar
                </button>
                <?php $this->load->view('pedidos/compra/totales_v'); ?>
            </div>
        </div>
    </form>

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
var compra_a_app = new Vue({
    el: '#compra_a_app',
    data: {
        order: <?= json_encode($row) ?>,
        cod_pedido: '<?= $row->cod_pedido ?>',
        region_id: '0<?= $row->region_id ?>',
        ciudad_id: '0<?= $row->ciudad_id ?>',
        form_values: <?= json_encode($row); ?>,
        extras: <?= json_encode($extras->result()) ?>,
        year: '01985',
        month: '006',
        day: '015',
        screen_width: screen.width,
        screen_height: screen.height,
        options_gift: { 0: 'No', 1: 'Sí'},
        options_region: <?= json_encode($options_region) ?>,
        options_ciudad: <?= json_encode($options_ciudad) ?>,
        loading: false,
    },
    methods: {
        guardar_lugar: function(){
            var params = new FormData();
            params.append('ciudad_id', this.ciudad_id);
            
            axios.post(url_app + 'pedidos/guardar_lugar/', params)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.get_order_info()
                }
            })
            .catch(function (error) { console.log(error) })
        },
        get_order_info: function(){
            axios.get(url_api + 'pedidos/get_info/' + this.order.cod_pedido)
            .then(response => {
                this.order = response.data.order
                this.extras = response.data.extras
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function(){
            axios.post(url_app + 'pedidos/guardar_pedido/', $('#compra_a_form').serialize())
            .then(response => {
                console.log(response.data)
                var destination = url_app + 'pedidos/compra_b'
                if ( this.form_values.is_gift == 1 ) destination = url_app + 'pedidos/datos_regalo'
                if ( response.data.status == 1) window.location = destination
            })
            .catch(function (error) { console.log(error) })
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
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>