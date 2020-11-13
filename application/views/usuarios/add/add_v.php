<?php $this->load->view('assets/bs4_chosen'); ?>

<?php 
    $options_role = $this->Item_model->options('categoria_id = 58', 'Rol de usuario');
    $options_sexo = $this->Item_model->options('categoria_id = 59 AND id_interno IN (1,2,90)');
    $options_ciudad = $this->App_model->opciones_lugar('tipo_id = 4', 'full_name');
    $options_tipo_documento = $this->Item_model->options('categoria_id = 53');
    $options_shipping_system = $this->Item_model->options('categoria_id = 183', 'Transportadora');
    $options_payment_channel = $this->Item_model->options('categoria_id = 185', 'Medio de pago DC');
?>

<div id="add_user">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-right">Rol</label>
                    <div class="col-md-8">
                        <?= form_dropdown('rol_id', $options_role, '021', 'class="form-control" required') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nombre" class="col-md-4 col-form-label text-right">Nombres y Apellidos *</label>
                    <div class="col-md-4">
                        <input
                            name="nombre" class="form-control" placeholder="Nombres"
                            required
                            v-model="form_values.nombre"
                            >
                    </div>
                    <div class="col-md-4">
                        <input
                            name="apellidos" class="form-control" placeholder="Apellidos"
                            required
                            v-model="form_values.apellidos"
                            >
                    </div>
                </div>
                    
                <div class="form-group row" id="form-group_no_documento">
                    <label for="no_documento" class="col-md-4 col-form-label text-right">No. Documento *</label>
                    <div class="col-md-4">
                        <input
                            name="no_documento" class="form-control"
                            placeholder="" title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                            required pattern=".{5,}[0-9]"
                            v-bind:class="{ 'is-invalid': validation.id_number_unique == 0 }"
                            v-model="form_values.no_documento"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El número de documento escrito ya fue registrado para otro usuario
                        </span>
                    </div>
                    <div class="col-md-4">
                        <?= form_dropdown('tipo_documento_id', $options_tipo_documento, '', 'class="form-control" required v-model="form_values.tipo_documento_id"') ?>
                    </div>
                </div>
                
                <div class="form-group row" id="form-group_email">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
                    <div class="col-md-8">
                        <input
                            name="email" type="email" class="form-control"
                            placeholder="" title="Dirección de correo electrónico"
                            v-bind:class="{ 'is-invalid': validation.email_unique == 0 }"
                            v-model="form_values.email" v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El correo electrónico ya fue registrado, por favor escriba otro
                        </span>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-right">Contraseña</label>
                    <div class="col-md-8">
                        <input
                            name="password" class="form-control"
                            placeholder="Escriba la contraseña del usuario" title="Debe tener al menos un número y una letra minúscula, y al menos 8 caractéres"
                            required
                            pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            v-model="form_values.password"
                            >
                    </div>
                </div>
            
                <div class="form-group row">
                    <label for="ciudad_id" class="col-md-4 col-form-label text-right">Ciudad residencia</label>
                    <div class="col-md-8">
                        <?= form_dropdown('ciudad_id', $options_ciudad, '', 'id="field-ciudad_id" class="form-control form-control-chosen-required" v-model="form_values.ciudad_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city_zone" class="col-md-4 col-form-label text-right">Barrio / Vereda</label>
                    <div class="col-md-8">
                        <input
                            name="city_zone" type="text" class="form-control"
                            title="Vereda o corregimiento" placeholder=""
                        >
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="fecha_nacimiento" class="col-md-4 col-form-label text-right">Fecha de nacimiento</label>
                    <div class="col-md-8">
                        <input
                            type="date" name="fecha_nacimiento" class="form-control bs_datepicker"
                            v-model="form_values.fecha_nacimiento"
                            >
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="sexo" class="col-md-4 col-form-label text-right">Sexo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('sexo', $options_sexo, '', 'class="form-control" required v-model="form_values.sexo"') ?>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Celular</label>
                    <div class="col-md-8">
                        <input name="celular" class="form-control">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="shipping_system" class="col-md-4 col-form-label text-right">Transportadora</label>
                    <div class="col-md-8">
                        <?= form_dropdown('shipping_system', $options_shipping_system, '', 'class="form-control"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="payment_channel" class="col-md-4 col-form-label text-right">Medio de pago DC</label>
                    <div class="col-md-8">
                        <?= form_dropdown('payment_channel', $options_payment_channel, '', 'class="form-control"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">
                            Crear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Usuario creado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Usuario creado correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="go_created">
                        Abrir usuario
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clean_form" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php
$this->load->view('usuarios/add/add_vue_v');