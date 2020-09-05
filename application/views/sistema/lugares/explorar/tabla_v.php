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
        $clases_col['tipo'] = 'hidden-sm hidden-xs';
        $clases_col['region'] = 'hidden-sm hidden-xs';
        $clases_col['pais'] = 'hidden-sm hidden-xs';
        $clases_col['continente'] = 'hidden-sm hidden-xs';
        $clases_col['botones'] = 'hidden-sm hidden-xs';
        $clases_col['cod_oficial'] = 'hidden-sm hidden-xs';
        $clases_col['tipo_id'] = 'hidden-sm hidden-xs';
        $clases_col['poblacion'] = 'hidden-sm hidden-xs';
        
        if ( $this->session->userdata('rol_id') >= 3 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
    //Clases columnas orden
        if ( $busqueda['o'] == 'tipo_id' ) { $clases_head['tipo'] = 'info'; }
        
    //Links orden encabezados
        $encabezados = array('id', 'nombre_lugar', 'tipo_id', 'region', 'pais');
        $orden_alt = $this->Pcrn->alternar($this->input->get('ot'), 'asc', 'desc');
        $b_sin_orden = $this->Pcrn->get_str('o,ot');
        
        foreach ( $encabezados as $encabezado )
        {
            $links_orden[$encabezado] = "{$cf}?{$b_sin_orden}&o={$encabezado}&ot={$orden_alt}";
        }
?>


<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <th width="10px;">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="check_todos" value="1">
                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                </label>
            </div>
        </th>
        <th width="45px;" class="warning">
            <?= anchor($links_orden['id'], 'ID', 'title="Ordenar por ID"') ?>
        </th>
        <th>
            <?= anchor($links_orden['nombre_lugar'], 'Nombre', 'title="Ordenar por nombre"') ?>
        </th>
        <th class="<?= $clases_col['tipo_id'] ?>">
            <?= anchor($links_orden['tipo_id'], 'Tipo', 'title="Ordenar por tipo"') ?>
        </th>
        <th class="<?= $clases_col['region'] ?>">
            <?= anchor($links_orden['region'], 'Dpto', 'title="Ordenar por departamento"') ?>
        </th>
        <th class="<?= $clases_col['pais'] ?>">
            <?= anchor($links_orden['pais'], 'País', 'title="Ordenar por país"') ?>
        </th>
        <th class="<?= $clases_col['cod_oficial'] ?>">Dane</th>
        <th class="<?= $clases_col['poblacion'] ?>">Población</th>
        <th class="<?= $clases_col['botones'] ?>" width="10px"></th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_lugar;
                    $link_elemento = anchor("lugares/sublugares/{$row_resultado->id}", $nombre_elemento);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
            ?>
            <tr id="fila_<?= $row_resultado->id ?>"> 
                <td>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_registro" value="1" data-id="<?= $row_resultado->id ?>">
                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        </label>
                    </div>
                </td>
                <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td>
                    <?= $link_elemento ?>
                </td>
                <td class="<?= $clases_col['tipo'] ?>"><?= $arr_tipos[$row_resultado->tipo_id] ?></td>
                <td class="<?= $clases_col['region'] ?>"><?= $row_resultado->region ?></td>
                <td class="<?= $clases_col['pais'] ?>"><?= $row_resultado->pais ?></td>
                <td class="<?= $clases_col['cod_oficial'] ?>"><?= $row_resultado->cod_oficial ?></td>
                <td class="<?= $clases_col['poblacion'] ?> text-right"><?= number_format($row_resultado->poblacion, 0) ?></td>

                <td class="<?= $clases_col['botones'] ?>">                    
                    <?= anchor("lugares/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>