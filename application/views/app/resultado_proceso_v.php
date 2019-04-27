<?php 
    $resultado_flash = $this->session->userdata('resultado');
?>
<div class="alert <?= $resultado_flash['clase'] ?>" role="alert">
    <?= $resultado_flash['mensaje'] ?>
</div>