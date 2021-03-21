<?php

    $att_username = array(
        'id'     => 'email',
        'name'   => 'username',
        'class'  => 'input-text required-entry',
        'value'  => '',
        'autofocus'  => TRUE,
        'required'  => TRUE,
        'placeholder'   => 'nombre@ejemplo.com',
        'title' => 'Dirección de correo electrónico'
    );

    $att_password = array(
        'id'     => 'pass',
        'name'   => 'password',
        'class'  => 'input-text required-entry validate-password',
        'value'  => '',
        'required'  => TRUE,
        'title' => 'Digite su contraseña'
    );

    //Mensajes de validación
    $mensajes = $this->session->flashdata('mensajes');
    
    //Mensajes de login
        $infoplus = $this->uri->segment(3);
        
        $mensajes_plus = array(
            'distribuidor' => 'Para solicitar ser Distribuidor debe registrarse como cliente.'
        );
?>

<?php if ( strlen($infoplus) ) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        <?= $mensajes_plus[$infoplus] ?>
    </div>
<?php } ?>

<div class="account-login">
    <div class="page-title">
        <h2>Ingresar o Registrarse</h2>
    </div>
    <fieldset class="col2-set">
        
        <legend>Login or Create an Account</legend>
        <div class="col-1 new-users"><strong>Nuevos clientes</strong>
            <div class="content">
                <p>Al crear una cuenta en nuestra tienda, usted será podrá realizar el proceso de compra más rápidamente, almacenar múltiples direcciones de envío, ver y realizar un seguimiento de sus pedidos en su cuenta y más.</p>
                <div class="buttons-set">
                    <button onclick="window.location = '<?= base_url('accounts/signup') ?>';" class="button create-account" type="button"><span>Crear una cuenta</span></button>
                </div>
            </div>
        </div>
        
        <div class="col-2 registered-users"><strong>Clientes registrados</strong>
            <div class="content">
                <p>Si usted tiene una cuenta, por favor ingrese con sus datos.</p>
                <?= form_open("app/validar_login") ?>
                    <ul class="form-list">
                        <li>
                            <label for="email">Correo electrónico <span class="required">*</span></label>
                            <br>
                            <?= form_input($att_username) ?>
                        </li>
                        <li>
                            <label for="pass">Contraseña <span class="required">*</span></label>
                            <br>
                            <?= form_password($att_password) ?>
                        </li>
                    </ul>

                    <p class="required">* Campos requeridos</p>
                    <div class="buttons-set">
                        <button id="send2" name="send" type="submit" class="button login"><span>Ingresar</span></button>

                        <?= anchor("usuarios/recuperar", 'Olvidé mi contraseña', 'class="pull-right" title=""') ?> 
                    </div>
                <?= form_close('') ?>
            </div>
            <?php if ( count($mensajes) > 0 ): ?>
                <br/>
                <div class="div2">
                    <?php foreach ($mensajes as $mensaje) : ?>
                            <?php if ( ! is_null($mensaje) ) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $mensaje ?>
                                </div>                            
                            <?php } ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </fieldset>
</div>