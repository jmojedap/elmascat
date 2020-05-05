<?php
    $arr_meta_productos = array(
        array('nombre_corto' => 'Edición Mayo 2020', 'qty_months' => '1'),
        array('nombre_corto' => '3 meses', 'qty_months' => '3'),
        array('nombre_corto' => '6 meses', 'qty_months' => '6'),
        array('nombre_corto' => '12 meses', 'qty_months' => '12')
    );

    $arr_productos = array();

    $i = 0;

    foreach ($productos->result() as $row_producto)
    {
        $producto = $arr_meta_productos[$i];
        $att_img = $this->Archivo_model->att_img($row_producto->imagen_id, '500px_');

        $producto['id'] = $row_producto->id;
        $producto['nombre_producto'] = $row_producto->nombre_producto;
        $producto['precio'] = $this->pml->money($row_producto->precio);
        $producto['descripcion'] = $row_producto->descripcion;
        $producto['img_src'] = $att_img['src'];
        $producto['precio_und'] = $this->pml->money($row_producto->precio / $producto['qty_months']);

        $arr_productos[] = $producto;

        $i++;
    }
?>

<style>
    .card_producto{
        margin-bottom: 1.5em;
    }

    .product_price{
        font-weight: bold;
        color: #1c95d1;
        font-size: 2em;
        margin-top: 10px;
        margin-bottom: 10px;
        display: block;
    }

    .main_price{
        font-weight: bold;
        color: #1c95d1;
        font-size: 2em;
    }

    .precio_und{
        font-weight: bold;
        font-size: 1.1em;
        background-color: #fdd922;
        padding: 0 10px;
        border-radius: 3px;
}

    .product_img {
        width: 100%;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        cursor: pointer;
    }

    .product_img:hover{
        -webkit-box-shadow: 3px 3px 5px 0px rgba(184,184,184,1);
        -moz-box-shadow: 3px 3px 5px 0px rgba(184,184,184,1);
        box-shadow: 3px 3px 5px 0px rgba(184,184,184,1);
    }

    .btn-cart{
        font-family: 'Open Sans';
        font-weight: bold;
        text-transform: uppercase;
        background: #fdd922;
        color: #333;
        font-size: 16px;
        text-shadow: none;
        padding: 7px 20px;
        height: 44px;
        float: left;
        
        transition: color 300ms ease-in-out 0s, background-color 300ms ease-in-out 0s, background-position 300ms ease-in-out 0s;
        border: none;
    }

    .page-title h1{
        font-size: 2.5em;
        color: #777;
    }

    .page-title h1 span{
        color: #1c95d1;
    }

    .presentation{
        font-size: 1.2em;
    }

    .presentation b{
        color: #1c95d1;
    }

    .link_demo{
        background-color: #fdd922;
        padding: 0 5px 0 5px;
    }
</style>

<div id="app_digitales">
    <div class="page-title text-center">
        <h1 class="title">Minutos de Amor &middot; <span>En Línea</span></h1>
    </div>

    aquí va para mobile

    <div class="row" v-show="producto_id == 0">
        <div class="col-md-8 col-md-offset-2 col-sm-12 presentation text-center">
            <p>
                Querido lector, queremos seguir <b>acompañándote en tu oración diaria</b> desde tu casa.
            </p>
            <p>
                Agradecemos tu ayuda en esta labor evangelizadora, además de seguir apoyando el trabajo 
                de nuestros colaboradores, y a los seminaristas respaldados por Minutos de Amor.
            </p>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12 presentation text-center">
            <p>
                Mira el demo de la edición de
                <a href="<?php echo base_url("books/demo/minutos-de-amor-abril-2020") ?>" class="link_demo">
                    <i class="fa fa-caret-right"></i> Abril de 2020 <i class="fa fa-caret-left"></i>
                </a>
            </p>
        </div>
    </div>
    <div class="row" v-show="producto_id == 0">
        <div class="col-md-3 text-center card_producto" v-for="(producto, key) in productos">
            <img
                v-bind:src="producto.img_src"
                class="product_img"
                alt="Imagen producto"
                onerror="this.src='<?php echo URL_IMG ?>app/500px_producto.png'"
                v-on:click="set_current(key)"
            >
            <div class="mb-2">
                <span class="product_price">{{ producto.precio }}</span>
                <span class="precio_und">{{ producto.precio_und }}</span> / mes
            </div>
            
        </div>
        <div class="col-md-3">
            
        </div>
    </div>

    <div class="row mb-2" v-show="producto_id == 0">
        <div class="col-md-8 col-md-offset-2 col-sm-12 presentation text-center">
            
        </div>
    </div>

    <div class="row" id="product_detail" v-show="producto_id > 0">
        <div class="col-md-4">
            <img
                v-bind:src="producto_actual.img_src"
                class="product_img"
                alt="image producto"
                onerror="this.src='<?php echo URL_IMG ?>app/500px_producto.png'"
            >
        </div>
        <div class="col-md-8">
            <button class="btn btn-polo w120p" v-on:click="unset_current"><i class="fa fa-arrow-left"></i> Volver</button>
            <h2>{{ producto_actual.nombre_producto }}</h2>
            <p>{{ producto_actual.descripcion }}</p>
            <p>
                <span class="main_price">{{ producto_actual.precio }}</span>
            </p>
            <p>
            <span class="precio_und">{{ producto_actual.precio_und }}</span> / mes
            </p>

            <div style="max-width: 300px;">
                <button class="btn-cart btn-block" v-on:click="agregar_producto">
                    COMPRAR
                </button>
            </div>
        </div>
        
    </div>
</div>

<script>
    new Vue({
        el: '#app_digitales',
        created: function(){
            //this.set_current(2);
        },
        data: {
            producto_key: 2,
            producto_id: 0,
            producto_actual: {
            },
            productos: <?php echo json_encode($arr_productos) ?>
        },
        methods: {
            set_current: function(key){
                this.producto_key = key;
                this.producto_id = this.productos[this.producto_key].id;
                this.producto_actual = this.productos[this.producto_key];
            },
            unset_current: function(){
                this.producto_id = 0;
                this.producto_actual = {}; 
            },
            agregar_producto: function(){
                var params = new FormData();
                params.append('producto_id', this.producto_id);
                params.append('cantidad', 1);
                
                axios.post(app_url + 'pedidos/guardar_detalle/', params)
                .then(response => {
                    if ( response.data > 0 ) {
                        window.location = app_url + 'pedidos/compra_a/';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>