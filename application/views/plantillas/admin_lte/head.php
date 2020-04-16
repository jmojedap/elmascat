<meta charset="UTF-8">
<title><?php echo $titulo_pagina ?></title>
<link rel="shortcut icon" href="<?= URL_IMG ?>app/icono.png" type="image/ico"/>

<!--JQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<!--Head tema AdminLTE 2-->
<?php $this->load->view('templates/admin_lte/parts/head_lte') ?>

<!--Estilos adicionales-->
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/admin_lte_add.css' ?>">
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/bs3_bs4.css' ?>">
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/style_pml.css' ?>">

<!-- Vue.js -->
<?php $this->load->view('assets/vue') ?>

<!-- Alertas y notificaciones -->
<?php $this->load->view('assets/toastr') ?>

<script>
    const app_url = '<?php echo base_url() ?>';
</script>

    
    
    
    
    
    

    