<?php $this->load->view('assets/toastr'); ?>
<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/bs_checkboxes'); ?>

<script src="<?php echo URL_RESOURCES ?>js/pcrn.js"></script>


<?php    
    //Clases botones acción
        $clases_btn['eliminar_seleccionados'] = 'hidden';
        if ( $this->session->userdata('rol_id') <= 1 ) { $clases_btn['eliminar_seleccionados'] = ''; }
        
        $clases_btn['exportar'] = 'hidden';
        if ( $this->session->userdata('rol_id') <= 2 ) { $clases_btn['exportar'] = ''; }
?>

<?php $this->load->view("{$carpeta_vistas}script_js"); ?>
<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6">
        <?php $this->load->view("{$carpeta_vistas}form_busqueda_v"); ?>
    </div>

    <div class="col-md-3">
        <a role="button" class="btn btn-default" href="<?= base_url("{$controlador}/exportar/?{$busqueda_str}") ?>" data-toggle="tooltip" title="Exportar resultados a Excel">
            <i class="fa fa-file-excel-o"></i>
        </a>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_eliminar">
            <span title="Eliminar registros seleccionados" data-toggle="tooltip">
                <i class="fa fa-trash-o"></i>
            </span>
        </button>
    </div>
    
    <div class="col-md-3">
        <div class="pull-right sep1">
            <?php $this->load->view('comunes/paginacion_ajax_v'); ?>
        </div>
    </div>
</div>

<div id="tabla_resultados">
    <?php $this->load->view("{$carpeta_vistas}tabla_v"); ?>
</div>
<?php $this->load->view('comunes/modal_eliminar'); ?>