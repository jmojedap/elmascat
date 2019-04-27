<?php
    $clase = 'alert-info';
    if ( ! is_null($resultado['clase'] ) ) { $clase = $resultado['clase']; };
?>
<div class="alert <?= $clase ?>">
    <i class="fa fa-info-circle"></i>
    <?= $resultado['mensaje'] ?>
</div>

<?php if ( isset($resultado['html']) ) { ?>
    <?= $resultado['html'] ?>
<?php } ?>