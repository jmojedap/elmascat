<?php $this->load->view($vista_menu); ?>

<?php
    $arr_lapses = array(
        7 => '7 d',
        28 => '28 d',
        90 => '90 d',
        365 => '1 a',
    );
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div class="btn-group mb-2">
    <?php foreach ( $arr_lapses as $lapse_days => $lapse_name ) { ?>
        <?php
            $cl_link = $this->Pcrn->clase_activa($lapse_days, $qty_days, 'btn-primary', 'btn-default');
        ?>
        <a href="<?= base_url("pedidos/resumen_dia/{$lapse_days}") ?>" class="btn <?= $cl_link ?> w2">
            <?= $lapse_name ?>
        </a>
    <?php } ?>
</div>


<div id="container" style="max-height: 850px;"></div>

<script>
    Highcharts.chart('container', {

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
            <?php foreach ( $resumen_dia->result() as $row_dia ) { ?>
                '<?= $this->pml->date_format($row_dia->dia, 'd/M'); ?>',
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
    }

    });
</script>