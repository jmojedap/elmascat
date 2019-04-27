<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );

    $nombre = array(
        'name' => 'nombre',
        'id' => 'nombre',
        'value' => set_value('nombre'),
        'class' => '',
        'placeholder' => 'nombres',
        'required' => TRUE
    );


    $apellidos = array(
        'name' => 'apellidos',
        'id' => 'apellidos',
        'value' => set_value('apellidos'),
        'placeholder' => 'apellidos',
        'required' => TRUE
    );

    $email = array(
        'name' => 'email',
        'id' => 'email',
        'value' => set_value('email'),
        'placeholder' => 'correo electrónico',
        'required' => TRUE
    );

    $att_captcha = array(
        'name' => 'captcha',
        'id' => 'captcha',
        'placeholder' => 'escriba las letras que aparecen en la imagen',
        'required' => TRUE
    );
    
    $opciones_rol = $this->Item_model->opciones('categoria_id = 5 AND id_interno in (21,22,23)');

    $submit = array(
        'value' => 'Registrarme',
        'class' => 'button'
    );
    
    
?>

<section style="padding: 50px 0 50px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 signup-form">
                <h2 class="">Registro de Usuarios</h2>

                <?= form_open('app/crear_usuario', $att_form); ?>
                <?= form_input($nombre); ?>
                <?= form_input($apellidos); ?>
                <?= form_input($email); ?>
                <?= form_dropdown('rol_id', $opciones_rol, NULL, 'class="form-control"'); ?>

                <div class="div2">
                    <?= $captcha['image'] ?>
                </div>

                <?= form_input($att_captcha); ?>
                <p>
                    <input type="checkbox" name="condiciones" value="1" style="display: inline; height: 15px; width: 15px;"/>
                    Acepto los <?= anchor('posts/leer/17/terminos-de-uso', 'Términos de uso', 'target="_blank"') ?> de Districatólicas S.A.S.
                </p>

                <button type="submit" class="btn btn-default">Registrarme</button>

                <?= form_close(); ?>

                <div class="div2">
                <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
                </div>
            </div>

            <div class="col-md-6">

            </div>

        </div>
    </div>
</section>

