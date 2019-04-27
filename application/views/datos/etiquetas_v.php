<?php $this->load->view('assets/icheck'); ?>

<?php
    //Valores formulario
        $valores['id'] = '';
        $valores['item'] = '';
        $valores['descripcion'] = '';
        $valores['padre_id'] = '';
        $valores['submit'] = 'Agregar';
        
        $clase_form = 'info';
        
        if ( ! is_null($row) ) {
            $valores['id'] = $row->id;
            $valores['item'] = $row->item;
            $valores['slug'] = $row->slug;
            $valores['descripcion'] = $row->descripcion;
            $valores['padre_id'] = $row->padre_id;
            $valores['submit'] = 'Actualizar';
            
            $clase_form = 'success';
        }
        
    //Campos fomulario
        $att_item = array(
            'name' => 'item',
            'id' => 'item',
            'class' => 'form-control',
            'value' => $valores['item'],
            'autofocus' => TRUE,
            'placeholder' => 'Nueva categoría',
            'required' => TRUE
        );

        $att_descripcion = array(
            'name' => 'descripcion',
            'class' => 'form-control',
            'value' => $valores['descripcion'],
            'placeholder' => 'Descripción',
            'required' => TRUE
        );

        $att_slug = array(
            'name' => 'slug',
            'id' => 'slug',
            'value' => $valores['slug'],
        );

        $opciones_etiquetas = $this->Item_model->opciones_id('categoria_id = 21', 'Ninguna');

        $att_submit = array(
            'value' => $valores['submit'],
            'class' => 'btn btn-info'
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
        
        $seleccionados_todos = '';
        foreach ( $etiquetas->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
        
        $padre_id_anterior = 0;
?>

<script>
    
//VARIABLES
//---------------------------------------------------------------------------------------------------

    var base_url = '<?php echo base_url() ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?php echo $seleccionados_todos ?>';
    var texto = '';
    var etiqueta_id = 0;
    
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
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
        
        $('#mostrar_menos').click(function(){
            $('.subnivel').toggle();
            $('#icono_mas').toggle();
            $('#icono_menos').toggle();
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'datos/eliminar_etiquetas',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'datos/etiquetas';
            }
        });
    }
</script>

<style>
    .subnivel{
        background: #f7f7f7;
    }
</style>

<?php $this->load->view('sistema/admin/parametros_menu_v'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="sep2">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default" id="mostrar_menos">
                        <i class="fa fa-minus-circle" id="icono_menos"></i>
                        <i class="fa fa-plus-circle" id="icono_mas" style="display: none"></i>
                    </button>
                    <?php echo anchor("datos/etiquetas", '<i class="fa fa-plus"></i> Nuevo', 'class="btn btn-info" title=""') ?>
                    <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                        <i class="fa fa-trash-o"></i>
                    </a>            
                    <?php echo anchor("datos/exportar_etiquetas", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title=""') ?>
                </div>
            </div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="10px;"><?php echo form_checkbox($att_check_todos) ?></th>
                    <th width="20px">ID</th>
                    <th>Etiqueta</th>
                    <th>Descripción</th>
                    <th>Superior</th>
                    <td width="100px"></td>
                </tr>
            </thead>
            <tbody>
                <tr class="<?php echo $clase_form ?>">
                    <?php echo form_open("datos/guardar_etiqueta/{$valores['id']}", $att_form) ?>
                    
                    <td></td>
                    
                    <td><?php echo $valores['id'] ?></td>
                    <td>
                        <?php echo form_input($att_item); ?>
                    </td>
                    <td>
                        <?php echo form_input($att_descripcion); ?>
                    </td>
                    <td>
                        <?php echo form_dropdown('padre_id', $opciones_etiquetas, $valores['padre_id'], 'class="form-control"') ?>
                    </td>
                    <td>
                        <?php echo form_submit($att_submit) ?>
                    </td>
                    <?php echo form_close('') ?>
                </tr>
                
                <?php foreach ($etiquetas->result() as $row_etiqueta) : ?>
                    <?php
                        $link_etiqueta = "datos/etiquetas/{$row_etiqueta->id}";
                        $repetir = $row_etiqueta->nivel - 1;
                        $nombre_etiqueta = anchor($link_etiqueta, $row_etiqueta->nombre_etiqueta);
                    
                        $clase_row = '';
                        if ( $row_etiqueta->id == $etiqueta_id ) { $clase_row = 'success'; }
                        
                        //Checkbox
                        $att_check['data-id'] = $row_etiqueta->id;
                        
                        $clase_nivel = '';
                        if ( $row_etiqueta->nivel > 1 ) {
                            $clase_nivel = 'subnivel';
                        }
                    ?>
                    <tr class="<?php echo $clase_row ?> padre_<?php echo $row_etiqueta->padre_id ?> <?php echo $clase_nivel ?> <?php echo $clase_mostrar ?>">
                        <td><?php echo form_checkbox($att_check) ?></td>
                        <td class="warning"><?php echo $row_etiqueta->id ?></td>
                        <td>
                            <span class="suave">
                                <?php echo str_repeat('---', $repetir) ?>
                            </span>
                            <?php echo $nombre_etiqueta ?>
                        </td>
                        <td><?php echo $row_etiqueta->descripcion ?></td>
                        <td><?php echo $this->App_model->nombre_item($row_etiqueta->padre_id) ?></td>
                        <td>
                            <?php echo anchor("datos/etiquetas/{$row_etiqueta->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title="Editar categoría"') ?>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $this->load->view('app/modal_eliminar'); ?>