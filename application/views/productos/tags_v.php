<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    var tabla_id = '<?= $tabla_id ?>';
    var elemento_id = '<?= $row->id ?>';
    var cant_activadas = <?= $tags_producto->num_rows() ?>;
    //var fila_id = 0;
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
    $(document).ready(function(){
        $('.add_categoria').click(function(){
            categoria_id = $(this).data('id');
            add_categoria();
        });
        
        $('.quitar_categoria').click(function(){
            categoria_id = $(this).data('id');
            quitar_categoria();
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
    //Ajax
    function add_categoria(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'meta/guardar/relacionado_id',
            data: {
                tabla_id : tabla_id,
                elemento_id : elemento_id,
                relacionado_id : categoria_id,
                dato_id : 21
            },
            success: function(){
                //alert(respuesta);
                activar();
            }
        });
    }
    
    //Ajax
    function quitar_categoria(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'meta/eliminar/',
            data: {
                tabla_id : tabla_id,
                elemento_id : elemento_id,
                relacionado_id : categoria_id,
                dato_id : 21
            },
            success: function(respuesta){
                console.log( respuesta);
                desactivar();
            }
        });
    }
    
    function activar()
    {
        cant_activadas++;
        
        $('#fila_' + categoria_id).addClass('info');
        $('#quitar_' + categoria_id).removeClass('hidden');
        $('#add_' + categoria_id).addClass('hidden');
        $('#cant_activadas').html(cant_activadas);
    }
    
    function desactivar()
    {
        cant_activadas += -1;
        
        $('#fila_' + categoria_id).removeClass('info');
        $('#quitar_' + categoria_id).addClass('hidden');
        $('#add_' + categoria_id).removeClass('hidden');
        $('#cant_activadas').html(cant_activadas);
    }
    
</script>

<style>
    tr.nivel_1{
        background: #f9f9f9;
        font-weight: bold;
    }
</style>

<div class="alert alert-info">
    Este <?= $elemento_s ?> tiene 
    <b class="" id="cant_activadas">
        <?= $tags_producto->num_rows() ?>
    </b>
        
    etiquetas asignadas.
</div>

<div class="bs-caja-no-padding">
    <div class="panel panel-default">
        <table class="table table-hover">
            <tbody>
                <?php foreach ($tags->result() as $row_tag) : ?>
                    <?php
                        $meta_id = $this->Producto_model->in_tag($row->id, $row_tag->id);
                        $clase_add = '';
                        $clase_quitar = 'hidden';
                        
                        if ( $meta_id > 0 )
                        {
                            $clase_add = 'hidden';
                            $clase_quitar = '';        
                        }
                        
                        $clase_tr = '';
                        if ( $meta_id > 0 ) { $clase_tr = 'info'; }
                        
                        $clase_nivel = '';
                        if ( $row_tag->nivel == 1 ) {
                            $clase_nivel = 'nivel_1';
                        }
                        
                    $repetir = ($row_tag->nivel - 1) * 5;
                        $nombre_tag = str_repeat('&nbsp;', $repetir) . ' ' . $row_tag->nombre_tag;
                    ?>
                    <tr id="fila_<?= $row_tag->id ?>" class="row_tag <?= $clase_tr ?> <?= $clase_nivel ?>">
                        <td width="40px">
                            <span id="quitar_<?= $row_tag->id ?>" class="quitar_categoria btn btn-primary btn-xs <?= $clase_quitar ?>" data-id="<?= $row_tag->id ?>">
                                <i class="fa fa-check"></i>
                            </span>
                            <span id="add_<?= $row_tag->id ?>" class="add_categoria btn btn-default btn-xs <?= $clase_add ?>" data-id="<?= $row_tag->id ?>">
                                <i class="far fa-circle"></i>
                            </span>
                        </td>
                        <td>
                            
                            <?= $nombre_tag ?>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>