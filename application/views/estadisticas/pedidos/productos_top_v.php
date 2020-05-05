<?php $this->load->view($vista_menu); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
                <th class="<?= $clases_col['nombre_producto'] ?>">Producto</th>
                <th class="<?= $clases_col['cant_vendidos'] ?>">Vendidos</th>
                <th class="<?= $clases_col['sum_valor'] ?>" width="50%">$ Venta</th>
            </thead>

            <tbody>
                <?php foreach($productos->result() as $row_producto) : ?>
                <?php
                    $porcentaje = $this->Pcrn->int_percent($row_producto->sum_valor, 15000000);
                ?>
                <tr>
                    <td class="<?= $clases_col['nombre_producto'] ?>">
                        <?= anchor("productos/ver/{$row_producto->producto_id}", $row_producto->nombre_producto, 'class="" title=""') ?>
                    </td>
                    <td class="<?= $clases_col['cant_vendidos'] ?> text-center">
                        <?= $row_producto->cant_vendidos ?>
                    </td>
                    <td class="<?= $clases_col['sum_valor'] ?> text-right">
                        <div class="progress">
                            <div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                                <?= $this->Pcrn->moneda($row_producto->sum_valor) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>