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

// Vue App
//-----------------------------------------------------------------------------

var datos_regalo_app = new Vue({
    el: '#datos_regalo_app',
    created: function(){
        this.get_empaques()
    },
    data: {
        loading: true,
        step: 'empaque',
        order: <?= json_encode($row) ?>,
        cod_pedido: '<?= $row->cod_pedido ?>',
        extras: <?= json_encode($extras->result()) ?>,
        meta: <?= json_encode($arr_meta); ?>,
        empaques: [],
        products: <?= json_encode($products->result()) ?>,
        current_key: -1,
        empaque: {}
    },
    methods: {
        set_step: function(step){
            this.step = step
            window.scrollTo(0, 0)
            console.log('moviendo')
        },
        get_empaques: function(){
            axios.get(url_api + 'productos/get_catalogo/1/20/?tag=707&o=price')
            .then(response => {
                this.empaques = response.data.list
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        set_product: function(key){
            this.current_key = key
            this.empaque = this.empaques[key]
        },
        //Agregar empaque al carrito
        add_product: function(key){
            this.set_product(key)
            axios.get(url_api + 'pedidos/add_product/' + this.empaque.id + '/1/' + this.order.cod_pedido)
            .then(response => {
                if ( response.data.status == 1 ) {
                    //this.step = 'datos_tarjeta'
                    toastr['success']('Empaque añadido al carrito')
                    toastr['info']('Haz clic en SIGUIENTE para escribir tu tarjeta')
                    this.get_order_info()
                }
            })
            .catch(function(error) { console.log(error) })
            console.log('activo')
        },
        validate_send: function(){
            if ( this.qty_empaques_in_cart > 0 ) {
                this.send_form()
            } else {
                toastr['warning']('Debes elegir un empaque para el regalo')
            }
        },
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('datos_regalo_form'))
            axios.post(url_app + 'pedidos/guardar_datos_regalo/', form_data)
            .then(response => {
                if ( response.data.status == 1) {
                    window.location = url_app + 'pedidos/compra_b'
                }
            })
            .catch(function (error) { console.log(error) })
        },
        delete_element: function(empaque_id){
            this.loading = true
            console.log('Eliminando producto');
            axios.get(url_api + 'pedidos/remove_product/' + empaque_id + '/' + this.order.cod_pedido)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['info']('El empaque fue retirado del carrito')
                    this.get_order_info()
                } else {
                    toastr['warning'](response.data.message)
                    setTimeout(() => {
                        window.location = url_app + 'pedidos/estado/?cod_pedido=' + this.order.order_code
                    }, 2000);
                }
            })
            .catch(function (error) { console.log(error) })
        },
        get_order_info: function(){
            axios.get(url_api + 'pedidos/get_info/' + this.order.cod_pedido)
            .then(response => {
                this.order = response.data.order
                this.products = response.data.products
                this.extras = response.data.extras
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        //Establece si un empaque está o no incluido en el carrito de compras
        in_cart: function(empaque_id){
            var product = this.products.find(product => product.producto_id == empaque_id)
            if ( product != null ) {
                return true
            } else {
                return false
            }
        },
        no_es_regalo: function(){
            this.loading = true
            var form_data = new FormData()
            form_data.append('is_gift',0)
            
            axios.post(url_app + 'pedidos/guardar_pedido/', form_data)
            .then(response => {
                console.log(response.data)
                var destination = url_app + 'pedidos/compra_b'
                if ( response.data.status == 1) window.location = destination
            })
            .catch(function (error) { console.log(error) })
        },
        warning_no_empaques: function(){
            toastr['info']('Debes elegir al menos un empaque')
        },
    },
    computed: {
        qty_empaques_in_cart: function(){
            var qty_empaques_in_cart = 0
            this.empaques.forEach(empaque => {
                if (this.in_cart(empaque.id)) {
                    qty_empaques_in_cart++
                }
            });
            return qty_empaques_in_cart
        }
    },
});
</script>