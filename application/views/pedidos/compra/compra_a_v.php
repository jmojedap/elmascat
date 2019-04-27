<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
        
    //Identificar valores del campos        
        $valores['nombre'] = $this->Pcrn->si_strlen(set_value('nombre'), $row->nombre);
        $valores['apellidos'] = $this->Pcrn->si_strlen(set_value('apellidos'), $row->apellidos);
        $valores['no_documento'] = $this->Pcrn->si_strlen(set_value('no_documento'), $row->no_documento);
        $valores['direccion'] = $this->Pcrn->si_strlen(set_value('direccion'), $row->direccion);
        $valores['direccion_detalle'] = $this->Pcrn->si_strlen(set_value('direccion_detalle'), $row->direccion_detalle);
        $valores['email'] = $this->Pcrn->si_strlen(set_value('email'), $row->email);
        $valores['celular'] = $this->Pcrn->si_strlen(set_value('celular'), $row->celular);
        $valores['telefono'] = $this->Pcrn->si_strlen(set_value('telefono'), $row->telefono);
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
            'class'   => 'form-control',
            'autofocus'   => TRUE,
            'placeholder'   => 'Nombres',
            'value' =>  $valores['nombre']
        );
        
        $att_apellidos = array(
            'id'     => 'apellidos',
            'name'   => 'apellidos',
            'class'   => 'form-control',
            'placeholder'   => 'Apellidos *',
            'required' => TRUE,
            'value' =>  $valores['apellidos'],
            'title' => 'Escriba sus apellidos'
        );
        
        $att_no_documento = array(
            'id'     => 'no_documento',
            'name'   => 'no_documento',
            'class'   => 'form-control',
            'placeholder'   => 'Cédula *',
            'required' => TRUE,
            'value' =>  $valores['no_documento'],
            'title' => 'Digite su número de cédula o documento'
        );
        
        $att_direccion = array(
            'id'     => 'direccion',
            'name'   => 'direccion',
            'class'   => 'form-control',
            'required' => TRUE,
            'placeholder'   => 'Dirección *',
            'value' =>  $valores['direccion'],
            'title' => 'Digite la dirección donde se entregará el pedido'
        );
        
        $att_direccion_detalle = array(
            'id'     => 'direccion_detalle',
            'name'   => 'direccion_detalle',
            'class'   => 'form-control',
            'placeholder'   => 'Barrio, sector, etc.',
            'value' =>  $valores['direccion_detalle'],
            'title' => 'Agregue información adicional sobre la dirección, barrio, sector, etc'
        );
        
        $att_email = array(
            'id'     => 'email',
            'name'   => 'email',
            'class'   => 'form-control',
            'type'   => 'email',
            'required' => TRUE,
            'placeholder'   => 'Correo electrónico *',
            'value' =>  $valores['email'],
            'title' => 'Escriba su correo electrónico'
        );
        
        $att_celular = array(
            'id'     => 'celular',
            'name'   => 'celular',
            'class'   => 'form-control',
            'placeholder'   => 'Celular *',
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
        
    //Mostrar direcciones
        $mostrar_direcciones = TRUE;
        if ( ! $this->session->userdata('logged') ) { $mostrar_direcciones = FALSE; }
        if ( $direcciones->num_rows() == 0 ) { $mostrar_direcciones = FALSE; }
        
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

<div class="row">
    <div class="col col-md-8">
        <div class="div2">
            <div class="row">
                <div class="col-md-12">
                    <?php if ( $this->session->userdata('logged') ){ ?>
                        <?php if ( $direcciones->num_rows() > 0 ){ ?>
                            

                            
                        <?php } else { ?>
                            <?= anchor("usuarios/direcciones/{$this->session->userdata('usuario_id')}", '<i class="fa fa-map-marker"></i> Agregar direcciones', 'class="btn btn-flat btn-primary" title="Agregar mis direcciones a mi perfil"') ?>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">
                            <i class="fa fa-info-circle"></i>
                            Regístrese en DistriCatolicas.com para guardar sus direcciones, seguir sus compras, obtener promociones y más.
                        </div>
                        <?= anchor('app/registro', '<i class="fa fa-plus"></i> Registrarme', 'class="btn btn-flat btn-primary" title=""') ?>
                        <?= anchor('app/login', '<i class="fa fa-user"></i> Ingresar', 'class="btn btn-flat btn-primary" title=""') ?>
                    <?php } ?>
                </div>
            </div>      
        </div>
        
        <hr/>

        <?php echo validation_errors(); ?>
        
        <div class="form-horizontal">
            <?php if ( $mostrar_direcciones ) { ?>
                <div class="form-group">
                    <label for="opciones" class="col-sm-3 control-label">Mis direcciones</label>
                    <div class="col-sm-9">
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Cargar datos desde mis direcciones <i class="fa fa-caret-down"></i><i class="fa fa-"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="collapseExample">
                <hr/>
                <?php foreach ($direcciones->result() as $row_direccion) : ?>

                    <div class="row div2">
                        <div class="col-md-3">
                            <?= $row_direccion->nombre_direccion ?>
                        </div>

                        <div class="col-md-6">
                            <?= $this->App_model->nombre_lugar($row_direccion->lugar_id, 'CR') ?><br/>
                            <?= $row_direccion->direccion ?><br/>
                            <?= $row_direccion->direccion_detalle ?> <br/>
                            Tel <?= $row_direccion->telefono ?>
                        </div>

                        <div class="col-md-3">
                            <?= anchor("pedidos/set_direccion/{$row->cod_pedido}/{$row_direccion->id}", 'Enviar a esta dirección', 'class="btn btn-warning" title="Enviar pedido a esta dirección"') ?>
                        </div>
                    </div>
                    <hr/>
                <?php endforeach ?>
            </div>
            <?php } ?>
        </div>
        
        <div class="form-horizontal">       
            <div class="form-group">
                <label for="opciones" class="col-sm-3 control-label">Ciudad de entrega</label>
                <div class="col-sm-9">
                    <?= form_dropdown('ciudad_id', $opciones_ciudad, $row->ciudad_id, 'id="ciudad_id" class="form-control chosen-select"') ?>
                </div>
            </div>
        </div>
        
        <div class="shopper-informations">
            <?php if ( $row->ciudad_id < 0 ){ ?>
                <h3 class="alert alert-info" role="alert">Seleccione la ciudad de entrega</h3>
            <?php } ?>
        </div>

        <hr/>

        <?php if ( $row->ciudad_id > 0 ){ ?>

            <div class="shopper-informations">
                <?= form_open($destino_form, $att_form) ?>
                <?= form_hidden('pedido_id', $pedido_id) ?>
                <div class="form-group">
                    <label for="nombre" class="col-sm-3 control-label">Nombres</label>
                    <div class="col-sm-9">
                        <?= form_input($att_nombre); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="apellidos" class="col-sm-3 control-label">Apellidos *</label>
                    <div class="col-sm-9">
                        <?= form_input($att_apellidos); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_documento" class="col-sm-3 control-label">No. Documento * </label>
                    <div class="col-sm-9">
                        <?= form_input($att_no_documento); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Correo electrónico *</label>
                    <div class="col-sm-9">
                        <?= form_input($att_email); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="direccion" class="col-sm-3 control-label">Dirección *</label>
                    <div class="col-sm-9">
                        <?= form_input($att_direccion); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="detalle_direccion" class="col-sm-3 control-label">Detalle dirección</label>
                    <div class="col-sm-9">
                        <?= form_input($att_direccion_detalle); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="att_telefono" class="col-sm-3 control-label">Teléfono</label>
                    <div class="col-sm-9">
                        <?= form_input($att_telefono); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="celular" class="col-sm-3 control-label">Celular *</label>
                    <div class="col-sm-9">
                        <?= form_input($att_celular); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notas" class="col-sm-3 control-label">Notas sobre el pedido</label>
                    <div class="col-sm-9">
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

