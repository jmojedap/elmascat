<?php $this->load->view('assets/bs4_chosen'); ?>

<?php 
    $options_role = $this->Item_model->options('categoria_id = 58', 'Rol de usuario');
    $options_sexo = $this->Item_model->options('categoria_id = 59 AND id_interno IN (1,2,90)');
    $options_ciudad = $this->App_model->opciones_lugar('tipo_id = 4', 'full_name');
    $options_tipo_documento = $this->Item_model->options('categoria_id = 53');
    $options_shipping_system = $this->Item_model->options('categoria_id = 183', 'Transportadora');
    $options_payment_channel = $this->Item_model->options('categoria_id = 185', 'Medio de pago DC');
?>

<div id="edit_user">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-right">Rol</label>
                    <div class="col-md-8">
                        <?= form_dropdown('rol_id', $options_role, '', 'class="form-control" required v-model="form_values.rol_id"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nombre" class="col-md-4 col-form-label text-right">Nombres y Apellidos</label>
                    <div class="col-md-4">
                        <input
                            name="nombre" class="form-control" placeholder="Nombres"
                            v-model="form_values.nombre"
                            v-on:change="set_display_name_auto"
                            >
                    </div>
                    <div class="col-md-4">
                        <input
                            name="apellidos" class="form-control" placeholder="Apellidos"
                            v-model="form_values.apellidos"
                            v-on:change="set_display_name_auto"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="institution_name" class="col-md-4 col-form-label text-right">Nombre Empresa/Institución</label>
                    <div class="col-md-8">
                        <input
                            name="institution_name" type="text" class="form-control"
                            title="Nombre empresa o institución"
                            v-model="form_values.institution_name"
                            v-on:change="set_display_name_auto"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right"><strong>Nombre (mostrar como)</strong></label>
                    <div class="col-md-8">
                    <div class="input-group">
                        <input
                            name="display_name" type="text" class="form-control"
                            required
                            title="Nombre mostrar"
                            v-model="form_values.display_name"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" title="Generar nombre automáticamente" v-on:click="set_display_name"><i class="fa fa-magic"></i></button>
                        </div>
                    </div>
                    </div>
                </div>
                    
                <div class="form-group row" id="form-group_no_documento">
                    <label for="no_documento" class="col-md-4 col-form-label text-right">No. Documento</label>
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
                    <label for="sexo" class="col-md-4 col-form-label text-right">Sexo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('sexo', $options_sexo, '', 'class="form-control" required v-model="form_values.sexo"') ?>
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

                <hr>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-right">Dirección completa</label>
                    <div class="col-md-8">
                        <input
                            name="address" type="text" class="form-control"
                            title="Dirección"
                            v-model="form_values.address"
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
                            title="Vereda o corregimiento" placeholder="" v-model="form_values.city_zone"
                        >
                    </div>
                </div>
                
                
                
                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Celular</label>
                    <div class="col-md-8">
                        <input name="celular" class="form-control" v-model="form_values.celular">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="telefono" class="col-md-4 col-form-label text-right">Otros teléfonos</label>
                    <div class="col-md-8">
                        <input name="telefono" class="form-control" v-model="form_values.telefono">
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label for="shipping_system" class="col-md-4 col-form-label text-right">Transportadora</label>
                    <div class="col-md-8">
                        <?= form_dropdown('shipping_system', $options_shipping_system, '', 'class="form-control" v-model="form_values.shipping_system"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="payment_channel" class="col-md-4 col-form-label text-right">Medio de pago</label>
                    <div class="col-md-8">
                        <?= form_dropdown('payment_channel', $options_payment_channel, '', 'class="form-control" v-model="form_values.payment_channel"') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notas" class="col-md-4 col-form-label text-right">Notas internas</label>
                    <div class="col-md-8">
                        <textarea
                            name="notas" class="form-control" rows="3"
                            title="Notas sobre el usuario" placeholder="Notas sobre el usuario"
                            v-model="form_values.notas"
                        ></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$this->load->view('usuarios/edit/vue_v');