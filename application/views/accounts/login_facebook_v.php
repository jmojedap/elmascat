<style>
    #login_app{
        max-width: 360px;
        margin: 0 auto;
    }
</style>

<?php $this->load->view('accounts/login_facebook_script_v') ?>

<div id="login_app" class="text-center">
    <?php if ( $this->uri->segment(3) == 'suscriptor' ) { ?>
        <div class="alert alert-success">
            <i class="fa fa-info-circle fa-2x text-center"></i>
            <br>
            Querido suscriptor, ingresa con tu <b>NÚMERO DE DOCUMENTO</b>, en las dos casillas siguientes, sin espacios ni puntos.
        </div>
    <?php } else {?>
        <p>
            Escribe tu correo electrónico y contraseña
        </p>
    <?php } ?>

    <form accept-charset="utf-8" method="POST" id="login_form" @submit.prevent="validate_login">
        <div class="form-group">
            <label class="sr-only" for="inputEmail">Email</label>
            <input
                class="form-control input-lg"
                name="username"
                placeholder="Correo electrónico"
                required
                title="Username o dirección de correo electrónico">
        </div>
        <div class="form-group">
            <label class="sr-only" for="inputPassword">Contraseña</label>
            <input type="password" class="form-control input-lg" id="inputPassword" name="password" placeholder="Contraseña" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="rememberme" value="1" checked> Mantener sesión iniciada</label>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-polo-lg btn-block">Ingresar</button>
        </div>

        <!-- Botón Login con Facebook -->
        <div class="form-group">
            <div class="fb-login-button"
                data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false" data-width=""
                scope="public_profile,email" onlogin="checkLoginState();">
            </div>
        </div>
        
        <div class="form-group clearfix">
            <a href="<?= base_url('usuarios/recuperar') ?>">¿Olvidaste los datos de tu cuenta?</a>
        </div>
    </form>

    <br/>
    
    <div id="messages" v-if="!status">
        <div class="alert alert-warning" v-for="message in messages">
            {{ message }}
        </div>
    </div>

    <p class="mb-2">¿No tienes una cuenta? <br></p>
    <p>
        <a class="btn-polo w120p" href="<?= base_url('accounts/signup') ?>">Regístrate</a>
    </p>

    
</div>

<script>
    var form_destination = app_url + 'app/validate_login';
    new Vue({
        el: '#login_app',
        data: {
            messages: [],
            status: 1
        },
        methods: {
            validate_login: function(){                
                axios.post(form_destination, $('#login_form').serialize())
                   .then(response => {
                        if ( response.data.status == 1 )
                        {
                           window.location = app_url + 'app/logged';
                        } else {
                            this.messages = response.data.messages;
                            this.status = response.data.status;
                        }
                   })
                   .catch(function (error) {
                        console.log(error);
                   });
            }
        }
    });
</script>
