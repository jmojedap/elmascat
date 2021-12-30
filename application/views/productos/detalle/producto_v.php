<?php
    $src = URL_UPLOADS . $image->carpeta . '262px_' . $image->nombre_archivo;
    $src = $
    $src_alt = URL_IMG . 'app/250px_producto.png';   //Imagen alternativa
    list($tamano_img['ancho'], $tamano_img['alto'], $tamano_img['tipo'], $tamano_img['atributos']) = getimagesize($src);

    $att_img['src'] = $src;
    $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
    $att_img['id'] = "img_producto";
    
    //Según las proporciones de la imagen, alternar alto o ancho al 100%
        $att_img['style'] = 'height: 100%; margin: 0 auto;';
        if ( $tamano_img['ancho'] > $tamano_img['alto'] ) { $att_img['style'] = 'width: 100%; margin: 0 auto;'; }
    
    //$att_img['width'] = '100%';
        
    //
        $contador_img = 0;  //Contador de imágenes

    //Cantidad disponibles
    $stock_status = 'Disponible';
    if ( $row->cant_disponibles == 0 ) { $stock_status = 'Agotado'; }
    if ( $this->session->userdata('role') <= 10 && $this->session->userdata('logged') ) { $stock_status = "{$row->cant_disponibles} disponbiles"; }
    
?>

<div id="product_details_app">
    <div class="col-main">
        <div class="row">
            <div class="product-view">
                <div class="product-essential">
                    <form action="#" method="post" id="product_addtocart_form">
                        <!-- EVITAR CARGUE DE IMÁGENES GRANDES TEMPORAL 2020-05-07 -->
                        <?php if ( $images->num_rows() > 0 ) { ?>
                            <div class="product-img-box col-lg-6 col-sm-6 col-xs-12">
                                <ul class="moreview" id="moreview">
                                    <?php foreach ($images->result() as $image) { ?>
                                        <?php 
                                            $contador_img++;
                                            $clase_li = "moreview_thumb thumb_{$contador_img} moreview_thumb_active";
                                            if ( $contador_img > 1 ) { $clase_li = "moreview_thumb thumb_{$contador_img}"; }
                                        ?>
                                        <li class="<?= $clase_li ?>">
                                            <img class="moreview_thumb_image" src="<?= $image->url ?>" alt="thumbnail mobile" onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'">
                                            <span class="roll-over">Mueva el mouse para hacer zoom</span>
                                            <img class="moreview_source_image" src="<?= $image->url ?>" alt="" onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'">
                                            <img  class="zoomImg" src="<?= $image->url ?>" alt="thumbnail" onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'">
                                        </li>
                                    <?php } ?>
                                </ul>
                                <div class="moreview-control">
                                    <a href="javascript:void(0)" class="moreview-prev"></a>
                                    <a href="javascript:void(0)" class="moreview-next"></a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="product-img-box col-lg-6 col-sm-6 col-xs-12">
                                <img id="img_producto" alt="Imagen producto" src="<?= $row->url_image ?>" class="w100pc"
                                    onError="this.src='<?= URL_IMG . 'app/250px_producto.png' ?>'">
                            </div>
                        <?php } ?>
                        
                        <div class="product-shop col-lg-6 col-sm-6 col-xs-12">
                            <div class="product-name">
                                <h1><?= $row_variacion->nombre_producto ?></h1>
                            </div>
                            
                            <p class="availability in-stock"><?= $stock_status ?></p>
                            
                            <div class="price-block">
                                <div class="price-box">
                                    <div class="special-price">
                                        <span class="price">$ {{ precio | currency }}</span>
                                        <span class="old-price" v-if="precio_nominal > precio">
                                            <span class="price">$ {{ precio_nominal | currency }}</span>
                                        </span>
                                        
                                        <div v-if="precio_nominal > precio">
                                            <i class="fa fa-check correcto"></i>
                                            {{ arr_precio.promocion_id | name_tipo_precio }}
                                        </div>
                                    </div>
                                </div>
                                <p v-if="wholesale_price_available()">
                                    <span class="wholesale_price">$ {{ arr_precios[3] | currency }}</span>
                                    al comprar <?= $min_quantity_wholesale ?> o más
                                </p>
                            </div>
                            
                            <?php if ( $variaciones->num_rows() > 0 ){ ?>
                                <?php $this->load->view('productos/detalle/variaciones_v'); ?>
                            <?php } ?>
                            
                            <div class="add-to-box">
                                <div v-if="product.cant_disponibles > 0">
                                    <div class="add-to-cart">
                                        <div v-show="!in_shopping_cart">
                                            <div class="pull-left" v-if="product.peso > 0">
                                                <div class="custom pull-left">
                                                    <button v-on:click="sum_quantity(-1)" class="reduced items-count" type="button"><i class="fa fa-minus">&nbsp;</i></button>
                                                    <input type="number" class="input-text qty" title="Cantidad" v-model="quantity" v-bind:max="product.cant_disponibles" min="0">
                                                    <button v-on:click="sum_quantity(1)" class="increase items-count" type="button"><i class="fa fa-plus">&nbsp;</i></button>
                                                </div>
                                            </div>
                                            <button class="button btn-cart" title="Agregar al carrito de compras" type="button" v-on:click="add_to_cart">
                                                <span><i class="fas fa-shopping-cart" style="margin-right: 5px;"></i> Al carrito</span>
                                            </button>
                                        </div>
                                        <a class="btn btn-success w100pc btn-lg" href="<?= URL_APP . 'pedidos/carrito' ?>" v-show="in_shopping_cart">
                                            <i class="fa fa-shopping-cart"></i> Ir al carrito
                                        </a>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="alert alert-warning">
                                        <i class="fa fa-info-circle"></i> Este producto se encuentra agotado
                                    </div>
                                </div>
                            </div>

                            <div class="short-description">
                                <h2>Descripción</h2>
                                <div class="product-description"><?= $row->descripcion ?></div>
                            </div>

                            <?php if ( $tags->num_rows() > 0 ) : ?>
                                <hr>
                                <span class="text-muted">Ver más en: </span>
                                <?php foreach ( $tags->result() as $tag ) : ?>
                                    <strong>
                                        <a href="<?= base_url("productos/catalogo/?tag={$tag->id}") ?>" class="text-primary">
                                            <?= $tag->nombre_tag ?>
                                        </a>
                                        <span class="text-muted">/</span>
                                    </strong>
                                <?php endforeach ?>
                            <?php endif; ?>
                            
                            <?php if ( $this->session->userdata('role') <= 10 && $this->session->userdata('logged') ) : ?>
                                <div style="margin-top: 10px;">
                                    <?= anchor("productos/edit/{$row->id}", '<i class="fa fa-pencil"></i> Editar', 'class="btn btn-warning" title="Editar producto"') ?>      
                                </div>
                            <?php endif ?>
                        </div>
                    </form>
                </div>
                <div class="product-collateral">
                    <div class="col-sm-12 wow bounceInUp animated">
                        
                        <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
                            <li class="active">
                                <a href="#product_tabs_description" data-toggle="tab"> Detalles </a> 
                            </li>
                            <li>
                                <a href="#comentarios_tabs" data-toggle="tab">Comentarios</a>
                            </li>
                            
                        </ul>
                        
                        <div id="productTabContent" class="tab-content">
                            <div class="tab-pane fade in active" id="product_tabs_description">
                                <div class="std">
                                    <?php $this->load->view('productos/detalle/producto_detalles_v'); ?>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="comentarios_tabs">
                                <div class="std">
                                    <?php $this->load->view('productos/detalle/comentarios_v'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('productos/detalle/vue_v') ?>