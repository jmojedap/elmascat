<?php
    $get_str = $this->Pcrn->get_str('fab,q,per_page');
?>

<div class="side-nav-categories">
    <div class="block-title"> Editoriales y Marcas </div>
    <!--block-title--> 
    <!-- BEGIN BOX-CATEGORY -->
    <div class="box-content box-category">
        <ul>
            <?php foreach ($fabricantes->result() as $row_fabricante) : ?>
                <?php
                    $clase_fab = '';
                    $clase_signo = 'plus';
                    
                    if ( $busqueda['fab'] == $row_fabricante->id ) {
                        $clase_fab = 'active';
                        $clase_signo = 'minus';
                    }
                ?>
                <li class="<?= $clase_cat ?>">
                    <?= anchor("productos/catalogo/?{$get_str}fab={$row_fabricante->id}", $row_fabricante->nombre_fabricante) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <!--box-content box-category--> 
</div>