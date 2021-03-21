<?php $this->load->view('catalogo/mda_en_linea/style_v') ?>

<div id="app_digitales">
    <div class="page-title text-center">
        <h1 class="title">Minutos de Amor &middot; <span>En Línea</span></h1>
    </div>

    <div class="row mb-2">
        <div class="col-xs-12">
            <p class="presentation text-center">Selecciona el plan</p>
        </div>
        <div class="col-xs-6" v-for="(producto, key) in productos">
            <div class="sm_box" v-bind:class="{'active': producto_key == key }" v-on:click="set_current(key)">
                <div class="sm_product">
                    {{ producto.nombre_corto }}
                </div>
                <p class="sm_precio">{{ producto.precio }}</p>
            </div>
        </div>
        <div class="col-xs-12 hidden-md hidden-lg">
            <p class="text-center">{{ producto_actual.descripcion }}</p>
        </div>
        <div class="col-xs-12 hidden-md hidden-lg text-center">
            <button class="btn-cart btn-block" v-on:click="agregar_producto">
                CONTINUAR
            </button>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12 presentation text-center">
            <p class="mb-2 hidden">
                <a href="<?= base_url("books/demo/minutos-de-amor-abril-2020") ?>" class="link_demo">
                    <i class="fa fa-caret-right"></i> Mira Abril de 2020 en Línea <i class="fa fa-caret-left"></i>
                </a>
            </p>
            <p>
                <a href="<?= base_url("posts/leer/331/como-comprar-minutos-de-amor-en-linea") ?>" class="link_demo">
                    ¿Cómo comprar? Paso a paso
                </a>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-12 presentation text-center">
            <p>
                Cuenta con <b>tu oración diaria</b> en tu celular o tableta.
            </p>
        </div>
    </div>

    <div class="row hidden-xs" id="product_detail" v-show="producto_id > 0">
        <div class="col-md-4">
            <img
                v-bind:src="producto_actual.img_src"
                class="product_img"
                alt="image producto"
                onerror="this.src='<?= URL_IMG ?>app/500px_producto.png'"
            >
        </div>
        <div class="col-md-8">
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
                    CONTINUAR
                </button>
            </div>
        </div>
        
    </div>
</div>

<?php $this->load->view('catalogo/mda_en_linea/vue_v') ?>