<?php $this->load->view('assets/recaptcha') ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/icheck'); ?>

<div class="page-title">
    <h2>Registro de usuarios</h2>
</div>

<div class="row div2" id="signup_app">
    
    <div class="col-md-6 caja1">
        
        <form accept-charset="utf-8" method="POST" id="signup_form" @submit.prevent="register" class="form-horizontal">
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">        
            
            <div class="form-group">
                <input
                    type="text"
                    id="field-nombre"
                    name="nombre"
                    required
                    class="form-control input1"
                    placeholder="nombre"
                    title="nombre"
                    >
            </div>
            <div class="form-group">
                <input
                    type="text"
                    id="field-apellidos"
                    name="apellidos"
                    required
                    class="form-control input1"
                    placeholder="apellidos"
                    title="apellidos"
                    >
            </div>

            <div class="form-group" v-bind:class="{'has-error': ! validation.email_unique }">
                <input
                    type="email"
                    id="field-email"
                    name="email"
                    required
                    class="form-control input1"
                    placeholder="correo electrónico"
                    title="correo electrónico"
                    v-on:change="validate_form"
                    >
                <span class="help-block" v-show="! validation.email_unique">
                    El correo electrónico ya está registrado, por favor escriba otro
                </span>
            </div>
        
            <div class="form-group">
                <input
                    type="text"
                    id="field-fecha_nacimiento"
                    name="fecha_nacimiento"
                    required
                    class="form-control bs_datepicker input1"
                    placeholder="fecha de nacimiento (AAAA-MM-DD)"
                    title="fecha de nacimiento (AAAA-MM-DD)"
                    >
            </div>
        
            <div class="form-group">
                <input type="radio" name="sexo" value="1" required> Mujer
                <input type="radio" name="sexo" value="2"> Hombre
            </div>

            <div class="form-group">
                <p>
                    <input type="checkbox" name="condiciones" value="1" required style="display: inline; height: 15px; width: 15px;"/>
                    Acepto los <?= anchor('posts/leer/17/terminos-de-uso', 'Términos de uso', 'target="_blank"') ?> de Districatólicas S.A.S.
                </p>
            </div>

            <button type="submit" class="button">
                <span>
                    Registrarme
                </span>
            </button>
        </form>
    </div>
    
    <div class="col-md-6">
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
                        Escriba sus datos personales, nombre, apellidos.
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
                        Use preferiblemente una cuenta de correo diferente a <b class="text-danger">@hotmail.com</b>,
                        ya que el mensaje de activación podría no llegar a su bandeja de entrada.
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
                        Escriba su fecha de nacimiento con el formato AAAA-MM-DD, por ejemplo "1991-03-12".
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
                        Lee >>aquí<< los términos de uso de este sitio. Activa la casilla de aceptación.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    var app_url = '<?php echo base_url() ?>';
    var url_api = '<?php echo base_url() ?>';
    new Vue({
        el: '#signup_app',
        data: {
            validation: {
                email_valid: true,
                email_unique: true,
                username_unique: true
            },
            validated: 0
        },
        methods: {
            register: function(){
                if ( this.validated ) {
                    
                    axios.post(url_api + 'accounts/register/', $('#signup_form').serialize())
                    .then(response => {
                        console.log(response.data.message);
                        if ( response.data.status == 1 ) {
                            window.location = app_url + 'accounts/registered/' + response.data.saved_id;
                        } else {
                            this.recaptcha_message = response.data.recaptcha_message;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            validate_form: function(){
                var form_data = $('#signup_form').serialize();
                
                axios.post(url_api + 'accounts/validate_signup', form_data)
                .then(response => {
                    this.validated = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>