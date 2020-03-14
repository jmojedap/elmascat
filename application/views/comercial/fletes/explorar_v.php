<?php
    //Fletees
      $arr_origenes = $this->Flete_model->origenes();
      $str_origenes = implode(', ', $arr_origenes);
      $opciones_origen = $this->App_model->opciones_lugar("id IN ({$str_origenes})", 1, '[Ciudad de origen]');
?>

<?php

    //Resultados de cargue, si es que aplica
        $res_proceso = $this->session->flashdata('res_proceso');
        
    //Configuración
        //$cf_explorar = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        $cf_explorar = "app/buscar/fletes/explorar";

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_texto_busqueda = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-info btn-flat',
            'value' =>  'Buscar'
        );
        
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );
?>

<script>
// Variables
//-----------------------------------------------------------------------------
        var base_url = '<?= base_url() ?>';
        var controlador = '<?= $this->uri->segment(1); ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var registro_id = 0;

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        
        $('.check_registro').change(function(){
            registro_id = '-' + $(this).data('id');
            
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
        });
        
        $('#check_todos').change(function() {
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').prop('checked', true);
                $('.check_registro').each( function(key, element) {
                    seleccionados += '-' + $(element).data('id');
                });
            } else {
                //Desactivado
                $('.check_registro').prop('checked', false);
                seleccionados = '';
            }
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + controlador + '/eliminar_seleccionados/',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + controlador + '/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu); ?>

<div class="bs-caja">
    <!--HERRAMIENTAS-->
    <!------------------------------------------------------------------------------------------->

    <div class="sep2">
        <div class="row">
            <div class="col-md-6">
                <?= form_open($cf_explorar, $att_form) ?>
                    <?= form_input($att_texto_busqueda) ?>
                    <?= form_dropdown('categoria_id', $opciones_origen, $busqueda['cat'], 'class="form-control"') ?>
                    <?= form_submit($att_submit) ?>
                <?= form_close() ?>
            </div>

            <div class="col-md-3">
                <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                    <i class="fa fa-trash"></i>
                </a>
                <?= anchor("{$this->uri->segment(1)}/exportar/?{$busqueda_str}", '<i class="fa fa-download"></i> Exportar', 'class="btn btn-success" title="Exportar resultados a archivo de MS Excel"') ?>
            </div>

            <div class="col-md-3">
                <div class="pull-right">
                    <?= $this->pagination->create_links(); ?>       
                </div>
            </div>
        </div>
    </div>

    <!--TABLA DE RESULTADOS-->
    <!------------------------------------------------------------------------------------------->

    <table class="table table-hover" cellspacing="0">
        <thead>
            <tr class="info">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="45px;" class="warning">ID</th>
                <th>Desde</th>
                <th>Destino</th>
                <th>Dpto/Estado</th>
                <th>País</th>
                <th>Costo fijo</th>
                <th>Rangos</th>
                <th>Ejemplo kg</th>
                <th>Ejemplo costo</th>
                <th width="50px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                $funcion = 'subfletes';
                if ( $row_resultado->tipo_id == 4 ) { $funcion = 'fletes'; }
                $row_destino = $this->Pcrn->registro_id('lugar', $row_resultado->destino_id);

                //Variables
                    $nombre_destino = $this->App_model->nombre_lugar($row_resultado->destino_id);
                    //$nombre_destino = anchor("fletes/{$funcion}/$row_resultado->id", $nombre_destino);
                    $editable = TRUE;

                //Checkbox
                    $att_check['data-id'] = $row_resultado->flete_id;

                //Ejemplo peso, simulado
                    $ejemplo_peso = rand(1, 25);
                    $ejemplo_flete = $this->Flete_model->flete(909, $row_resultado->destino_id, $ejemplo_peso);                

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->flete_id ?></span></td>
                    <td><?= $this->App_model->nombre_lugar($row_resultado->origen_id) ?></td>
                    <td><?= $nombre_destino ?></td>
                    <td><?= $row_destino->region ?></td>
                    <td><?= $row_destino->pais ?></td>
                    <td><?= $this->Pcrn->moneda($row_resultado->costo_fijo) ?></td>
                    <td><?= $row_resultado->rangos ?></td>
                    <td><?= $ejemplo_peso ?></td>
                    <td><?= $this->Pcrn->moneda($ejemplo_flete) ?></td>

                    <td>
                        <?php if ( $editable ){ ?>
                            <?= anchor("fletes/editar/edit/{$row_resultado->flete_id}", '<i class="fa fa-pencil-alt"></i>', 'class="btn btn-light btn-sm" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>      
</div>

<?= $this->load->view('app/modal_eliminar'); ?>