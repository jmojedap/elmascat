<?php
    $payu_data['ref_venta'] = $row->cod_pedido;
    $payu_data['mensaje'] = 'Mensaje de POL desde formulario a las ' . date('h:i:s');
    $payu_data['ref_pol'] = 'POL123';
    $payu_data['valor'] = $row->valor_total;
    $payu_data['moneda'] = 'COP';
    $payu_data['fecha_procesamiento'] = date('Y-m-d H:i:s');

    $options_estado_pol = $this->Item_model->options('categoria_id = 9');
    $options_codigo_respuesta_pol = $this->Item_model->options('categoria_id = 10');
?>

<form action="<?php echo base_url('pedidos/respuesta/') ?>" accept-charset="utf-8" method="GET">
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-8 offset-md-4">
                    <button class="btn btn-success w120p" type="submit">
                        Enviar
                    </button>
                </div>
            </div>

            <div class="form-group row">
                <label for="estado_pol" class="col-md-4 col-form-label text-right">estado pol</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('estado_pol', $options_estado_pol, '04', 'class="form-control"') ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="codigo_respuesta_pol" class="col-md-4 col-form-label text-right">código respuesta pol</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('codigo_respuesta_pol', $options_codigo_respuesta_pol, '01', 'class="form-control"') ?>
                </div>
            </div>

            <?php foreach ( $payu_data as $field => $field_value ) { ?>

            <div class="form-group row">
                <label for="" class="col-md-4 col-form-label text-right"><?php echo $field ?></label>
                <div class="col-md-8">
                    <input
                        type="text"
                        name="<?php echo $field ?>"
                        required
                        class="form-control"
                        value="<?php echo $field_value ?>"
                        >
                </div>
            </div>

            <?php } ?>
            
        </div>
    </div>
</form>