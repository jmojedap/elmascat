<?php
    //Campos del pedido
        $att_form = array(
            'class' =>  'form1'
        );
        
        $att_submit = array(
            'class'  => 'btn-polo-lg',
            'value'  => 'Ir a pagar'
        );
        
    //Form envío
    
    //Verificar si puede ir a pagar
        $datos_faltantes = 0;
        if ( strlen($row->no_documento) == 0 ) { $datos_faltantes++; }
        if ( strlen($row->email) == 0 ) { $datos_faltantes++; }
        if ( strlen($row->direccion) == 0 ) { $datos_faltantes++; }
        if ( strlen($row->celular) == 0 ) { $datos_faltantes++; }
        
        $att_submit['class'] = 'btn-polo-lg';
        if ( $datos_faltantes > 0 ) { $att_submit['class'] .= ' hidden'; }
?>

<div>
    <div class="row wow bounceInUp animated">
        <div class="col col-md-4">
            <div class="cart ">
                <div class="page-title">
                    <h2>Productos</h2>
                </div>
                <div class="table-responsive">
                    <!--<form method="post">-->
                        <input type="hidden" value="Vwww7itR3zQFe86m" name="form_key">
                        <fieldset>
                            <table class="data-table cart-table" id="shopping-cart-table">
                                <thead>
                                    <tr class="first last">
                                        <th rowspan="1">
                                            <span class="nobr">Productos y precios</span>
                                        </th>
                                        
                                    </tr>
                                </thead>
                                
                                <tfoot>
                                    <tr class="first last">
                                        <td class="a-right last">
                                            <?= anchor("pedidos/carrito/", '<i class="fa fa-shopping-cart"></i><span><span> Editar</span></span>', 'class="btn btn-polo w3" title="Editar datos de entrega"') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                                
                                <tbody>
                                    <?php foreach ($detalle->result() as $row_detalle) : ?>
                                        <?php
                                            $precio_especial = FALSE;
                                            if ( $row_detalle->precio_nominal > $row_detalle->precio ) { $precio_especial = TRUE; }
                                            $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                                            $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                                            $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                                            $pct_descuento = 100 - $this->Pcrn->int_percent($row_detalle->precio, $row_detalle->precio_nominal);

                                            
                                        ?>
                                        <tr class="last even">
                                            <td>
                                                <h2 class="product-name">
                                                    <b>
                                                        <?= anchor("productos/detalle/{$row_detalle->producto_id}", $row_detalle->nombre_producto) ?>
                                                    </b>
                                                </h2>
                                                <p>
                                                    <b>
                                                        <?= $row_detalle->cantidad ?>
                                                    </b>
                                                    <span class="suave">
                                                        x
                                                    </span>
                                                    <span class="suave"><?php echo $this->Pcrn->moneda($row_detalle->precio) ?></span>
                                                    
                                                    <span class="suave">
                                                        <i class="fa fa-caret-right"></i>
                                                    </span>
                                                    <span class="cart-price"><span class="money">
                                                        <?= $this->Pcrn->moneda($precio_detalle) ?>
                                                    </span></span>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            
                            <br/>
                            
                        </fieldset>
                    <!--</form>-->
                </div>
                
                

            </div>
        </div>
        
        <div class="col col-md-4">
            <div class="page-title">
                <h2>Datos de entrega</h2>
            </div>
            
            <table class="data-table cart-table mb-2" id="shopping-cart-table">
                <thead>
                    <tr class="first last">
                        <th colspan="2">
                            <span class="nobr">
                                Contacto y Dirección
                            </span>
                        </th>
                    </tr>
                </thead>
                
                <tfoot>
                    <tr class="first last">
                        <td class="a-right last" colspan="2">
                            <?= anchor("pedidos/compra_a/{$row->cod_pedido}", '<i class="fa fa-edit"></i><span><span> Editar</span></span>', 'class="btn btn-polo w3" title="Editar productos del carrito"') ?>
                        </td>
                    </tr>
                </tfoot>

                <tbody>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-user fa-2x text-primary"></i>
                        </td>
                        <td>
                            <span class="resaltar">
                                <?= $row->nombre ?> <?= $row->apellidos ?><br/>
                            </span>
                            <span class="text-muted"><?php echo $this->Item_model->nombre(53, $row->tipo_documento_id) ?></span>
                            <?php echo $row->no_documento ?><br/>
                            <?php echo $row->email ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-map-marker fa-2x text-primary"></i>
                        </td>
                        <td>
                            <?php echo $row->direccion ?><br>
                            <span class="suave">
                                Celular
                            </span>
                            <?php echo $row->celular ?><br/>
                            <?php echo $row->ciudad ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="25px">
                            <i class="fa fa-info-circle fa-2x text-primary"></i>
                        </td>
                        <td>
                            <?= $row->notas ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="col col-md-4">
            <div class="page-title">
                <h2>Valores</h2>
            </div>
            
            <?php $this->load->view('pedidos/compra/totales_v'); ?>
            <ul class="checkout">
                <?php echo form_open($destino_form, $att_form) ?>

                    <?php foreach ($form_data as $key => $valor) : ?>
                        <?php echo form_hidden($key, $valor) ?>
                    <?php endforeach ?>

                    <li>
                        <?php echo form_submit($att_submit) ?>
                    </li>
                <?php echo form_close('') ?>
            </ul>

            <div class="alert alert-info">
                <p class="text-center">
                    <i class="fa fa-info-circle"></i> <strong>Aviso importante</strong>
                </p>
                <p class="text-center">
                    Debido a la contingencia sanitaria la entrega de tu compra puede tardar
                    hasta 3 días hábiles en Bogotá y hasta <b>4 días hábiles</b> para el resto del país.
                </p>
            </div>
            
            <?php if ( $row->pais_id != 51 ) { ?>
                <a class="btn btn-info btn-block" href="<?php echo base_url("pedidos/compra_b_usd/{$row->cod_pedido}") ?>">
                    Pagar en Dólares (USD)
                </a>
            <?php } ?>
            
            
        </div>
    </div>
</div>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>
