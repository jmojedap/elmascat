<?php $this->load->view('assets/icheck'); ?>

<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'role' => 'form',
            'class' => 'form-inline',
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
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
        
        $seleccionados_todos = $this->Pcrn->query_to_str($resultados, 'id');
        
    //Clases de colmnas
        $clases_col['descripcion'] = 'hidden-xs hiden-sm';
        $clases_col['carpeta'] = 'hidden-xs hiden-sm';
        $clases_col['botones'] = 'hidden-xs hiden-sm';
        
        if ( $this->session->userdata('rol_id') > 1 ) { $clases_col['selector'] = 'hidden'; }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
        var base_url = '<?= base_url() ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
                seleccionados = '';
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'archivos/eliminar_seleccionados/img',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'archivos/imagenes';
            }
        });
    }
</script>

<?php $this->load->view($vista_menu); ?>

<div class="bs-caja">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("app/buscar/archivos/imagenes", $att_form) ?>
                <?= form_input($att_q) ?>
                <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        <div class="col-md-3 sep2">
            <?php if ( $this->session->userdata('usuario_id') <= 1 ) { ?>
                <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                    <i class="fa fa-trash"></i>
                </a>
                <?= anchor("{$this->uri->segment(1)}/exportar/?{$busqueda_str}", '<i class="fa fa-download"></i> Exportar', 'class="btn btn-success hidden-sm hidden-xs" title="Exportar resultados a archivo de MS Excel"') ?>
            <?php } ?>
        </div>

        <div class="col-md-3 sep2">
            <div class="pull-right">
                <?= $this->pagination->create_links(); ?>       
            </div>
        </div>   
    </div>
    
    <table class="table table-responsive table-hover" cellspacing="0">
        <thead>
            <tr>
                <th width="10px;" class="<?php echo $clases_col['selector'] ?>"><?= form_checkbox($att_check_todos) ?></th>
                <th width="45px;">ID</th>
                <th width="45px;">Imagen</th>
                <th>Nombre archivo</th>
                <th class="<?= $clases_col['descripcion'] ?>">Descripción</th>
                <th class="<?= $clases_col['carpeta'] ?>">Carpeta</th>
                <th class="<?= $clases_col['botones'] ?>" width="50px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_archivo){ ?>
                <?php
                    //Variables
                        $nombre_archivo = $row_archivo->titulo_archivo;
                        $link_archivo = anchor("archivos/editar/{$row_archivo->id}", $nombre_archivo);
                        $editable = $this->Archivo_model->editable($row_archivo->id);

                    //Checkbox
                        $att_check = array(
                            'id' => 'registro_' . $row_archivo->id,
                            'value' => 1,
                            'class' => 'check_registro',
                            'data-id' => $row_archivo->id
                        );

                    //Imagen
                        $att_img = $this->Archivo_model->att_thumbnail($row_archivo->id);

                ?>
                <tr>
                    <td class="<?php echo $clases_col['selector'] ?>">
                        <?= form_checkbox($att_check) ?>
                    </td>
                    <td class="warning"><span class="etiqueta primario w1"><?= $row_archivo->id ?></span></td>
                    <td>
                        <div class="thumbnail">
                            <div class="miniatura">
                                <?= anchor("archivos/editar/{$row_archivo->id}", img($att_img)) ?>
                            </div>
                        </div>
                        
                        
                    </td>
                    <td>
                        <?= $link_archivo ?>
                        <br/>
                        <span class="suave"><?= $row_archivo->nombre_archivo ?></span>
                    </td>
                    <td class="<?= $clases_col['descripcion'] ?>">
                        <?= $row_archivo->descripcion ?>
                    </td>
                    <td class="<?= $clases_col['carpeta'] ?>"><?= word_limiter($row_archivo->carpeta, 10) ?></td>
                    <td class="<?= $clases_col['botones'] ?>">
                        <?php if ( $editable ){ ?>
                            <?= anchor("archivos/editar/{$row_archivo->id}", '<i class="fa fa-edit"></i>', 'class="btn btn-sm btn-light" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>


<?= $this->load->view('app/modal_eliminar'); ?>