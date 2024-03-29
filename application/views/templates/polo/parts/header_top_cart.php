<?php
    if ( ! is_null($this->session->userdata('order_code')) ) 
    {
        $order_code = $this->session->userdata('order_code');
        $row_pedido = $this->Db_model->row('pedido', "cod_pedido = '{$order_code}'");
        
        $detalle = $this->App_model->detalle_pedido($row_pedido->id);
    }
    
    $order_qty_items = 0;
    if ( ! is_null($this->session->userdata('order_qty_items')) ) { $order_qty_items = $this->session->userdata('order_qty_items'); }
    
    //Contador productos, para limitar listado
        $i = 1;
?>

<div class="top-cart-contain">
    <div class="mini-cart">
        <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle">
            <a href="#">
                <i class="glyphicon glyphicon-shopping-cart"></i>
                <div class="cart-box">
                    <span class="title">carrito</span>
                    <span id="cart-total"><?= $order_qty_items ?> item </span>
                </div>
            </a>
        </div>
        <div>
            <?php if ( ! is_null($this->session->userdata('order_code')) ){ ?>
                <div class="top-cart-content arrow_box">
                    <div class="block-subtitle">Item(s) agregados recientemente</div>
                    <ul id="cart-sidebar" class="mini-products-list">
                        <?php foreach ($detalle->result() as $row_detalle) : ?>
                            <?php if ( $i <= 3 ){ ?>
                                <li class="item even">
                                    <a href="<?= URL_APP . "productos/detalle/{$row_detalle->producto_id}" ?>" class="product-image" title="<?= $row_detalle->nombre_producto ?>">
                                        <img src="<?= $row_detalle->url_thumbnail ?>" alt="Imagen producto" style="width: 80px;" onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'">
                                    </a>
                                    <div class="detail-item">
                                        <div class="product-details">
                                            <p class="product-name">
                                                <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                                            </p>
                                        </div>
                                        <div class="product-details-bottom">
                                            <span class="price">
                                                <?= $this->Pcrn->moneda($row_detalle->cantidad * $row_detalle->precio); ?>
                                            </span>
                                            <span class="title-desc">Cant:</span>
                                            <strong><?= $row_detalle->cantidad ?></strong>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <?php $i++; ?>
                        <?php endforeach ?>
                    </ul>
                    <div class="top-subtotal">Subtotal productos:
                        <span class="price">
                            <?= $this->Pcrn->moneda($row_pedido->total_productos) ?>
                        </span>
                    </div>
                    <div class="actions">
                        <button class="btn-checkout" type="button" onclick="window.location.href='<?= base_url() . 'pedidos/compra_a/' ?>'">
                            <span>Pagar</span>
                        </button>
                        <button class="view-cart" type="button" onclick="window.location.href='<?= base_url() . 'pedidos/carrito' ?>'">
                            <span>Ver carrito</span>
                        </button>
                    </div>
                </div>
            <?php } else { ?>
                <div class="top-cart-content arrow_box">
                    <div class="block-subtitle">El carrito está vacío</div>
                </div>
            <?php } ?>
            
        </div>
    </div>
    <div id="ajaxconfig_info"> <a href="#/"></a>
        <input value="" type="hidden">
        <input id="enable_module" value="1" type="hidden">
        <input class="effect_to_cart" value="1" type="hidden">
        <input class="title_shopping_cart" value="Go to shopping cart" type="hidden">
    </div>
</div>