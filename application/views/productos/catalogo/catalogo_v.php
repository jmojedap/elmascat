<?php
    $data_carrusel['imagenes'] = $this->App_model->imagenes_carrusel();

    $nombre_categoria = NULL;
    if ( strlen($busqueda['cat']) > 0 ) {
        $nombre_categoria = $this->App_model->nombre_item($busqueda['cat']);
    }
    
    $nombre_fabricante = NULL;
    if ( strlen($busqueda['fab']) > 0 ) {
        $nombre_fabricante = $this->App_model->nombre_item($busqueda['fab']);
    }
    
    
    //Ordenamiento
        $arr_orden['slug'] = 'Nombre';
        $arr_orden['precio'] = 'Precio';
        $arr_orden['puntaje'] = 'relevancia';
    
        $texto_orden = 'Orden';
        
        if ( strlen($busqueda['o']) > 0 ) {
            $texto_orden = $arr_orden[$busqueda['o']];
        }
    
        $get_str_sin_o = $this->Pcrn->get_str('o');
        $get_str_sin_ot = $this->Pcrn->get_str('ot');
?>

<?php $this->load->view('productos/catalogo/catalogo_js_v'); ?>

<div class="row">
    <section class="col-main col-sm-9 col-sm-push-3 wow bounceInUp animated animated" style="visibility: visible;">
        <?php if ( strlen($busqueda_str) == 0 ){ ?>
            <?php $this->load->view('productos/catalogo/carrusel_v'); ?>
        <?php } ?>
        
        <?php if ( ! is_null($nombre_categoria) ){ ?>
            <div class="category-title">
                <h1><?= $nombre_categoria ?></h1>
            </div>
        <?php } ?>
        
        <?php if ( ! is_null($nombre_fabricante) ){ ?>
            <div class="category-title">
                <h1>
                    <?= $nombre_fabricante ?>
                    <span class="suave" style="font-size: 0.61em;">
                        (<?= $cant_resultados ?> títulos)
                    </span>
                </h1>
            </div>
        <?php } ?>
        
        <div class="category-products">
            
            <div class="toolbar">
                <div id="sort-by">
                    <label class="left">Ordenar por: </label>
                    <ul>
                        <li>
                            <a href="#">
                                <?= $texto_orden ?>
                                <span class="right-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <?= anchor("productos/catalogo/?{$get_str_sin_o}o=slug", 'Nombre', 'class="" title=""') ?>
                                </li>
                                <li>
                                    <?= anchor("productos/catalogo/?{$get_str_sin_o}o=precio", 'Precio', 'class="" title=""') ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <?= anchor("productos/catalogo/?{$get_str_sin_ot}ot=asc", '<span class="glyphicon glyphicon-arrow-up"></span>', 'class="button-asc left" title="Orden ascendente"') ?>
                    <?= anchor("productos/catalogo/?{$get_str_sin_ot}ot=desc", '<span class="glyphicon glyphicon-arrow-down"></span>', 'class="button-asc left" title="Orden descendente"') ?>
                </div>
                <div class="pager">
                    <div class="pages">
                        <?= $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
            
            <?php $this->load->view('productos/catalogo/productos_grid_v'); ?>
            
            <div class="toolbar">
                <div class="pager">
                    <div class="pages">
                        <?= $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <aside class="col-left sidebar col-sm-3 col-xs-12 col-sm-pull-9 wow bounceInUp animated animated" style="visibility: visible;">
        <?php $this->load->view('productos/catalogo/tags_v'); ?>
        <?php $this->load->view('productos/catalogo/fabricantes_v'); ?>
    </aside>
</div>
    