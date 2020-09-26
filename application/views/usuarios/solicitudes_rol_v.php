<?php $this->load->view($vista_menu); ?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<?php
    
?>

<div class="bs-caja">
    <table class="table table-default">
        <thead>
            <th class="<?= $clases_col['usuario'] ?>">Usuario</th>
            <th class="<?= $clases_col['email'] ?>">E-mail</th>
            <th class="<?= $clases_col['estado'] ?>">Estado solicitud</th>
            <th class="<?= $clases_col['rol_actual'] ?>">Rol actual</th>
            <th class="<?= $clases_col['rol_requerido'] ?>">Rol requerido</th>
            <th class="<?= $clases_col['accion'] ?>"></th>
        </thead>

        <tbody>
            <?php foreach($solicitudes->result() as $row_solicitud) : ?>
                <?php
                    $row_usuario = $this->Pcrn->registro_id('usuario', $row_solicitud->usuario_id);
                    
                    $clase_estado = 'success';
                    if ( $row_solicitud->estado > 1 ) { $clase_estado = 'warning'; }
                ?>
                <tr>
                    <td class="<?= $clases_col['usuario'] ?>">
                        <?= anchor("usuarios/profile{$row_usuario->id}", $row_usuario->nombre . ' ' . $row_usuario->apellidos, 'class="" title=""') ?>
                    </td>
                    <td class="<?= $clases_col['email'] ?>">
                        <?= $row_usuario->email ?>
                    </td>
                    <td class="<?= $clase_estado ?> <?= $clases_col['estado'] ?>">
                        <?= $this->Item_model->nombre(43, $row_solicitud->estado) ?>
                    </td>
                    <td class="<?= $clases_col['rol_actual'] ?>">
                        <?= $this->Item_model->nombre(58, $row_usuario->rol_id) ?>
                    </td>
                    <td class="info <?= $clases_col['rol_requerido'] ?>">
                        <?= $this->Item_model->nombre(58, $row_solicitud->rol_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['accion'] ?>">
                        <?php if ( $row_solicitud->estado != 1 ) { ?>
                            <?= anchor("usuarios/aprobar_rol/{$row_solicitud->meta_id}", 'Aprobar', 'class="btn btn-success" title=""') ?>
                        <?php } ?>
                    </td>
                    
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>
