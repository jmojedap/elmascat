<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var producto_id = 0;

//DOCUMENT
//---------------------------------------------------------------------------------------------------

    $(document).ready(function(){
        $('.ajax_add_to_cart_button').click(function(){
            producto_id = $(this).data('producto_id');
            guardar_detalle();
            return false;
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    //Ajax
    function guardar_detalle(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/guardar_detalle',
            data: {
                producto_id : producto_id,
                cantidad : 1
            },
            success: function(response){
                console.log(response);
                window.location = base_url + 'pedidos/carrito';
            }
        });
    }
</script>