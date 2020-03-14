<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Sidebar, según el rol del usuario
        $sidebar = 'templates/admin_lte/parts/menus/sidebar_' . $this->session->userdata('rol_id');
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
                    <?php $this->load->view($vista_a); ?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0
                </div>
                <strong>Copyright &copy; 2009-2018 <a href="http://www.pacarina.com">Pacarina Media Lab</a>.</strong>
            </footer>
        </div><!-- ./wrapper -->

        

    </body>
</html>