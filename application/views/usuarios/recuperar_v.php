<?php

    $mensaje = NULL;

    if ( $resultado == 'no_encontrado' ) {
        $clase = 'alert-warning';
        $mensaje = '<i class="fa fa-info-circle"></i> No existe ningún usuario con el correo electrónico enviado.';
    }
    
    if ( $resultado == 'enviado' ) {
        $clase = 'alert-success';
        $mensaje = '<i class="fa fa-check-circle"></i> Enviamos un mensaje a su correo electrónico para restaurar se cuenta. Revise también la carpeta de correo no deseado.';
    }
    
?>

<div class="row">
    <div class="col-md-12">
        <div class="page-title">
            <h2 class="title"><i class="fa fa-user resaltar"></i> Recuperación de cuentas</h2>
        </div>
        <div class="login-form"><!--login form-->
            <p>Ingrese su dirección de correo electrónico, enviaremos un mensaje para recuperar su cuenta de usuario.</p>
            <?= form_open("usuarios/recuperar_e", $att_form) ?>
                <div class="div1">
                    <input name="email" type="text" class="form-control" required="required" placeholder="Correo electrónico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
                </div>
                
                <button type="submit" class="btn-polo w3">Enviar</button>
            <?= form_close('') ?>
        </div><!--/login form-->

        <div class="div2">
            <?php if ( ! is_null($mensaje) ):?>
                <div class="alert <?= $clase ?>" role="alert">
                    <?= $mensaje ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>