<?php 
    //Nuevas compras
        $clases['nuevas_compras'] = 'bg-blue';
        if ( $nuevas_compras > 0 ) { $clases['nuevas_compras'] = 'bg-orange'; } 
        
    //Meta anual
        $resumen_anio = $this->Estadistica_model->pedidos_resumen_anio(1);
        $json_metas = $this->App_model->valor_opcion(300001);
        $arr_metas = json_decode($json_metas, TRUE);
        $anio = date('Y');
        $sum_valor_total = 0;
        foreach( $resumen_anio->result() as $row_anio )
        {
            if ( $anio == $row_anio->periodo ) { $sum_valor_total = $row_anio->sum_valor_total; }
            
        }
        $pct_meta = $this->Pcrn->int_percent($sum_valor_total/$arr_metas[$anio]);
            
            
?>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box <?= $clases['nuevas_compras'] ?>">
            <div class="inner">
                <h3>
                    <?= $nuevas_compras ?>
                </h3>

                <p>Envíos pendientes</p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="<?= base_url('pedidos/explorar/?est=3') ?>" class="small-box-footer">
                Más información <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= $pct_meta ?>
                    <sup style="font-size: 20px">%</sup>
                </h3>

                <p>Meta anual</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= base_url('pedidos/meta_anual') ?>" class="small-box-footer"> 
                Más info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?= $cant_usuarios ?>
                </h3>

                <p>Clientes registrados</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="<?= base_url('usuarios/explorar') ?>" class="small-box-footer">
                Ver <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>