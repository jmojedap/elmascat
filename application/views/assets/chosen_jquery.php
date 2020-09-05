<?php $carpeta_chosen = URL_ASSETS . 'chosen_jquery/'; ?>
<link rel="stylesheet" href="<?= $carpeta_chosen ?>chosen.css">
<link rel="stylesheet" href="<?= $carpeta_chosen ?>chosen_bootstrap.css">
<script src="<?= $carpeta_chosen ?>chosen.jquery.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $('.chosen-select').chosen();
    });
</script>