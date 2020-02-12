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
        'class' => 'btn btn-success btn-block btn-flat'
    );

?>  

<!DOCTYPE html>
<html>
    <head>
        <?= $this->load->view('admin_lte/head') ?>
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="../../index2.html"><b>Districatólicas</b></a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Digite sus datos para iniciar sesión</p>
                
                <?= form_open('app/validar_login'); ?>
                    <div class="form-group has-feedback">
                        <?= form_input($username); ?>    
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <?= form_password($password); ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <?= form_submit($submit) ?>    
                    </div>
                <?= form_close(); ?>

                <a href="register.html" class="text-center">Registrarme</a>

            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <?= $this->load->view('admin_lte/foot_scripts') ?>
    </body>
</html>