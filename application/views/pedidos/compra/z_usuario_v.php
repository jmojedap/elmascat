<?php $this->load->view('assets/recaptcha') ?>

<div id="pedido_usuario_app" class="center_box_450">
    <form accept-charset="utf-8" method="POST" id="email_form" @submit.prevent="check_email" v-show="show_email_form">
        <div class="form-group">
            <label for="email" class="">Correo electrónico</label>
            <input
                type="email"
                id="field-email"
                name="email"
                autofocus
                required
                class="form-control input-lg"
                placeholder="Correo electrónico"
                title="Correo electrónico"
                v-model="email"
                >
        </div>
        <div class="form-group">
            <button class="btn btn-polo-lg">
                CONTINUAR
            </button>
        </div>
    </form>

    <form accept-charset="utf-8" method="POST" id="register_form" @submit.prevent="check_pw" v-show="show_register_form">
        <!-- Campo para validación Google ReCaptcha V3 -->
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        <input type="hidden" v-model="email" name="email">
        <input type="hidden" v-model="sexo" name="sexo">
        
        <h3 class="text-center text-primary">{{ email }}</h3>        

        <div class="form-group">
            <label for="nombre" class="control-label">Nombres</label>
            <input type="text" name="nombre" required class="form-control input-lg" placeholder="Nombres" title="Nombres">
        </div>
        <div class="form-group">
            <label for="apellidos" class="control-label">Apellidos</label>
            <input type="text" name="apellidos" required class="form-control input-lg" placeholder="Apellidos" title="Apellidos">
        </div>

        
        <div class="form-group">
            <label for="fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
            <div class="row">
                <div class="col-xs-4 col-md-3">
                    <select name="day" v-model="day" class="form-control input-lg" required>
                        <option v-for="(option_day, key_day) in options_day" v-bind:value="key_day">{{ option_day }}</option>
                    </select>
                </div>
                <div class="col-xs-4 col-md-5">
                    <select name="month" v-model="month" class="form-control input-lg" required>
                        <option v-for="(option_month, key_month) in options_month" v-bind:value="key_month">{{ option_month }}</option>
                    </select>
                </div>
                <div class="col-xs-4 col-md-4">
                    <select name="year" v-model="year" class="form-control input-lg" required>
                        <option v-for="(option_year, key_year) in options_year" v-bind:value="key_year">{{ option_year }}</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col-md-6">
                <button type="button" class="btn btn-block" v-bind:class="{'btn-primary': sexo == 1}" v-on:click="set_sexo(1)">
                    <i class="fa fa-check" v-show="sexo == 1"></i>
                    Mujer</button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-block" v-bind:class="{'btn-primary': sexo == 2}" v-on:click="set_sexo(2)">
                    <i class="fa fa-check" v-show="sexo == 2"></i>
                    Hombre</button>
            </div>
        </div>

        <?php if ( $qty_digital_products > 0 ) : ?>
            <p class="text-center">
                Crea <b>tu contraseña</b> para DistriCatolicas.com
            </p>
            <p class="text-center">Con tu correo y contraseña podrás ingresar a leer tus contenidos en línea</p>
            <div class="form-group">
                <label for="password">Tu contraseña</label>
                <input
                    type="password" name="password" class="form-control input-lg"
                    required pattern="(?=.*\d)(?=.*[a-z]).{8,}" title="8 caractéres o más, al menos un número y una letra minúscula"
                    v-model="pw"
                    >
                    <p class="help-block">8 caracteres o más, con letras y números</p>
                
            </div>
            <div class="form-group">
                <label for="password">Confirmar contraseña</label>
                <input type="password" name="passconf" required class="form-control input-lg" title="Confirma tu contraseña" v-model="pc">
            </div>
        <?php endif; ?>
            
        <div class="form-group">
            <button class="btn btn-polo-lg">ENVIAR</button>
        </div>
        <div class="text-center alert alert-warning" v-show="!pw_match">
            <i class="fa fa-warning"></i>
            La contraseña de confirmación no coincide con la primera
        </div>
        
    </form>

    <div v-show="show_success_register">
        <div class="alert alert-success text-center">
            <i class="fa fa-user fa-2x"></i> <br>
            Tu cuenta de usuario fue creada con el email: <b>{{ email }}</b>
            y la contraseña que acabaste escribir.
        </div>
        <a href="<?= base_url("pedidos/compra_a/") ?>" class="btn btn-polo-lg">
            CONTINUAR COMPRA <i class="fa fa-arrow-right"></i>
        </a>
    </div>

    <div v-show="show_existing">
        <div class="alert alert-success text-center">
            <i class="fa fa-user fa-2x"></i> <br>
            Hola {{ user.display_name }}, ya estás en DistriCatolicas.com
        </div>
        <a href="<?= base_url("pedidos/compra_a/") ?>" class="btn btn-polo-lg">
            CONTINUAR
        </a>
    </div>
</div>

<script>
var pedido_usuario_app = new Vue({
    el: '#pedido_usuario_app',
    data: {
        order: <?= json_encode($row) ?>,
        qty_digital_products: <?= $qty_digital_products ?>,
        user: {},
        email: 'fadsjfldsk@gmail.com.co',
        year: '01985',
        month: '006',
        day: '015',
        sexo: 1,
        pw: '',
        pc: '',
        pw_match: true,
        options_day: <?= json_encode($options_day) ?>,
        options_month: <?= json_encode($options_month) ?>,
        options_year: <?= json_encode($options_year) ?>,
        show_email_form: true,
        show_register_form: false,
        show_success_register: false,
        show_existing: false,
    },
    methods: {
        check_email: function(){
            var params = new FormData();
            params.append('email', this.email);
            
            axios.post(url_app + 'accounts/check_email/', params)
            .then(response => {
                console.log(response.data);
                this.show_email_form = false;
                if ( response.data.status == 1 )
                {
                    this.user = response.data.user;
                    this.set_user();
                } else {
                    console.log('No registrado');
                    this.show_register_form = true;
                    /*if ( this.qty_digital_products > 0 ) {
                        console.log('Hay productos digitales, solicitar password');
                    }*/
                }
            })
            .catch(function (error) { console.log(error) })
        },
        set_user: function(){
            axios.get(url_app + 'pedidos/set_user/' + this.user.id)
            .then(response => {
                if ( response.data.user_id > 0 ) {
                    this.show_existing = true;
                }
                console.log(response.data)
            })
            .catch(function (error) { console.log(error) })
        },
        check_pw: function(){
            console.log(this.pw + '-' + this.pc);
            if ( this.pw == this.pc )
            {
                this.fast_register();
            } else {
                this.pw_match = false;
            }
        },
        set_sexo: function(sexo){
            this.sexo = sexo
        },
        fast_register: function(){                
            axios.post(url_app + 'accounts/fast_register/', $('#register_form').serialize())
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.show_success_register = true
                    this.show_register_form = false
                }
                console.log(response.data);
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>