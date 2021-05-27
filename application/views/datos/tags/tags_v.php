<?php $this->load->view('productos/explorar/menu_v') ?>

<style>
    .subnivel{
        color: #666;
        background-color: #fafafa;
    }
</style>

<div id="etiquetas_app">

    <div style="margin-bottom: 15px;">
        <button class="btn btn-success" data-toggle="modal" data-target="#modal_form" v-on:click="new_element()">
            <i class="fa fa-plus"></i> Nueva
        </button>

        <?= anchor("datos/export_tags", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-default" title="Exportar listado de etiquetas"') ?>
    </div>

    <table class="table table-hover bg-blanco">
        <thead>
            <th>Etiqueta</th>
            <th>Descripción</th>
            <th width="85px"></th>
        </thead>
        <tbody>
            
            <tr
                v-bind:class="{'subnivel': etiqueta.nivel > 1, 'info': etiqueta.id == element_id}"
                v-for="(etiqueta, key) in list"
                v-on:click="set_current(key)"
                >
                <td>
                    <span class="text-muted" v-for="n in ((parseInt(etiqueta.nivel) - 1) * 20)">&nbsp;</span>
                    <i class="fa fa-caret-right"></i>
                    {{ etiqueta.nombre_tag }}
                </td>
                <td>
                    {{ etiqueta.descripcion }}
                </td>
                <td>
                    <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_form" v-on:click="edit_element(key)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_delete" v-on:click="set_current(key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <?php $this->load->view('datos/tags/form_v') ?>
    <?php $this->load->view('comunes/modal_delete_v') ?>
</div>

<?php $this->load->view('datos/tags/vue_v') ?>