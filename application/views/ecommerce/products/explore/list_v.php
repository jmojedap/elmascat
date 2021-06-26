<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
        <th width="10px"><input type="checkbox" @change="select_all" v-model="all_selected"></th>

        <th>Nombre</th>
        <th>Precio</th>
        <th>Disponibles</th>
        <th>Ventas</th>
        <th>Promoción</th>
        <th>Estado</th>
        <th>Puntajes</th>

        <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td><input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id"></td>

                
                <td>
                    <a v-bind:href="`<?= $this->url_controller ?>info/` + element.id">
                        {{ element.name }}
                    </a>
                    <br>
                    <span class="text-muted">{{ element.code }}</span>
                </td>
                <td class="text-right table-info">{{ element.price | currency }}</td>

                <td>{{ element.stock }}</td>
                <td>{{ element.qty_sold }}</td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "posts/edit/" ?>` + element.promocion_id">
                        {{ element.promocion_id | promocion_name }}
                    </a>
                </td>
                <td>
                    <span v-show="element.status==1"><i class="fa fa-check-circle text-success"></i> Activo</span>
                    <span v-show="element.status==2"><i class="fa fa-exclamation-triangle text-warning"></i> Inactivo</span>
                    <span v-show="element.status==3"><i class="fas fa-minus-circle text-info"></i> Borrador</span>
                </td>
                <td>{{ element.priority }} &middot; {{ element.priority_auto }}</td>

                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>