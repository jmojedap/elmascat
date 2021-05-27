<?php
    //Resumen
        $peso_total = 0;
        
        foreach ($detalle->result() as $row_detalle) {
            $peso_total += $row_detalle->cantidad * $row_detalle->peso;
        }    
?>

<h1><?= $row->cod_pedido ?><small class="ml-3 text-muted"><?= $head_subtitle ?></small></h1>

<table class="table table-condensed">
    <tbody>
        
        <tr>
            <td width="25%"><?= $row->nombre . ' ' . $row->apellidos ?></td>
            <td>
                    <small><?= $this->Item_model->nombre(53, $row->tipo_documento_id) ?></small>
                    <strong><?= $row->no_documento ?></strong>
                    <span> | </span>

                    <small>E-mail</small>
                    <strong><?= $row->email ?></strong>
                    <span> | </span>

                    <small>Ciudad</small>
                    <strong><?= $row->ciudad ?></strong>
                    <span> | </span>

                    <small>Dirección</small>
                    <strong><?= $row->direccion ?></strong>
                    <span> | </span>



                    <small>Teléfono</small>
                    <strong><?= $row->telefono ?></strong>
                    <span> | </span>

                    <small>Celular</small>
                    <strong><?= $row->celular ?></strong>
                    <span> | </span>

                    <small>Creado</small>
                    <strong>
                        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d h:i') ?>
                        - Hace <?= $this->Pcrn->tiempo_hace($row->creado) ?>
                    </strong>
                    <span> | </span>
            </td>
        </tr>
        
        <tr>
            <td width="25%">Notas</td>
            <td>
                <?= $row->notas ?>
            </td>
        </tr>
        
        
        
        <tr>
            <td>
                <?= $this->App_model->nombre_item($row->estado_pedido, 1, 7) ?>        
                <br/>
                <?= $this->App_model->nombre_item($row->codigo_respuesta_pol, 2, 10) ?>
            </td>
            <td>
                <p>
                    <small>Total</small>
                    <strong>
                        <?= $this->Pcrn->moneda($row->valor_total) ?>
                    </strong>
                    <span> >> </span>
                    
                    <small>Subtotal productos</small>
                    <strong>
                        <?= $this->Pcrn->moneda($row->total_productos) ?>
                    </strong>
                    <span> | </span>

                    <small>Subtotal otros</small>
                    <strong>
                        <?= $this->Pcrn->moneda($row->total_extras) ?>
                    </strong>
                    <span> | </span>

                    <small>Peso</small>
                    <strong><?= $row->peso_total ?> kg</strong>        
                </p>
                
                <?php if ( count($arr_respuesta_pol) > 0 ) { ?>
                    <p>
                        <small>Tipo medio de pago</small>
                        <strong>
                            <?= $this->App_model->nombre_item($arr_respuesta_pol['tipo_medio_pago'], 1, 11) ?>
                        </strong>
                        <span> | </span>

                        <small>Medio de pago</small>
                        <strong>
                            <?= $this->App_model->nombre_item($arr_respuesta_pol['medio_pago'], 1, 12) ?>
                        </strong>
                        <span> | </span>
                    </p>
                <?php } ?>
                
                    
                <p>
                    <small>Factura</small>
                    <strong>
                        <?= $row->factura ?>
                    </strong>
                    <span> | </span>

                    <small>Guía</small>
                    <strong>
                        <?= $row->no_guia ?>
                    </strong>
                    <span> | </span>

                    <small>Anotaciones</small>
                    <strong>
                        <?= $row->notas_admin ?>
                    </strong>
                    <span> | </span>

                    <small>Editado por</small>
                    <strong>
                        <?= $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?>
                    </strong>
                    <span> | </span>

                    <small>Editado</small>
                    <strong>
                        <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d h:i') ?>
                        - Hace <?= $this->Pcrn->tiempo_hace($row->editado) ?>
                    </strong>
                    <span> | </span>
                </p>
            </td>    
        </tr>
        
        <tr>
            <td>Subtotal otros</td>
            <td>
                <p>
                    <strong><?= $this->Pcrn->moneda($row->total_extras); ?></strong>
                    <span> >>  </span>

                    <?php foreach ($extras->result() as $row_extra) : ?>
                        <small><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?> : </small>
                        <strong>
                            <?= $this->Pcrn->moneda($row_extra->precio) ?>
                        </strong>
                        <span> | </span>
                    <?php endforeach ?>

                </p>
            </td>
        </tr>

        <?php if ( $row->is_gift == 1 ) : ?>
            <tr>
                <td>
                    Datos de regalo
                </td>
                <td>
                    <p>
                        <strong>De:</strong>
                        <?= $arr_meta['regalo']['de'] ?>
                        &middot;
                        <strong>Para:</strong>
                        <?= $arr_meta['regalo']['para'] ?>
                        &middot;
                        <strong>Mensaje/Dedicatoria:</strong>
                        <?= $arr_meta['regalo']['mensaje'] ?>

                    </p>
                </td>
            </tr>
        <?php endif; ?>

    </tbody>
    
</table>

<h3>Productos (<?= $detalle->num_rows() ?>)</h3>
        
<table class="table table-bordered">
    <thead>
        
        <th>Ref.</th>
        <th>Producto</th>
        <th>Cant.</th>
        <th>Precio</th>
        <th>Total</th>
        <th>Web ID</th>
    </thead>

    <tbody>    
        <tr class="success">
            
            <td></td>
            <td>Total</td>
            <td></td>
            <td></td>
            <td>
                <b>
                    <?= $this->Pcrn->moneda($row->total_productos) ?>
                </b>
            </td>
            <td></td>
        </tr>
        <?php foreach ($detalle->result() as $row_detalle) : ?>
            <?php
                $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                $sum_precio = $row_detalle->cantidad * $row_detalle->precio;
                
                $precio_especial = FALSE;
                if ( $row_detalle->precio_nominal > $row_detalle->precio ) { $precio_especial = TRUE; }

                //Checkbox
                    $att_check = array(
                        'class' =>  'check_registro',
                        'data-id' => $row_detalle->id,
                        'checked' => FALSE
                    );
            ?>

            <tr>
                
                <td><?= $row_producto->referencia ?></td>
                <td>
                    <?= $row_detalle->nombre_producto ?>
                    <?php if ($precio_especial) { ?>
                        <br/>
                        <i class="fa fa-info-circle"></i>
                        <?= $arr_tipos_precio[$row_detalle->promocion_id] ?>
                    <?php } ?>
                </td>
                <td><?= $row_detalle->cantidad ?></td>
                <td class="text-right"><?= $this->Pcrn->moneda($row_detalle->precio) ?></td>
                <td class="text-right"><?= $this->Pcrn->moneda($sum_precio) ?></td>
                <td class="warning">
                    <span class="etiqueta primario w1"><?= $row_detalle->producto_id ?></span>
                </td>
                
            </tr>
            <?php endforeach ?>
            
            <tr class="text-center">
                <td colspan="7"> >> Fin productos << </td>
            </tr>
    </tbody>
</table>

<div class="text-muted text-center">
    <p>Creado por Pacarina Media Lab &copy; 2020</p>
</div>