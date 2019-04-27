<table class="table table-default bg-blanco">
    <?php
        $utilidad_max = $row->precio - $row->costo;
        $margen_max = $this->Pcrn->int_percent($utilidad_max, $row->costo);
    ?>

    <thead>
        <th class="">Detalle de costo y precios</th>
    </thead>

    <thead>
        <th class="<?= $clases_col['nombre_precio'] ?>">Tipo</th>
        <th class="<?= $clases_col['precio'] ?>">Precio</th>
        <th class="<?= $clases_col['margen'] ?>">Margen</th>
    </thead>

    <tbody>
        <tr>
            <td <?= $clases_col['nombre_precio'] ?>>
                Costo de compra
            </td>
            <td <?= $clases_col['precio'] ?>>
                <?= $this->Pcrn->moneda($row->costo) ?>
            </td>
            <td <?= $clases_col['margen'] ?>>

            </td>
        </tr>
            <?php foreach ($arr_precios as $key_precio => $precio) : ?>
            <?php
                $utilidad = $precio - $row->costo;
                $margen = $this->Pcrn->int_percent($utilidad, $row->costo);
                $pct = $this->Pcrn->int_percent($margen, $margen_max);
                $clase_barra = $this->bootstrap->clase_pct($pct);
            ?>
            <tr>
                <td class="<?= $clases_col['nombre_precio'] ?>">
                    <?= $arr_tipos_precio[$key_precio] ?>
                </td>
                <td class="<?= $clases_col['precio'] ?>">
                    <?= $this->Pcrn->moneda($precio) ?>
                </td>
                <td class="<?= $clases_col['margen'] ?>">
                    <?= $this->bootstrap->progress_bar($pct, $margen . '%', $clase_barra); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>