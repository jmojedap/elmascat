<?php $this->load->view('assets/grocery_crud'); ?>
<?php $this->load->view('usuarios/direcciones_menu_v'); ?>
<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<?php if ( strlen($this->uri->segment(4)) == 0 ) { ?>
    <table class="table table-default bg-blanco">
        <thead>
            <th class="<?= $clases_col['principal'] ?>" width="50px">Principal</th>
            <th class="<?= $clases_col['nombre_direccion'] ?>">Título</th>
            <th class="<?= $clases_col['direccion'] ?>">Dirección</th>
            <th class="<?= $clases_col['botones'] ?>" width="70px"></th>
        </thead>

        <tbody>
            <?php foreach ($direcciones->result() as $row_direccion) : ?>
                <?php 
                    
                    $icono_principal = '<i class="fa fa-square-o"></i>';
                    $clase_principal = 'default';
                    if ( $row_direccion->es_principal ) 
                    {
                        $icono_principal = '<i class="fa fa-check-square"></i>';
                        $clase_principal = 'info';
                    } 
                ?>
                <tr class="<?= $clase_fila ?>">
                    <td class="<?= $clases_col['principal'] ?>">
                        <?= anchor("usuarios/act_dir_principal/{$row->id}/{$row_direccion->id}", $icono_principal, 'class="btn btn-' . $clase_principal . '" title="Establecer como dirección principal"') ?>    
                    </td>
                    <td class="<?= $clases_col['nombre_direccion'] ?>">
                        <?= $row_direccion->nombre_direccion ?>
                        <br/>
                    </td>
                    <td class="<?= $clases_col['direccion'] ?>">
                        <?= $row_direccion->direccion ?>
                        <br/>
                        <?= $row_direccion->direccion_detalle ?>
                        <br/>
                        <?= $this->App_model->nombre_lugar($row_direccion->lugar_id, 'CRP'); ?>
                        <br/>
                    </td>
                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("usuarios/direcciones/{$row->id}/edit/{$row_direccion->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?= $this->Pcrn->anchor_confirm("usuarios/eliminar_direccion/{$row->id}/{$row_direccion->id}", '<i class="fa fa-trash-o"></i>', 'class="a4"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php } elseif ( $this->uri->segment(4) == 'add' or $direccion_editable ) { ?>
    <div class="bg-blanco">
        <?= $output ?>
    </div>
<?php } else { ?>
    <div class="alert alert-warning">
        <i class="fa fa-info"></i>
        No tiene permiso para editar este registro.
    </div>
<?php } ?>

<?php if ( ! is_null($this->session->userdata('pedido_id')) ) { ?>
    <div class="sep2">
        <?= anchor("pedidos/compra_a/", '<i class="fa fa-arrow-left"></i> Volver al pedido', 'class="btn btn-success" title=""') ?>            
    </div>
<?php } ?>

