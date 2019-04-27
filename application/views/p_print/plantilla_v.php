<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('p_print/partes/head') ?>
    </head>
    <body class="">
        <div class="wrapper">

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
                    <b>Version</b> 1.0
                </div>
                <strong>Copyright &copy; 2009-2017 Pacarina Media Lab.</strong>
            </footer>
        </div><!-- ./wrapper -->

        <?php //$this->load->view('admin_lte/foot_scripts') ?>

    </body>
</html>