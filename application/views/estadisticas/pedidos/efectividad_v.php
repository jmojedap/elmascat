<?php $this->load->view($vista_menu); ?>

<?php
    //Contador de fila
    $num_fila_pre = 0;
    $sum_sum_valor_total = 0;
    $sum_sum_valor_total_total = 0;
    $cant_pedidos = 0;
    $cant_pedidos_total = 0;
    
    foreach ( $resumen_mes->result() as $row_mes )
    {
        $sum_sum_valor_total += $row_mes->sum_valor_total;
        $cant_pedidos += $row_mes->cant_pedidos;
        
        $num_fila_pre++;
        $row_mes_total = $resumen_mes_total->row($num_fila_pre);
        $sum_sum_valor_total_total += $row_mes_total->sum_valor_total;
        $cant_pedidos_total += $row_mes_total->cant_pedidos;
    }
    
    $porcentaje_total = $this->Pcrn->int_percent($sum_sum_valor_total, $sum_sum_valor_total_total);
    //$porcentaje_total = 30;
    
    //Clases columnas
        $clases_col['valor'] = 'hidden-xs';
        $clases_col['valor_total'] = 'hidden-xs';
        $clases_col['cant_pedidos'] = 'hidden-xs hidden-sm';
        $clases_col['cant_pedidos_total'] = 'hidden-xs hidden-sm';
        
    //Contador de fila
        $num_fila = 0;
?>

<div class="bs-caja">
    <div class="sep2">
        <p>
            % de pedidos finalizados y pagados respecto al total de pedidos iniciados.
        </p>
    </div>
    
    <table class="table">
        <thead>
            <th width="100px">Mes</th>
            <th>
                % Pedidos finalizados
            </th>
            <th width="100px" class="<?= $clases_col['valor'] ?>">Finalizados</th>
            <th width="100px" class="<?= $clases_col['valor_total'] ?>">Iniciados</th>
            <th class="<?= $clases_col['cant_pedidos'] ?>" width="100px">Pedidos</th>
        </thead>
        
        <tbody>
            <tr class="info">
                <td>Total</td>
                <td class="text-right">
                    <div class="progress">
                        <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="<?= $porcentaje_total ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje_total ?>%;">
                            <?= $porcentaje_total ?>%
                        </div>
                    </div>
                </td>
                <td class="<?= $clases_col['valor'] ?> text-right"><?= $this->Pcrn->moneda($sum_sum_valor_total) ?></td>
                <td class="<?= $clases_col['valor_total'] ?> text-right"><?= $this->Pcrn->moneda($sum_sum_valor_total_total) ?></td>
                <td class="<?= $clases_col['cant_pedidos'] ?> text-right">
                    <?= $cant_pedidos ?>/<?= $cant_pedidos_total ?>
                </td>
            </tr>
            <?php foreach ($resumen_mes->result() as $row_mes) : ?>
            <?php
                //Fila, resumen total
                    $num_fila++;
                    $row_mes_total = $resumen_mes_total->row($num_fila);
                
                //Cálculos
                    $porcentaje = $this->Pcrn->int_percent($row_mes->sum_valor_total, $row_mes_total->sum_valor_total);
            ?>
            <tr>
                <td><?= $row_mes->periodo ?></td>
                <td class="text-right">
                    <div class="progress">
                        <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                            <?= $porcentaje ?>%
                        </div>
                    </div>
                </td>
                <td class="<?= $clases_col['valor'] ?> success text-right"><?= $this->Pcrn->moneda($row_mes->sum_valor_total) ?></td>
                <td class="<?= $clases_col['valor_total'] ?> text-right"><?= $this->Pcrn->moneda($row_mes_total->sum_valor_total) ?></td>
                <td class="<?= $clases_col['cant_pedidos'] ?> text-right">
                    <?= $row_mes->cant_pedidos ?>/<?= $row_mes_total->cant_pedidos ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>      
</div>