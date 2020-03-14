<meta charset="UTF-8">
<title><?= $titulo_pagina ?></title>
<link rel="shortcut icon" href="<?= URL_IMG ?>app/icono.png" type="image/ico"/>

<!--JQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<!--Head tema AdminLTE 2-->
<?php $this->load->view('plantillas/admin_lte/head_lte') ?>

<!--Estilos adicionales-->
<link type="text/css" rel="stylesheet" href="<?= base_url('recursos/css/admin_lte_add.css') ?>">

<!-- Vue.js -->
<?php $this->load->view('assets/vue') ?>

<!-- Alertas y notificaciones -->
<?php $this->load->view('assets/toastr') ?>

    
    
    
    
    
    

    