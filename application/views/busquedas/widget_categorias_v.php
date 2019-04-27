<?php
    $categorias = $this->App_model->categorias();
?>

<div class="side-nav-categories">
    <div class="block-title"> Categorías </div>
    <!--block-title--> 
    <!-- BEGIN BOX-CATEGORY -->
    <div class="box-content box-category">
        <ul>
            <?php foreach ($categorias->result() as $row_categoria) : ?>
                <?php
                    $categorias_n2 = $this->App_model->categorias($row_categoria->id);
                    $busqueda_str_cat = str_replace('cat=','nocat=',$busqueda_str) . "cat={$row_categoria->id}";
                    
                    $clase_cat = '';
                    $clase_signo = 'plus';
                    
                    if ( $busqueda['cat'] == $row_categoria->id ) {
                        $clase_cat = 'active';
                        $clase_signo = 'minus';
                    }
                ?>
                <li class="<?= $clase_cat ?>">
                    <?= anchor("busquedas/productos/?{$busqueda_str_cat}", $row_categoria->item) ?>
                    
                    <?php if ( $categorias_n2->num_rows() > 0 ){ ?>
                        <span class="subDropdown <?= $clase_signo ?>"></span>
                    <?php } ?>
                    
                    
                    
                    <?php if ( $categorias_n2->num_rows() > 0 ){ ?>
                        <ul class="">
                            <?php foreach ($categorias_n2->result() as $row_categoria_n2) : ?>
                                <?php  
                                    $clase_cat_n2 = '';
                                    if ( $busqueda['cat'] == $row_categoria_n2->id ) {
                                        $clase_cat_n2 = 'active';
                                    }
                                    
                                    $busqueda_str_subcat = str_replace('cat=','nocat=',$busqueda_str) . "cat={$row_categoria_n2->id}";
                                ?>
                                <li class="<?= $clase_cat_n2 ?>">
                                    <?= anchor("busquedas/productos/?{$busqueda_str_subcat}", $row_categoria_n2->item) ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                        <!--level0--> 
                    <?php } ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <!--box-content box-category--> 
</div>