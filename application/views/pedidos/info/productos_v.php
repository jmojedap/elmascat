<div class="card mb-2">
    <div class="card-header">Productos</div>
    
    <table class="table bg-white">
        <thead class="text-c">
            <th width="45px;" class="warning">ID</th>
            <th>Referencia</th>
            <th width="30px"></th>
            <th>Producto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Tipo precio</th>
            <th>Descuento</th>
            <th>Peso</th>
        </thead>

        <tbody>    
            <tr class="table-info">
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td></td>
                <td></td>

                <td class="text-right">
                    <b>
                        $ {{ order.total_productos | currency }}
                    </b>
                </td>
                <td></td>
                <td></td>
                <td>
                    <strong>{{ order.peso_total }} kg</strong>
                </td>
            </tr>
            <?php foreach ($detalle->result() as $row_detalle) : ?>
                <?php
                    $row_producto = $this->Db_model->row_id('producto', $row_detalle->producto_id);

                    $sum_precio = $row_detalle->cantidad * $row_detalle->precio;
                    $sum_peso = $row_detalle->cantidad * $row_detalle->peso;

                    //Descuento
                        $descuento = $row_detalle->cantidad * ($row_detalle->precio_nominal - $row_detalle->precio);
                ?>

                <tr>
                    <td class="warning"><span class="etiqueta primario w1"><?= $row_detalle->producto_id ?></span></td>
                    <td><?= $row_producto->referencia ?></td>
                    <td>
                        <a href="<?= $row_producto->url_thumbnail ?>" data-lightbox="image-1">
                            <img src="<?= $row_producto->url_thumbnail ?>" alt="Miniatura producto" class="w30p">
                        </a>
                    </td>
                    <td>
                        <a href="<?= URL_ADMIN . "productos/info/{$row_detalle->producto_id}" ?>">
                            <?= $row_detalle->nombre_producto ?>
                        </a>
                    </td>
                    <td><?= $row_detalle->cantidad ?></td>
                    <td class="text-right"><?= $this->Pcrn->moneda($row_detalle->precio) ?></td>
                    <td class="text-right"><?= $this->Pcrn->moneda($sum_precio) ?></td>
                    <td>
                        <?= $arr_tipos_precio[$row_detalle->promocion_id] ?>
                    </td>
                    <td>
                        (<?= $this->Pcrn->moneda($descuento) ?>)
                    </td>
                    <td>
                        <?= $sum_peso ?> g
                    </td>
                </tr>
                <?php endforeach ?>
        </tbody>
    </table>
</div>