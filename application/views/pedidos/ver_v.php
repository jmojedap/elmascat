<?php
    //Resumen
        $peso_total = 0;
        
        foreach ($detalle->result() as $row_detalle) {
            $peso_total += $row_detalle->cantidad * $row_detalle->peso;
        }
        
    $att_cantidad = array(
        'id'     => 'cantidad',
        'name'   => 'cantidad',
        'class'  => 'form-control',
        'value'  => '1',
        'placeholder'   => 'Cantidad',
        'type' => 'number',
        'min' => '1',
        'max' => '10000',
        'step' => '1'
    );
    
    //Opciones productos
        $opciones_producto = $this->App_model->opciones_producto('id > 0');
?>

<script>
//Variables
        var base_url = '<?= base_url() ?>';
        var pedido_id = <?= $row->id ?>;
        var estado_pedido = <?= $row->estado_pedido ?>;
//Docready
    $(document).ready(function(){
        $('.boton_estado').click(function(){
            estado_pedido = $(this).data('estado');
            //act_estado(); 
        });
    });

//Functions

    //Ajax
    function act_estado(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/act_estado/' + pedido_id + '/' + estado_pedido,
            success: function(){
                window.location = base_url + 'pedidos/ver/' + pedido_id;
            }
        });
    }
</script>

<div class="sep2">
    <?= anchor("pedidos/reporte/{$row->id}", '<i class="fa fa-print"></i> Resumen', 'class="btn btn-default" target="_blank" title="Imprimir reporte resumen"') ?>
</div>

<div class="row">
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-user"></i>
                Cliente
            </div>

            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td>Código pedido</td>
                        <td><?= $row->cod_pedido ?></td>
                    </tr>
                    
                    <tr>
                        <td>Cliente</td>
                        <td><?= $row->nombre . ' ' . $row->apellidos ?></td>
                    </tr>
                    
                    <tr>
                        <td>Documento</td>
                        <td><?= $row->no_documento ?></td>
                    </tr>
                    
                    <tr>
                        <td>E-mail</td>
                        <td><?= $row->email ?></td>
                    </tr>
                    
                    <tr>
                        <td>Creado</td>
                        <td title="<?= $row->creado ?>"> 
                            <?= $this->Pcrn->fecha_formato($row->creado, 'M-d h:i') ?>
                            | <?= $this->Pcrn->tiempo_hace($row->creado) ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Total $</td>
                        <td> 
                            <b>
                                <?= number_format($row->valor_total, 0, ',', '.') ?>
                            </b>
                            <span class="suave">
                                =
                                (<?= number_format($row->total_productos, 0, ',', '.') ?> + 
                                <?= number_format($row->total_extras, 0, ',', '.') ?> )
                            </span>
                        </td>
                    </tr>
                    
                    
                </tbody>
                    
            </table>
        </div>
    </div>
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-map-marker"></i>
                Envío
            </div>

            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td>Ciudad</td>
                        <td><?= $row->ciudad ?></td>
                    </tr>
                    <tr>
                        <td>Dirección</td>
                        <td>
                            <?= $row->direccion ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Teléfonos</td>
                        <td>
                            <?= $row->celular ?>
                            <?php if ( strlen($row->telefono) ) { ?>
                                |
                                <?= $row->telefono ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Notas</td>
                        <td>
                            <?= $row->notas ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Peso</td>
                        <td>
                            <?= $row->peso_total ?> kg
                        </td>
                    </tr>
                    
                    
                    
                    
                </tbody>
                    
            </table>
        </div>
    </div>
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Gestión
            </div>

            <table class="table table-condensed">
                <tbody>
                    <tr class="info">
                        <td>Estado</td>
                        <td>
                            <b>
                                <?= $this->Item_model->nombre(7, $row->estado_pedido) ?>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td>Editado</td>
                        <td>
                            <?= $this->Pcrn->fecha_formato($row->editado, 'M-d h:i') ?> |
                            Hace <?= $this->Pcrn->tiempo_hace($row->editado) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Por</td>
                        <td><?= $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Factura</td>
                        <td>
                            <?= $row->factura ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Guía</td>
                        <td>
                            <?= $row->no_guia ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Anotación</td>
                        <td>
                            <?= $row->notas_admin ?>
                        </td>
                    </tr>
                </tbody>
                    
            </table>
        </div>
    </div>
</div>

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Productos</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-default bg-blanco">
            <thead class="text-c">
                <th width="45px;" class="warning">ID</th>
                <th>Referencia</th>
                <th>Producto</th>
                <th>Cant</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Tipo precio</th>
                <th>Descuento</th>
                <th>Peso</th>
            </thead>

            <tbody>    
                <tr class="info">
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td></td>

                    <td class="text-right">
                        <b>
                            <?= $this->Pcrn->moneda($row->total_productos) ?>
                        </b>
                    </td>
                    <td></td>
                    <td></td>
                    <td><?= $row->peso_total ?> kg</td>
                </tr>
                <?php foreach ($detalle->result() as $row_detalle) : ?>
                    <?php
                        $row_producto = $this->Pcrn->registro_id('producto', $row_detalle->producto_id);

                        $sum_precio = $row_detalle->cantidad * $row_detalle->precio;
                        $sum_peso = $row_detalle->cantidad * $row_detalle->peso;

                        //Descuento
                            $descuento = $row_detalle->cantidad * ($row_detalle->precio_nominal - $row_detalle->precio);

                        //Checkbox
                            $att_check = array(
                                'class' =>  'check_registro',
                                'data-id' => $row_detalle->id,
                                'checked' => FALSE
                            );
                    ?>

                    <tr>
                        <td class="warning"><span class="etiqueta primario w1"><?= $row_detalle->producto_id ?></span></td>
                        <td><?= $row_producto->referencia ?></td>
                        <td>
                            <?= anchor("productos/ver/{$row_detalle->producto_id}", $row_detalle->nombre_producto, 'class="" title=""') ?>
                        </td>
                        <td><?= $row_detalle->cantidad ?></td>
                        <td class="text-right"><?= $this->Pcrn->moneda($row_detalle->precio) ?></td>
                        <td class="text-right"><?= $this->Pcrn->moneda($sum_precio) ?></td>
                        <td>
                            <?= $arr_tipos_precio[$row_detalle->promocion_id] ?>
                        </td>
                        <td>
                            (<?= $this->Pcrn->moneda($descuento) ?>)
                        </td>
                        <td>
                            <?= $sum_peso ?> g
                        </td>
                    </tr>
                    <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Otros</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered">
            <thead>
                <th>Concepto</th>
                <th>Valor</th>
            </thead>
            <tbody>
                <tr class="info">
                    <td>Total</td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->total_extras); ?>
                        </b>
                    </td>
                </tr>
                <?php foreach ($extras->result() as $row_extra) : ?>
                <tr>
                    <td><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?></td>
                    <td>
                        <span>
                            <?= $this->Pcrn->moneda($row_extra->precio) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>