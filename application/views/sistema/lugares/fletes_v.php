
<?php

    $kilos = 15;
    
    $json_fletes = $this->Db_model->field_id('flete', 1, 'rangos');
    
    $arr_fletes = json_decode($json_fletes);
    
    $valor_rango = $this->Pcrn->valor_rango($arr_fletes, $kilos);
    
    
?>

<table class="table table-hover bg-blanco">
    <thead>
        <th width="45px;" class="warning">ID</th>
        <th>Desde</th>
        <th>Hacia</th>
        <th>Costo base</th>
        <th>Rangos</th>
        <th width="60px"></th>
    </thead>
    <tbody>
        <?php foreach ($fletes->result() as $row_flete) : ?>
        <tr>
            <td class="warning"><span class="etiqueta primario w1"><?= $row_flete->id ?></span></td>
            <td><?= $row_flete->origen_id ?></td>
            <td><?= $row_flete->destino_id ?></td>
            <td><?= $row_flete->costo_fijo ?></td>
            <td><?= $row_flete->rangos ?></td>
            <td>
                <?= anchor("fletes/editar/edit/{$row_flete->id}", '<i class="fa fa-pencil"></i>', 'class="btn-sm btn-info" title="Editar registro"') ?>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>