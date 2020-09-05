<?php
    $carpeta_tema = URL_RESOURCES . 'templates/polo/blue/';
?>
        <meta charset="utf-8">
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
        <title><?= $head_title ?></title>
        <link rel="shortcut icon" href="<?= URL_IMG ?>app/favicon.png" type="image/ico" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Libreria Catolica Bogota Colombia">
        <meta content="libreria, catolica, minutos de amor" name="keywords" />
        <meta name="author" content="Revista Minutos de Amor">
        
        <?php if ( ! is_null($image_src) ){ ?>
            <link rel="image_src" href="<?= $image_src ?>">
        <?php } ?>
        
        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

        <!-- Bootstrap 3.3.2 -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        
        <?php if ( ENVIRONMENT == 'production' ) : ?>
            <?php $this->load->view('app/google_analytics'); ?>
        <?php endif; ?>

        <!-- Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="stylesheet" href="<?php //echo $carpeta_tema . 'css/animate.css'?>" type="text/css">
        <link rel="stylesheet" href="<?= $carpeta_tema ?>css/style.css" type="text/css">
        <link rel="stylesheet" href="<?= $carpeta_tema ?>css/revslider.css" type="text/css">
        <link rel="stylesheet" href="<?= $carpeta_tema ?>css/owl.carousel.css" type="text/css">
        <link rel="stylesheet" href="<?= $carpeta_tema ?>css/owl.theme.css" type="text/css">

        <link rel="stylesheet" href="<?= URL_RESOURCES . 'css/style_pml.css' ?>" type="text/css">
        <link rel="stylesheet" href="<?= URL_RESOURCES . 'css/bs3_bs4.css' ?>" type="text/css">
        <link rel="stylesheet" href="<?= URL_RESOURCES . 'css/polo_add_12.css' ?>" type="text/css">
        
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,300,700,800,400,600' rel='stylesheet' type='text/css'>

        <!--Iconos Font Awesome-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        
        <!--Javascript-->
        <script type="text/javascript" src="<?= $carpeta_tema ?>js/parallax.js"></script> 
        <script type="text/javascript" src="<?= $carpeta_tema ?>js/common.js"></script> 
        <script type="text/javascript" src="<?= $carpeta_tema ?>js/cloudzoom.js"></script> 
        <script type="text/javascript" src="<?= $carpeta_tema ?>js/revslider.js"></script>
        <script type="text/javascript" src="<?= $carpeta_tema ?>js/owl.carousel.min.js"></script> 

        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

        <script>
            const app_url = '<?= base_url() ?>';
            const url_app = '<?= URL_APP ?>';
            const url_api = '<?= URL_API ?>';
        </script>