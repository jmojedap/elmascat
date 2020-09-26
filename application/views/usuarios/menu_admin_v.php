<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    if ( $seccion == 'cambiar_contrasena' ) {$clases['contrasena'] = 'active'; }
?>

<ul class="nav nav-tabs">
    <li role="presentation" class="<?= $clases['info'] ?>">
        <?= anchor("usuarios/profile{$row->id}", 'Información') ?>
    </li>
    <li role="presentation" class="<?= $clases['direcciones'] ?>">
        <?= anchor("usuarios/direcciones/{$row->id}", 'Direcciones') ?>
    </li>

    <li role="presentation" class="<?= $clases['editar'] ?>">
        <?= anchor("usuarios/editar/edit/{$row->id}", '<i class="fa fa-pencil"></i> Editar') ?>
    </li>
</ul>