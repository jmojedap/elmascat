<?php
    //$reload_url = base_url("books/read/{$book_code}/{$meta_id}/{$slug}/vl");
    $reload_url = base_url("books/read/{$book_code}/{$meta_id}/{$slug}");
?>

<script>
    function reload_book()
    {
        setTimeout(function () {
            window.location = '<?= $reload_url; ?>';
        }, 5000);
    }
</script>

<div id="app_book" class="container">
    <!-- BARRA DE HERRAMIENTAS-->
    <div class="mb-2 text-center section">
        <button class="w2 float-left btn btn-arrows" v-on:click="change_page(-1)">
            <i class="fa fa-chevron-left"></i>
        </button>

        <?php if ( is_null($this->session->userdata('logged')) ) { ?>
            <a href="<?= base_url('catalogo/productos_digitales/') ?>" class="btn btn-light" title="Volver a catálogo digital">
                <i class="fa fa-arrow-left"></i> <span class="hidden-sm">Volver</span> 
            </a>
        <?php } else { ?>
            <?php if ( $this->session->userdata('logged') ) { ?>
                <a href="<?= base_url('usuarios/books/') ?>" class="btn btn-light" title="Ir a mis libros">
                    <i class="fa fa-arrow-left"></i> <span class="hidden-sm">Mis libros</span> 
                </a>
            <?php } ?>
        <?php } ?>

        <button class="btn" v-on:click="set_mode('index')" v-bind:class="{'btn-warning': mode == 'index', 'btn-light': mode != 'index' }">
            <i class="fa fa-list-ol"></i> <span class="hidden-sm">Índice</span> 
        </button>
        <button class="btn" v-on:click="set_mode('mini')" v-bind:class="{'btn-warning': mode == 'mini', 'btn-light': mode != 'mini' }">
            <i class="far fa-file"></i> <span class="hidden-sm">Páginas</span> 
        </button>

        <button class="w2 float-right btn btn-arrows" v-on:click="change_page(1)">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>

    <div class="section">
        <input
            type="range"
            class="custom-range"
            min="0"
            v-bind:max="pages.length - 1"
            value="0"
            v-model="key_page"
            v-on:change="change_page(0)"
            >
    </div>

    <div class="row book_index section" v-show="mode == 'index'">
        <div class="mb-2">
            <button class="btn btn-info w120p" v-on:click="toggle_index_detail" v-show="!show_index_detail">
                <i class="fa fa-plus"></i>
                Detalle
            </button>
            <button class="btn btn-success  w120p" v-on:click="toggle_index_detail" v-show="show_index_detail">
                <i class="fa fa-minus"></i>
                Detalle
            </button>
        </div>
        <a
            href="#"
            v-for="(index_el, index_key) in book_index"
            v-bind:class="index_el.level_class"
            v-on:click="set_index(index_key)"
            v-show="show_index_detail || index_el.level == 1"
            >
            <span class="index_title">{{ index_el.title }}</span>
            <span class="num_page">{{ index_el.num_page | nice_num_page }}</span>
        </a>
    </div>

    <div class="" v-show="mode == 'mini'">
        <a href="#" v-for="(mini, mini_key) in pages" class="page_mini" v-on:click="set_page(mini_key)">
            <img v-bind:src="`<?= $url_folder ?>mini/` + mini" onerror="this.src='<?= URL_IMG ?>books/sm_nd.png'">
        </a>
    </div>

    <div class="section" v-show="mode == 'page'">
        <!-- <img class="page" v-bind:src="page.src" alt="Página libro" onerror="this.src='<?= URL_IMG ?>books/no_cargada.png'">         -->
        <img class="page" v-bind:src="page.src" alt="Página libro" onerror="reload_book();">
    </div>
</div>