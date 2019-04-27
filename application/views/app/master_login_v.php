<?php

    $username = array(
        'name'  =>  'username',
        'id'    =>  'username',
        'value' =>  '',
        'class' =>  'input1'
    );
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'input1'
    );
    
    $as_username = array(
        'name'  =>  'as_username',
        'id'    =>  'as_username',
        'value' =>  '',
        'class' =>  'input1'
    );
    
    $submit = array(
        'value' =>  'Iniciar sesión',
        'class' => 'button white'
    );

?>        

<script type="text/javascript">
    //Poner foco al cargar la página en el primer input del formulario
    $(document).ready(function() {
        $(":input:first").focus();
    });
</script>


    
<div class="container_12" id="contenido">
    <div class="grid_12 div2" style="margin-top: 100px;">

    </div>
    
    <div class="prefix_4 grid_5">
        
        

        <div class="div-gris">

            <span class="grande">Iniciar sesión</span>

            <?= form_open('app/verificar_master_login'); ?>

                <div class="div1" style="text-align: right;">
                    <a class="suave" href="http://www.pacarina.com" target="blank">Pacarina Media Lab</a>    
                </div>

                <div class="div1">
                    <span class="h-campo">Usuario</span>
                    <?= form_input($username); ?>    
                </div>

                <div class="div1">
                    <span class="h-campo">Contraseña</span>
                    <?= form_password($password); ?>
                </div>
            
                <div class="div1">
                    <span class="h-campo">Ingresar como Usuario</span>
                    <?= form_input($as_username); ?>    
                </div>

                <div class="div1">
                    <?= form_submit($submit) ?>    
                </div>

            <?= form_close(); ?>
        </div>

        <div class="div1 rojo">
            <?php if ( validation_errors() ):?>
                <?= validation_errors ('<h4 class="alerta-error">', '</h4>') ?>
            <?php endif ?>
        </div>
    </div>

        

    
</div>