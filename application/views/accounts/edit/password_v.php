<?php if ( $row->id == $this->session->userdata('user_id') ) { ?>
    <div id="app_password" class="card center_box_450">
        <div class="card-body">
            
            <form accept-charset="utf-8" id="password_form" @submit.prevent="send_form">
                
                <div class="form-group row">
                    <label for="current_password" class="col-md-5 col-form-label text-right">Contraseña actual</label>
                    <div class="col-md-7">
                        <input
                            id="field-current_password"
                            name="current_password"
                            type="password"
                            class="form-control"
                            title="Contraseña actual"
                            required
                            autofocus
                            >
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="password" class="col-md-5 col-form-label text-right">Nueva contraseña
                        </label>
                        <div class="col-md-7">
                            <input
                                id="field-password"
                                name="password"
                                type="password"
                                class="form-control"
                                title="Al menos un número y una letra minúscula, y al menos 8 caractéres"
                                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                                required
                                >
                        </div>
                    </div>
            
                <div class="form-group row">
                    <label for="passconf" class="col-md-5 col-form-label text-right">Confirmar contraseña</label>
                    <div class="col-md-7">
                        <input
                            id="field-passconf"
                            name="passconf"
                            type="password"
                            class="form-control"
                            title="Confirme la nueva contraseña"
                            pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            required
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7 offset-md-5">
                        <button class="btn btn-primary btn-block" type="submit">
                            Cambiar
                        </button>
                    </div>
                </div>
            </form>

            <div class="alert alert-success" role="alert" id="success_alert" style="display: none;">
                <i class="fa fa-check"></i>
                La contraseña fue cambiada exitosamente.
            </div>
            <div class="alert alert-danger" role="alert" id="error_alert" style="display: none;">
                <i class="fa fa-exclamation-triangle"></i>
                <span id="error_text"></span>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app_password',
            methods: {
                send_form: function(){
                    axios.post(url_api + 'accounts/change_password/', $('#password_form').serialize())
                    .then(response => {
                        if ( response.data.status == 1 ) {
                            toastr['success']('La contraseña fue cambiada exitosamente');
                        } else {
                            toastr['error'](response.data.error);
                        }
                        this.clean_form();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });  
                },
                clean_form: function(){
                    $('#field-current_password').val('');
                    $('#field-password').val('');
                    $('#field-passconf').val('');
                },
            }
        });
    </script>
<?php } ?>
