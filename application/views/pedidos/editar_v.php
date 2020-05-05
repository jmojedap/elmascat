<?php
    $att_factura = array(
        'id'     => 'campo-factura',
        'name'   => 'factura',
        'class'  => 'form-control',
        'value'  => $row->factura,
        'placeholder'   => 'Nó factura',
        'title'   => 'Escriba el número de la factura del pedido'
    );
    
    $att_no_guia = array(
        'id'     => 'campo-no_guia',
        'name'   => 'no_guia',
        'class'  => 'form-control',
        'value'  => $row->no_guia,
        'placeholder'   => 'Número de guía de envío',
        'title'   => 'Escriba el número guía del envío del pedido'
    );
    
    $att_notas_admin = array(
        'id'     => 'campo-notas_admin',
        'name'   => 'notas_admin',
        'class'  => 'form-control',
        'value'  => $row->notas_admin,
        'rows'  => 4,
        'placeholder'   => 'Notas internas sobre el pedido',
        'title'   => 'Notas internas sobre el pedido, no visibles por el cliente'
    );
    
    $opciones_estado = $this->Item_model->opciones('categoria_id = 7');
?>

<script>
    var pedido_id = '<?php echo $row->id ?>';

    $(document).ready(function(){
        $('#pedido_form').submit(function(){
            act_pedido();
            return false;
        });
    });

    function act_pedido(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'pedidos/guardar_admin/' + pedido_id,
            data: $('#pedido_form').serialize(),
            success: function(response){
                var type = 'success';
                if ( response.status == 1 ) {
                    type = 'success';
                }
                toastr[type](response.message);
            }
        });
    }
</script>

<div class="panel panel-default center_box_750">
    <div class="panel-body">
        <form accept-charset="utf-8" method="POST" id="pedido_form" class="form-horizontal">
            <div class="form-group">
                <label for="estado_pedido" class="col-md-3 control-label">Estado</label>
                <div class="col-md-9">
                    <?= form_dropdown('estado_pedido', $opciones_estado, $row->estado_pedido, 'class="form-control"') ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="factura" class="col-md-3 control-label">No. factura</label>
                <div class="col-md-9">
                    <?= form_input($att_factura); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="no_guia" class="col-md-3 control-label">No. guía</label>
                <div class="col-md-9">
                    <?= form_input($att_no_guia); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notas_admin" class="col-md-3 control-label">Notas internas</label>
                <div class="col-md-9">
                    <?= form_textarea($att_notas_admin); ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-primary w120p" type="submit">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>