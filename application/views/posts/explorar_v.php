<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
    $arr_tipos_post = $this->Item_model->arr_interno('categoria_id = 33');
?>

<?php

    $elemento_s = 'Post';  //Elemento en singular
    $elemento_p = 'Posts'; //Elemento en plural
    $controlador = $this->uri->segment(1);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
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
        
        //Opciones de dropdowns
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 33', 'Filtrar por tipo');
        
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
        
        $seleccionados_todos = '';
        foreach ( $resultados->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
        
    //Clases columnas
        $clases_col['nombre_post'] = '';
        $clases_col['tipo_id'] = '';
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var seleccionados = '';
        var registro_id = 0;
        var controlador = '<?= $controlador ?>';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
</script>

<script>
    $(document).ready(function(){
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
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
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });
</script>

<script>
    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + controlador + '/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + controlador + '/explorar';
            }
        });
    }
</script>


<?php $this->load->view('posts/explorar_menu_v'); ?>

<div class="bs-caja">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("app/buscar/{$controlador}/explorar", $att_form) ?>
            <?= form_input($att_q) ?>
            <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control"') ?>
            <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        
        <div class="col-md-3 col-xs-6 sep2">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
                <div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                        <i class="fa fa-trash"></i>
                    </a>            
                </div>

                <div class="btn-group hidden-xs" role="group">
                    <?= anchor("{$controlador}/exportar/?{$busqueda_str}", '<i class="fa fa-download"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-xs-6 sep2">
            <div class="pull-right">
                <?= $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
    
    <table class="table table-hover bg-blanco" cellspacing="0">
        <thead>
            <tr>
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>
                <th><?= $elemento_s ?></th>

                <th class="<?= $clases_col['tipo_id'] ?>">
                    Tipo
                </th>

                <th width="35px" class="hidden-xs"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $this->Pcrn->si_vacia($row_resultado->nombre_post, "Post {$row_resultado->id}");
                    $link_elemento = anchor("posts/index/$row_resultado->id", $nombre_elemento);
                    $editable = $this->Post_model->editable($row_resultado->id);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    <td class="warning"><?= $row_resultado->id ?></td>
                    <td><?= $link_elemento ?></td>

                    <td class="<?= $clases_col['tipo_id'] ?>">
                        <?= $arr_tipos_post[$row_resultado->tipo_id] ?>
                    </td>

                    <td class="hidden-xs">
                        <?php if ( $editable ){ ?>
                            <?= anchor("{$controlador}/editar/{$row_resultado->id}", '<i class="fa fa-edit"></i>', 'class="btn btn-sm btn-light" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('app/modal_eliminar'); ?>