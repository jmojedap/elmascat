

<div id="app_book" class="container">
    <!-- BARRA DE HERRAMIENTAS-->
    <div class="mb-2 text-center mw800p">
        <button class="w2 float-left btn btn-secondary" v-on:click="change_page(-1)">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button class="btn" v-on:click="set_mode('index')" v-bind:class="{'btn-warning': mode == 'index', 'btn-light': mode != 'index' }"><i class="fa fa-list-ol"></i> Índice</button>
        <button class="btn" v-on:click="set_mode('mini')" v-bind:class="{'btn-warning': mode == 'mini', 'btn-light': mode != 'mini' }"><i class="far fa-file"></i> Páginas</button>
        <button class="w2 float-right btn btn-secondary" v-on:click="change_page(1)">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>

    <div class="mw800p">
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

    <div class="row book_index" v-show="mode == 'index'">
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
            <img v-bind:src="`<?php echo $url_folder ?>mini/` + mini" onerror="this.src='<?php echo URL_IMG ?>books/sm_nd.png'">
        </a>
    </div>

    <div class="mw800p" v-show="mode == 'page'">
        <img class="page" v-bind:src="`<?php echo $url_folder ?>read/` + page" alt="Página libro">
    </div>
</div>