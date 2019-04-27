<div class="page-title">
    <h2><?= $row->nombre . ' ' . $row->apellidos ?></h2>
</div>

<div class="alert <?= $resultado['clase'] ?>">
    <i class="fa fa-info-circle"></i>
    <?= $resultado['mensaje'] ?>
</div>