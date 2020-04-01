<?php       
    //Fecha comparación nuevo producto
        $fecha_nuevo = $this->Pcrn->suma_fecha(date('Y-m-d H:i:s'), '-1 month');
?>

<ul class="products-grid">
    
    <?php foreach ($resultados->result() as $row_producto) : ?>
        <?php
            list($tamano_img['ancho'], $tamano_img['alto'], $tamano_img['tipo'], $tamano_img['atributos']) = getimagesize($src);
            
            //Según las proporciones de la imagen, alternar alto o ancho al 100%
            $img_style = '';
            if ( $tamano_img['ancho'] > $tamano_img['alto'] ) { $img_style = 'width: 100%; margin: 0 auto;'; }

            $link_visitar = base_url("productos/visitar/{$row_producto->id}/{$row_producto->slug}");
            
            $arr_precio = $this->Producto_model->arr_precio($row_producto->id);
            
            $en_promocion = 0;
            if ( $row_producto->precio > $arr_precio['precio'] ) { $en_promocion = 1; }
            
            $es_nuevo = 0;
            $segundos_creado = $this->Pcrn->segundos_lapso($row_producto->creado, $fecha_nuevo);
            if ( $segundos_creado < (2629800) ) { $es_nuevo = 1; }
        ?>
        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="col-item">
                
                <?php if ( $en_promocion ) { ?>
                    <div class="sale-label sale-top-right">Oferta</div>
                <?php } ?>
                    
                <?php if ( $es_nuevo ) { ?>
                    <div class="new-label new-top-right">Nuevo</div>
                <?php } ?>
                
                <div class="product-image-area">
                    <a class="product-image" title="<?php echo $row_producto->nombre_producto ?>" href="<?php echo $link_visitar ?>">
                        <div style="overflow: hidden; height: 380px; padding: 1px; background: #e1f5fe;">
                            <img
                                src="<?php echo URL_UPLOADS . $row_producto->carpeta . '500px_' . $row_producto->nombre_archivo; ?>"
                                class="img-responsive"
                                alt="Imagen producto"
                                onError="this.src='<?php echo URL_IMG . 'app/262px_producto.png' ?>'"
                                style="<?php echo $img_style ?>"
                            >
                        </div>
                    </a>
                    <div class="hover_fly">
                        <?php if ( $row_producto->cant_disponibles > 0 ){ ?>
                            <a class="exclusive ajax_add_to_cart_button" href="" title="Agregar al carrito de compras" data-producto_id="<?php echo $row_producto->id ?>">
                                <div>
                                    <i class="fa fa-shopping-cart"></i><span>Al carrito</span>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="info">
                    <div class="info-inner">
                        <div class="item-title">
                            <a title="<?php echo $row_producto->nombre_producto ?>" href="<?php echo $link_visitar ?>">
                                <?php echo word_limiter($row_producto->nombre_producto, 16) ?>
                            </a> 
                        </div>
                        <!--item-title-->
                        <div class="item-content">
                            <div class="price-box">
                                <?php if ( $en_promocion ) { ?>
                                    <span class="old-price">
                                        <span class="price">
                                            <?php echo $this->Pcrn->moneda($row_producto->precio); ?>
                                        </span>
                                    </span>
                                <?php } ?>
                                <p class="special-price">
                                    <span class="price">
                                        <?php echo $this->Pcrn->moneda($arr_precio['precio']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <!--item-content--> 
                    </div>
                    <!--info-inner-->

                    <div class="clearfix"> </div>
                </div>
            </div>
        </li>
    <?php endforeach ?>
</ul>