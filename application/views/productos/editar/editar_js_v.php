<script>
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function()
    {
        
        $("#campo-puntaje").ionRangeSlider({
            type: "single",
            step: 1,
            from: <?= $row->puntaje ?>,
            hideMinMax: false,
            hideFromTo: false
        });
        
        $('#field-precio').change(function(){
            act_precio_base();
        });
        $('#field-precio_base').change(function(){
            act_precio_base();
        });
        $('#field-iva').change(function(){
            act_precio_base();
        });
        
        $('#field-iva_porcentaje').change(function(){
            act_precio_base();
        });
        
        $('#field-costo').change(function(){
            act_costo();
        });
        
        $("#categorias").chosen().change(function(){
            console.log($(this).val());
            if ( $(this).val() === null ) {
                $('#group-categorias').addClass('has-error');
                $('#alerta-categorias').show();
            } else {
                $('#group-categorias').removeClass('has-error');
                $('#alerta-categorias').hide();
            }
        });
        
        $(".chosen-containter").css('width: 100%');
    });
    
    

// Fuciones
//-----------------------------------------------------------------------------
    
    function act_precio_base()
    {
        var precio_base = $('#field-precio').val() / ( 1 + $('#field-iva_porcentaje').val()/100) ;
        var iva = parseFloat($('#field-precio').val()) - precio_base;
        $('#field-iva').val(iva.toFixed(2));
        $('#field-precio_base').val(precio_base.toFixed(2));
    }
    
    //Si el costo es mayor al precio base, alerta
    function act_costo()
    {
        var diferencia = $('#field-costo').val() - $('#field-precio_base').val();
        if ( diferencia > 0 ) 
        {
            var nuevo_costo = $('#field-precio_base').val();
            $('#field-costo').val(nuevo_costo);
            alert('El costo del producto es superior al precio base, confirme los valores.');
        }
    }
</script>