<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    
    <div class="form-group only-sm">
        <select name="cat_1" v-model="filters.cat_1" class="form-control" v-on:change="get_list">
            <option value="">[ Todas las categorías ]</option>
            <option v-for="(option_category, key_category) in options_category" v-bind:value="key_category">{{ option_category }}</option>
        </select>
    </div>

    <div class="form-group">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" name="q"
                v-model="filters.q" v-on:change="get_list"
            >
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>

    <div class="list-group mb-2 only-lg">
        <a href="#" class="list-group-item list-group-item-action pointer"
            v-on:click="set_category('')"
            v-bind:class="{'active': filters.cat_1 == '' }"
            >
            Todas las categorías
        </a>
        <a href="#" class="list-group-item list-group-item-action pointer"
            v-for="(option_category, kc) in options_category"
            v-on:click="set_category(kc)"
            v-bind:class="{'active': filters.cat_1 == kc }"
            >
            {{ option_category }}
        </a>
    </div>
</form>