<?php $this->load->view($vista_menu); ?>
<?php krsort($arr_metas); ?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function ()
    {
        Highcharts.chart('container', {
            colors: ['#1c95d1', '#64b448'],
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Metas de ventas por año'
            },
            xAxis: {
                categories: [
                    <?php foreach ($arr_metas as $anio => $meta){ ?>
                        '<?= $anio ?>',
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
            series: [{
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
        
    });
</script>

<div class="panel panel-default">
    <div class="panel-body">
        <div id="container" style=""></div>
    </div>
</div>

