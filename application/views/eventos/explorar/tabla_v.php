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
        $clases_col['selector'] = '';
        $clases_col['botones'] = 'hidden-sm hidden-xs';
        
        $clases_col['tipo'] = '';
        
        if ( $this->session->userdata('rol_id') >= 2 )
        {
            $clases_col['selector'] = 'hidden';
        }
        
    //Clases columnas orden
        if ( $busqueda['o'] == 'tipo_id' ) { $clases_head['tipo'] = 'info'; }
?>

<table class="table table-default bg-blanco" cellspacing="0">
    
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="10px;">
            <?= form_checkbox($att_check_todos) ?>
        </th>
        <th width="45px;" class="warning">
            ID
        </th>
        
        
        
        <th width="50px;" class="<?= $clases_col['info'] ?>"></th>
        
        <th class="<?php echo $clases_col['tipo'] ?>">Tipo evento</th>
        <th class="<?= $clases_col['inicio'] ?>">Iniciado</th>
        <th>Nombre evento</th>
        <th class="<?php echo $clases_col['creador'] ?>">Usuario</th>
    </thead>
    
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    /*$nombre_elemento = $row_resultado->nombre_evento;
                    $link_elemento = anchor("evento/info/$row_resultado->id", $nombre_elemento);*/

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
                
                
                
                <td>
                    <a href="<?php echo base_url("evento/info/$row_resultado->id") ?>" class="btn btn-default">
                        <i class="fa fa-plus"></i>
                    </a>
                </td>
                
                <td class="<?= $clases_col['tipo'] ?>">
                    <?php echo $arr_tipos[$row_resultado->tipo_id] ?>
                </td>
                
                <td>
                    <?php echo $this->Pcrn->fecha_formato($row_resultado->inicio, 'Y-M-d H:i') ?>
                    <br/>
                    <span class="suave">
                        <?php echo $this->Pcrn->tiempo_hace($row_resultado->inicio, true); ?>
                    </span>
                </td>
                
                <td>
                    <?php echo $row_resultado->nombre_evento ?>
                </td>
                
                <td class="<?= $clases_col['creador'] ?>">
                    <?php echo $this->App_model->nombre_usuario($row_resultado->creador_id, 2); ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

<?= $this->load->view('app/modal_eliminar'); ?>