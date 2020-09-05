<?php $this->load->view($vista_menu) ?>

<?php
    $arr_inventario = $this->Pcrn->query_to_array($inventario_categoria, 'valor', 'categoria_id');

    $sum_inventario = 0;
    foreach ( $arr_inventario as $valor )
    {
        $sum_inventario += $valor;
    }

    //Ventas
    $sum_ventas = 0;
    foreach ( $ventas_categoria->result() as $row_categoria ) { $sum_ventas += $row_categoria->valor; }
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div class="row">
    <div class="col-md-6">
        <table class="table bg-blanco">
            <thead>
                <th>Categoría</th>
                <th>Ventas</th>
                <th>%</th>
                <th>Inventario</th>
                <th>%</th>
                <th>Balance</th>
            </thead>
            <tbody>
                <?php foreach ( $ventas_categoria->result() as $row_categoria ) { ?>
                    <?php
                        $pct_ventas = $this->Pcrn->int_percent($row_categoria->valor, $sum_ventas);
                        $valor_inventario = $arr_inventario[$row_categoria->categoria_id];
                        $pct_inventario = $this->Pcrn->int_percent($valor_inventario, $sum_inventario);
                        $balance = $this->Pcrn->dividir($pct_ventas, $pct_inventario);
                    ?>
                    <tr>
                        <td><?= $row_categoria->nombre_categoria ?></td>
                        <td class="text-right"><?= $this->pacarina->moneda($row_categoria->valor, 'M') ?></td>
                        <td class="text-center"><?= $pct_ventas ?></td>
                        <td class="text-right"><?= $this->pacarina->moneda($valor_inventario, 'M') ?></td>
                        <td><?= $pct_inventario ?></td>
                        <td><?= number_format($balance, 2) ?></td>
                    </tr>
                <?php } ?>
                <tr class="info text-bold">
                    <td>Total</td>
                    <td class="text-right"><?= $this->pacarina->moneda($sum_ventas, 'M') ?></td>
                    <td></td>
                    <td class="text-right"><?= $this->pacarina->moneda($sum_inventario, 'M') ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <div class="panel">
            <div id="chart" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
    </div>
</div>


<script>
    Highcharts.chart('chart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Ventas por Categoría de Producto'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [
            <?php foreach ( $ventas_categoria->result() as $row_categoria ) { ?>
            {
                name: '<?= $row_categoria->nombre_categoria ?>',
                y: <?= $row_categoria->valor ?>
            },
        <?php } ?>
        ]
    }]
});
</script>