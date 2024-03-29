<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
            <?php if ( $this->session->userdata('role') <= 2 ) : ?>
                <th width="10px" class="d-none">
                    <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
                </th>
            <?php endif; ?>
            
            <th width="120px">Ref. venta</th>
            <th>Cliente</th>
            <th>Pagado</th>
            <th>Canal</th>
            <th>Valor</th>
            <th>Estado</th>
            <th>PayU</th>
            <th>Peso (kg)</th>
            <th>Imprimir</th>
            <th>Regalo</th>
            <th>Editado</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                
                <?php if ( $this->session->userdata('role') <= 2 ) : ?>
                    <td class="d-none">
                        <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                    </td>
                <?php endif; ?>

                <td>
                    <a v-bind:href="`<?= base_url("pedidos/ver/") ?>` + element.id">
                        {{ element.cod_pedido }}                        
                    </a>
                </td>
                    
                </td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "usuarios/pedidos" ?>/` + element.usuario_id">
                        {{ element.nombre }} {{ element.apellidos }}
                    </a>
                    <br>
                    {{ element.email }}
                </td>

                <td class="text-center">
                    <i class="fa fa-check-circle text-success" v-if="element.payed == 1"></i>
                </td>

                <td>
                    <div v-if="element.payment_channel > 0">
                        <i class="fa fa-circle" v-bind:class="`channel_` + element.payment_channel"></i>
                        {{ element.payment_channel | payment_channel_name }}
                    </div>
                </td>

                <td class="text-right">
                    <span class="text-muted" v-if="element.payed == 0">
                        {{ element.valor_total | currency }}
                    </span>
                    <strong v-if="element.payed == 1">
                        {{ element.valor_total | currency }}
                    </strong>
                </td>

                <td v-bind:class="{'table-danger': element.estado_pedido == 6, 'table-warning': element.estado_pedido == 3 }">
                    {{ element.estado_pedido | status_name }}
                </td>

                <td>
                    <div v-show="element.payu_mensaje_respuesta_pol">
                        <span v-show="element.payu_firma == element.firma_pol_confirmacion">
                            <i class="fa fa-check-circle text-success" v-show="element.payu_codigo_respuesta_pol == 1"></i>
                            <i class="far fa-circle text-muted" v-show="element.payu_codigo_respuesta_pol != 1"></i>
                        </span>
                        <i class="fa fa-exclamation-triangle text-warning" v-show="element.payu_firma != element.firma_pol_confirmacion"></i>
                        <a v-bind:href="`<?= base_url("pedidos/pol/") ?>` + element.id">
                            {{ element.payu_mensaje_respuesta_pol }}
                        </a>
                    </div>
                </td>

                <td v-bind:class="{'table-info': element.peso_total > 0 }">
                    <span v-show="element.peso_total > 0">
                        {{ element.peso_total }}
                    </span>
                </td>

                <td class="only-lg">
                    <div v-show="element.payed == 1">
                        <a v-bind:href="`<?= base_url("pedidos/reporte/") ?>` + element.id" class="btn btn-sm btn-light" target="_blank">R</a>
                        <a v-bind:href="`<?= base_url("pedidos/reporte/") ?>` + element.id + `/label`" class="btn btn-sm btn-light" target="_blank">L</a>
                    </div>
                </td>

                <td>
                    <i class="fa fa-gift text-success" v-show="element.is_gift == 1"></i>
                </td>

                <td>
                    <span>{{ element.updater_username }}</span>
                    &middot;
                    <span class="text-muted">{{ element.editado | ago }}</span>
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