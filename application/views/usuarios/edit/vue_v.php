<script>    
    var form_values = {
        nombre: '<?= $row->nombre ?>',
        apellidos: '<?= $row->apellidos ?>',
        no_documento: '<?= $row->no_documento ?>',
        tipo_documento_id: '0<?= $row->tipo_documento_id ?>',
        email: '<?= $row->email ?>',
        ciudad_id: '0<?= $row->ciudad_id ?>',
        city_zone: '<?= $row->city_zone ?>',
        fecha_nacimiento: '<?= $row->fecha_nacimiento ?>',
        sexo: '0<?= $row->sexo ?>',
        celular: '<?= $row->celular ?>'
    };
            
    new Vue({
        el: '#edit_user',
        data: {
            form_values: form_values,
            validation: {
                id_number_unique: -1,
                email_unique: -1
            },
            user_id: <?= $row->id ?>
        },
        methods: {
            validate_send: function () {
                axios.post(url_app + 'usuarios/validate/' + this.user_id, $('#edit_form').serialize())
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
                axios.post(url_app + 'usuarios/update/' + this.user_id, $('#edit_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.saved_id;
                        toastr['success']('Guardado')
                    } else {
                        toastr['error']('Ocurrió un error al guardar')
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            validate_form: function() {
                axios.post(url_app + 'usuarios/validate/' + this.user_id, $('#edit_form').serialize())
                .then(response => {
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                     console.log(error);
                });
            }
        }
    });
</script>