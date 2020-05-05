<div class="row">
    <div class="col-md-12">
        <div class="page-title">
            <h2 class="title"><?php echo $head_title ?></h2>
        </div>
        <div class="status alert alert-success" style="display: none"></div>
        <form id="main-contact-form" class="contact-form row" name="contact-form" method="post">
            <div class="col-md-2">
                <?php if ( $this->session->userdata('logged') ){ ?>
                    <a href="<?php echo base_url("usuarios/mis_pedidos") ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-arrow-left"></i>
                        Mis pedidos
                    </a>
                <?php } ?>
            </div>
            <div class="form-group col-md-8">
                <input
                    type="text"
                    id="field-cod_pedido"
                    name="cod_pedido"
                    required
                    class="form-control"
                    placeholder="Escribe el código de tu pedido"
                    title="Escriba el código de su pedido"
                    value="<?php echo $cod_pedido ?>"
                    >
            </div>

            <div class="form-group col-md-2">
                <button class="btn btn-primary btn-block">
                    Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ( is_null($pedido_id) ){ ?>
    <div class="">
        <div class="alert alert-info" role="alert">
            <i class="fa fa-info-circle"></i>
            Lo sentimos, no encontramos ningún pedido con el código <b> <?php echo $cod_pedido ?></b>
        </div>
    </div>
<?php } ?>

<?php if ( $pedido_id > 0 ){ ?>

    <div class="mb-2">

        <div class="shopper-informations">
            <div class="row">

                <div class="col-sm-8">
                    <dl class="dl-horizontal">
                        <dt>Código de Pedido</dt>
                        <dd>
                            <span class="resaltar">
                                <?= $row->cod_pedido ?>
                            </span>
                        </dd>
                        
                        <dt>Estado pedido</dt>
                        <dd>
                            <span class="resaltar">
                                <?= $this->Item_model->nombre(7, $row->estado_pedido) ?>
                            </span>
                        </dd>
                        
                        <dt>Estado del pago</dt>
                        <dd>
                            <span class="resaltar">
                                <?= $this->Item_model->nombre(10, $row->codigo_respuesta_pol) ?>
                            </span>
                        </dd>
                        
                        <dt>Factura</dt>
                        <dd><?= $row->factura ?></dd>
                        
                        <dt>No. guía</dt>
                        <dd><?= $row->no_guia ?></dd>
                    
                        <dt>Nombre</dt>
                        <dd><?= $row->nombre ?> <?= $row->apellidos ?></dd>
                    
                        <dt>Entregar en</dt>
                        <dd>
                            <?= $row->direccion ?>, <?= $row->direccion_detalle ?><br/>
                            <?= $row->ciudad ?>
                            <br/>
                            <span class="suave">Tél </span><?= $row->telefono ?>
                            <span class="suave">Cel </span><?= $row->celular ?>
                            
                        </dd>
                    
                        <dt>Notas</dt>
                        <dd><?= $row->notas ?></dd>
                    
                        <dt>Peso del pedido</dt>
                        <dd><?= $row->peso_total ?> Kg</dd>
                    </dl>
                </div>

                <div class="col-sm-4">
                    <div class="order-message total_area">
                        <h3>Resumen de la compra</h3>

                        <table class="table table-condensed total-result">
                            <tr>
                                <td>Subtotal productos</td>
                                <td class="text-right">
                                    <?= $this->Pcrn->moneda($row->total_productos) ?>
                                </td>
                            </tr>

                            <?php if ( $arr_extras['gastos_envio'] ) { ?>
                                <tr>
                                    <td>
                                        Gastos transacción y envío
                                    </td>
                                    <td class="text-right">
                                        <?php echo $this->Pcrn->moneda($arr_extras['gastos_envio']);  ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if ( $arr_extras['dto_distribuidor'] ) { ?>
                                <tr>
                                    <td>
                                        Descuento para Distribuidor
                                    </td>
                                    <td>
                                        <span>
                                            <?= $this->Pcrn->moneda($arr_extras['dto_distribuidor']);  ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>


                            <tr>
                                <td>Total</td>
                                <td class="text-right">
                                    <span class="money" style="font-size: 1.5em;">
                                        <?= $this->Pcrn->moneda($row->valor_total) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>	
                </div>
            </div>
        </div>
        
        <div class="page-title">
            <h2 class="title">Detalle del pedido</h2>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr class="cart_menu">
                    <td class="description">Producto</td>
                    <td class="">Precio</td>
                    <td class="quantity">Cantidad</td>
                    <td class="total">Subtotal</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalle->result() as $row_detalle) : ?>
                    <?php
                        $precio_especial = FALSE;
                        if ( $row_detalle->precio_nominal > $row_detalle->precio ) { $precio_especial = TRUE; }
                        $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                        $ahorro = $row_detalle->cantidad * ($row_detalle->precio_nominal - $row_detalle->precio);
                        $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                        $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                        $pct_descuento = 100 - $this->Pcrn->int_percent($row_detalle->precio, $row_detalle->precio_nominal);

                        $att_img = $this->Producto_model->att_img($row_detalle->producto_id, 500);
                        $att_img['width'] = 75;
                        $att_imt['alt'] = $row_detalle->nombre_producto;
                    ?>
                    <tr>
                        <td class="">
                            <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                            <p>
                                Web ID: <?= $row_detalle->producto_id ?>
                                | <?= $row_detalle->peso ?> g
                            </p>
                            
                        </td>
                        <td>
                            <p>
                                <span class="cart-price">
                                    <span class="price" style="font-size: 125%; color: red; font-weight: bold;">
                                        <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                                    </span>
                                </span>
                            </p>
                            <?php if ( $precio_especial ) { ?>
                                <p>
                                    <?php if ( $precio_especial ) { ?>
                                        <span class="correcto">
                                            <i class="fa fa-caret-right"></i>
                                            <?= $arr_tipos_precio[$row_detalle->promocion_id] ?>
                                        </span>
                                    <?php } ?>
                                <br/>
                                    <span class="suave">
                                        Precio normal 
                                    </span>
                                    <span class="suave">
                                        <?= $this->Pcrn->moneda($row_detalle->precio_nominal) ?>
                                    </span>

                                    <span class="label label-success">
                                         -<?= $pct_descuento ?>%
                                    </span>
                                </p>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <?php echo $row_detalle->cantidad ?>
                        </td>
                        <td class="text-right">
                            <?php echo $this->Pcrn->moneda($precio_detalle) ?>
                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        
    </div>

<?php } ?>