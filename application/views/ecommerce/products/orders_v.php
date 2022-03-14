<div id="orders_app">
    <table class="table bg-white">
        <thead>
            <th>Ref. venta</th>
            <th>Cliente</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total producto</th>
            <th>Total pedido</th>
            <th>Actualizado</th>
        </thead>
        <tbody>
            <tr v-for="(order, key) in orders">
                <td>
                    <a v-bind:href="`<?= URL_APP . "pedidos/info/" ?>` + order.id">
                        {{ order.cod_pedido }}
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_APP . "usuarios/pedidos/" ?>` + order.usuario_id">
                        {{ order.nombre }} {{ order.apellidos }}
                    </a>
                </td>
                <td class="text-center">{{ order.cantidad }}</td>
                <td class="text-right">{{ order.precio | currency }}</td>
                <td class="text-right">{{ order.cantidad * order.precio | currency }}</td>
                <td class="text-right">
                    <span class="text-muted" v-if="order.payed == 0">
                        {{ order.valor_total | currency }}
                    </span>
                    <strong v-if="order.payed == 1">
                        {{ order.valor_total | currency }}
                    </strong>
                </td>
                <td>
                    <span>{{ order.editado | date_format }}</span>
                    <br>
                    <span class="text-muted">hace {{ order.editado | ago }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>

// Filtros
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return '';
    value = new Intl.NumberFormat().format(value);
    return value;
});

Vue.filter('date_format', function (date) {
    if (!date) return ''
    return moment(date).format('D [de] MMMM, dddd')
});

Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow(true);
});

// VueApp
//-----------------------------------------------------------------------------
var orders_app = new Vue({
    el: '#orders_app',
    created: function(){
        //this.get_list()
    },
    data: {
        orders: <?= json_encode($orders->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>