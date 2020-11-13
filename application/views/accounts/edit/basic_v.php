<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_gender = $this->Item_model->options('categoria_id = 59 AND id_interno <= 2', 'Sexo');
    $options_ciudad = $this->App_model->opciones_lugar('tipo_id = 4', 'full_name', 'Ciudad');
    $options_id_number_type = $this->Item_model->options('categoria_id = 53', 'Tipo documento');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="nombre" class="col-md-4 col-form-label text-right">Nombre | Apellidos</label>
                    <div class="col-md-4">
                        <input
                            id="field-nombre"
                            name="nombre"
                            class="form-control"
                            placeholder="Nombres"
                            title="Nombres"
                            required
                            autofocus
                            v-model="form_values.nombre"
                            >
                    </div>
                    <div class="col-md-4">
                        <input
                            id="field-apellidos"
                            name="apellidos"
                            class="form-control"
                            placeholder="Apellidos"
                            title="Apellidos"
                            required
                            v-model="form_values.apellidos"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Mostrar como</label>
                    <div class="col-md-8">
                        <input
                            id="field-display_name"
                            name="display_name"
                            class="form-control"
                            placeholder="Nombre para mostrar"
                            title="Nombre para mostrar"
                            required
                            v-model="form_values.display_name"
                            >
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
                        <?= form_dropdown('tipo_documento_id', $options_id_number_type, '', 'class="form-control" required v-model="form_values.tipo_documento_id"') ?>
                    </div>
                </div>

                <div class="form-group row" id="form-group_email">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
                    <div class="col-md-8">
                        <input
                            id="field-email"
                            name="email"
                            type="email"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.email_unique }"
                            placeholder="Dirección de correo electrónico"
                            title="Dirección de correo electrónico"
                            v-model="form_values.email"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            <span v-show="! validation.email_unique">Ya está registrado para otro usuario</span>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ciudad_id" class="col-md-4 col-form-label text-right">Ciudad residencia</label>
                    <div class="col-md-8">
                        <?= form_dropdown('ciudad_id', $options_ciudad, '', 'id="field-ciudad_id" class="form-control form-control-chosen-required" v-model="form_values.ciudad_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fecha_nacimiento" class="col-md-4 col-form-label text-right">Fecha de nacimiento</label>
                    <div class="col-md-8">
                        <input
                            id="field-fecha_nacimiento" name="fecha_nacimiento" class="form-control bs_datepicker" type="date"
                            v-model="form_values.fecha_nacimiento"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sexo" class="col-md-4 col-form-label text-right">Sexo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('sexo', $options_gender, $row->sexo, 'class="form-control" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Número celular</label>
                    <div class="col-md-8">
                        <input
                            id="field-celular"
                            name="celular"
                            class="form-control"
                            placeholder="Número celular"
                            title="Número celular"
                            minlength="10"
                            v-model="form_values.celular"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-info w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var form_values = {
        nombre: '<?= $row->nombre ?>',
        apellidos: '<?= $row->apellidos ?>',
        display_name: '<?= $row->display_name ?>',
        no_documento: '<?= $row->no_documento ?>',
        tipo_documento_id: '0<?= $row->tipo_documento_id ?>',
        username: '<?= $row->username ?>',
        email: '<?= $row->email ?>',
        ciudad_id: '0<?= $row->ciudad_id ?>',
        fecha_nacimiento: '<?= $row->fecha_nacimiento ?>',
        sexo: '<?= $row->sexo ?>',
        celular: '<?= $row->celular ?>',
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                email_unique: true,
                id_number_unique: true
            }
        },
        created: function(){
            this.validate_form();
        },
        methods: {
            validate_form: function() {
                axios.post(url_api + 'accounts/validate/', $('#edit_form').serialize())
                .then(response => {
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(url_api + 'accounts/validate/', $('#edit_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        this.send_form();
                    } else {
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function() {
                axios.post(url_api + 'accounts/update/', $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.message);
                        if (response.data.status == 1)
                        {
                        toastr['success']('Datos actualizados');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('nombre', this.form_values.nombre);
                params.append('apellidos', this.form_values.apellidos);
                
                axios.post(url_app + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
        }
    });
</script>