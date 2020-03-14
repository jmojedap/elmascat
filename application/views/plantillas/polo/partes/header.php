<?php
    
    $cant_productos = 0;
    if ( ! is_null($this->session->userdata('cant_productos')) ) { $cant_productos = $this->session->userdata('cant_productos'); }
    
    //Formulario búsqueda
        $att_form = array(
            'id' => 'search_mini_form'
        );
        
    //Opciones categorías
        $opciones_categorias = $this->Item_model->opciones_id('categoria_id = 21', 'Categorías');
?>

<header class="header-container">
    <div class="header-top">
        <div class="container">
            <div class="row"> 
                <!-- Header Language -->
                <div class="col-xs-3">
                    <div class="welcome-msg hidden-xs"> ¡Bienvenidos a DistriCatólicas! </div>
                </div>
                <div class="col-xs-6">
                    <a class="welcome-msg hidden-xs pull-right" href="https://wa.me/573013054053" target="_blank">
                        <?php echo img(RUTA_IMG . 'app/whatsapp-logo-vector_16x16.png') ?>
                        WhatsApp 301 305 4053
                    </a>
                </div>
                        
                <div class="col-xs-3">
                    
                    <!-- Header Top Links -->
                    <div class="toplinks">
                        <div class="links">
                            <?php if ( $this->session->userdata('logged') ){ ?>
                                <div class="myaccount">
                                    <?php echo anchor("usuarios/mi_perfil", '<span class="hidden-xs">Mi cuenta</span>', 'title="Mi cuenta de usuario"') ?>
                                </div>
                                <div class="check">
                                    <?php echo anchor("pedidos/carrito", '<span class="hidden-xs">Pagar</span>', 'title="Ir a pagar"') ?>
                                </div>
                                <div class="logout">
                                    <?php echo anchor("app/logout", '<span class="hidden-xs">Salir</span>', 'title="Cerrar sesión"') ?>
                                </div>
                            <?php } else { ?>
                                <div class="myaccount">
                                    <a href="<?php echo base_url("accounts/signup") ?>" title="Registrarme">
                                        <span class="hidden-xs">Registrarme</span>
                                    </a>
                                </div>
                                <div class="check">
                                    <?php echo anchor("pedidos/carrito", '<span class="hidden-xs">Pagar</span>', 'title="Ir a pagar"') ?>
                                </div>
                                <div class="login">
                                    <?php echo anchor("app/login", '<span class="hidden-xs">Ingresar</span>', 'title="Iniciar sesión"') ?>
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
                <a class="logo" title="Inicio" href="<?php echo base_url() ?>">
                    <img alt="DistriCatólicas" src="<?php echo URL_IMG ?>app/logo_polo.png">
                </a> 
                <!-- End Header Logo --> 
            </div>
            <div class="col-lg-6 col-sm-6 col-md-8"> 
                <!-- Search-col -->
                <div class="search-box">
                    <?php echo form_open("productos/catalogo_redirect/") ?>
                        <input type="text" placeholder="Buscar..." value="<?php echo $busqueda['q'] ?>" maxlength="70" class="" name="q" id="search" autofocus>
                        <button id="submit-button" class="search-btn-bg">
                            <span>Buscar</span>
                        </button>
                    <?php echo form_close('') ?>
                </div>
                <!-- End Search-col --> 
            </div>

            <!-- Top Cart -->
            <div class="col-lg-2 col-sm-3 col-md-2">
                <?php $this->load->view('plantillas/polo/partes/header_top_cart'); ?>
            </div>
            <!-- End Top Cart --> 

            <div class="col-lg-2 col-sm-3 col-md-2">
                <div class="top-cart-contain">
                    <a class="btn btn-wa" href="https://wa.me/573013054053" target="_blank">
                        <i class="fa fa-whatsapp"></i>
                        ENVIAR MENSAJE
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>