<?php
    //Verificar si puede ir a pagar
        $datos_faltantes = array();
        if ( strlen($row->no_documento) == 0 ) { $datos_faltantes[] = 'Número de documento'; }
        if ( strlen($row->email) == 0 ) { $datos_faltantes[] = 'Correo electrónico'; }
        if ( strlen($row->direccion) == 0 ) { $datos_faltantes[] = 'Dirección de entrega'; }
        if ( strlen($row->celular) == 0 ) { $datos_faltantes[] = 'Número de celular'; }
?>

<div style="margin-bottom: 20px;">
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
                
                <?php if ( $this->session->userdata('rol') <= 1 ) { ?>
                    <tfoot>
                        <tr class="first last">
                            <td class="a-right last" colspan="2">
                                <a href="<?php echo base_url("pedidos/editar/edit/{$row->id}") ?>" class="btn btn-polo w3" title="Editar pedido">
                                    <i class="fa fa-edit"></i><span><span> Editar</span></span>
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                <?php } ?>

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
            <div class="page-title">
                <h2>Valores</h2>
            </div>
            <?php $this->load->view('pedidos/compra/totales_v'); ?>
            <ul class="checkout">
                <?php echo form_open($destino_form) ?>

                    <?php foreach ($form_data as $key => $valor) : ?>
                        <?= form_hidden($key, $valor) ?>
                    <?php endforeach ?>

                    <li>
                        <?php if ( count($datos_faltantes) == 0 ) { ?>
                            <button class="btn-polo-lg" type="submit">
                                IR A PAGAR
                            </button>
                        <?php } else { ?>
                            <div class="alert alert-warning">
                                <p> 
                                    <i class="fa fa-info-circle fa-2x"></i>
                                </p>
                                <p>
                                    <?php echo count($datos_faltantes) ?> datos faltantes: <?php echo implode(', ', $datos_faltantes); ?>.
                                </p>
                            </div>
                        <?php } ?>
                    </li>
                <?php echo form_close('') ?>
            </ul>
            
            <?php if ( $row->pais_id != 51 ) { ?>
                <a class="btn btn-info btn-block" href="<?php echo base_url("pedidos/compra_b_usd/{$row->cod_pedido}") ?>">
                    Pagar en Dólares (USD)
                </a>
            <?php } ?>
            
        </div>
    </div>
</div>
