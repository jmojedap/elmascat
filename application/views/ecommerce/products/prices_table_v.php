<table class="table bg-white">
    <?php
        $utilidad_max = $row->precio - $row->costo;
        $margen_max = $this->Pcrn->int_percent($utilidad_max, $row->costo);
    ?>

    <thead>
        <th>Tipo</th>
        <th>Precio</th>
        <th>Margen</th>
    </thead>

    <tbody>
        <tr>
            <td>Costo de compra</td>
            <td><?= $this->Pcrn->moneda($row->costo) ?></td>
            <td></td>
        </tr>

        <?php foreach ($arr_precios as $key_precio => $precio) : ?>
            <?php
                $utilidad = $precio - $row->costo;
                $margen = $this->Pcrn->int_percent($utilidad, $row->costo);
                $pct = $this->Pcrn->int_percent($margen, $margen_max);
                $clase_barra = $this->bootstrap->clase_pct($pct);
            ?>
        <tr>
            <td>
                <?= $arr_tipos_precio[$key_precio] ?>
            </td>
            <td>
                <?= $this->Pcrn->moneda($precio) ?>
            </td>
            <td>
                <?= $this->bootstrap->progress_bar($pct, $margen . '%', $clase_barra); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>