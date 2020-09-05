<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/print_bs4/parts/head_v') ?>
    </head>
    <body>
        <div class="container mt-2">
            <?php $this->load->view($view_a); ?>
        </div>
    </body>
</html>