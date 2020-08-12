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
                                        <span class="nobr">Productos y precios (USD)</span>
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
                                                <b>
                                                    $ <?php echo number_format($row_detalle->precio/$precio_dolar, 2, '.', ',') ?>
                                                </b>
                                                
                                                <span class="suave">
                                                    <i class="fa fa-caret-right"></i>
                                                </span>
                                                <span class="cart-price">
                                                    <span class="price">
                                                        $ <?php echo number_format($precio_detalle/$precio_dolar, 2, '.', ',') ?>
                                                    </span>
                                                </span>
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
        
        <table class="data-table cart-table" id="shopping-cart-table">
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
                        <?= anchor("pedidos/compra_a/{$row->cod_pedido}", '<i class="fa fa-caret-left"></i><span><span> Editar</span></span>', 'class="btn btn-polo w3" title="Editar productos del carrito"') ?>
                    </td>
                </tr>
            </tfoot>

            <tbody>
                <tr>
                    <td width="25px">
                        <i class="fa fa-user fa-2x"></i>
                    </td>
                    <td>
                        <span class="resaltar">
                            <?= $row->nombre ?> <?= $row->apellidos ?><br/>
                        </span>
                        CC <?= $row->no_documento ?><br/>
                        <?= $row->email ?>
                    </td>
                </tr>
                <tr>
                    <td width="25px">
                        <i class="fa fa-map-marker fa-2x"></i>
                    </td>
                    <td>
                        <?= $row->ciudad ?><br/>
                        <?= $row->direccion ?>, <?= $row->direccion_detalle ?><br/>
                        <span class="suave">
                            Teléfonos:
                        </span>
                        <?= $row->celular ?> - <?= $row->telefono ?>
                    </td>
                </tr>
                <tr>
                    <td width="25px">
                        <i class="fa fa-info fa-2x"></i>
                    </td>
                    <td>
                        <?= $row->notas ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="col col-md-4">
        

        
        <?php $this->load->view('pedidos/compra/totales_usd_v'); ?>
        <ul class="checkout">
            <?= form_open($destino_form, $att_form) ?>

                <?php foreach ($form_data as $key => $valor) : ?>
                    <?= form_hidden($key, $valor) ?>
                <?php endforeach ?>
            
            

                <li>
                    <?= form_submit($att_submit) ?>
                </li>
            <?= form_close('') ?>
        </ul>
        
        <a class="btn btn-block btn-default" href="<?php echo base_url("pedidos/compra_b/{$row->cod_pedido}") ?>">
            Pagar en Pesos Colombianos (COP)
        </a>
        
        
        
    </div>
</div>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>