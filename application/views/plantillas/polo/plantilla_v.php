<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Libreria Catolica Bogota Colombia">
        <meta content="libreria, catolica, minutos de amor" name="keywords" />
        <meta name="author" content="Revista Minutos de Amor">
        <title><?php echo $titulo_pagina ?></title>
        
        <?php if ( ! is_null($image_src) ){ ?>
            <link rel="image_src" href="<?php echo $image_src ?>">
        <?php } ?>
        
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/icono.png" type="image/ico" />
        
        <?php $this->load->view('assets/jquery'); ?>
        <?php $this->load->view('assets/bootstrap'); ?>
        
        <?php $this->load->view('plantillas/polo/partes/head'); ?>

        <link rel="stylesheet" href="<?php URL_RESOURCES . 'css/polo_add_02.css'; ?>" type="text/css">
        
        <?php $this->load->view('app/google_analytics'); ?>
    </head>

    <body>
        <div class="page"> 
            <!-- Header -->
            <?php $this->load->view('plantillas/polo/partes/header'); ?>
            <!-- end header --> 
            
            <!-- Navbar -->
            <?php $this->load->view('plantillas/polo/partes/navbar'); ?>
            <!-- end nav --> 

            <!--Contenido-->
            <div class="main-container col2-right-layout">
                <div class="main container">
                    <?php $this->load->view($vista_a); ?>
                </div>
            </div>
            
            <?php $this->load->view('plantillas/polo/partes/footer'); ?>
            <!-- End Footer --> 

        </div>
    </body>
</html>