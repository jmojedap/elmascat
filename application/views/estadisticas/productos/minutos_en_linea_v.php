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

let chart = new Highcharts.chart('chart_container', {
    title: {
        text: 'Suscriptores por Edición'
    },
    chart: {
        type: 'column'
    },
    xAxis: {
        categories: [
            <?php foreach ( $ediciones_usuarios->result() as $edicion ) { ?> '<?= $edicion->edicion; ?>',
            <?php } ?>
        ]
    },
    yAxis: {
        title: {
            text: 'Suscriptores'
        }
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
            name: 'Suscriptores',
            data: [
                <?php foreach ( $ediciones_usuarios->result() as $edicion ) { ?>
                <?= intval($edicion->cant_usuarios); ?>,
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