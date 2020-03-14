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
        
        $clases_col['ml'] = 'hidden';
        $clases_col['ciudad'] = 'hidden-sm hidden-xs';
        $clases_col['no_documento'] = 'hidden-sm hidden-xs';
        $clases_col['rol'] = 'hidden-sm hidden-xs';
        $clases_col['fecha_nacimiento'] = 'hidden-xs hidden-sm';
        $clases_col['telefono'] = 'hidden-xs hidden-sm';
        $clases_col['sexo'] = 'sexo';
        
        if ( $this->session->userdata('rol_id') >= 2 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
        if ( $this->session->userdata('rol_id') <= 1 )
        {
            $clases_col['ml'] = '';
        }
        
    //Clases columnas orden
        if ( $busqueda['o'] == 'tipo_id' ) { $clases_head['tipo'] = 'info'; }
        
    //Links orden encabezados
        $encabezados = array('id');
        $orden_alt = $this->Pcrn->alternar($this->input->get('ot'), 'asc', 'desc');
        $b_sin_orden = $this->Pcrn->get_str('o,ot');
        
        foreach ( $encabezados as $encabezado )
        {
            $links_orden[$encabezado] = "{$cf}?{$b_sin_orden}&o={$encabezado}&ot={$orden_alt}";
        }
?>


<table class="table table-default bg-blanco" cellspacing="0">
    
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="10px;">
            <?= form_checkbox($att_check_todos) ?>
        </th>
        <th width="45px;" class="warning">
            <?= anchor($links_orden['id'], 'ID', 'title="Ordenar por ID"') ?>
        </th>
        <th width="50px;" class="<?= $clases_col['ml'] ?>"></th>
        <th>
            Usuario
        </th>
        
        <th class="<?= $clases_col['rol'] ?>">Rol</th>
        <th class="<?= $clases_col['sexo'] ?>">Sexo</th>
        <th class="<?= $clases_col['no_documento'] ?>">No. Documento</th>
        <th class="<?= $clases_col['fecha_nacimiento'] ?>">Nacimiento</th>
        <th class="<?= $clases_col['telefono'] ?>">Contacto</th>
    </thead>
    
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre . ' ' . $row_resultado->apellidos;
                    $link_elemento = anchor("usuarios/info/$row_resultado->id", $nombre_elemento);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Otros
                    $get_str_sin_dcto = $this->Pcrn->get_str('dcto');
            ?>
            <tr>
                <td class="<?= $clases_col['selector'] ?>">
                    <?= form_checkbox($att_check) ?>
                </td>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                
                <td class="<?= $clases_col['ml'] ?>">
                    <?= anchor("admin/ml/{$row_resultado->id}", '<i class="fa fa-sign-in-alt"></i>', 'class="btn btn-default btn-xs" title="Ingresar con la cuenta de este usuario"') ?>
                </td>
                
                <td>
                    <?= $link_elemento ?>
                    <br/>
                    <?php echo $row_resultado->email ?>
                </td>
                
                
                <td class="<?= $clases_col['rol'] ?>">
                    <?php if ( $row_resultado->estado == 1 ) { ?>
                        <i class="fa fa-check-circle-o text-success" title="Activo"></i>
                    <?php } else { ?>
                        <i class="fa fa-circle-o resaltar" title="Inactivo"></i>
                    <?php } ?>
                    <?= $arr_roles[$row_resultado->rol_id] ?>
                </td>
                
                <td class="<?= $clases_col['sexo'] ?>">
                    <?= $arr_sexos[$row_resultado->sexo] ?>
                </td>
                
                <td class="<?= $clases_col['no_documento'] ?>">
                    <?= $row_resultado->no_documento ?>
                </td>
                
                <td class="<?= $clases_col['fecha_nacimiento'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->fecha_nacimiento, 'Y-M-d') ?>
                    <br/>
                    <span class="suave"><?= $this->Pcrn->tiempo_hace($row_resultado->fecha_nacimiento); ?></span>
                </td>


                <td class="<?= $clases_col['telefono'] ?>">
                    <?= $this->App_model->nombre_lugar($row_resultado->ciudad_id, 'CR') ?>
                    <br/>
                    <span class="suave"><i class="fa fa-phone"></i></span>
                    <?= $row_resultado->telefono ?>
                    <span class="suave"> - <i class="fa fa-mobile"></i></span>
                    <?= $row_resultado->celular ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

<?= $this->load->view('app/modal_eliminar'); ?>