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

<div class="center_box_450">
    <div class="page-title">
        <h2 class="title"><i class="fa fa-user text-primary"></i> Recuperación de cuentas</h2>
    </div>
    <div class="box_1">
        <p>Ingresa tu dirección de correo electrónico, enviaremos un mensaje para restaurar la contraseña de tu cuenta de usuario.</p>
        <?= form_open("accounts/recovery_email", $att_form) ?>
            <div class="form-group">
                <input name="email" type="text" class="form-control" required="required" placeholder="Correo electrónico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
            </div>
            
            <button type="submit" class="btn-polo w120p">Enviar</button>
        <?= form_close('') ?>
    </div>

    <div class="mb-2">
        <?php if ( ! is_null($mensaje) ):?>
            <div class="alert <?= $clase ?>" role="alert">
                <?= $mensaje ?>
            </div>
        <?php endif ?>
    </div>
</div>
