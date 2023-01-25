<?php
    $randomNumber = rand(100,999);
?>

<script>
var pedido_usuario_app = new Vue({
    el: '#pedido_usuario_app',
    data: {
        loading: false,
        order: <?= json_encode($row) ?>,
        qty_digital_products: <?= $qty_digital_products ?>,
        user: {
            nombre: '', apellidos:''
        },
        email: '',
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
            var params = new FormData()
            params.append('email', this.email)
            
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
                this.createUser()
            } else {
                this.pw_match = false;
            }
        },
        set_sexo: function(sexo){
            this.sexo = sexo
        },
        createUser: function(){                
            this.loading = true
            var formValues = new FormData(document.getElementById('signUpForm'))
            axios.post(url_app + 'accounts/create_fast/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.show_success_register = true
                    this.show_register_form = false
                    window.scrollTo(0,0);
                }
                if ( response.data.recaptcha == -1 ) {
                    toastr['info']('El recaptcha de validación expiró, se recargará la página')
                    setTimeout(() => {
                        window.location = url_app + 'pedidos/usuario'
                    }, 3000);
                }
                console.log(response.data);
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>