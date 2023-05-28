<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input type="hidden" name="promo" v-model="filters.promo">
    <input type="hidden" name="d1" v-model="filters.d1">
    <div class="form-group">
        <label for="q">Buscar
            <a @click="removeFilter('q')" class="remove-filter" v-show="filters.q"><i class="fa fa-times"></i></a>
        </label>
        <input
            type="text" name="q" class="catalogo-filter" autofocus
            v-model="filters.q" v-on:change="getList"
            v-bind:class="{'active': filters.q && filters.q.length > 0 }"
            >
    </div>
    
    <div id="advanced_filters" class="mb-2" v-bind:style="advancedFiltersStyle">
        <div class="form-group">
            <label for="cat">Categoría producto
                <a @click="removeFilter('cat')" class="remove-filter" v-show="filters.cat"><i class="fa fa-times"></i></a>
            </label>
            <select name="cat" v-model="filters.cat" class="catalogo-filter" title="Filtrar por categoría" v-on:change="getList"
                v-bind:class="{'active': filters.cat && filters.cat.length > 0 }"
            >
                <option value="">[ Todas las categorías ]</option>
                <option v-for="optionCategoria in arrCategorias" v-bind:value="optionCategoria.str_cod">{{ optionCategoria.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tag">
                Temas y Etiquetas
                <a @click="removeFilter('tag')" class="remove-filter" v-show="filters.tag"><i class="fa fa-times"></i></a>
            </label>
            <select name="tag" v-model="filters.tag" class="catalogo-filter" title="Filtrar por tema/etiqueta" v-on:change="getList"
                v-bind:class="{'active': filters.tag && filters.tag.length > 0 }"
                >
                <option value="">[ Todas ]</option>
                <option v-for="optionTag in arrTags" v-bind:value="optionTag.str_id">{{ optionTag.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fab">
                Editorial o Marca
                <a @click="removeFilter('fab')" class="remove-filter" v-show="filters.fab"><i class="fa fa-times"></i></a>
            </label>
            <select name="fab" v-model="filters.fab" class="catalogo-filter" v-on:change="getList"
                v-bind:class="{'active': filters.fab && filters.fab.length > 0 }"
                >
                <option value="">[ Todos ]</option>
                <option v-for="optionFabricante in arrFabricantes" v-bind:value="optionFabricante.str_id">{{ optionFabricante.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fe3">Precio
                <a @click="removeFilter('fe3')" class="remove-filter" v-show="filters.fe3"><i class="fa fa-times"></i></a>
            </label>
            <select name="fe3" v-model="filters.fe3" class="catalogo-filter" v-on:change="getList"
                v-bind:class="{'active': filters.fe3 && filters.fe3.length > 0 }"
                >
                <option value="">[ Todos ]</option>
                <option v-for="optionRangoPrecio in arrRangoPrecio" v-bind:value="optionRangoPrecio.cod">{{ optionRangoPrecio.name }}</option>
            </select>
        </div>

        <!-- Variables de ordenamiento de registros -->
        <div class="form-group">
            <label for="ordering" class="">Ordenar por</label>
            <select name="ordering" v-model="filters.ordering" class="catalogo-filter" @change="getList">
                <option v-for="optionOrdering in arrOrdering" v-bind:value="optionOrdering.value" v-show="optionOrdering.scope == 1">{{ optionOrdering.name }}</option>
            </select>
        </div>
    </div>
</form>