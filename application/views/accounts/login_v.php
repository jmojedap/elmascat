<style>
    #login_app{
        max-width: 360px;
        margin: 0 auto;
    }
</style>

<div id="login_app" class="text-center box_1">
    <p>
        Escribe tu correo electrónico
        <br/>
        y tu contraseña para ingresar.
    </p>

    <form accept-charset="utf-8" method="POST" id="login_form" @submit.prevent="validate_login">
        <div class="form-group">
            <label class="sr-only" for="inputEmail">Email</label>
            <input
                class="form-control"
                name="username"
                placeholder="Username o correo electrónico"
                required
                title="Username o dirección de correo electrónico">
        </div>
        <div class="form-group">
            <label class="sr-only" for="inputPassword">Contraseña</label>
            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Contraseña" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </div>
        
        <div class="form-group clearfix">
            <a href="<?php echo base_url('usuarios/recuperar') ?>">¿Olvidaste los datos de tu cuenta?</a>
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
        <a class="btn-polo w120p" href="<?php echo base_url('accounts/signup') ?>">Regístrate</a>
    </p>
</div>

<script>
    var app_url = '<?php echo base_url() ?>';
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
