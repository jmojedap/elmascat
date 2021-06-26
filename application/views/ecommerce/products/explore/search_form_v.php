<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q" class="form-control"
                    placeholder="Buscar" title="Buscar"
                    autofocus
                    v-model="filters.q" v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn" title="Mostrar filtros para búsqueda avanzada"
                        v-on:click="toggle_filters"
                        v-bind:class="{'btn-primary': display_filters, 'btn-light': !display_filters }"
                        >
                        <i class="fas fa-chevron-down" v-show="!display_filters"></i>
                        <i class="fas fa-chevron-up" v-show="display_filters"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>" class="mb-2">
        <div class="form-group row">
            <div class="col-md-9">
                <select name="status" v-model="filters.status" class="form-control">
                    <option v-for="(option_estado, key_status) in options_estado" v-bind:value="key_status">{{ option_estado }}</option>
                </select>
            </div>
            <label for="status" class="col-md-3 col-form-label">Estado</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="cat" v-model="filters.cat" class="form-control" title="Filtrar por categoría">
                    <option v-for="(option_categoria, key_categoria) in options_categoria" v-bind:value="key_categoria">{{ option_categoria }}</option>
                </select>
            </div>
            <label for="cat" class="col-md-3 col-form-label">Categoría</label>
        </div>

        <div class="form-group row">
            <div class="col-md-9">
                <select name="tag" v-model="filters.tag" class="form-control">
                    <option v-for="(option_tag, key_tag) in options_tag" v-bind:value="key_tag">{{ option_tag }}</option>
                </select>
            </div>
            <label for="tag" class="col-md-3 col-form-label">Etiqueta</label>
        </div>

        <div class="form-group row">
            <div class="col-md-9">
                <select name="fab" v-model="filters.fab" class="form-control">
                    <option v-for="(option_fab, key_fab) in options_fabricante" v-bind:value="key_fab">{{ option_fab }}</option>
                </select>
            </div>
            <label for="fab" class="col-md-3 col-form-label">Etiqueta</label>
        </div>

        <div class="form-group row">
            <div class="col-md-9">
                <select name="dcto" v-model="filters.dcto" class="form-control">
                    <option v-for="(option_promocion, key_dcto) in options_promocion" v-bind:value="key_dcto">{{ option_promocion }}</option>
                </select>
            </div>
            <label for="dcto" class="col-md-3 col-form-label">Promoción</label>
        </div>

        <div class="form-group row">
            <div class="col-md-9">
                <input name="fe1" type="number" class="form-control" min="0"
                    v-model="filters.fe1"
                >
            </div>
            <label for="fe1" class="col-md-3 col-form-label">Peso máximo (g)</label>
        </div>

        <!-- Botón ejecutar y limpiar filtros -->
        <div class="form-group row">
            <div class="col-md-9 text-right">
                <button class="btn btn-light w120p" v-on:click="remove_filters" type="button" v-show="active_filters">Todos</button>
                <button class="btn btn-primary w120p" type="submit">Buscar</button>
            </div>
        </div>
    </div>
</form>