<style>
    .subnivel{
        color: #666;
        background-color: #fafafa;
    }
</style>

<div id="etiquetas_app">
    <div class="mb-2">
        <button class="btn btn-success" data-toggle="modal" data-target="#modal_form" v-on:click="new_element()">
            <i class="fa fa-plus"></i> Nueva
        </button>

        <?= anchor("datos/export_tags", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-default" title="Exportar listado de etiquetas"') ?>
    </div>

    <table class="table table-hover table-sm bg-white">
        <thead>
            <th width="10px">ID</th>
            <th>Etiqueta</th>
            <th>Descripción</th>
            <th width="110px"></th>
        </thead>
        <tbody>
            <tr
                v-bind:class="{'subnivel': etiqueta.nivel > 1, 'info': etiqueta.id == element_id}"
                v-for="(etiqueta, key) in list"
                v-on:click="set_current(key)"
                >
                <td>{{ etiqueta.id }}</td>
                <td>
                    <span class="text-muted" v-for="n in ((parseInt(etiqueta.nivel) - 1) * 15)">&nbsp;</span>
                    <i class="fa fa-caret-right" v-show="parseInt(etiqueta.nivel) == 1"></i>
                    {{ etiqueta.nombre_tag }}
                </td>
                <td>
                    {{ etiqueta.descripcion }}
                </td>
            
                <td>
                        <a v-bind:href="`<?= base_url() . "productos/catalogo/?tag=" ?>` + etiqueta.id" class="a4" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <button class="a4" data-toggle="modal" data-target="#modal_form" v-on:click="edit_element(key)">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <?php $this->load->view('datos/tags/form_v') ?>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view('datos/tags/vue_v') ?>