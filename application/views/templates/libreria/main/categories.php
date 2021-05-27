<?php
    $categorias = $this->Item_model->arr_cod('categoria_id = 25');
?>

<style>
    .menu_categorias {

    }

    .menu_categorias .categoria {
        padding: 0.5em;
        color: white;
        background-color: blue;
        text-decoration: none;
    }

    .menu_categorias .categoria:hover {
        background-color: green;
    }
</style>

<div id="categorias_app">
    <div class="d-flex flex-column justify-content-center menu_categorias">
        <a href="#" class="categoria" v-for="(categoria, ck) in categorias">
            {{ categoria }}
        </a>
    </div>
</div>

<script>
var categorias_app = new Vue({
    el: '#categorias_app',
    created: function(){
        //this.get_list()
    },
    data: {
        categorias: <?= json_encode($categorias) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>