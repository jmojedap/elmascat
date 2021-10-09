<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q"
                    class="form-control"
                    placeholder="Buscar"
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-light btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="display_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!display_filters"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <select name="status" v-model="filters.status" class="form-control">
                    <option v-for="(option_status, key_status) in options_status" v-bind:value="key_status">{{ option_status }}</option>
                </select>
            </div>
            <label for="status" class="col-md-3 control-label col-form-label">Estado pedido</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="fe2" v-model="filters.fe2" class="form-control">
                    <option v-for="(option_crpol, key_crpol) in options_crpol" v-bind:value="key_crpol">{{ option_crpol }}</option>
                </select>
            </div>
            <label for="status" class="col-md-3 control-label col-form-label">Respuesta PayU</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="fe1" v-model="filters.fe1" class="form-control">
                    <option v-for="(option_peso, key_peso) in options_peso" v-bind:value="key_peso">{{ option_peso }}</option>
                </select>
            </div>
            <label for="status" class="col-md-3 col-form-label">Tipo peso</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input type="date" name="d1" class="form-control" title="Creados desde" v-model="filters.d1">
            </div>
            <label for="d1" class="col-md-3 col-form-label">Creado desde</label>
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