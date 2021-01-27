<?php
    $sum_sum_valor_total = 0;
    
    foreach ( $ventas_departamento->result() as $row_departamento ) {
        $sum_sum_valor_total += $row_departamento->sum_valor_total;
    }
    
    //Clases columnas
        $clases_col['cant_pedidos'] = 'hidden-xs';
        $clases_col['total_productos'] = 'hidden-xs hidden-sm';
        $clases_col['total_extras'] = 'hidden-xs hidden-sm';
        $clases_col['avg_valor'] = 'hidden-xs hidden-sm';
?>

<div class="mb-2">
    <p>
        Suma del valor de los pedidos con <strong class="text-success">Transacción aprobada</strong>
    </p>
</div>

<table class="table bg-white">
    <thead>
        <th width="150px">Ciudad</th>
        <th class="info text-center"><?= $this->Pcrn->moneda($sum_sum_valor_total); ?></th>
        <th class="<?= $clases_col['cant_pedidos'] ?>" width="100px">Pedidos</th>
        <th class="<?= $clases_col['avg_valor'] ?>">$ Promedio</th>
        <th class="<?= $clases_col['total_productos'] ?>">Total productos</th>
        <th class="<?= $clases_col['total_extras'] ?>">Total extras</th>
        
    </thead>
    <tbody>
        <?php foreach ($ventas_departamento->result() as $row_departamento) : ?>
        <?php
            $porcentaje = $this->Pcrn->int_percent($row_departamento->sum_valor_total, 160000000);
            $avg_valor = $this->Pcrn->dividir($row_departamento->sum_valor_total, $row_departamento->cant_pedidos);
            $ventas_miles = number_format($this->Pcrn->dividir($row_departamento->sum_valor_total, 1000), 0, ',', '.');
        ?>
        <tr>
            <td><?= $row_departamento->region ?></td>
            <td class="text-right">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                        <?= $ventas_miles ?>
                    </div>
                </div>
            </td>
            <td class="<?= $clases_col['cant_pedidos'] ?>"><?= $row_departamento->cant_pedidos ?></td>
            <td class="<?= $clases_col['avg_valor'] ?>"><?= $this->Pcrn->moneda($avg_valor) ?></td>
            <td class="<?= $clases_col['total_productos'] ?>"><?= $this->Pcrn->moneda($row_departamento->sum_total_productos) ?></td>
            <td class="<?= $clases_col['total_extras'] ?>"><?= $this->Pcrn->moneda($row_departamento->sum_total_extras) ?></td>
            
        </tr>
        <?php endforeach ?>
    </tbody>
</table>      