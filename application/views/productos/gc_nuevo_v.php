<?php $this->load->view('assets/grocery_crud'); ?>

<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
    $(document).ready(function()
    {        
        $('#field-referencia').focus();
        
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
        });
    });

//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
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
        if ( $('#field-costo').val() > $('#field-precio_base').val() ) 
        {
            var nuevo_costo = $('#field-precio_base').val();
            $('#field-costo').val(nuevo_costo);
            alert('El costo del producto es superior al precio base, confirme los valores.');
        }
    }
</script>

<?php $this->load->view('assets/chosen_jquery'); ?>

<?php if ( ! IS_NULL($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<?php echo $output; ?>

