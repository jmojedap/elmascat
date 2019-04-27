<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
    //Valores para formulario
        $valores_form['nombre'] = '';
        $valores_form['apellidos'] = '';
        $valores_form['email'] = '';
        $valores_form['fecha_nacimiento'] = '';
        
        if ( $this->session->flashdata('valores_form') ) { $valores_form = $this->session->flashdata('valores_form'); }

    $att_form = array(
        'class' => 'form-horizontal_'
    );

    $nombre = array(
        'name' => 'nombre',
        'id' => 'campo-nombre',
        'value' => $valores_form['nombre'],
        'class' => 'form-control input1',
        'placeholder' => 'nombre',
        'required' => TRUE,
        'autofocus' => TRUE
    );

    $apellidos = array(
        'name' => 'apellidos',
        'id' => 'apellidos',
        'value' => $valores_form['apellidos'],
        'class' => 'form-control input1',
        'placeholder' => 'apellidos',
        'required' => TRUE
    );

    $email = array(
        'name' => 'email',
        'id' => 'email',
        'value' => $valores_form['email'],
        'class' => 'form-control input1',
        'type' => 'email',
        'placeholder' => 'correo electrónico',
        'required' => TRUE
    );
    
    $fecha_nacimiento = array(
        'name' => 'fecha_nacimiento',
        'id' => 'campo-fecha_nacimiento',
        'value' => $valores_form['fecha_nacimiento'],
        'class' => 'form-control bs_datepicker input1',
        'placeholder' => 'fecha de nacimiento (AAAA-MM-DD)',
        'required' => TRUE
    );
?>

<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="page-title">
    <h2>Registro de usuarios</h2>
</div>

<div class="row div2">
    
    <div class="col-md-6 caja1">

        <?= form_open($destino_form, $att_form); ?>
            <div class="div1">
                <?= form_input($nombre); ?>
            </div>
            <div class="div1">
                <?= form_input($apellidos); ?>
            </div>

            <?= form_input($email); ?>
        
            <div class="div2">
                <?= form_input($fecha_nacimiento); ?>
            </div>
        
            <div class="div2">
                <input type="radio" name="sexo" value="1" required> Mujer
                <input type="radio" name="sexo" value="2"> Hombre
            </div>
        
            <div class="div2">
                <div class="g-recaptcha" data-sitekey="6LfC3TQUAAAAADqKhDASMgTilTj5fJJmwyp6Rj-m"></div>
            </div>

            <p>
                <input type="checkbox" name="condiciones" value="1" required style="display: inline; height: 15px; width: 15px;"/>
                Acepto los <?= anchor('posts/leer/17/terminos-de-uso', 'Términos de uso', 'target="_blank"') ?> de Districatólicas S.A.S.
            </p>

            <button type="submit" class="button">
                <span>
                    Registrarme
                </span>
            </button>
        <?= form_close(); ?>

        <div class="div2">
            <?php if ( $this->session->flashdata('mensajes') ):?>
                <?php $mensajes = $this->session->flashdata('mensajes'); ?>
                <?php foreach ($mensajes as $mensaje) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $mensaje ?>
                    </div>
                <?php endforeach ?>

            <?php endif ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <table width="100%" class="table table-default">
            <tbody>
                <tr>
                    <td width="40%">
                        <h4 class="text-success">
                            <i class="fa fa-user"></i>
                            Datos básicos
                        </h4>
                    </td>
                    <td>
                        Escriba sus datos personales, nombre, apellidos.
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4 class="text-warning">
                            <i class="fa fa-envelope-o"></i>
                            Correo electrónico
                        </h4>
                    </td>
                    <td>
                        Use preferiblemente una cuenta de correo diferente a <b class="text-danger">@hotmail.com</b>,
                        ya que el mensaje de activación podría no llegar a su bandeja de entrada.
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4 class="">
                            <i class="fa fa-calendar-check-o"></i>
                            Fecha de nacimiento
                        </h4>
                    </td>
                    <td>
                        Escriba su fecha de nacimiento con el formato AAAA-MM-DD, por ejemplo "1991-03-12".
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4>
                            <i class="fa fa-check-square-o"></i>
                            No soy un robot
                        </h4>
                    </td>
                    <td>
                        Activa la casilla "No soy un robot" para verificar el uso seguro de la página.
                    </td>
                </tr>

                <tr>
                    <td>
                        <h4>
                            <i class="fa fa-file-text-o"></i>
                            Términos de uso
                        </h4>
                    </td>
                    <td>
                        Lee >>aquí<< los términos de uso de este sitio. Activa la casilla de aceptación.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>