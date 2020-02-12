<?php $this->load->view($vista_menu); ?>

<?php
    $sum_sum_valor_total = 0;
    
    foreach ( $ventas_ciudad->result() as $row_ciudad ) {
        $sum_sum_valor_total += $row_ciudad->sum_valor_total;
    }
    
    //Clases columnas
        $clases_col['cant_pedidos'] = 'hidden-xs';
        $clases_col['total_productos'] = 'hidden-xs hidden-sm';
        $clases_col['total_extras'] = 'hidden-xs hidden-sm';
        $clases_col['avg_valor'] = 'hidden-xs hidden-sm';
?>

<div class="bs-caja">
    <div class="sep2">
        <p>
            Suma del valor de los pedidos con 
            <span class="resaltar">
                Transacción aprobada
            </span>
        </p>
    </div>
    
    <table class="table">
        <thead>
            <th width="100px">ID</th>
            <th width="100px">Ciudad</th>
            <th width="100px">Departamento</th>
            <th class="info text-center"><?= $this->Pcrn->moneda($sum_sum_valor_total); ?></th>
            <th class="<?= $clases_col['cant_pedidos'] ?>" width="100px">Pedidos</th>
            <th class="<?= $clases_col['avg_valor'] ?>">$ Promedio</th>
            <th class="<?= $clases_col['total_productos'] ?>">Total productos</th>
            <th class="<?= $clases_col['total_extras'] ?>">Total extras</th>
            
        </thead>
        <tbody>
            <?php foreach ($ventas_ciudad->result() as $row_ciudad) : ?>
            <?php
                $porcentaje = $this->Pcrn->int_percent($row_ciudad->sum_valor_total, 15000000);
                $avg_valor = $this->Pcrn->dividir($row_ciudad->sum_valor_total, $row_ciudad->cant_pedidos);
                $ventas_miles = number_format($this->Pcrn->dividir($row_ciudad->sum_valor_total, 1000), 0);

                $row_lugar = $this->Pcrn->registro_id('lugar', $row_ciudad->ciudad_id);
            ?>
            <tr>
                <td><?= $row_ciudad->ciudad_id ?></td>
                <td><?= $this->App_model->nombre_lugar($row_ciudad->ciudad_id) ?></td>
                <td><?= $row_lugar->region ?></td>
                <td class="text-right">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                            <?= $this->Pcrn->moneda($row_ciudad->sum_valor_total) ?>
                        </div>
                    </div>
                </td>
                <td class="<?= $clases_col['cant_pedidos'] ?>"><?= $row_ciudad->cant_pedidos ?></td>
                <td class="<?= $clases_col['avg_valor'] ?>"><?= $this->Pcrn->moneda($avg_valor) ?></td>
                <td class="<?= $clases_col['total_productos'] ?>"><?= $this->Pcrn->moneda($row_ciudad->sum_total_productos) ?></td>
                <td class="<?= $clases_col['total_extras'] ?>"><?= $this->Pcrn->moneda($row_ciudad->sum_total_extras) ?></td>
                
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>      
</div>