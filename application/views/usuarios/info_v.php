<?php

    //Gestión de solicitud de rol de distribuidor
        $mostrar_solicitud = 0;
        if ( $row->id == $this->session->userdata('usuario_id') && $row->rol_id == 21  ) { $mostrar_solicitud = 1; }
        //if ( $this->uri->segment(3) == 'test'  ) { $mostrar_solicitud = 1; }  //Para pruebas
?>

<?php 
    $src_perfil = URL_IMG . 'app/user_mid.jpg';
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?php echo $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_set_activation_key').click(function(){
            set_activation_key();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    function set_activation_key(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'usuarios/set_activation_key/' + user_id,
            success: function(response){
                $('#activation_key').html(app_url + 'usuarios/activar/' + response);
                toastr['success']('Se actualizó la clave de activación');
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-4 hidden-xs hidden-sm">
        <div class="panel">
            <div class="panel-body">
                <img class="profile-user-img img-responsive img-circle" src="<?= $src_perfil ?>" alt="Imagen de perfil de usuario">
                <h3 class="text-center"><?= $row->nombre . ' ' . $row->apellidos ?></h3>
                <p class="text-muted text-center"><?= $this->App_model->nombre_item($row->rol_id, 1, 58) ?></p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Documento</b>
                        <span class="pull-right"><?= $row->no_documento ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Fecha Nacimiento</b>
                        <span class="pull-right"><?= $row->fecha_nacimiento ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Edad</b>
                        <span class="pull-right"><?= $this->Pcrn->tiempo_hace($row->fecha_nacimiento) ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Perfil</b>
                        <span class="pull-right"><?= $this->Item_model->nombre(58, $row->rol_id) ?></span>
                    </li>

                    <li class="list-group-item">
                        <b>Teléfono</b>
                        <span class="pull-right"><?= $row->telefono ?></span>
                    </li>
                    
                    <li class="list-group-item">
                        <b>Celular</b>
                        <span class="pull-right"><?= $row->celular ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <table class="table table-striped bg-blanco">
            <tbody>
                <tr>
                    <td class="text-right" width="25%"><span class="suave">Nombre</span></td>
                    <td width="75%"><?= $row->nombre ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="suave">Apellidos</span></td>
                    <td><?= $row->apellidos ?></td>
                    
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Nombre de usuario</span></td>
                    <td><?= $row->username ?></td>
                    
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Correo electrónico</span></td>
                    <td><?= $row->email ?></td>
                    
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Sexo</span></td>
                    <td><?= $this->Item_model->nombre(59, $row->sexo) ?></td>
                    
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Tipo de usuario</span></td>
                    <td><?= $this->Item_model->nombre(58, $row->rol_id) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Fecha de nacimiento</span></td>
                    <td><?= $this->Pcrn->fecha_formato($row->fecha_nacimiento, 'Y-M-d') ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Dirección</span></td>
                    <td><?php echo $row->address ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="suave">Teléfono</span></td>
                    <td><?= $row->telefono ?></td>
                    
                </tr>
                <tr>
                    <td class="text-right"><span class="suave">Celular</span></td>
                    <td><?= $row->celular ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="suave">Página Web</span></td>
                    <td>
                        <?= anchor($this->Pcrn->preparar_url($row->url), $this->Pcrn->texto_url($row->url), 'target="_blank"') ?>
                    </td>
                    
                </tr>
                <?php if ( $this->session->userdata('role') <= 2  ) { ?>
                    <tr>
                        <td class="text-right">
                            <button class="btn btn-primary btn-sm" id="btn_set_activation_key">
                                <i class="fa fa-redo-alt"></i>
                            </button>
                            <span class="text-muted">Activación</span>
                        </td>
                        <td>
                            <span id="activation_key"></span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>



<?php if ( $mostrar_solicitud ) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col col-md-8">
                    <p>
                        Si usted hace sus compras en <span class="resaltar">Districatolicas.com</span> para su librería o negocio puede
                        solicitar que su cuenta se asigne con perfil de <span class="resaltar">Distribudor</span>.
                        Un distribuidor de Districatólicas tiene acceso a descuentos especiales en algunas editoriales.
                    </p>
                    
                    <p>
                        Para hacer la solicitud debe haber registrado sus datos personales (<?= anchor("usuarios/editarme/edit/{$row->id}", 'Editar mis datos') ?>), incluyendo número de documento, dirección, teléfono y 
                        celular. Uno de nuestros asesores se contactará con usted para continuar con el proceso.
                    </p>
                    
                    
                </div>
                <div class="col col-md-4">
                    <?= anchor("usuarios/solicitar_rol/{$row->id}/22", '<i class="fa fa-user"></i> Solicitar perfil de Distribuidor', 'class="btn btn-success btn-block" title=""') ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>