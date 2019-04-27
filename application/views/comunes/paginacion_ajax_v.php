<?php 
    $att_num_pagina = array(
        'id' => 'campo-num_pagina',
        'class' => 'form-control pull-rigth',
        'style' => 'height: 34px;',
        'type' => 'number',
        'value' => $num_pagina + 1,
        'min' => 1,
        'max' => $max_pagina + 1,
        'title' => 'Ir a la página'
    );
?>

<div class="input-group pull-right" style="width: 120px">
    <span class="input-group-addon btn btn-default" type="Página anterior" id="btn_explorar_ant">
        <i class="fa fa-caret-left"></i>
    </span>
    <?php echo form_input($att_num_pagina) ?>
    <span class="input-group-addon btn btn-default" type="Página siguiente" id="btn_explorar_sig">
        <i class="fa fa-caret-right"></i>
    </span>
</div>
