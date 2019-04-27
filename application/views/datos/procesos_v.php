<?php if ( ! is_null($clase_alert) ){ ?>
    <h4 class="<?= $clase_alert ?>">
        <?= $mensaje ?>
    </h4>
<?php } ?>

<div class="panel">
    <div class="panel-body">
        <table class="table" cellspacing="0">
            <thead>
                <th style="width: 100px;">Ejecutar</th>
                <th>Procesos</th>
                <th>Descripción</th>
            </thead>
            <tbody>
                <tr>
                    <td><?= anchor('datos/', 'Ejecutar', 'class="btn btn-info"') ?></td>
                    <td>Eliminación en cascada</td>
                    <td>Elimina los registros huérfanos de tablas relacionadas.</td>
                </tr>
            </tbody>
        </table>  
    </div>
</div>




