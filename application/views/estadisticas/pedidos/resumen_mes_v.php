<?php $this->load->view($vista_menu); ?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div id="container" style="min-height: 500px;"></div>

<script>
    Highcharts.chart('container', {

    title: {
        text: 'Ventas por mes'
    },

    subtitle: {
        text: 'Millones de Pesos COP'
    },
    xAxis: {
        categories: [
            <?php foreach ( $resumen_mes->result() as $row_mes ) { ?>
                '<?php echo $row_mes->periodo ?>',
            <?php } ?>
        ]
    },
    yAxis: {
        title: {
            text: '$ Millones'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'center',
        verticalAlign: 'top'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

    series: [{
        name: 'Ventas',
        data: [
            <?php foreach ( $resumen_mes->result() as $row_mes ) { ?>
                <?php
                    $ventas_millones = $this->Pcrn->dividir($row_mes->sum_valor_total, 1000000);
                ?>
                <?php echo number_format($ventas_millones, 1); ?>,
            <?php } ?>
        ]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

    });
</script>

<?php
    $sum_sum_valor_total = 0;
    
    foreach ( $resumen_mes->result() as $row_mes ) {
        $sum_sum_valor_total += $row_mes->sum_valor_total;
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
            <th width="100px">Mes</th>
            <th class="info text-center"><?= $this->Pcrn->moneda($sum_sum_valor_total); ?></th>
            <th class="<?= $clases_col['cant_pedidos'] ?>" width="100px">Pedidos</th>
            <th class="<?= $clases_col['avg_valor'] ?>">$ Promedio</th>
            <th class="<?= $clases_col['total_productos'] ?>">Total productos</th>
            <th class="<?= $clases_col['total_extras'] ?>">Total extras</th>
            
        </thead>
        <tbody>
            <?php foreach ($resumen_mes->result() as $row_mes) : ?>
            <?php
                $porcentaje = $this->Pcrn->int_percent($row_mes->sum_valor_total, 12000000);
                $avg_valor = $this->Pcrn->dividir($row_mes->sum_valor_total, $row_mes->cant_pedidos);
            ?>
            <tr>
                <td><?= $row_mes->periodo ?></td>
                <td class="text-right">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                            <?= number_format($row_mes->sum_valor_total, 0, ',', '.') ?>
                        </div>
                    </div>
                </td>
                <td class="<?= $clases_col['cant_pedidos'] ?>"><?= $row_mes->cant_pedidos ?></td>
                <td class="<?= $clases_col['avg_valor'] ?>"><?= $this->Pcrn->moneda($avg_valor) ?></td>
                <td class="<?= $clases_col['total_productos'] ?>"><?= $this->Pcrn->moneda($row_mes->sum_total_productos) ?></td>
                <td class="<?= $clases_col['total_extras'] ?>"><?= $this->Pcrn->moneda($row_mes->sum_total_extras) ?></td>
                
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>      
</div>