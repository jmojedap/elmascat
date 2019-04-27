<?php $this->load->view($vista_menu); ?>

<div class="sep2">
    <div class="row">
        <div class="col-md-12">
            <?= anchor("archivos/unlink_no_usados/{$anio}/{$mes}", '<i class="fa fa-trash-o"></i> No usados', 'class="btn btn-success" title=""') ?>
            <?= anchor("archivos/mod_original/{$anio}/{$mes}", 'Modificar original', 'class="btn btn-default" title=""') ?>
        </div>
    </div>  
</div>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <!-- Años -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle w3">
                <?= $anio ?>
            </button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach($anios as $opcion_anio) : ?>
                    <?php
                        $clase_anio = $this->Pcrn->clase_activa($anio, $opcion_anio, 'active');
                    ?>
                    <li class="<?= $clase_anio ?>">
                        <?= anchor("archivos/carpetas/{$opcion_anio}/{$mes}", $opcion_anio) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Meses -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle w3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= $nombre_mes ?>
            </button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach($meses as $cod_mes => $nombre_mes) : ?>
                    <?php
                        $clase_mes = $this->Pcrn->clase_activa($mes, $cod_mes, 'active');
                    ?>
                    <li class="<?= $clase_mes ?>">
                        <?= anchor("archivos/carpetas/{$anio}/{$cod_mes}", $nombre_mes) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <hr/>
        
        <ul>
            <?php foreach ($archivos as $nombre_archivo) : ?>
                <li><?= $nombre_archivo ?></li>
            <?php endforeach ?>
        </ul>
    </div>
</div>