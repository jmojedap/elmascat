<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div class="card">
    <div id="chart_container" class="card-body" style="min-height: 600px;"></div>
</div>

<script>
    Highcharts.chart('chart_container', {

    title: {
        text: 'Ventas por mes'
    },
    chart: {
        type: 'line'
    },
    subtitle: {
        text: 'Millones de Pesos COP'
    },
    xAxis: {
        categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
    },
    yAxis: {
        title: {
            text: '$ Millones'
        }
    },
    legend: {
        layout: 'horizontal',
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

    series: [
        <?php foreach ( $resumen_mes as $year => $query ) { ?>
            {
                name: '<?= $year ?>',
                data: [
                    <?php foreach ( $query->result() as $row_mes ) { ?>
                        <?php
                            $ventas_millones = $this->Pcrn->dividir($row_mes->sum_valor_total, 1000000);
                        ?>
                        <?= number_format($ventas_millones, 1); ?>,
                    <?php } ?>
                ]
            },
        <?php } ?>
        
    ],

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