<?php $this->load->view('assets/momentjs') ?>

<div id="app_extras">
    <div class="row">
        <div class="col-md-6">
            <table class="table bg-blanco">
                <thead>
                    <th>Concepto</th>
                    <th>Valor</th>
                    <th width="40%">Nota</th>
                    <th width="100px" v-if="editable == 1"></th>
                </thead>
                <tbody>
                    <tr v-for="(extra, key) in list" v-bind:class="{'info': row_key == key }" v-bind:id="`extra_` + extra.id">
                        <td>{{ extra.producto_id | extra_name }}</td>
                        <td>{{ extra.precio | currency }}</td>
                        <td>{{ extra.nota }}</td>
                        <td v-if="editable == 1">
                            <a class="btn btn-light btn-sm" v-on:click="set_form(key)"><i class="fa fa-pencil-alt"></i></a>
                            <a class="btn btn-danger btn-sm" v-on:click="set_current(key)" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-lg-6 col-md-6" v-if="editable == 1">
            <div class="mb-2">
                <button type="button" class="btn btn-primary w120p" v-on:click="cancel_edition">
                    <i class="fa fa-plus"></i>
                    Nuevo
                </button>
            </div>
            <div class="panel mb-1">
                <div class="panel-body" id="new_extra">
                    <?php $this->load->view('pedidos/extras/form_v') ?>
                </div>
            </div>

            
        </div>
    </div>
    <?php $this->load->view('common/bs3/modal_single_delete_v') ?>
</div>

<?php $this->load->view('pedidos/extras/vue_v') ?>