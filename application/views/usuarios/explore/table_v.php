<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <?php if ( $this->session->userdata('role') <= 2 ) : ?>
                <th width="10px">
                    <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
                </th>
            <?php endif; ?>
            
            <th class="table-warning" width="1px">ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Contacto</th>
            <th></th>
            <th>Documento</th>
            
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                
                <?php if ( $this->session->userdata('role') <= 2 ) : ?>
                    <td>
                        <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                    </td>
                <?php endif; ?>

                <td class="table-warning">
                    {{ element.id }}                        
                </td>
                    
                </td>
                <td>
                    <a v-bind:href="`<?= base_url("usuarios/info/") ?>/` + element.id">
                        {{ element.display_name }}
                    </a>
                    <br>
                    {{ element.city_name }}
                </td>

                <td>
                    <i class="fa fa-check-circle text-success" v-if="element.status == 1"></i>
                    <i class="fa fa-check-circle text-warning" v-if="element.status == 2"></i>
                    <i class="far fa-circle text-danger" v-if="element.status == 0"></i>
                    {{ element.role | role_name }}
                </td>
                <td>
                    {{ element.email }}
                    <br>
                    <span class="text-muted" v-show="element.phone_number"><i class="fas fa-mobile-alt"></i> {{ element.phone_number }}</span>
                </td>

                <td>
                    {{ element.gender | gender_name }}
                    &middot;
                    {{ element.birth_date | ago }}
                </td>
                <td>
                    <span class="text-muted">{{ element.id_number_type | id_number_type_name }}&middot;</span>{{ element.id_number }}
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>