<script>
    $(document).ready(function(){
        //$('#field-precio').attr('disabled', 'disabled');
        
        $('#field-precio_base').change(function(){
            act_precio();
        });
        $('#field-iva').change(function(){
            act_precio();
        });
        $('#field-iva_porcentaje').change(function(){
            act_precio();
        });
        $('#field-precio').change(function(){
            act_precio();
        });
    });
</script>

<script>
    function act_precio()
    {
        //var precio = parseFloat($('#field-precio_base').val()) + parseFloat($('#field-iva').val());
        var iva = $('#field-precio_base').val() * ($('#field-iva_porcentaje').val()/100);
        var precio = parseFloat($('#field-precio_base').val()) + iva;
        $('#field-iva').val(iva);
        $('#field-precio').val(precio);
    }
</script>

<?php $this->load->view('assets/chosen_jquery'); ?>

<?php if ( ! IS_NULL($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<?php echo $output; ?>

