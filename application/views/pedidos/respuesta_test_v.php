<?php
    $arr_respuesta_pol['ref_venta'] = $this->input->get('ref_venta');
    $arr_respuesta_pol['estado_pol'] = rand(1, 9);
    $arr_respuesta_pol['codigo_respuesta_pol'] = rand(1, 26);
    $arr_respuesta_pol['mensaje'] = 'Mensaje de POL desde formulario a las ' . date('h:i:s');
    $arr_respuesta_pol['ref_pol'] = 'POL123';
    $arr_respuesta_pol['valor'] = '49350';
    $arr_respuesta_pol['moneda'] = 'COP';
    $arr_respuesta_pol['fecha'] = '2015-09-16 17:15:00';
    
    
    foreach($arr_respuesta_pol as $key => $valor) 
    {
        $arr_att_campos[$key] = array(
            'name' => $key,
            'value' => $valor,
            'class' => 'form-control'
        );
    }
    
    $att_submit = array(
        'class'  => 'btn btn-primary',
        'value'  => 'Enviar'
    );
    
?>

<div class="row div2">
    <div class="col-md-12">
        <?= form_open('pedidos/respuesta_test', $att_form) ?>
            <div class="form-group">
                <?= form_submit($att_submit) ?>
                <hr/>
                <?php foreach ($arr_att_campos as $att_campo) : ?>
                    <label for=""><?= $att_campo['name'] ?></label>
                    <?= form_input($att_campo); ?>
                <?php endforeach ?>
            </div>
            
        <?= form_close('') ?>
    </div>
</div>