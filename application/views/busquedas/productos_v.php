<?php
    $src_alt = URL_IMG . 'app/250px_producto.png';          //Imagen alternativa
    $att_img['onError'] = "this.src='" . $src_alt . "'";    //Imagen alternativa
            
    $pedido_id = 0;
    if ( ! is_null($this->session->userdata('pedido_id')) ) {
        $pedido_id = $this->session->userdata('pedido_id');
    }
    
    $cant_productos = 0;
    if ( ! is_null($this->session->userdata('cant_productos')) ) { $cant_productos = $this->session->userdata('cant_productos'); }
    
?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var producto_id = 0;
    var cant_productos = <?= $cant_productos ?>;
</script>

<script>
    $(document).ready(function(){
        $('.ajax_add_to_cart_button').click(function(){
            producto_id = $(this).data('producto_id');
            guardar_detalle();
        });
    });
</script>

<script>
    //Ajax
    function guardar_detalle(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/guardar_detalle',
            data: {
                producto_id : producto_id,
                cantidad : 1
            },
            success: function(){
                window.location = base_url + 'pedidos/carrito';
            }
        });
    }
</script>

<ul class="products-grid">
    <?php foreach ($resultados->result() as $row_producto) : ?>
        <?php
            $att_img['src'] = RUTA_UPLOADS . $row_producto->carpeta . '500px_' . $row_producto->nombre_archivo;
            $att_img['class'] = 'img-responsive';
            $att_img['alt'] = 'a';
            $att_img['width'] = '100%';

            $link_detalle = base_url() . "productos/detalle/{$row_producto->id}/{$row_producto->slug}";
        ?>
        <li class="item col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="col-item">
                <div class="product-image-area">
                    <a class="product-image" title="<?= $row_producto->nombre_producto ?>" href="<?= $link_detalle ?>">
                        <div style="overflow: hidden; height: 360px; padding: 0px; background: #f3f3f3">
                            <?= img($att_img) ?>
                        </div>
                    </a>
                    <div class="hover_fly">
                        <?php if ( $row_producto->cant_disponibles > 0 ){ ?>
                            <a class="exclusive ajax_add_to_cart_button" href="" title="Agregar al carrito de compras" data-producto_id="<?= $row_producto->id ?>">
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
                            <a title="<?= $row_producto->nombre_producto ?>" href="<?= $link_detalle ?>">
                                <?= word_limiter($row_producto->nombre_producto, 16) ?>
                            </a> 
                        </div>
                        <!--item-title-->
                        <div class="item-content">
                            <div class="price-box">
                                <p class="special-price">
                                    <span class="price">
                                        $<?= number_format($row_producto->precio, 0, ',', '.') ?>
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