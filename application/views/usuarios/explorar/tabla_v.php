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
        
        if ( $this->session->userdata('role') >= 2 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
        if ( $this->session->userdata('role') <= 1 )
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

    //Estado
    $arr_estados[1] = '<i class="fa fa-check-circle text-success" title="Activo"></i>';
    $arr_estados[0] = '<i class="far fa-circle text-danger" title="Inactivo"></i>';
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
        <th class="<?= $clases_col['telefono'] ?>">Contacto</th>
        <th class="<?= $clases_col['sexo'] ?>">Otros</th>
        <th class="<?= $clases_col['no_documento'] ?>">No. Documento</th>
    </thead>
    
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre . ' ' . $row_resultado->apellidos;
                    $link_elemento = anchor("usuarios/profile$row_resultado->id", $nombre_elemento);

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
                    <?= $row_resultado->email ?>
                </td>
                
                
                <td class="<?= $clases_col['rol'] ?>">
                    <?= $arr_estados[$row_resultado->estado] ?>
                    <?= $arr_roles[$row_resultado->rol_id] ?>
                </td>

                <td class="<?= $clases_col['telefono'] ?>">
                    <?= $row_resultado->celular ?>
                    &middot;
                    <?= $this->App_model->nombre_lugar($row_resultado->ciudad_id, 'CR') ?>
                    <br/>

                    <a href="https://wa.me/57<?= $row_resultado->celular ?>" class="btn-success btn btn-xs" target="_blank">
                        <i class="fab fa-whatsapp"></i> Mensaje
                    </a>
                    
                </td>
                
                <td class="<?= $clases_col['sexo'] ?>">
                    <?= $arr_sexos[$row_resultado->sexo] ?>
                    &middot;
                    <?= $this->pml->ago($row_resultado->fecha_nacimiento, false); ?>
                </td>
                
                <td class="<?= $clases_col['no_documento'] ?>">
                    <?= $row_resultado->no_documento ?>
                </td>
                
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>