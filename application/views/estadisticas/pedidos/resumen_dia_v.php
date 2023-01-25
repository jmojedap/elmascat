<?php
    $arr_lapses = array(
        7 => '7 d',
        28 => '28 d',
        90 => '90 d',
        365 => '1 a',
    );
?>

<style>
    #chart_container{
        height: calc(100vh - 170px);
    }
</style>

<div class="text-center">
    <div class="btn-group mb-2">
        <?php foreach ( $arr_lapses as $lapse_days => $lapse_name ) { ?>
        <?php
                $cl_link = $this->Pcrn->clase_activa($lapse_days, $qty_days, 'btn-primary', 'btn-default');
            ?>
        <a href="<?= base_url("estadisticas/resumen_dia/{$lapse_days}") ?>" class="btn <?= $cl_link ?> w2">
            <?= $lapse_name ?>
        </a>
        <?php } ?>
    </div>
</div>

<div class="card">
    <div id="chart_container" class="card-body"></div>
</div>


<?php $this->load->view('assets/highcharts') ?>
<script>
Highcharts.theme = hc_districatolicas_theme;
Highcharts.setOptions(Highcharts.theme);

let chart = new Highcharts.chart('chart_container', {
    title: {
        text: 'Ventas por día'
    },
    chart: {
        type: 'column'
    },
    subtitle: {
        text: 'Miles de pesos de Pesos COP'
    },
    xAxis: {
        categories: [
            <?php foreach ( $resumen_dia->result() as $row_dia ) { ?> '<?= $this->pml->date_format($row_dia->dia, 'd/M'); ?>',
            <?php } ?>
        ]
    },
    yAxis: {
        title: {
            text: '$ Miles'
        }
    },
    legend: {
        layout: 'horizontal',
        align: 'center',
        verticalAlign: 'top'
    },

    plotOptions: {
        column: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [
        {
            name: 'Ventas',
            data: [
                <?php foreach ( $resumen_dia->result() as $row_dia ) { ?>
                <?php
                    $ventas_miles = $this->Pcrn->dividir($row_dia->sum_valor_total, 1000);
                ?>
                <?= intval($ventas_miles); ?>,
                <?php } ?>
            ]
        },
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
    },
    credits: {
        enabled: false
    },
});
</script>