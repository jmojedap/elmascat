<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------

    let base_url = '<?= base_url() ?>';
    let productId = '<?= $row->id ?>';
    let tagId = 0;
    let cant_activadas = <?= $tags_producto->num_rows() ?>;
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
    $(document).ready(function(){
        $('.add_tag').click(function(){
            tagId = $(this).data('id');
            add_tag();
        });
        
        $('.quitar_categoria').click(function(){
            tagId = $(this).data('id');
            quitar_categoria();
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
    //Ajax
    function add_tag(){
        $.ajax({        
            type: 'GET',
            url: base_url + 'productos/add_tag/' + productId + '/' + tagId,
            success: function(){
                activar();
            }
        });
    }
    
    //Ajax
    function quitar_categoria(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'productos/remove_tag/' + productId + '/' + tagId,
            success: function(respuesta){
                desactivar();
            }
        });
    }
    
    function activar()
    {
        cant_activadas++;
        
        $('#fila_' + tagId).addClass('info');
        $('#quitar_' + tagId).removeClass('d-none');
        $('#add_' + tagId).addClass('d-none');
        $('#cant_activadas').html(cant_activadas);
    }
    
    function desactivar()
    {
        cant_activadas += -1;
        
        $('#fila_' + tagId).removeClass('info');
        $('#quitar_' + tagId).addClass('d-none');
        $('#add_' + tagId).removeClass('d-none');
        $('#cant_activadas').html(cant_activadas);
    }
    
</script>

<style>
    tr.nivel_1{
        background: #f1f1f1;
        font-weight: bold;
    }
</style>

<div class="center_box_750">
    <div class="alert alert-info">
        Este <?= $elemento_s ?> tiene 
        <b class="" id="cant_activadas">
            <?= $tags_producto->num_rows() ?>
        </b>
            
        etiquetas asignadas.
    </div>
    <table class="table table-sm table-hover bg-white">
        <tbody>
            <?php foreach ($tags->result() as $tag) : ?>
                <?php
                    $meta_id = $this->Producto_model->in_tag($row->id, $tag->id);
                    $clase_add = '';
                    $clase_quitar = 'd-none';
                    
                    if ( $meta_id > 0 )
                    {
                        $clase_add = 'd-none';
                        $clase_quitar = '';        
                    }
                    
                    $clase_tr = '';
                    if ( $meta_id > 0 ) { $clase_tr = 'info'; }
                    
                    $clase_nivel = '';
                    if ( $tag->nivel == 1 ) {
                        $clase_nivel = 'nivel_1';
                    }
                    
                    $repetir = 0;
                    if ( $tag->nivel > 0 ) $repetir = ($tag->nivel - 1) * 5;
                    $nombre_tag = str_repeat('&nbsp;', $repetir) . ' ' . $tag->nombre_tag;
                ?>
                <tr id="fila_<?= $tag->id ?>" class="row_tag <?= $clase_tr ?> <?= $clase_nivel ?>">
                    <td width="40px">
                        <button id="quitar_<?= $tag->id ?>" class="quitar_categoria btn btn-primary btn-sm <?= $clase_quitar ?>" data-id="<?= $tag->id ?>">
                            <i class="fa fa-check"></i>
                        </button>
                        <button id="add_<?= $tag->id ?>" class="add_tag btn btn-light btn-sm <?= $clase_add ?>" data-id="<?= $tag->id ?>">
                            <i class="far fa-circle"></i>
                        </button>
                    </td>
                    <td><?= $nombre_tag ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
