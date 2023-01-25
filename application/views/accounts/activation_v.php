<div id="activationApp">
    <div class="center_box_320">
        <div class="text-center" v-show="loading">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
        <div v-show="status == -1">
            <div v-show="userId > 0" class="text-center">
                <h4 class="white"><?= $user->display_name ?></h4>
                <p class="text-muted">
                    <?= $user->email ?>
                </p>
                <p>Establece tu contraseña en <?= APP_NAME ?></p>
                <form id="setPasswordForm" method="post" accept-charset="utf-8" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading">
                        <div class="mb-2">
                            <input name="password" type="password" class="form-control input-lg" placeholder="contrase&ntilde;a"
                                title="Debe tener un número y una letra minúscula, y al menos 8 caractéres" required
                                autofocus pattern="(?=.*\d)(?=.*[a-z]).{8,}">
                        </div>
                        <div class="mb-2">
                            <input name="passconf" type="password" class="form-control input-lg"
                                placeholder="confirma tu contrase&ntilde;a" title="passconf contrase&ntilde;a" required>
                        </div>
                        <div class="mb-2">
                            <button type="submit" class="btn btn-polo-lg">
                                <i class="fa fa-spin fa-spinner" v-show="loading"></i>
                                Guardar
                            </button>
                        </div>
                        <fieldset>
                </form>


            </div>
        </div>
        <div v-show="status == 1">
            <p class="text-center"><i class="fa fa-check-circle text-success fa-2x"></i></p>
            <h1><?= $user->display_name ?></h1>
            <p>
                Tu cuenta fue activada correctamente
            </p>
            <a class="btn btn-success btn-lg btn-block" href="<?= URL_APP . 'accounts/logged' ?>">
                Continuar
            </a>
        </div>
        <div v-show="status == 0">
            <p class="text-center"><i class="fa fa-exclamation-triangle text-warning"></i> No activada</p>
            <h1>Cuenta no identificada</h1>
            <p>El código de activación no corresponde a ninguna cuenta de usuario</p>
        </div>
        <div class="alert alert-danger" v-show="errors.length">
            {{ errors }}
        </div>
    </div>

</div>

<script>
var activationApp = new Vue({
    el: '#activationApp',
    created: function() {
        //this.activate()
    },
    data: {
        loading: false,
        status: -1,
        userId: <?= $user->id ?>,
        display_name: '',
        activation_key: '<?= $activation_key ?>',
        errors: '',
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('setPasswordForm'))
            axios.post(url_api + 'accounts/activate/' + this.activation_key, formValues)
            .then(response => {
                this.errors = response.data.errors
                if ( response.data.status == 1 ) {
                    toastr['success']('Tu cuenta fue activada exitosamente')
                    toastr['info']('Cargando...')
                    setTimeout(function(){
                        window.location = url_app + 'accounts/edit/basic' },
                        3000
                    );
                } else {
                    document.getElementById('setPasswordForm').reset()
                    this.loading = false
                }
            })
            .catch(function (error) { console.log(error)})
        },
    }
})
</script>