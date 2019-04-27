<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    if ( $seccion == 'crear_categoria' ) { $clases['categorias'] = 'active'; }
?>

<ul class="nav nav-tabs">
    <li role="presentation" class="<?= $clases['sis_opcion'] ?>">
        <?= anchor("datos/sis_opcion", 'General') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['categorias'] ?>">
        <?= anchor("datos/categorias", 'Categorías') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['palabras_clave'] ?>">
        <?= anchor("datos/palabras_clave", 'Palabras clave') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['listas'] ?>">
        <?= anchor("datos/listas", '<i class="fa fa-list"></i> Listas') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['metadatos'] ?>">
        <?= anchor("datos/metadatos", 'Metadatos') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['fabricantes'] ?>">
        <?= anchor("datos/fabricantes", 'Fabricantes') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['extras'] ?>">
        <?= anchor("datos/extras", 'Extras pedidos') ?>
    </li>
    
    <li role="presentation" class="<?= $clases['estado_pedido'] ?>">
        <?= anchor("datos/estado_pedido", 'Estados pedido') ?>
    </li>
</ul>