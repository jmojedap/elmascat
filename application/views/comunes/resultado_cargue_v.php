<?php
    $clase_resultado = 'danger';
    if ( $valido ) {
        $clase_resultado = 'success';
    }
    
    $detalle_no_cargados = implode(', ', $no_cargados);
    
    $clase_no_cargados = '';
    if ( count($no_cargados) > 0 ) {
        $clase_no_cargados = 'warning';
    }
    
    $cant_cargados = count($array_hoja) - count($no_cargados);
    
?>

<?php if ( ! IS_NULL($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-hover">
            <tbody>
                <tr class="<?= $clase_resultado ?>">
                    <td width="250px">Resultado</td>
                    <td><?= $mensaje ?></td>
                </tr>
                <tr>
                    <td width="250px">Hoja de cálculo</td>
                    <td><?= $nombre_hoja ?></td>
                </tr>
                <tr>
                    <td>Filas encontradas</td>
                    <td><?= count($array_hoja) ?></td>
                </tr>
                <tr>
                    <td>Filas cargadas</td>
                    <td><?= $cant_cargados ?></td>
                </tr>
                <tr class="<?= $clase_no_cargados ?>">
                    <td>Filas no cargadas</td>
                    <td>
                        <?= count($no_cargados) ?> 
                        <?php if ( count($no_cargados) > 0 ){ ?>
                            <button style="margin-left: 10px;" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#detalle_no_cargados" aria-expanded="false" aria-controls="detalle_no_cargados">
                                Ver detalle
                            </button>
                        <?php } ?>
                    </td>
                </tr>
                <tr class="collapse" id="detalle_no_cargados">
                    <td>Números de las filas no cargadas</td>
                    <td>
                        <?= $detalle_no_cargados ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



