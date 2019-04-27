<?php $this->load->view('assets/grocery_crud'); ?>
<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('sistema/develop/database_menu_v'); ?>

<?php

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

    $tablas = $this->db->get_where('sis_tabla', 'id NOT IN (1040)');
    
    $opciones_tabla = $this->Pcrn->query_to_array($tablas, 'nombre_tabla', 'nombre_tabla');
    
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var nombre_tabla = '<?= $nombre_tabla ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#campo-tabla').change(function(){
            nombre_tabla = $(this).val();
            window.location = base_url + 'develop/tablas/' + nombre_tabla;
        });
        
    });
</script>

<div class="row">

    <div class="col-md-3 sep2">
        <?= form_dropdown('nombre_tabla', $opciones_tabla, $nombre_tabla, 'id="campo-tabla" class="form-control chosen-select"') ?>
    </div>

    <div class="col-md-9 sep2">

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $output; ?>
    </div>
</div>
    
