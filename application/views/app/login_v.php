<?php

    $username = array(
        'name'  =>  'username',
        'id'    =>  'username',
        'value' =>  '',
        'required' =>  TRUE,
        'placeholder' =>    'Usuario',
        'class' =>  'form-control'
    );
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'placeholder' =>    'Contraseña',
        'required' =>  TRUE
    );
    
    $submit = array(
        'value' =>  'Iniciar sesión',
        'class' => 'btn btn-success btn-block'
    );
    
    

?>        

<script type="text/javascript">
    //Poner foco al cargar la página en el primer input del formulario
    $(document).ready(function() {
        $(":input:first").focus();
    });
</script>

    
<div class="row">
    
    <div class="col-md-4 col-md-offset-4">
        
        <div class="" style="text-align: center; padding-top: 60px; padding-bottom: 80px;">
            <?= img('media/images/app/logo_med.png') ?>
        </div>

        <div>

            <?= form_open('app/verificar_login'); ?>

                <div class="form-group">
                    <?= form_input($username); ?>    
                </div>

                <div class="form-group">
                    <?= form_password($password); ?>
                </div>

                <div class="">
                    <?= form_submit($submit) ?>    
                </div>

            <?= form_close(); ?>
        </div>
        
        <div class="">
            <?php if ( validation_errors() ):?>
                <hr/>
                <?= validation_errors ('<div class="alert alert-danger" role="alert">', '</div>') ?>
            <?php endif ?>
        </div>
        
    </div>
    
    

        

    
</div>