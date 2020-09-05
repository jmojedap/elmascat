<script>
// Variables
//-----------------------------------------------------------------------------
    var destino_form = '<?= base_url($destino_form) ?>';
    var producto_id = <?= $row->id ?>;

// Doc Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#producto_form').submit(function(){
           guardar();
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    function guardar(){
        $.ajax({        
            type: 'POST',
            url: destino_form,
            data: $('#producto_form').serialize(),
            success: function(response){
                var type = 'error';
                if ( response.ejecutado == 1 ) { type = 'success'; }
                toastr[type](response.mensaje);
            }
        });
    }
</script>