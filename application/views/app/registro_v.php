<?php
    $att_captcha = array(
        'name' => 'captcha',
        'id' => 'captcha',
        'placeholder' => 'escriba las letras que aparecen en la imagen',
        'required' => TRUE
    );
    
    $opciones_rol = $this->Item_model->opciones('categoria_id = 5 AND id_interno in (21,22,23)');    
?>

<section style="padding: 50px 0 50px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 signup-form">
                <h2 class="">Registro de Usuarios</h2>

                
                <form accept-charset="utf-8" method="POST" id="registro_form" action="<?= base_url('app/crear_usuario') ?>" class="form-horizontal">
                
                hola
                
                <input
                    type="text"
                    id="field-nombre"
                    name="nombre"
                    required
                    class="form-control"
                    placeholder="nombre"
                    title="nombre"
                    >
                <input
                    type="text"
                    id="field-apellidos"
                    name="apellidos"
                    required
                    class="form-control"
                    placeholder="apellidos"
                    title="apellidos"
                    >

                <input
                    type="text"
                    id="field-email"
                    name="email"
                    required
                    class="form-control"
                    placeholder="correo electrónico"
                    title="correo electrónico"
                    >
                
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

                </form>

                <div class="div2">
                <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
                </div>
            </div>
        </div>
    </div>
</section>

