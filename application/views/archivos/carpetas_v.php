<script>
    var year = '<?= $year ?>';
    var month = '<?= $month ?>';

    $(document).ready(function(){
        $('#btn_unlink_no_usados').click(function(){
            unlink_no_usados();
        });        

        $('#btn_unlink_thumbnails').click(function(){
            unlink_thumbnails();
        });        

        $('#btn_mod_original').click(function(){
            mod_original();
        });        
    });

// Funciones
//-----------------------------------------------------------------------------

    function unlink_no_usados(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'archivos/unlink_no_usados/' + year + '/' + month,
            success: function(response){
                toastr['info'](response.message)
            }
        });
    }

    function unlink_thumbnails(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'archivos/unlink_thumbnails/' + year + '/' + month,
            success: function(response){
                toastr['info'](response.message)
            }
        });
    }

    function mod_original(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'archivos/mod_original/' + year + '/' + month,
            success: function(response){
                toastr['info'](response.message)
            }
        });
    }
</script>

<?php $this->load->view($vista_menu); ?>

<div class="mb-2">
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" id="btn_unlink_no_usados">
                Eliminar no usados
            </button>
            <button class="btn btn-primary" id="btn_unlink_thumbnails">
                <i class="fa fa-trash"></i> Miniaturas
            </button>
            <button class="btn btn-primary" id="btn_mod_original">
                <i class="fa fa-edit"></i> Original
            </button>
        </div>
    </div>  
</div>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <!-- Años -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle w3">
                <?= $year ?>
            </button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach($years as $year_option) : ?>
                    <?php
                        $clase_anio = $this->Pcrn->clase_activa($year, $year_option, 'active');
                    ?>
                    <li class="<?= $clase_anio ?>">
                        <?= anchor("archivos/carpetas/{$year_option}/{$month}", $year_option) ?>
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
                <?php foreach($months as $cod_mes => $nombre_mes) : ?>
                    <?php
                        $clase_mes = $this->Pcrn->clase_activa($month, $cod_mes, 'active');
                    ?>
                    <li class="<?= $clase_mes ?>">
                        <?= anchor("archivos/carpetas/{$year}/{$cod_mes}", $nombre_mes) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        
        <table class="table">
            <thead>
                <th>Archivo</th>
            </thead>
            <tbody>
                <?php foreach ($archivos as $nombre_archivo) : ?>
                    <tr><td><?= $nombre_archivo ?></td></tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>