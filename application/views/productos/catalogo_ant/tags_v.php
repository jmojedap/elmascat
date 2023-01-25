<?php
    $get_str = $this->Pcrn->get_str('tag,q,per_page');
?>

<div class="side-nav-categories">
    <div class="block-title"> Temas y Categorías </div>
    <!--block-title--> 
    <!-- BEGIN BOX-cateGORY -->
    <div class="box-content box-category">
        <ul>
            <?php foreach ($tags->result() as $row_tag) : ?>
                <?php
                    $tags_n2 = $this->App_model->tags($row_tag->id);
                    
                    $tag_class = '';
                    $clase_signo = 'plus';
                    
                    if ( $busqueda['tag'] == $row_tag->id ) {
                        $tag_class = 'active';
                        $clase_signo = 'minus';
                    }
                ?>
                <li class="<?= $tag_class ?>">
                    <?= anchor("productos/catalogo/?tag={$row_tag->id}", $row_tag->item) ?>
                    
                    <?php if ( $tags_n2->num_rows() > 0 ){ ?>
                        <span class="subDropdown <?= $clase_signo ?>"></span>
                    <?php } ?>
                    
                    <?php if ( $tags_n2->num_rows() > 0 ){ ?>
                        <ul class="">
                            <?php foreach ($tags_n2->result() as $row_tag_n2) : ?>
                                <?php  
                                    $tag_class_n2 = '';
                                    if ( $busqueda['tag'] == $row_tag_n2->id ) {
                                        $tag_class_n2 = 'active';
                                    }
                                    
                                    $busqueda_str_subtag = str_replace('tag=','notag=',$busqueda_str) . "tag={$row_tag_n2->id}";
                                ?>
                                <li class="<?= $tag_class_n2 ?>">
                                    <?= anchor("productos/catalogo/?tag={$row_tag_n2->id}", $row_tag_n2->item) ?>
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