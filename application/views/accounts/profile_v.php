<?php

    //Gestión de solicitud de rol de distribuidor
        $mostrar_solicitud = 0;
        if ( $row->id == $this->session->userdata('usuario_id') && $row->rol_id == 21  ) { $mostrar_solicitud = 1; }
        //if ( $this->uri->segment(3) == 'test'  ) { $mostrar_solicitud = 1; }  //Para pruebas

    $wa_message = "Hola *{$row->nombre}*, te saludamos de DistriCatolicas.com - Minutos de Amor. ";
    $wa_message .= ' Por favor ingresa al siguiente link para activar tu cuenta de usuario:';

    $wa_message = urlencode($wa_message);
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?= $row->id ?>';

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
            url: url_app + 'usuarios/set_activation_key/' + user_id,
            success: function(response){
                $('#activation_key').html(url_app + 'usuarios/activar/' + response);
                toastr['success']('Se actualizó la clave de activación');
            }
        });
    }
</script>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="<?= $row->url_image ?>" alt="Imagen de perfil de usuario" onerror="this.src='<?php echo URL_IMG ?>users/user.png'">
                <div class="card-body">
                    <h3 class="text-center"><?= $row->display_name ?></h3>
                    <p class="text-muted text-center"><?= $this->App_model->nombre_item($row->rol_id, 1, 58) ?></p>
                    <p class="text-center">
                        <?php if ( $row->estado == 1 ) { ?>
                            <i class="fa fa-check-circle text-success"></i> Activo
                        <?php } else { ?>
                            <i class="far fa-circle text-danger"></i> Inactivo
                        <?php } ?>
                    </p>
                    <?php if ( $this->session->userdata('role') <= 1 ) { ?>
                        <p class="text-center">
                            <a href="<?= base_url("admin/ml/{$row->id}") ?>" class="btn btn-primary btn-lg" title="Acceder como este usuario">
                                <i class="fa fa-sign-in-alt"></i>
                                Acceder
                            </a>
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td class="text-right" width="33%"><span class="text-muted">Nombre</span></td>
                        <td width="67%"><?= $row->display_name ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                        <span class="text-muted">
                            Documento
                        </span></td>
                        <td>
                            <?= $row->no_documento ?> &middot;
                            <?= $this->Item_model->nombre(53, $row->tipo_documento_id);  ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Nombre de usuario</span></td>
                        <td><?= $row->username ?></td>
                        
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Correo electrónico</span></td>
                        <td><?= $row->email ?></td>
                        
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Sexo</span></td>
                        <td><?= $this->Item_model->nombre(59, $row->sexo) ?></td>
                        
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Fecha de nacimiento</span></td>
                        <td>
                            <?= $this->Pcrn->fecha_formato($row->fecha_nacimiento, 'Y-M-d') ?>
                            &middot;
                            <span class="text-muted">
                                <?= $this->pml->ago($row->fecha_nacimiento, false) ?>
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Ciudad</span></td>
                        <td><?= $this->App_model->nombre_lugar($row->ciudad_id, 'CR') ?></td>
                        
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Dirección</span></td>
                        <td><?= $row->address ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Celular</span></td>
                        <td>
                            <?= $row->celular ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Otros teléfonos</span></td>
                        <td>
                            <?= $row->telefono ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Transportadora</span></td>
                        <td><?= $this->Item_model->nombre(183, $row->shipping_system) ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Notas sobre el usuario</span></td>
                        <td><?= $row->notas ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Actualizado</span></td>
                        <td title="<?= $row->editado ?>">
                            <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d') ?>
                            <span class="text-muted">
                                (<?= $this->Pcrn->tiempo_hace($row->editado) ?>)
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Registrado desde</span></td>
                        <td title="<?= $row->creado ?>">
                            <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
                            <span class="text-muted">
                                (<?= $this->Pcrn->tiempo_hace($row->creado) ?>)
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>