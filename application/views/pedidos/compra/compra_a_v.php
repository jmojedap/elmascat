<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
        
    //Identificar valores del campos        
        $valores['nombre'] = $this->Pcrn->si_strlen(set_value('nombre'), $row->nombre);
        $valores['apellidos'] = $this->Pcrn->si_strlen(set_value('apellidos'), $row->apellidos);
        $valores['no_documento'] = $this->Pcrn->si_strlen(set_value('no_documento'), $row->no_documento);
        $valores['direccion'] = $this->Pcrn->si_strlen(set_value('direccion'), $row->direccion);
        $valores['email'] = $this->Pcrn->si_strlen(set_value('email'), $row->email);
        $valores['celular'] = $this->Pcrn->si_strlen(set_value('celular'), $row->celular);
        $valores['notas'] = $this->Pcrn->si_strlen(set_value('notas'), $row->notas);

    //Campos del pedido
        $att_form = array(
            'class' =>  'form-horizontal'
        );
        
        $att_submit = array(
            'class'  => 'btn-polo-lg',
            'value'  => 'Continuar'
        );

        $att_nombre = array(
            'id'     => 'nombre',
            'name'   => 'nombre',
            'required' => TRUE,
            'class'   => 'form-control',
            'autofocus'   => TRUE,
            'placeholder'   => 'Nombres',
            'value' =>  $valores['nombre']
        );
        
        $att_apellidos = array(
            'id'     => 'apellidos',
            'name'   => 'apellidos',
            'class'   => 'form-control',
            'placeholder'   => 'Apellidos',
            'required' => TRUE,
            'value' =>  $valores['apellidos'],
            'title' => 'Escriba sus apellidos'
        );
        
        $att_no_documento = array(
            'id'     => 'no_documento',
            'name'   => 'no_documento',
            'class'   => 'form-control',
            'placeholder'   => 'CC o NIT',
            'required' => TRUE,
            'value' =>  $valores['no_documento'],
            'title' => 'Digite su número de cédula o documento'
        );
        
        $att_direccion = array(
            'id'     => 'direccion',
            'name'   => 'direccion',
            'class'   => 'form-control',
            'required' => TRUE,
            'placeholder'   => '',
            'value' =>  $valores['direccion'],
            'title' => 'Digite la dirección donde se entregará el pedido'
        );
        
        $att_email = array(
            'id'     => 'email',
            'name'   => 'email',
            'class'   => 'form-control',
            'type'   => 'email',
            'required' => TRUE,
            'placeholder'   => '',
            'value' =>  $valores['email'],
            'title' => 'Escriba su correo electrónico'
        );
        
        $att_celular = array(
            'id'     => 'celular',
            'name'   => 'celular',
            'class'   => 'form-control',
            'placeholder'   => '',
            'required' => TRUE,
            'value' =>  $valores['celular'],
            'title' => 'Escriba su número de celular, sin espacios ni guiones',
            'pattern' =>    '[0-9]{10}'
        );
        
        $att_telefono = array(
            'id'     => 'telefono',
            'name'   => 'telefono',
            'class'   => 'form-control',
            'placeholder'   => 'Teléfono',
            'value' =>  $valores['telefono']
        );
        
        $att_notas = array(
            'id'     => 'notas',
            'name'   => 'notas',
            'class'   => 'form-control',
            'rows'   => 3,
            'placeholder'   => 'Notas sobre su pedido e instrucciones de envío',
            'value' =>  $valores['notas']
        );
        
    //Valores lugar
        $pais_id = 0;
        $region_id = 0;
        $ciudad_id = 0;
        
        if ( ! is_null($row->pais_id) ) { $pais_id = $row->pais_id; }
        if ( ! is_null($row->region_id) ) { $region_id = $row->region_id; }
        if ( ! is_null($row->ciudad_id) ) { $ciudad_id = $row->ciudad_id; }
        
    //Opciones lugares
        $opciones_ciudad = $this->App_model->opciones_lugar("tipo_id = 4 AND activo = 1", 'CRP', 'Ciudad');
        
?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    
    var pais_id = <?= $pais_id ?>;
    var region_id = <?= $region_id ?>;
    var ciudad_id = <?= $ciudad_id ?>;
    var cod_pedido = '<?= $row->cod_pedido ?>';
</script>

<script>
    $(document).ready(function(){

        //Actualizar ciudad
        $('#ciudad_id').change(function(){
            ciudad_id = $(this).val();
            act_lugar();
        });
    });
</script>

<script>
    //Ajax
    function act_lugar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/guardar_lugar/',
            data: {
                ciudad_id : ciudad_id
            },
            success: function(){
                window.location = base_url + 'pedidos/compra_a/' + cod_pedido;
            }
        });
    }
</script>

<a href="<?php echo base_url("pedidos/carrito") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<div class="row">
    <div class="col col-md-8">

        <?php echo validation_errors(); ?>

        <?php if ( ! ($row->ciudad > 0) ) { ?>
        <div class="form-horizontal">
            <div class="form-group">
                <label for="ciudad_id" class="col-md-4 control-label">Ciudad de entrega</label>
                <div class="col-md-8">
                    <?= form_dropdown('ciudad_id', $opciones_ciudad, $row->ciudad_id, 'id="ciudad_id" class="form-control chosen-select"') ?>
                </div>
            </div>
            <hr>
        </div>
        <?php } ?>

        <?php if ( $row->ciudad_id > 0 ){ ?>

            <div class="shopper-informations">
                <?= form_open($destino_form, $att_form) ?>
                <?= form_hidden('pedido_id', $pedido_id) ?>
                <div class="form-group">
                    <label for="nombre" class="col-sm-4 control-label">Nombres | Apellidos</label>
                    <div class="col-sm-4">
                        <?php echo form_input($att_nombre); ?>
                    </div>
                    <div class="col-sm-4">
                        <?php echo form_input($att_apellidos); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_documento" class="col-md-4 control-label">No. Documento </label>
                    <div class="col-md-8">
                        <?= form_input($att_no_documento); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-4 control-label">Correo electrónico</label>
                    <div class="col-md-8">
                        <?= form_input($att_email); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion" class="col-md-4 control-label">Dirección entrega</label>
                    <div class="col-md-8">
                        <?= form_input($att_direccion); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="celular" class="col-md-4 control-label">Celular</label>
                    <div class="col-md-8">
                        <?= form_input($att_celular); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notas" class="col-md-4 control-label">Notas sobre el pedido</label>
                    <div class="col-md-8">
                        <?= form_textarea($att_notas) ?>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
    <div class="col col-md-4">
        <?php $this->load->view('pedidos/compra/totales_v'); ?>
        <?= form_submit($att_submit) ?>
        <?= form_close() ?>
    </div>
</div>

