<?php krsort($arr_metas); ?>

<style>
    #chart_container{
        height: calc(100vh - 130px);
    }
</style>

<div class="card">
    <div id="chart_container" class="card-body"></div>
</div>

<?php $this->load->view('assets/highcharts') ?>

<script>
Highcharts.theme = hc_districatolicas_theme;
Highcharts.setOptions(Highcharts.theme);
Highcharts.chart('chart_container', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Metas de ventas por año'
    },
    xAxis: {
        categories: [
            <?php foreach ($arr_metas as $anio => $meta){ ?> '<?= $anio ?>',
            <?php } ?>
        ],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Ventas (Millones)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' millones'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [
        {
            name: 'Meta',
            data: [
                <?php foreach ($arr_metas as $anio => $meta){ ?>
                <?php
                    $cant_dias_total = $this->Pcrn->dias_lapso($anio - 1 . '/12/31', date('Y-m-d'));
                    $cant_dias = $this->Pcrn->limitar_entre($cant_dias_total, 1, 365);
                    $meta_diaria = $this->Pcrn->dividir($arr_metas[$anio], 365);
                    $meta_actual = $meta_diaria * $cant_dias;
                ?>
                <?= number_format($meta_actual / 1000000, 1) ?>,
                <?php } ?>
            ]
    }, {
        name: 'Vendido',
        data: [
            <?php foreach ($resumen_anio->result() as $row_anio) { ?>
            <?= number_format($row_anio->sum_valor_total / 1000000, 1) ?>,
            <?php } ?>
        ]
    }]
});
</script>