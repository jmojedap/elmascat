<?php
    //String todos los registros de la lista actual
        $seleccionados_todos = '';
        foreach ( $resultados->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
?>

<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var registro_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){

        $('#check_todos').change(function() {  
            
            if($(this).is(':checked')) {  
                $('.check_registro').prop('checked', true);
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').prop('checked', false);
                seleccionados = '';
            }
        });

        $('.check_registro').change(function() {  
            registro_id = '-' + $(this).data('id');
            if($(this).is(':checked')) {  
                seleccionados += registro_id;
            } else {
                seleccionados = seleccionados.replace(registro_id, '');
            }
        }); 
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
        
        $('.sin_filtrar').hide();
        $('.b_avanzada_no').hide();
        
        $('#alternar_avanzada').click(function(){
            $('.sin_filtrar').toggle('fast');
            $('.b_avanzada_si').toggle();
            $('.b_avanzada_no').toggle();
        });
        
        
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'pedidos/explorar/?' + busqueda_str;
            }
        });
    }
</script>