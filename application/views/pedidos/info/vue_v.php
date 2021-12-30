<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

Vue.filter('date_format', function (date) {
    if (!date) return ''
    return moment(date).format('dddd, D [de] MMM')
});

Vue.filter('currency', function (value) {
    if (!value) return '';
    value = '' + new Intl.NumberFormat().format(value);
    return value;
});

// VueApp
//-----------------------------------------------------------------------------
var pedido_info = new Vue({
    el: '#pedido_info',
    data: {
        order: <?= json_encode($row) ?>,
        form_values: {
            payment_channel: '0<?= $row->payment_channel ?>',
        },
        loading: false,
        options_payment_channels: <?= json_encode($options_payment_channels) ?>,
        missing_data: <?= json_encode($missing_data) ?>
    },
    methods: {
        reiniciar_pedido: function(){
            $('#btn_reiniciar_pedido').removeClass('btn-light');
            $('#btn_reiniciar_pedido').addClass('btn-warning');
            $('#btn_reiniciar_pedido').html('<i class="fa fa-info-circle"></i> Reiniciando...');

            var order_id = this.order.id
            axios.get(url_api + 'pedidos/reiniciar/' + this.order.cod_pedido)
            .then(response => {
                if (response.data.cod_pedido.length > 0) {
                    toastr['success']('Pedido reiniciado, cargando...');
                    $('#btn_reiniciar_pedido').removeClass('btn-warning');
                    $('#btn_reiniciar_pedido').addClass('btn-success');
                    $('#btn_reiniciar_pedido').html('<i class="fa fa-check"></i> Recargando...');
                    setTimeout(function(){
                        window.location = url_admin + 'pedidos/info/' + order_id;
                    }, 1500);
                } else {
                    toastr['error']('No se pudo reiniciar el pedido');
                }
            })
            .catch(function(error) { console.log(error) })
        },
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('payment_form'))
            axios.post(url_api + 'pedidos/update_payment/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success'](response.data.message)
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>