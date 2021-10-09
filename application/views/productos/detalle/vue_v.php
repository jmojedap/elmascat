<script>
// Variables
//-----------------------------------------------------------------------------
const arr_tipos_precio = <?= json_encode($arr_tipos_precio) ?>;
const current_arr_precio = <?= json_encode($arr_precio) ?>;
const min_quantity_wholesale = 3;   //Cantidad mínima para tener precio por mayor

// Filtros
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return ''
    value = new Intl.NumberFormat().format(value)
    return value
});

Vue.filter('name_tipo_precio', function (value) {
    if (!value) return ''
    value = arr_tipos_precio[value]
    return value
})

// VueApp
//-----------------------------------------------------------------------------
var product_details_app = new Vue({
    el: '#product_details_app',
    data: {
        order_code: '<?= $this->session->userdata('order_code') ?>',
        product: <?= json_encode($row) ?>,
        quantity: 1,
        in_shopping_cart: false,
        arr_precios: <?= json_encode($arr_precios) ?>,
        arr_precio: <?= json_encode($arr_precio) ?>,
        precio_nominal: <?= $row->precio ?>,
        precio: <?= $arr_precio['precio'] ?>,
        loading: false,
    },
    methods: {
        add_to_cart: function(){
            this.loading = true
            axios.get(url_api + 'pedidos/add_product/' + this.product.id + '/' + this.quantity + '/' + this.order_code)
            .then(response => {
                if ( response.data.qty_items > 0 )
                {
                    $('#order_qty_items').removeClass('badge-dark')
                    $('#order_qty_items').addClass('badge-danger')
                    $('#order_qty_items').html(response.data.qty_items)
                    $('#modal_product_added').modal('show')
                    this.in_shopping_cart = true
                    toastr['success']('El producto fue agregado al carrito')
                }
                this.loading = false
            })
            .catch(function(error) {console.log(error)})
        },
        sum_quantity: function(sum){
            this.quantity += sum
            if ( this.quantity > this.product.cant_disponibles ) this.quantity = parseInt(this.product.cant_disponibles)
            if ( this.quantity <= 0 ) this.quantity = 1
            this.check_wholesale_price()
        },
        wholesale_price_available: function(){
            var available = false

            //Si el precio de mayoristas no está definido
            if ( this.arr_precios[3] != null ) {
                //Precio mayorista definido, pero es mas alto que el actual disponible
                if ( this.arr_precios[3] < current_arr_precio['precio'] ) available = true
            }

            //Si hay menos de tres unidades
            if ( this.product.cant_disponibles < min_quantity_wholesale ) available = false

            return available
        },
        set_wholesale_price: function(){
            if ( this.quantity < min_quantity_wholesale ) this.quantity = min_quantity_wholesale
            this.arr_precio = {"promocion_id":3,"precio":this.arr_precios[3]} 
            this.precio = this.arr_precios[3]
        },
        check_wholesale_price: function(){
            if ( this.quantity >= min_quantity_wholesale ) {
                if ( this.wholesale_price_available() ) {
                    this.set_wholesale_price()
                }
            } else {
                this.arr_precio = current_arr_precio
                this.precio = current_arr_precio['precio']
            }
        },
    }
})
</script>