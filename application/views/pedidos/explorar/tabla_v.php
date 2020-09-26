<?php
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );

    //Clases columna
        $cl_col['cliente'] = 'hidden-xs';
        $cl_col['estado'] = 'hidden-xs';
        $cl_col['valor_total'] = '';
        $cl_col['respuesta_pol'] = 'hidden-xs hidden-sm';
        $cl_col['peso'] = 'hidden-xs hidden-sm';
        $cl_col['otros'] = 'hidden-xs hidden-sm';
        $cl_col['creado'] = 'hidden-xs hidden-sm';
        $cl_col['editar'] = 'hidden-xs';
        
        
        if ( $this->session->userdata('rol_id') >= 3 )
        {
            $cl_col['selector'] = 'hidden';
            $cl_col['botones'] = 'hidden';
        }
        
        if ( $this->session->userdata('rol_id') <= 2 )
        {
            $cl_col['selector'] = '';
        }
        
    //Arrays con valores para contenido en lista
        $clases_estado = $this->Pedido_model->clases_estado();
        $arr_respuestas_pol = $this->Item_model->arr_item(10);

    //Estado usuario
    $arr_estados[1] = '<i class="fa fa-check-circle text-success" title="Activo"></i>';
    $arr_estados[0] = '<i class="far fa-circle text-danger" title="Inactivo"></i>';

?>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <th class="<?= $cl_col['selector'] ?>" width="10px;">
            <input type="checkbox" name="check_todos" id="check_todos">
        </th>
        <th width="45px;" class="warning">ID</th>
        <th>Ref. Venta</th>
        <th class="<?= $cl_col['cliente'] ?>">Cliente</th>
        <th class="<?= $cl_col['estado'] ?>">Estado</th>
        <th class="<?= $cl_col['valor_total'] ?>">Valor</th>
        <th class="<?= $cl_col['respuesta_pol'] ?>">PayU</th>
        <th class="<?= $cl_col['peso'] ?>">Peso (kg)</th>
        <th class="<?= $cl_col['otros'] ?>">Datos</th>
        <th class="<?= $cl_col['editado'] ?>">Editado</th>
        
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_pedido = $row_resultado->cod_pedido;
                    $link_pedido = anchor("pedidos/ver/$row_resultado->id", $nombre_pedido);
                    $editable = $this->Pedido_model->editable($row_resultado->id);

                //Datos POL
                    $condicion = "dato_id = 3005 AND elemento_id = {$row_resultado->id}";
                    $confirmacion = $this->Pedido_model->confirmacion($row_resultado->id);
                    
                    //PayU Estado
                    $payu['row_class'] = '';
                    $payu['icono'] = '';
                    $payu['class'] = '';
                    if ( ! is_null($confirmacion) )
                    {
                        $payu['icono'] = '<i class="far fa-circle"></i>';
                        $payu['class'] = 'text-primary';
                        
                        if ( $confirmacion->codigo_respuesta_pol == 1 ) {
                            $payu['icono'] = '<i class="fa fa-check-circle"></i>';
                            $payu['class'] = 'text-success';
                        }

                        //Verificar firma
                        $firma_pol_confirmacion = $this->Pedido_model->firma_pol_confirmacion($row_resultado->id, $confirmacion->estado_pol);
                        if ( $firma_pol_confirmacion != $confirmacion->firma )
                        {
                            $payu['icono'] = '<i class="fa fa-exclamation-circle"></i>';
                            $payu['class'] = 'text-warning';
                            $payu['row_class'] = 'warning';
                        }
                    }

                    $cl_peso = '';
                    if ( $row_resultado->peso_total > 0 ) { $cl_peso = 'info'; }

                //Respuesta pol
                    $indice = '0' . $row_resultado->codigo_respuesta_pol;
                    
                //Identificar cliente
                    $nombre_cliente = $row_resultado->nombre . ' ' . $row_resultado->apellidos;
                    $row_usuario = $this->Db_model->row_id('usuario', $row_resultado->usuario_id);
                    $estado_cliente = 'No Registrado';
                    if ( ! is_null($row_usuario) )
                    {
                        $estado_cliente = $arr_estados[$row_usuario->estado];
                        //$cant_contenidos = $this->Db_model->num_rows('meta', "dato_id = 100012 AND elemento_id = {$row_usuario->id}");
                    }
            ?>
            <tr>
                <?php if ( $this->session->userdata('rol_id') <= 2  ) { ?>
                    <td>
                        <input type="checkbox" class="check_registro" data-id="<?= $row_resultado->id ?>">
                    </td>
                <?php } ?>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td><?= $link_pedido ?></td>
                <td class="<?= $cl_col['cliente'] ?>">
                    <?php if ( $row_resultado->usuario_id > 0 ) { ?>
                        <?= anchor("usuarios/profile{$row_resultado->usuario_id}", $nombre_cliente, 'title="Ver pedidos del cliente"') ?>
                        <!-- <a href="<?php //echo base_url("usuarios/books/{$row_resultado->usuario_id}") ?>" class="btn btn-default btn-xs" target="_blank" title="Contenidos asignados">
                            <?php //echo $cant_contenidos ?>
                        </a> -->
                    <?php } else { ?>
                        <?= $nombre_cliente ?>
                    <?php } ?>
                    <br>
                    <?= $row_resultado->email ?>
                </td>
                <td class="<?= $cl_col['estado'] ?> <?= $clases_estado[$row_resultado->estado_pedido] ?>"><?= $this->App_model->nombre_item($row_resultado->estado_pedido, 1, 7) ?></td>
                <td class="<?= $cl_col['valor_total'] ?> text-right">
                    <?= number_format($row_resultado->valor_total, 0, ',', '.'); ?>
                </td>
                <td class="<?= $cl_col['respuesta_pol'] ?> <?= $payu['row_class'] ?>">
                    <?php if ( ! is_null($confirmacion) ){ ?>
                        <span class="<?= $payu['class'] ?>">
                            <?= $payu['icono'] ?>
                        </span>
                        <a href="<?= base_url("pedidos/pol/{$row_resultado->id}") ?>" title="Datos de transación en PayU">
                            <?= $arr_respuestas_pol[$indice] ?>
                        </a>
                    <?php } ?>
                </td>
                <td class="<?= $cl_col['peso'] ?> <?= $cl_peso ?>">
                    <?php if ( $row_resultado->peso_total > 0 ) { ?>
                        <?= $row_resultado->peso_total ?>
                    <?php } ?>
                </td>
                <td class="<?= $cl_col['otros'] ?>">
                    <?php if ( strlen($row_resultado->factura) > 0 ) { ?>
                        <span class="suave">Factura:</span>
                        <?= $row_resultado->factura ?>
                        <span class="suave"> | </span>
                    <?php } ?>
                    <?php if ( strlen($row_resultado->no_guia) > 0 ) { ?>
                        <span class="suave">Guía:</span>
                        <?= $row_resultado->no_guia ?>
                    <?php } ?>
                </td>
                <td class="<?= $cl_col['editado'] ?>">
                    <?= $this->App_model->nombre_usuario($row_resultado->editado_usuario_id); ?>
                    &middot;
                    <span class="suave" title="<?= $row_resultado->editado ?>">
                        <?= $this->pml->ago($row_resultado->editado, false); ?>
                    </span>
                </td>
            </tr>

        <?php } //foreach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>