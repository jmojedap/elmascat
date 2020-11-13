<?php
    $att_form = array(
        
    );

    $password_actual = array(
        'name'  =>  'password_actual',
        'id'    =>  'password_actual',
        'class' =>  'form-control',
        'autofocus' =>  TRUE,
        'placeholder' =>  'Contraseña actual',
        'required' => TRUE
    );
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'placeholder' =>  'Escriba su nueva contraseña',
        'pattern' => '(?=.*\d)(?=.*[a-z]).{8,}',
        'required' => TRUE,
        'title' => 'Debe tener al menos un número y una letra minúscula, y al menos 8 caractéres'
    );
    
    $passconf = array(
        'name'  =>  'passconf',
        'id'    =>  'passconf',
        'class' =>  'form-control',
        'placeholder' =>  'Repita su nueva contraseña',
        'required' => TRUE
    );
    
    $submit = array(
        'value' =>  'Guardar',
        'class' =>  'btn btn-success w120p'
    )

?>

<div class="card card-default center_box_450">
    <div class="card-body">
        <div class="row">   
            <div class="col-md-12">
                <?= form_open('usuarios/cambiar_contrasena'); ?>
                    <?= form_hidden('id', $usuario_id_cambio); ?>

                    <div class="form-group">
                        <?= form_password($password_actual); ?>    
                    </div>

                    <div class="form-group">
                        <?= form_password($password); ?>
                    </div>

                    <div class="form-group">
                        <?= form_password($passconf); ?>
                    </div>

                    <div class="form-group">
                        <?= form_submit($submit) ?>        
                    </div>

                <?= form_close();?>
            </div>

            <div class="col-md-12">
                <?php if ( validation_errors() ):?>
                    <?= validation_errors('<div class="alert alert-warning" role="alert">', '</div>') ?>
                <?php endif ?>

                <?php if ( $resultado ):?>
                    <div class="alert alert-success" role="alert">
                        Su contraseña fue cambiada exitosamente
                    </div>
                <?php endif ?>
            </div>

        </div>   

    </div>
</div>






    

    
    
    
    





            
        
        

