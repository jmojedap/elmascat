<?php
    $this->load->model('Busqueda_model');
    $lista_id = $this->App_model->valor_opcion(10); //Recomendados
    $busqueda_recomendados['list'] = $lista_id;    
    $recomendados[1] = $this->Busqueda_model->productos($busqueda_recomendados, 3, 0);
    $recomendados[2] = $this->Busqueda_model->productos($busqueda_recomendados, 3, 3);
    
    //Imágenes
        $src_alt = URL_IMG . 'app/250px_producto.png';   //Imagen alternativa
        $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
        $att_img['style'] = 'width: 125px;';
?>

<div class="recommended_items"><!--recommended_items-->
    <h2 class="title text-center">Recomendados</h2>

    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active" style="height: ">
                <?php foreach ($recomendados[1]->result() as $row_producto) : ?>
                    <?php
                        $att_img['src'] = RUTA_UPLOADS . $row_producto->carpeta . '250px_' . $row_producto->nombre_archivo;
                    ?>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <?= img($att_img); ?>
                                    <h2>$<?= $row_producto->precio ?></h2>
                                    <p><?= $row_producto->nombre_producto ?></p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Al carrito</a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            
            <div class="item" style="height: ">
                <?php foreach ($recomendados[2]->result() as $row_producto) : ?>
                    <?php
                        $att_img['src'] = RUTA_UPLOADS . $row_producto->carpeta . '250px_' . $row_producto->nombre_archivo;
                    ?>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <?= img($att_img); ?>
                                    <h2>$<?= $row_producto->precio ?></h2>
                                    <p><?= $row_producto->nombre_producto ?></p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Al carrito</a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            
        </div>
        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
            <i class="fa fa-caret-left"></i>
        </a>
        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
            <i class="fa fa-caret-right"></i>
        </a>			
    </div>
</div><!--/recommended_items-->