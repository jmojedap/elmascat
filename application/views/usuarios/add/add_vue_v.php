<script>
    /*var random = '16073' + Math.floor(Math.random() * 100000);
    var form_values = {
        nombre: 'Henry',
        apellidos: 'Jones',
        no_documento: random,
        tipo_documento_id: '01',
        email: random + 'jairo@gmail.com',
        //email: 'jmojedap@gmail.com',
        password: 'dc' + Math.floor(Math.random() * 10000000),
        ciudad_id: '0909',
        //ciudad_id: '',
        fecha_nacimiento: '1982-12-31',
        sexo: '01'
    };*/
    
    var form_values = {
        nombre: '',
        apellidos: '',
        no_documento: '',
        tipo_documento_id: '01',
        email: '',
        password: 'dc' + Math.floor(Math.random() * 1000000),
        ciudad_id: '0909',
        fecha_nacimiento: '',
        sexo: '01'
    };
            
    new Vue({
        el: '#add_user',
        data: {
            form_values: form_values,
            validation: {
                id_number_unique: -1,
                email_unique: -1
            },
            row_id: 0
        },
        methods: {
            validate_send: function () {
                axios.post(url_app + 'usuarios/validate/', $('#add_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.send_form();
                    } else {
                        this.validation = response.data.validation;
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            send_form: function() {
                axios.post(url_app + 'usuarios/insert/', $('#add_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.saved_id;
                        this.clean_form();
                        $('#modal_created').modal();
                    } else {
                        toastr['error']('Ocurrió un error al guardar')
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            validate_form: function() {
                axios.post(url_app + 'usuarios/validate/', $('#add_form').serialize())
                .then(response => {
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                for ( key in form_values ) this.form_values[key] = ''
            },
            go_created: function() {
                window.location = url_app + 'usuarios/profile/' + this.row_id;
            }
        }
    });
</script>