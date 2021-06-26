<?php $this->load->view('assets/lightbox2') ?>

<style>
    .detalle_product{
        display: grid;
        grid-template-areas:    "ct ct ct"
                                "tn ic pf"
                                "pd pd pd";
        grid-template-rows: 46px 1fr 1fr;
        grid-template-columns: 75px 1fr 360px;
        grid-gap: 10px;
    }

    .detalle_product .categories{ grid-area: ct; }
    .detalle_product .thumbnails{ grid-area: tn; }
    .detalle_product .image_container{ grid-area: ic; }
    .detalle_product .product_info{ grid-area: pf; }
    .detalle_product .product_details{
        border-top: 1px solid #DDD;
        margin-top: 2em;
        padding-top: 2em;
        grid-area: pd;
    }

    .detalle_product h1{
        font-size: 20px;
        font-weight: bold;
    }

    .detalle_product h2{
        font-size: 18px;
    }

    .detalle_product .price{
        font-size: 2em;
        color: #1C95D1;
    }

    .detalle_product .main_image {
        border-radius: 2px;
        width: [object Object]px; 
        height: [object Object]px; 
        background: #F8F8F8; 
        border: solid #BDBDBD 1px; 
        box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.3)  ; 
        -webkit-box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.3)  ; 
        -moz-box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.3)  ; 
    }

    .detalle_product .thumbnail{
        cursor: pointer;
        width: 100%;
        margin-bottom: 0.5em;
        border: 2px solid #DDD;
        border-radius: 2px;
    }

    .detalle_product .thumbnail:hover{
        border: 2px solid    #1C95D1;
    }

    .detalle_product .thumbnail .active{
        border: 2px solid    #1C95D1;
    }

    /* Pantallas pequeñas */
    @media (max-width: 767px) {
        .detalle_product{
            grid-template-areas:    "ct ct ct"
                                    "tn tn tn"
                                    "ic ic ic"
                                    "pd pd pd";
        }

        .detalle_product .thumbnail {
            width: 30px;
        }
    }
</style>

<div id="product_app">
    <div class="detalle_product">
        <nav aria-label="breadcrumb" class="categories">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_APP . 'tienda/productos' ?>">Productos</a></li>
                <li class="breadcrumb-item" v-show="product.categoria_id > 0">
                    <a href="<?= URL_APP . "tienda/productos/?cat_1=0{$row->categoria_id}" ?>"><?= $cat_1_name; ?></a>
                </li>
            </ol>
        </nav>
        <div class="thumbnails">
            <img
                v-for="(image, i) in images"
                class="thumbnail" alt="Imagen producto" onerror="this.src='<?= URL_IMG ?>app/262px_product.png'"
                v-bind:src="image.url_thumbnail" v-on:click="set_image(i)" v-bind:class="{'active': image.id == curr_image.id }"
            >
        </div>
        <div class="image_container text-center">
            <a v-bind:href="curr_image.url" data-lightbox="image-1">
                <img
                    v-bind:src="curr_image.url_thumbnail"
                    class="main_image"
                    alt="Imagen producto"
                    onerror="this.src='<?= URL_IMG ?>app/sm_nd.png'"
                >
            </a>
        </div>
        <div class="product_info">
            <div class="card">
                <div class="card-body">
                    <h1>{{ product.nombre_producto }}</h1>
                    <p class="price">
                        {{ product.precio | currency }}
                    </p>
                    <div v-if="product.cant_disponibles > 0">
                        <button class="btn btn-main w100pc btn-lg" type="button" v-on:click="add_to_cart" v-show="!in_shopping_cart">
                            <i class="fa fa-spin fa-spinner" v-show="loading"></i>
                            Agregar al carrito
                        </button>
                        <a class="btn btn-success w100pc btn-lg" href="<?= URL_APP . 'tienda/carrito' ?>" v-show="in_shopping_cart">
                            <i class="fa fa-check"></i> Agregado
                        </a>
                    </div>
                    <hr>
                    <p v-html="product.descripcion"></p>
                </div>
            </div>
        </div>
        <div class="product_details">
            <h2>Características principales</h2>
            <table class="table">
                <thead>
                    <th>hola    </th>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('currency', function (value) {
    if (!value) return ''
    value = '$ ' +  new Intl.NumberFormat().format(value)
    return value
});

// VueApp
//-----------------------------------------------------------------------------
var product_app = new Vue({
    el: '#product_app',
    created: function(){
        this.set_image(0)
    },
    data: {
        product: <?= json_encode($row) ?>,
        image_key: 0,
        images: <?= json_encode($images->result()) ?>,
        curr_image: {},
        quantity: 1,
        in_shopping_cart: false,
        screenWidth: screen.width,
        loading: false,
    },
    methods: {
        set_image: function(key){
            this.curr_image = this.images[key]
        },
        add_to_cart: function(){
            this.loading = true;
            axios.get(url_api + 'orders/add_product/' + this.product.id + '/' + this.quantity + '/' + this.order_code)
            .then(response => {
                if ( response.data.qty_items > 0 )
                {
                    console.log('hola')
                    $('#order_qty_items').removeClass('badge-dark')
                    $('#order_qty_items').addClass('badge-danger')
                    $('#order_qty_items').html(response.data.qty_items)
                    $('#modal_product_added').modal('show')
                    this.in_shopping_cart = true
                }
                this.loading = false
            })
            .catch(function(error) {console.log(error)})  
        },
    }
})
</script>