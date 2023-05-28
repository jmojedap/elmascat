<script>
// Variables
//-----------------------------------------------------------------------------
var arr_extras_pedidos = <?= json_encode($arr_extras_pedidos) ?>;

// Filters
//-----------------------------------------------------------------------------
Vue.filter('currency', function(value) {
    if (!value) return ''
    value = '$' + new Intl.NumberFormat().format(value)
    return value
})

Vue.filter('name_extra_pedido', function(value) {
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
        store_pickup: false,
        year: '01985',
        month: '006',
        day: '015',
        screen_width: screen.width,
        screen_height: screen.height,
        options_gift: {
            0: 'No',
            1: 'Sí'
        },
        options_region: <?= json_encode($options_region) ?>,
        options_ciudad: <?= json_encode($options_ciudad) ?>,
        loading: false,
    },
    methods: {
        guardar_lugar: function() {
            this.loading = true
            var params = new FormData();
            params.append('ciudad_id', this.ciudad_id);


            axios.post(url_app + 'pedidos/guardar_lugar/', params)
                .then(response => {
                    if (response.data.status == 1) {
                        this.controlShippingMethodId()
                    }
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        get_order_info: function() {
            axios.get(url_api + 'pedidos/get_info/' + this.order.cod_pedido)
                .then(response => {
                    this.order = response.data.order
                    this.extras = response.data.extras
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        send_form: function() {
            this.loading = true
            var form_data = new FormData(document.getElementById('compra_a_form'))

            axios.post(url_app + 'pedidos/guardar_pedido/', form_data)
                .then(response => {
                    console.log(response.data)
                    var destination = url_app + 'pedidos/verificar'
                    if (this.form_values.is_gift == 1) destination = url_app + 'pedidos/datos_regalo'
                    if (response.data.status == 1) window.location = destination
                })
                .catch(function(error) { console.log(error) })
        },
        get_cities: function() {
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
                .catch(function(error) {
                    console.log(error)
                })
        },
        controlShippingMethodId: function(){
            if ( this.ciudad_id != '0909' ) {
                console.log('Cambiando método de entrega')
                this.form_values.shipping_method_id = 0
            }
            this.setShippingMethodId()
        },
        setShippingMethodId: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('shipping_method_id', this.form_values.shipping_method_id)
            axios.post(url_app + 'pedidos/guardar_pedido/1', formValues)
            .then(response => {
                this.get_order_info()
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
});
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment">
</div>