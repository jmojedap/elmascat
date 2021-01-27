<table class="table bg-white">
    <thead>
        <th>Producto</th>
        <th>Vendidos</th>
        <th width="50%">Venta (Millones $)</th>
    </thead>

    <tbody>
        <?php foreach($productos->result() as $row_producto) : ?>
        <?php
            $porcentaje = $this->Pcrn->int_percent($row_producto->sum_valor, 25000000);
        ?>
        <tr>
            <td>
                <?= anchor("productos/ver/{$row_producto->producto_id}", $row_producto->nombre_producto, 'class="" title=""') ?>
            </td>
            <td class="text-center">
                <?= $row_producto->cant_vendidos ?>
            </td>
            <td class="text-right">
                <div class="progress">
                    <div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                        <?= number_format($this->Pcrn->dividir($row_producto->sum_valor, 1000000),2) ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>