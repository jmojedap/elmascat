<?php
    $src = RUTA_UPLOADS . $row_archivo->carpeta . '500px_' . $row_archivo->nombre_archivo;
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
?>

<script>
//Variables
    var base_url = '<?= base_url() ?>';
    var producto_id = <?= $row_variacion->id ?>;
    var cantidad = 1;

//Document ready

    $(document).ready(function(){
        $('.mini_img').click(function(){
            $('#img_producto').attr('src', $(this).data('src'));
        });
        
        $('#add-to-cart').click(function(){
            cantidad = $('#qty').val();
            guardar_detalle();
        });
    });


//Funciones

    //Ajax
    function guardar_detalle(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/guardar_detalle',
            data: {
                producto_id : producto_id,
                cantidad : cantidad
            },
            success: function(){
                window.location = base_url + 'pedidos/carrito';
            }
        });
    }
</script>

<div class="col-main">
    <div class="row">
        <div class="product-view">
            <div class="product-essential">
                <form action="#" method="post" id="product_addtocart_form">
                    <?php if ( $imagenes->num_rows() > 1 ) { ?>
                        <div class="product-img-box col-lg-6 col-sm-6 col-xs-12">
                            <ul class="moreview" id="moreview">
                                <?php foreach ($imagenes->result() as $row_archivo) { ?>
                                    <?php 
                                        $contador_img++;
                                        $src = $row_archivo->url_carpeta . $row_archivo->nombre_archivo;
                                        $clase_li = "moreview_thumb thumb_{$contador_img} moreview_thumb_active";
                                        if ( $contador_img > 1 ) { $clase_li = "moreview_thumb thumb_{$contador_img}"; }
                                    ?>
                                    <li class="<?= $clase_li ?>">
                                        <img class="moreview_thumb_image" src="<?= $src ?>" alt="thumbnail">
                                        <span class="roll-over">Mueva el mouse para hacer zoom</span>
                                        <img class="moreview_source_image" src="<?= $src ?>" alt="">
                                        <img  class="zoomImg" src="<?= $src ?>" alt="thumbnail">
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
                            <div style="max-height: 540px; max-width: 560px;" class="text-center">
                                <?= img($att_img) ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <div class="product-shop col-lg-6 col-sm-6 col-xs-12">
                        <div class="product-next-prev">
                            <a class="product-next" href="#"><span></span></a> <a class="product-prev" href="#"><span></span></a> </div>
                        <div class="product-name">
                            <h1><?= $row_variacion->nombre_producto ?></h1>
                        </div>
                        
                        <p class="availability in-stock">
                            Disponibles:
                            <span>
                                <?php if ( $row->cant_disponibles > 0 ){ ?>
                                    <?= $row_variacion->cant_disponibles ?>
                                <?php } else { ?>
                                    agotado
                                <?php } ?> 
                            </span>
                        </p>
                        
                        <div class="price-block">
                            <div class="price-box">
                                <p class="special-price">
                                    <span class="price">
                                        <?= $this->Pcrn->moneda($arr_precio['precio']) ?>
                                    </span>
                                </p>
                                <?php if ( $row->precio > $arr_precio['precio'] ) { ?>
                                    <p class="old-price">
                                        <span class="price">
                                            <?= $this->Pcrn->moneda($row->precio) ?>
                                        </span>
                                    </p>
                                    <br/>
                                    <i class="fa fa-check correcto"></i>
                                    <?= $arr_tipos_precio[$arr_precio['promocion_id']] ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="short-description">
                            <h2>Descripción</h2>
                            <div class="text-justify">
                                <?php echo $row->descripcion ?>
                            </div>
                        </div>
                        
                        <?php if ( $variaciones->num_rows() > 0 ){ ?>
                            <?php $this->load->view('productos/detalle/variaciones_v'); ?>
                        <?php } ?>
                        
                        <div class="add-to-box">
                            <?php if ( $row->cant_disponibles > 0 ){ ?>
                                <div class="add-to-cart">
                                    <label for="qty">Cantidad:</label>
                                    <div class="pull-left">
                                        <div class="custom pull-left">
                                            <button onClick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty ) &amp;&amp; qty &gt; 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="fa fa-minus">&nbsp;</i></button>
                                            <input type="text" class="input-text qty" title="Qty" value="1" max="<?= $row->cant_disponibles ?>" maxlength="12" id="qty" name="qty">
                                            <button onClick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty )) result.value++;return false;" class="increase items-count" type="button"><i class="fa fa-plus">&nbsp;</i></button>
                                        </div>
                                    </div>
                                    <button id="add-to-cart" class="button btn-cart" title="Agregar al carrito de compras" type="button">
                                        <span><i class="icon-basket"></i> Al carrito</span>
                                    </button>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-info-circle"></i> Este producto se encuentra agotado
                                </div>
                            <?php } ?>
                            
                        </div>
                        
                        <?php if ( $this->session->userdata('rol_id') <= 10 && $this->session->userdata('logged') ) : ?>
                            <div style="margin-top: 10px;">
                                <?= anchor("productos/editar/{$row->id}", '<i class="fa fa-pencil"></i> Editar', 'class="btn btn-warning" title="Editar producto"') ?>      
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