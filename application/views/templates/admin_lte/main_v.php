<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Sidebar, según el rol del usuario
        $sidebar = 'templates/admin_lte/menus/sidebar_' . $this->session->userdata('role');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/admin_lte/parts/head') ?>
        <?php $this->load->view('templates/admin_lte/parts/foot_scripts') ?>
    </head>
    <body class="skin-blue">
        <div class="wrapper">

            <?php $this->load->view('templates/admin_lte/parts/header'); ?>
            <?php $this->load->view($sidebar); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= $titulo_pagina ?>
                        <small><?= $subtitulo_pagina ?></small>
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php if ( ! is_null($nav_2) ) { ?>
                        <?php $this->load->view($nav_2); ?>
                    <?php } ?>
                    <?php $this->load->view($vista_a); ?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            
            <footer class="main-footer text-center">
                <strong>&copy; 2020 &middot; <a href="http://www.pacarina.com" target="_blank">Pacarina Media Lab</a></strong>
            </footer>
        </div><!-- ./wrapper -->

        

    </body>
</html>