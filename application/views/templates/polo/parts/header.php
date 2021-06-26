<?php
    
    $cant_productos = 0;
    if ( ! is_null($this->session->userdata('cant_productos')) ) { $cant_productos = $this->session->userdata('cant_productos'); }
    
    //Formulario búsqueda
        $att_form = array(
            'id' => 'search_mini_form'
        );
?>

<header class="header-container">
    <div class="header-top hidden-xs">
        <div class="container">
            <div class="row"> 
                <!-- Header Language -->
                <div class="col-xs-3">
                    <div class="welcome-msg hidden-xs"> ¡Bienvenidos a DistriCatólicas! </div>
                </div>
                <div class="col-xs-3">
                    <div class="social_links hidden-xs">
                        <a href="https://www.facebook.com/districatolicas/" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.instagram.com/districatolicas/" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-xs-3">
                    <a class="welcome-msg hidden-xs pull-right" href="https://wa.me/573013054053" target="_blank">
                        <?= img(URL_IMG . 'app/whatsapp-logo-vector_16x16.png') ?>
                        WhatsApp 301 305 4053
                    </a>
                </div>
                        
                <div class="col-xs-3">
                    
                    <!-- Header Top Links -->
                    <div class="toplinks">
                        <div class="links">
                            <?php if ( $this->session->userdata('logged') ){ ?>
                                <div>
                                    <a href="<?= base_url("accounts/profile") ?>" class="hidden-xs" title="Mi cuenta de usuario">Mi cuenta</a>
                                </div>
                                <div>
                                    <a href="<?= base_url("pedidos/carrito") ?>" class="hidden-xs">Ir a pagar</a>
                                </div>
                                <div>
                                    <a href="<?= base_url("app/logout") ?>" class="hidden-xs">Cerrar sesión</a>
                                </div>
                            <?php } else { ?>
                                <div class="myaccount">
                                    <a href="<?= base_url("accounts/signup") ?>" title="Registrarme">
                                        <span class="hidden-xs">Registrarme</span>
                                    </a>
                                </div>
                                <div class="check">
                                    <?= anchor("pedidos/carrito", '<span class="hidden-xs">Pagar</span>', 'title="Ir a pagar"') ?>
                                </div>
                                <div class="login">
                                    <?= anchor("accounts/login", '<span class="hidden-xs">Ingresar</span>', 'title="Iniciar sesión"') ?>
                                </div>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <!-- End Header Top Links --> 
                </div>
            </div>
        </div>
    </div>
    <div class="header container">
        <div class="row">
            <div class="col-lg-2 col-sm-3 col-md-2"> 
                <!-- Header Logo --> 
                <a class="logo" title="Inicio" href="<?= base_url() ?>">
                    <img alt="DistriCatólicas" src="<?= URL_IMG ?>app/logo_polo.png">
                </a> 
                <!-- End Header Logo --> 
            </div>
            <div class="col-lg-6 col-sm-6 col-md-8"> 
                <!-- Search-col -->
                <div class="search-box">
                    <?= form_open("productos/catalogo_redirect/") ?>
                        <input type="text" placeholder="Buscar..." value="<?= $filters['q'] ?>" maxlength="70" class="" name="q" id="search" autofocus>
                        <button id="submit-button" class="search-btn-bg">
                            <span>Buscar</span>
                        </button>
                    <?= form_close('') ?>
                </div>
                <!-- End Search-col --> 
            </div>

            <!-- Top Cart -->
            <div class="col-lg-2 col-sm-3 col-md-2">
                <?php $this->load->view('templates/polo/parts/header_top_cart'); ?>
            </div>
            <!-- End Top Cart --> 
            
            <div class="col-lg-2 col-sm-3 col-md-2">
                <div class="top-cart-contain">
                    <a class="btn btn-success" style="background-color: #01e675; border-color: #01e675" href="https://wa.me/573013054053" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        ENVIAR MENSAJE
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>