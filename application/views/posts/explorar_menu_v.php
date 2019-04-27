<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
?>

<ul class="nav nav-tabs">
    <li class="<?= $clases['explorar'] ?>">
        <?= anchor("posts/explorar/", '<i class="fa fa-list-alt"></i> Explorar') ?>
    </li>
    <li class="<?= $clases['nuevo'] ?>">
        <?= anchor("posts/nuevo/add/", '<i class="fa fa-plus"></i> Nuevo') ?>
    </li>
</ul>