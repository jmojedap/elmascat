<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php $this->load->view('templates/libreria/main/head') ?>
    <?php $this->load->view('assets/revslider') ?>
</head>

<body>
    <?php $this->load->view('templates/libreria/main/header') ?>
    <?php $this->load->view('templates/libreria/main/navbar') ?>

    <div class="container main-container">
        <div id="nav_2">
            <?php if ( ! is_null($nav_2) ) { ?>
                <?php $this->load->view($nav_2); ?>
            <?php } ?>
        </div>
        <div id="view_a">
            <?php $this->load->view($view_a) ?>
        </div>
    </div>

    <?php $this->load->view('templates/libreria/main/footer') ?>
</body>

</html>