<div class="short-description">
    <h2>Tallas</h2>
    <div class="btn-group" role="group" aria-label="...">
        <?php foreach ($variaciones->result() as $row_producto) : ?>
            <?php
            $clase_boton = 'btn-default';
            if ($row_producto->talla == $row_variacion->talla) {
                $clase_boton = 'btn-primary';
            }
            ?>
            <?= anchor("productos/detalle/{$row_producto->id}/{$row_producto->slug}", $row_producto->talla, 'class="w2 btn ' . $clase_boton . '" title=""') ?>
        <?php endforeach ?>
    </div>
</div>