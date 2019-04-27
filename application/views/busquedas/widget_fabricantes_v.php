<?php
    $fabricantes = $this->App_model->fabricantes();
?>

<div class="brands_products">
    <h2 class="title">Editorial/Marca</h2>
    <div class="brands-name">
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($fabricantes->result() as $row_fabricante) : ?>
                <?php
                    $busqueda_str_fab = str_replace('fab=','nofab=',$busqueda_str) . "fab={$row_fabricante->id}";
                    $texto_link = '<span class="pull-right">' . $row_fabricante->cant_productos . '</span>' .  $row_fabricante->nombre_fabricante;
                ?>
                <li>
                    <?php if ( $busqueda['fab'] == $row_fabricante->id ){ ?>
                        
                    <?php } ?>
                    <?= anchor("busquedas/productos/?{$busqueda_str_fab}", $texto_link, 'class="resaltar" title="Un título"') ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>

