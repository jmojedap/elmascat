<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="get_list">
    <div class="form-group">
        <input
            type="text" name="q" class="form-control"
            placeholder="Buscar" title="Buscar"
            autofocus
            v-model="filters.q" v-on:change="get_list"
            >
    </div>
    <div id="adv_filters" class="mb-2">
        <div class="form-group">
            <label for="cat">Categoría</label>
            <select name="cat" v-model="filters.cat" class="form-control" title="Filtrar por categoría">
                <option v-for="(option_categoria, key_categoria) in options_categoria" v-bind:value="key_categoria">{{ option_categoria }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tag">Categorías y temas</label>
            <select name="tag" v-model="filters.tag" class="form-control">
                <option v-for="(option_tag, key_tag) in options_tag" v-bind:value="key_tag">{{ option_tag }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fab">Marca/Editorial</label>
            <select name="fab" v-model="filters.fab" class="form-control">
                <option v-for="(option_fab, key_fab) in options_fabricante" v-bind:value="key_fab">{{ option_fab }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fe3">Rango de precio</label>
            <select name="fe3" v-model="filters.fe3" class="form-control">
                <option value="">[ Todos ]</option>
                <option v-for="optionRangoPrecio in arrRangoPrecio" v-bind:value="optionRangoPrecio.cod">{{ optionRangoPrecio.name }}</option>
            </select>
        </div>

        <!-- Variables de ordenamiento de registros -->
        <div class="form-group">
            <label for="order_by" class="">Ordenar por</label>
            <div class="row">
                <div class="col-md-6">
                    <select name="o" v-model="filters.o" class="form-control">
                        <option v-for="(option_order_by, key_order_by) in options_order_by" v-bind:value="key_order_by">{{ option_order_by }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="ot" v-model="filters.ot" class="form-control">
                        <option value="ASC">Ascendente</option>
                        <option value="DESC">Descendente</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Botón ejecutar y limpiar filtros -->
        <div class="form-group">
            <button class="btn btn-light w120p" v-on:click="remove_filters" type="button" v-show="active_filters">Todos</button>
            <button class="btn btn-primary w120p" type="submit">Buscar</button>
        </div>
    </div>
</form>