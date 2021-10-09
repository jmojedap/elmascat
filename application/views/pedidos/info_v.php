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
// Variables
//-----------------------------------------------------------------------------
        var base_url = '<?= base_url() ?>';
        var pedido_id = <?= $row->id ?>;
        var cod_pedido = '<?= $row->cod_pedido ?>';
        var estado_pedido = <?= $row->estado_pedido ?>;

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('.boton_estado').click(function(){
            estado_pedido = $(this).data('estado');
            //act_estado(); 
        });

        $('#btn_reiniciar_pedido').click(function(){
            reiniciar_pedido();
        });
    });

// Functions
//-----------------------------------------------------------------------------

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

    function reiniciar_pedido(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/reiniciar/' + cod_pedido,
            success: function(response){
                if (response.qty_affected > 0) {
                    toastr['success']('Pedido reiniciado');
                    $('#btn_reiniciar_pedido').removeClass('btn-light');
                    $('#btn_reiniciar_pedido').addClass('btn-success');
                    $('#btn_reiniciar_pedido').html('<i class="fa fa-check"></i> Reiniciado');
                    setTimeout(function(){ 
                        window.location = base_url + 'pedidos/ver/' + pedido_id;
                    }, 3000);
                } else {
                    toastr['error']('No se pudo reiniciar el pedido');
                }
            }
        });
    }
</script>

<div class="mb-2">
    <a href="<?= base_url("pedidos/reporte/{$row->id}") ?>" class="btn btn-light w120p" target="_blank" title="Imprimir reporte resumen">
        <i class="fa fa-print"></i> Resumen
    </a>
    <a href="<?= base_url("pedidos/reporte/{$row->id}/label") ?>" class="btn btn-light w120p" target="_blank" title="Imprimir label envío">
        <i class="fa fa-print"></i> Label
    </a>
    <?php if ( $row->estado_pedido == 1 ) { ?>
        <a href="<?= base_url("pedidos/link_pago/{$row->cod_pedido}") ?>" class="btn btn-success" target="_blank" title="Link para iniciar proceso de pago">
            <i class="fa fa-link"></i>
            Link de pago
        </a>
    <?php } ?>
    <?php if ( $row->estado_pedido < 3 && $this->session->userdata('rol_id') <= 1 ) { ?>
        <button class="btn btn-light w120p" title="Reiniciar el pedido para intentar nuevamente el pago" id="btn_reiniciar_pedido">
            <i class="fa fa-sync-alt"></i>
            Reiniciar
        </button>
    <?php } ?>
    <?php if ( $this->session->userdata('rol_id') == 0 ) { ?>
        <a href="<?= base_url("admin/tablas/pedido/edit/{$row->id}") ?>" class="btn btn-light w120p" title="Edición del registro en la base de datos" target="_blank">
            <i class="fa fa-edit"></i>
            Editar Row
        </a>
    <?php } ?>
</div>

<div class="row mb-2">
    <div class="col col-md-4">
        <div class="card card-default">
            <div class="card-header">
                <i class="fa fa-user"></i>
                Cliente
            </div>

            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td>Ref. venta</td>
                        <td><?= $row->cod_pedido ?></td>
                    </tr>
                    
                    <tr>
                        <td>Usuario</td>
                        <td>
                            <?php if ( $row->usuario_id > 0 ) { ?>
                                <a href="<?= base_url("usuarios/profile/{$row->usuario_id}") ?>">
                                    <?= $this->App_model->nombre_usuario($row->usuario_id, 'na') ?>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>A nombre de</td>
                        <td><?= $row->nombre . ' ' . $row->apellidos ?></td>
                    </tr>
                    
                    <tr>
                        <td>Documento</td>
                        <td>
                            <span class="text-muted"><?= $this->Item_model->nombre(53, $row->tipo_documento_id) ?></span>
                            <?= $row->no_documento ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>E-mail</td>
                        <td><?= $row->email ?></td>
                    </tr>
                    
                    <tr>
                        <td>Creado</td>
                        <td title="<?= $row->creado ?>"> 
                            <?= $this->Pcrn->fecha_formato($row->creado, 'M-d h:i') ?>
                            &middot; <?= $this->Pcrn->tiempo_hace($row->creado) ?>
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
        <div class="card card-default mb-2">
            <div class="card-header">
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
                                &middot;
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

        <?php if ( $row->is_gift ) : ?>
            <div class="card card-default">
                <div class="card-header">
                    <i class="fa fa-gift text-danger"></i>
                    Este pedido es un regalo
                    <span class="badge badge-success">Atención</span>
                </div>

                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td>De</td>
                            <td><?= $arr_meta['regalo']['de'] ?></td>
                        </tr>
                        <tr>
                            <td>Para</td>
                            <td><?= $arr_meta['regalo']['para'] ?></td>
                        </tr>
                        <tr>
                            <td>Mensaje</td>
                            <td><?= $arr_meta['regalo']['mensaje'] ?></td>
                        </tr>
                    </tbody>
                        
                </table>
            </div>
        <?php endif; ?>

    </div>
    <div class="col col-md-4">
        <div class="card card-default">
            <div class="card-header">
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
                            <?= $this->Pcrn->fecha_formato($row->editado, 'M-d h:i') ?> &middot;
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

<div class="card mb-2">
    <div class="card-header">Productos</div>
    
        <table class="table bg-white">
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

                        //Checkcard
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
                            <?= anchor("productos/info/{$row_detalle->producto_id}", $row_detalle->nombre_producto, 'class="" title=""') ?>
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

<div class="card card-default">
    <div class="card-header">Extras</div>
    <!-- /.card-header -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Concepto</th>
                <th>Nota</th>
                <th>Valor</th>
            </thead>
            <tbody>
                <tr class="info">
                    <td>Total</td>
                    <td></td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->total_extras); ?>
                        </b>
                    </td>
                </tr>
                <?php foreach ($extras->result() as $row_extra) : ?>
                <tr>
                    <td><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?></td>
                    <td><?= $row_extra->nota; ?></td>
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
    <!-- /.card-body -->
</div>