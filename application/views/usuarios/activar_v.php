<?php

    //Textos
        $textos['subtitulo'] = 'Activación de cuenta';
        $textos['boton'] = 'Activar mi cuenta';
        
        if ( $tipo_activacion == 'restaurar' )
        {
            $textos['subtitulo'] = 'Restauración de contraseña';
            $textos['boton'] = 'Enviar';
        } 
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var cod_activacion = '<?= $cod_activacion ?>';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#activar_form').submit(function(){
            $("#error_not_match").hide();
            if ( $('#field-password').val() == $("#field-passconf").val() ) {
                activar_usuario();
            } else {
                $("#error_not_match").show('fast');
            }
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function activar_usuario(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'usuarios/activar_e/' + cod_activacion,
            data: $('#activar_form').serialize(),
            success: function(response){
                if (response.user_id > 0) {
                    //window.location = app_url + 'usuarios/mi_perfil/';
                    $('#card_form').hide();
                    $('#success_activation').show('fast');
                } else {
                    console.log('Ocurrió un error en la activación');
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="text-center">
            <h2 class="resaltar"><?= $row->nombre . ' ' . $row->apellidos ?></h2>
            <h3 class="suave"><?= $textos['subtitulo'] ?></h3>
            <p class="suave"><?= $row->username ?></p>
            <p>Establece tu contraseña para DistriCatolicas.com</p>
        </div>
            
        <div class="mb-2" id="card_form">
            <form method="post" accept-charset="utf-8" id="activar_form">
                <div class="form-group">
                    <input
                        type="password"
                        id="field-password"
                        name="password"
                        required
                        autofocus
                        pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                        class="form-control"
                        placeholder="contraseña"
                        title="8 caractéres o más, al menos un número y una letra minúscula"
                        >
                </div>
                <div class="form-group">
                    <input
                        type="password"
                        id="field-passconf"
                        name="passconf"
                        required
                        class="form-control"
                        placeholder="confirma tu contraseña"
                        title="confirma tu contraseña"
                        minlength="8"
                        >
                </div>
                <div class="form-group">
                    <button class="btn btn-polo-lg btn-block" type="submit">
                        <?= $textos['boton'] ?>
                    </button>
                </div>
            </form>
        </div>
        <div class="alert alert-danger text-center" id="error_not_match" style="display: none;">
            Las contraseñas no coinciden
        </div>

        <div id="success_activation" class="alert alert-success text-center" style="display: none;">
            <p>
                <i class="fa fa-check fa-2x"></i>
            </p>
            <p class="mb-2">
                Tu cuenta fue activada correctamente
            </p>
            <p>
                <a href="<?= base_url("usuarios/books") ?>" class="btn btn-success btn-block">
                    Continuar
                </a>
            </p>
        </div>
    </div>
</div>