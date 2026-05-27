<script>
// Variables
//-----------------------------------------------------------------------------
var arr_tipos_precio = <?= json_encode($arr_tipos_precio) ?>;
var arr_extras_pedidos = <?= json_encode($arr_extras_pedidos) ?>;

// Filters
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return ''
    value = '$' + new Intl.NumberFormat().format(value)
    return value
})

Vue.filter('nombre_tipo_precio', function (value) {
    if (!value) return ''
    value = arr_tipos_precio[value]
    return value
})

Vue.filter('name_extra_pedido', function (value) {
    if (!value) return ''
    value = arr_extras_pedidos[value]
    return value
})

// VueApp
//-----------------------------------------------------------------------------
var cotizadorApp = new Vue({
    el: '#cotizadorApp',
    created: function(){
        //this.get_list()
    },
    data: {
        order: <?= json_encode($row) ?>,
        products: <?= json_encode($products->result()) ?>,
        product_key: -1,
        product: {},
        extras: <?= json_encode($extras->result()) ?>,
        loading: false,
        region_id: '0<?= $row->region_id ?>',
        ciudad_id: '0<?= $row->ciudad_id ?>',
        options_region: <?= json_encode($options_region) ?>,
        options_ciudad: <?= json_encode($options_ciudad) ?>,
        fields: {
            q: '',
        },
        users: [],
    },
    methods: {
        set_product: function(product_key){
            this.product_key = product_key
            this.product = this.products[product_key]
        },
        checked_quantity: function(product_key){
            //Controlar cantidad, mayor a cero y menor o igual a stock
            var stock = parseInt(this.products[product_key].cant_disponibles)
            var checked_quantity = this.products[product_key].cantidad
            if ( checked_quantity <= 0 ) checked_quantity = 1
            if ( checked_quantity > stock ) checked_quantity = cant_disponibles
            return parseInt(checked_quantity)
        },
        add_product: function(product_key){
            this.loading = true
            this.set_product(product_key)

            var new_quantity = this.checked_quantity(product_key)

            //Ejecutar cambio de cantidad
            axios.get(URL_API2 + 'pedidos/add_product/' + this.products[product_key].producto_id + '/' + new_quantity + '/' + this.order.cod_pedido)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.get_order_info() 
                } else {
                    toastr['warning'](response.data.message)
                    setTimeout(() => {
                        window.location = url_app + 'tienda/estado_compra/' + this.order.order_code
                    }, 2000)
                }
            })
            .catch(function(error) { console.log(error) })

        },
        get_order_info: function(){
            axios.get(URL_API2 + 'pedidos/get_info/' + this.order.cod_pedido)
            .then(response => {
                this.order = response.data.order
                this.products = response.data.products
                this.extras = response.data.extras
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        cancel_order: function(){
            axios.get(URL_API2 + 'pedidos/cancel/')
            .then(response => {
                if ( response.data.status == 1 ) {
                    window.location = url_app + 'productos/catalogo'
                }
            })
            .catch(function (error) { console.log(error) })
        },
        delete_element: function(){
            this.loading = true
            console.log('Eliminando producto');
            axios.get(URL_API2 + 'pedidos/remove_product/' + this.product.producto_id + '/' + this.order.cod_pedido)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['info']('El producto fue retirado de tu compra')
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
        is_special_price: function(product_key){
            if ( this.products[product_key].precio_nominal > this.products[product_key].precio ) {
                return true;
            } else {
                return false;
            }
        },
        discount_percent: function(product){
            var discount_percent = 0;
            if ( parseInt(product.precio_nominal) > 0 ) {
                //discount_percent = 100 - 100 * parseInt(product.precio / product.precio_nominal)
                discount_percent = Pcrn.round(100 - 100 * (product.precio / product.precio_nominal),0)
            }
            return discount_percent;
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
        guardar_lugar: function() {
            this.loading = true
            var params = new FormData();
            params.append('ciudad_id', this.ciudad_id);

            axios.post(URL_API2 + 'pedidos/guardar_lugar/', params)
                .then(response => {
                    this.get_order_info()
                    this.loading = false
                    toastr['success']('Lugar de entrega actualizado')
                })
                .catch(function(error) { console.log(error) })
        },
        getUsers: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('usersForm')) 
            axios.post(url_api + 'usuarios/get/', formValues)
            .then(response => {
                this.users = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setUser: function(userId){
            axios.get(URL_API2 + 'pedidos/set_user/' + userId)
            .then(response => {
                this.get_order_info()
                this.loading = false
                this.users = []
                this.fields.q = ''
                toastr['success']('Usuario comprador asignado')
                scroll(0,0)
            })
            .catch(function(error) { console.log(error) })

        },
    }
})
</script>
