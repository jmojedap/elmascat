<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/polo/parts/head') ?>
    </head>

    <body>
        <div class="page">
            
            <!-- Header -->
            <?php $this->load->view('templates/polo/parts/header'); ?>
            <!-- end header --> 
            
            <!-- Navbar -->
            <?php $this->load->view('templates/polo/parts/navbar'); ?>
            <!-- end nav --> 

            <!--Contenido-->
            <div class="main-container col2-right-layout">
                <div class="main container">
                    <?php $this->load->view($view_a); ?>

                    <?php if ( ! is_null($view_b) ) { ?>
                        <?php $this->load->view($view_b); ?>
                    <?php } ?>
                </div>
            </div>
            
            <?php $this->load->view('templates/polo/parts/footer'); ?>
        </div>
    </body>
</html>