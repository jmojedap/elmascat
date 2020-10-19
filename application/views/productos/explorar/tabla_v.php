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

    //Clases columnas
        $clases_col['botones'] = '';
        $clases_col['selector'] = '';
        $clases_col['botones'] = 'hidden-sm hidden-xs';
        
        if ( $this->session->userdata('rol_id') > 1 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
    //Clases columnas orden
        if ( $busqueda['o'] == 'tipo_id' ) { $clases_head['tipo'] = 'info'; }
        
    //Links orden encabezados
        $encabezados = array('id', 'nombre_producto', 'precio', 'cant_disponibles');
        $orden_alt = $this->Pcrn->alternar($this->input->get('ot'), 'asc', 'desc');
        $b_sin_orden = $this->Pcrn->get_str('o,ot');
        
        foreach ( $encabezados as $encabezado )
        {
            $links_orden[$encabezado] = "{$cf}?{$b_sin_orden}&o={$encabezado}&ot={$orden_alt}";
        }
?>


<table class="table table-default table-responsive bg-blanco" cellspacing="0">
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="10px;">
            <?= form_checkbox($att_check_todos) ?>
        </th>
        <th width="45px;" class="warning">
            <?= anchor($links_orden['id'], 'ID', 'title="Ordenar por ID"') ?>
        </th>
        <th>
            <?= anchor($links_orden['nombre_producto'], 'Nombre', 'title="Ordenar por nombre"') ?>
        </th>
        <th class="<?= $clases_col['precio'] ?>">
            <?= anchor($links_orden['precio'], 'Precio', 'title="Ordenar por cantidad disponible"') ?>
        </th>
        <th class="<?= $clases_col['cant_disponibles'] ?>">
            <?= anchor($links_orden['cant_disponibles'], 'Disponibles', 'title="Ordenar por cantidad disponible"') ?>
        </th>
        <th class="<?= $clases_col['cant_ventas'] ?>">
            Ventas
        </th>
        <th class="<?= $clases_col['peso'] ?>">
            Peso
        </th>
        <th class="<?= $clases_col['promocion'] ?>">Promoción</th>
        <th class="<?= $clases_col['descripcion'] ?>">Descripción</th>
        <th class="<?= $clases_col['puntajes'] ?>">Puntajes</th>
        
        <th class="<?= $clases_col['botones'] ?>" width="10px"></th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_producto;
                    $link_elemento = anchor("productos/ver/{$row_resultado->id}", $nombre_elemento);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Otros
                    $get_str_sin_dcto = $this->Pcrn->get_str('dcto');

                //Vendidos
                    $cant_ventas = $this->Db_model->num_rows('pedido_detalle', "producto_id = {$row_resultado->id} AND pedido_id IN (SELECT id FROM pedido WHERE codigo_respuesta_pol = 1)");
            ?>
            <tr>
                <td class="<?= $clases_col['selector'] ?>">
                    <?= form_checkbox($att_check) ?>
                </td>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td>
                    <?= $link_elemento ?>
                    <br/>
                    <?= $row_resultado->referencia ?>
                    |
                    <span class="suave"><?= $arr_estados[$row_resultado->estado] ?></span>
                </td>
                
                <td class="info text-right <?= $clases_col['precio'] ?>">
                    <?= number_format($row_resultado->precio, 0, ',', '.') ?>
                </td>
                
                <td class="text-right <?= $clases_col['cant_disponibles'] ?>">
                    <?= $row_resultado->cant_disponibles ?>
                </td>

                <td class="text-right <?= $clases_col['cant_ventas'] ?>">
                    <?= $cant_ventas ?>
                </td>

                <td class="text-right <?= $clases_col['peso'] ?>">
                    <?= $row_resultado->peso ?>
                </td>
                
                
                <td class="<?= $clases_col['promocion'] ?>">
                    <?php if ( $row_resultado->promocion_id > 0) { ?>
                        <?= anchor("productos/explorar/?{$get_str_sin_dcto}&dcto={$row_resultado->promocion_id}", $this->App_model->nombre_post($row_resultado->promocion_id)) ?>
                    <?php } ?>
                </td>
                
                <td class="<?= $clases_col['descripcion'] ?>"><?= word_limiter($row_resultado->descripcion, 20) ?></td>
                <td class="<?= $clases_col['puntajes'] ?>"><?= $row_resultado->puntaje ?> - <?= $row_resultado->puntaje_auto ?></td>
                
                <td class="<?= $clases_col['botones'] ?>">                    
                    <?= anchor("productos/editar/{$row_resultado->id}", '<i class="fa fa-edit"></i>', 'class="btn btn-sm" title=""') ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>