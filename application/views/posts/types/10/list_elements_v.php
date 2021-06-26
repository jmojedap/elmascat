<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
    //PlugIn, auto completar
    $this->load->view('assets/biggora_autocomplete');
    $this->load->view('assets/html5sortable');  
?>

<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------
    var tabla = '<?= $tabla ?>';
    var tabla_id = <?= $tabla_id ?>;
    var relacionado_id = '<?= $row->id ?>';
    var elemento_id = 0;
    var dato_id = 22;   //Elemento de lista
    var orden = <?= $elementos_lista->num_rows() ?>;
    var nombre_elemento = '';
    var meta_id = 0;
    var cant_elementos = <?= $elementos_lista->num_rows() ?>;
    var str_orden = '';
    
// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function()
    {
        /**
         * Función genera autocompletar
         */
        $('#q_elementos').typeahead({
            ajax: {
                url: url_app + 'app/arr_elementos_ajax/' + tabla,
                method: 'post',
                triggerLength: 2
            },
            onSelect: agregar_elemento
        });
        
        // Configura elemento como reordenable
        $('#lista_elementos').sortable({
            handle: 'span',
            update: function( event, ui ) {
                str_orden = $(this).sortable('serialize');
                reordenar_lista();
            }
        });
        
        //Elimina un elemento de la lista
        $('.quitar_elemento').click(function(){
            meta_id = $(this).data('meta_id');
            elemento_id = $(this).data('elemento_id');
            quitar_elemento();
        });
        
    });
    
// Functions
//-----------------------------------------------------------------------------
    
    //Ajax
    function agregar_elemento(item)
    {
        elemento_id = item.value;
        nombre_elemento = item.text;
    
        $.ajax({        
            type: 'POST',
            url: url_app + 'meta/guardar/',
            data: {
                tabla_id : tabla_id,
                elemento_id : elemento_id,
                relacionado_id : relacionado_id,
                dato_id : dato_id,
                orden : cant_elementos
            },
            success: function(response){
                //console.log(response);
                window.location = url_app + 'posts/list_elements/' + relacionado_id;
            }
        });
    }
    
    //Ejecuta eliminación de un elemento via ajax
    function quitar_elemento()
    {
       $.ajax({
            type: 'POST',
            url: url_app + 'app/eliminar_meta/',
            data: { meta_id: meta_id },
            success: function(){
                console.log('ocultando');
                $('#element_' + elemento_id).hide('slow');
                cant_elementos--;
                $('#cant_elementos').html(cant_elementos);
            }
        });
    }
    
    //Ajax
    function reordenar_lista()
    {
        console.log('Reordenando');
        $.ajax({        
            type: 'POST',
            url: url_app + 'posts/reordenar_lista/' + relacionado_id,
            data: { str_orden : str_orden },
            success: function(response){
                if ( response.affected_rows > 0 ) {
                    toastr['success']('Orden guardado');
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <input
                        name="q_elementos" id="q_elementos" type="text" class="form-control"
                        title="Agregar..." placeholder="Agregar..."
                    >
                </div>

                <ul class="handles list" id="lista_elementos">
                    <?php foreach ($elementos_lista->result() as $row_elemento) : ?>
                        <li id="element_<?= $row_elemento->elemento_id ?>">
                            <span class="btn btn-light btn-sm">
                                <i class="fas fa-arrows-alt-v"></i>
                            </span>

                            <?= $this->App_model->nombre_ac($tabla, $row_elemento->elemento_id) ?>

                            <button class="float-right btn btn-warning btn-sm quitar_elemento" data-meta_id="<?= $row_elemento->id ?>" data-elemento_id="<?= $row_elemento->elemento_id ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>
    </div>
    
    <div class="col-md-4">
        <ul class="list-group">
            <li class="list-group-item">
                Lista de elementos de la tabla <b class="text-success"><?= $tabla ?></b>
            </li>
            <li class="list-group-item">
                Número de elementos: 
                <b class="text-success" id="cant_elementos"><?= $elementos_lista->num_rows() ?></b>
            </li>
            <li class="list-group-item">
                <?= $row->contenido ?>
            </li>
        </ul>
    </div>
</div>