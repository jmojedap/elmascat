<?php
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
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
        <th class="<?= $cl_col['selector'] ?>" width="10px;"><?= form_checkbox($att_check_todos) ?></th>
        <th width="45px;" class="warning">ID</th>
        <th>Ref. Venta</th>
        <th class="<?= $cl_col['cliente'] ?>">Cliente</th>
        <th class="<?= $cl_col['estado'] ?>">Estado</th>
        <th class="<?= $cl_col['valor_total'] ?>">Valor</th>
        <th class="<?= $cl_col['respuesta_pol'] ?>">Estado pago</th>
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
                    $cant_reg_pol = $this->Pcrn->num_registros('meta', $condicion);

                //Checkbox
                    $att_check = array(
                        'class' =>  'check_registro',
                        'data-id' => $row_resultado->id,
                        'checked' => FALSE
                    );

                //Clase fila
                    $clase_fila = '';
                    if ( $cant_reg_pol > 0 && $row_resultado->estado_pedido <= 1 ) {
                        $clase_fila = 'danger';
                    }

                    $cl_peso = '';
                    if ( $row_resultado->peso_total > 0 ) { $cl_peso = 'warning'; }

                //Respuesta pol
                    $indice = '0' . $row_resultado->codigo_respuesta_pol;
                    
                //Identificar cliente
                    $nombre_cliente = $row_resultado->nombre . ' ' . $row_resultado->apellidos;
                    $row_usuario = $this->Db_model->row_id('usuario', $row_resultado->usuario_id);
                    $estado_cliente = 'No Registrado';
                    if ( ! is_null($row_usuario) )
                    {
                        $estado_cliente = $arr_estados[$row_usuario->estado];
                        $cant_contenidos = $this->Db_model->num_rows('meta', "dato_id = 100012 AND elemento_id = {$row_usuario->id}");
                    }
            ?>
            <tr class="<?= $clase_fila ?>">
                <?php if ( $this->session->userdata('rol_id') <= 2  ) { ?>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                <?php } ?>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td><?= $link_pedido ?></td>
                <td class="<?= $cl_col['cliente'] ?>">
                    <?php if ( $row_resultado->usuario_id > 0 ) { ?>
                        <?= $estado_cliente ?>
                        <?= anchor("usuarios/info/{$row_resultado->usuario_id}", $nombre_cliente, 'title="Ver pedidos del cliente"') ?>
                        <a href="<?php echo base_url("usuarios/books/{$row_resultado->usuario_id}") ?>" class="btn btn-default btn-xs" target="_blank" title="Contenidos asignados">
                            <?= $cant_contenidos ?>
                        </a>
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
                <td class="<?= $cl_col['respuesta_pol'] ?>">
                    <?php if ( $cant_reg_pol > 0 ){ ?>
                        <?= anchor("pedidos/pol/{$row_resultado->id}", '<i class="fa fa-credit-card"></i>', 'class="btn btn-warning btn-xs" title="Datos de transación en Pagos On Line"') ?>
                    <?php } ?>
                    <?= $arr_respuestas_pol[$indice] ?>
                </td>
                <td class="<?php echo $cl_col['peso'] ?> <?= $cl_peso ?>">
                    <?php if ( $row_resultado->peso_total > 0 ) { ?>
                        <?php echo $row_resultado->peso_total ?>
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