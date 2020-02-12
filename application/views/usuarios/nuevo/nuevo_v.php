<?php $this->load->view('assets/toastr'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/chosen_jquery'); ?>

<?php 
    $opciones_rol = $this->Item_model->opciones('categoria_id = 58', 'Rol de usuario');
    $opciones_sexo = $this->Item_model->opciones('categoria_id = 59 AND id_interno <= 2', 'Sexo');
    $opciones_ciudad = $this->App_model->opciones_lugar('tipo_id = 4', 'CR', 'Ciudad');
    $opciones_tipo_documento = $this->Item_model->opciones('categoria_id = 53', 'Tipo documento');
?>

<?php $this->load->view($vista_menu); ?>
<?php $this->load->view('usuarios/nuevo/js_v'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <form id="formulario" accept-charset="utf-8" class="form-horizontal">
            <div class="form-group row">
                <div class="col-sm-offset-4 col-md-8">
                    <button class="btn btn-success btn-block" type="submit">
                        Guardar
                    </button>
                </div>
            </div>

            <div class="form-group row">
                <label for="nombre" class="col-md-4 control-label">Nombre y Apellidos</label>
                <div class="col-md-4">
                    <input
                        id="campo-nombre"
                        name="nombre"
                        class="form-control arg_username"
                        placeholder="Nombres"
                        title="Nombres del usuario"
                        required
                        autofocus
                        >
                </div>
                <div class="col-md-4">
                    <input
                        id="campo-apellidos"
                        name="apellidos"
                        class="form-control arg_username"
                        placeholder="Apellidos"
                        title="Apellidos del usuario"
                        required

                        >
                </div>
            </div>
            
            <div class="form-group row" id="form-group_no_documento">
                <label for="no_documento" class="col-md-4 control-label">No. documento</label>
                <div class="col-md-4">
                    <input
                        id="campo-no_documento"
                        name="no_documento"
                        class="form-control"
                        placeholder="Número de documento"
                        title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                        required
                        pattern=".{5,}[0-9]"

                        >
                </div>
                <div class="col-md-4">
                    <?php echo form_dropdown('tipo_documento_id', $opciones_tipo_documento, '', 'class="form-control" required') ?>
                </div>
            </div>
            
            <div class="form-group row" id="form-group_email">
                <label for="email" class="col-md-4 control-label">Correo electrónico</label>
                <div class="col-md-8">
                    <input
                        id="campo-email"
                        name="email"
                        type="email"
                        class="form-control"
                        placeholder="Dirección de correo electrónico"
                        title="Dirección de correo electrónico"
                        >
                </div>
            </div>
            
            <div class="form-group row" id="form-group_username">
                <label for="username" class="col-md-4 control-label">Username *</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary" title="Generar username" id="btn_generar_username">
                                <i class="fa fa-magic"></i>
                            </button>
                        </div>
                        <!-- /btn-group -->
                        <input
                            id="campo-username"
                            name="username"
                            class="form-control"
                            placeholder="Username"
                            title="Username"
                            required
                            >
                        
                      </div>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="password" class="col-md-4 control-label">Contraseña *</label>
                <div class="col-md-8">
                    <input
                        id="campo-password"
                        name="password"
                        class="form-control"
                        placeholder="Escriba la contraseña del usuario"
                        title="Debe tener al menos un número y una letra minúscula, y al menos 8 caractéres"
                        required
                        pattern="(?=.*\d)(?=.*[a-z]).{8,}"

                        >
                </div>
            </div>
            
            <div class="form-group row">
                <label for="ciudad_id" class="col-md-4 control-label">Ciudad residencia</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('ciudad_id', $opciones_ciudad, '', 'class="form-control chosen-select" required') ?>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="address" class="col-md-4 control-label">Dirección residencia</label>
                <div class="col-md-8">
                    <input
                        id="field-address"
                        name="address"
                        class="form-control"
                        placeholder="Dirección de residencia"
                        >
                </div>
            </div>

            <div class="form-group row">
                <label for="fecha_nacimiento" class="col-md-4 control-label">Fecha de nacimiento *</label>
                <div class="col-md-8">
                    <input
                        id="campo-fecha_nacimiento"
                        name="fecha_nacimiento"
                        class="form-control bs_datepicker"
                        required
                        placeholder="AAAA-MM-DD"
                        >
                </div>
            </div>
            
            <div class="form-group row">
                <label for="rol_id" class="col-md-4 control-label">Rol *</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('rol_id', $opciones_rol, '021', 'class="form-control" required') ?>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="sexo" class="col-md-4 control-label">Sexo *</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('sexo', $opciones_sexo, '', 'class="form-control" required') ?>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="celular" class="col-md-4 control-label">Celular</label>
                <div class="col-md-8">
                    <input
                        id="campo-celular"
                        name="celular"
                        class="form-control"
                        placeholder="Número celular"
                        title="Número celular"
                        >
                </div>
            </div>
            
        </form>
    </div>
</div>
