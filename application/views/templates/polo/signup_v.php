<?php $this->load->view('assets/recaptcha') ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<div class="page-title">
    <h2>Registro de usuarios</h2>
</div>

<div class="row mb-2" id="SignUpApp">
    
    <div class="col-md-5">
        <div class="box_1 mb-2">
            <form accept-charset="utf-8" method="POST" id="SignUpForm" @submit.prevent="handleSubmit">
                <!-- Campo para validación Google ReCaptcha V3 -->
                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">        
                
                <div class="form-group">
                    <input
                        type="text" name="nombre" v-model="user.nombre"
                        required class="form-control"
                        placeholder="Nombre"
                        title="nombre"
                        >
                </div>
                <div class="form-group">
                    <input
                        type="text" name="apellidos" required class="form-control"
                        v-model="user.apellidos"
                        placeholder="Apellidos" title="apellidos"
                        >
                </div>

                <div class="form-group" v-bind:class="{'has-error': ! validation.email_unique }">
                    <input
                        type="email" name="email" required class="form-control" v-model="user.email"
                        placeholder="Correo electrónico" title="correo electrónico"
                        v-on:change="validate_form"
                        >
                    <span class="help-block" v-show="! validation.email_unique">
                        El correo electrónico ya está registrado, por favor escriba otro
                    </span>
                </div>
            
                <div class="form-group">
                    <input
                        type="text" name="fecha_nacimiento" required v-model="user.fecha_nacimiento"
                        class="form-control bs_datepicker"
                        placeholder="Fecha de nacimiento (AAAA-MM-DD)"
                        title="fecha de nacimiento (AAAA-MM-DD)"
                        >
                </div>
            
                <div class="form-group">
                    <input type="radio" name="sexo" value="1" required v-model="user.sexo"> Mujer
                    <input type="radio" name="sexo" value="2" v-model="user.sexo"> Hombre
                </div>

                <div class="form-group">
                    <p>
                        <input type="checkbox" name="condiciones" value="1" required>
                        Acepto los 
                        
                        <a href="<?= base_url("posts/leer/17/terminos-de-uso") ?>" target="_blank">
                            Términos de uso
                        </a>
                        de Districatólicas S.A.S.
                    </p>
                </div>

                <button type="submit" class="btn btn-polo-lg"><span>Registrarme</span></button>

                <div class="text-center">
                    <a class="btn btn-polo" href="<?= base_url("accounts/login") ?>">
                        Ya tengo una cuenta
                    </a>
                    <a class="btn btn-polo" href="<?= base_url("accounts/recovery") ?>">
                        Olvidé mis datos
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-7">
        <table width="100%" class="table table-default">
            <tbody>
                <tr>
                    <td width="40%">
                        <h4 class="text-success">
                            <i class="fa fa-user"></i>
                            Datos básicos
                        </h4>
                    </td>
                    <td>
                        Escribe tus datos personales, nombre, apellidos.
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4 class="text-warning">
                            <i class="fa fa-envelope-o"></i>
                            Correo electrónico
                        </h4>
                    </td>
                    <td>
                        Usa preferiblemente una cuenta de correo diferente a <b class="text-danger">@hotmail.com</b>,
                        ya que el mensaje de activación podría no llegar a tu bandeja de entrada.
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4 class="">
                            <i class="fa fa-calendar-check-o"></i>
                            Fecha de nacimiento
                        </h4>
                    </td>
                    <td>
                        Escribe tu fecha de nacimiento con el formato AAAA-MM-DD, por ejemplo "1991-03-12".
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4>
                            <i class="fa fa-file-text-o"></i>
                            Términos de uso
                        </h4>
                    </td>
                    <td>
                        Lee >>
                        <a href="<?= base_url("posts/leer/17/terminos-de-uso") ?>" target="_blank">
                            aquí
                        </a>
                        << los términos de uso de este sitio. Activa la casilla de aceptación para realizar el registro.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    new Vue({
        el: '#SignUpApp',
        data: {
            user: {
                nombre: '',
                apellidos: '',
                email: '',
                fecha_nacimiento: '',
                sexo: -1,
            },
            validation: {
                email_valid: true,
                email_unique: true,
                username_unique: true
            },
            validated: 0,
            loading: false,
        },
        methods: {
            handleSubmit: function(){
                if ( this.validated ) {
                    this.loading = true
                    
                    var formValues = new FormData(document.getElementById('SignUpForm'))
                    axios.post(url_api + 'accounts/create/', formValues)
                    .then(response => {
                        if ( response.data.saved_id > 0 ) {
                            window.location = url_app + 'accounts/registered/' + response.data.saved_id;
                        } else {
                            toastr['error']('No se creó la cuenta de usuario')
                        }

                        if ( response.data.recaptcha = -1 ) {
                            //ReCaptcha vencido, se reinicia
                            setTimeout(() => {
                                toastr['info']('Reiniciando formulario')
                                window.location = url_app + 'accounts/signup'
                            }, 3000);
                        }
                    })
                    .catch(function (error) { console.log(error) })
                } else {
                    toastr['warning']('Hay datos que no han sido validados')
                }
            },
            validate_form: function(){
                var form_data = $('#SignUpForm').serialize();
                
                axios.post(url_api + 'accounts/validate_signup', form_data)
                .then(response => {
                    this.validated = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) { console.log(error) })
            }
        }
    });
</script>