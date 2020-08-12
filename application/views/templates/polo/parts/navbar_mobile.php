<!-- mobile-menu -->
<div class="hidden-desktop" id="mobile-menu">
    <ul class="navmenu">
        <li>
            <div class="menutop">
                <div class="toggle"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></div>
                <h2>Menu</h2>
            </div>
            <ul class="submenu">
                <li>
                    <ul class="topnav">

                        <li class="level0 parent drop-menu">
                            <a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Inicio</a>
                        </li>

                        <?php if ( $this->session->userdata('logged') ){ ?>
                            <li class="level0 parent drop-menu" title="Información de mi cuenta de usuario">
                                <a href="<?php echo base_url("usuarios/mi_perfil") ?>">
                                    Mi cuenta
                                </a>
                            </li>
                            <li class="level0 parent drop-menu" title="Cerrar sesión de usuario">
                                <a href="<?php echo base_url("app/logout") ?>">
                                    Cerrar sesión
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="level0 parent drop-menu" title="Iniciar sesión de usuario">
                                <a href="<?php echo base_url("accounts/login/") ?>">
                                    Iniciar sesión
                                </a>
                            </li>
                            <li class="level0 parent drop-menu" title="Registrarme en Districatolicas.com">
                                <a href="<?php echo base_url("accounts/signup/") ?>">
                                    Registrarme
                                </a>
                            </li>
                        <?php } ?>

                        <li class="level0 parent drop-menu" title="Carrito">
                            <a href="<?php echo base_url("pedidos/carrito") ?>"> Carrito de compras</a>
                        </li>

                        <li class="level0 parent drop-menu">
                            <a href="<?php echo base_url("posts/leer/333/como-comprar-en-districatolicas") ?>"> ¿Como comprar?</a>
                        </li>

                        

                        <li class="level0 nav-6 level-top first parent"> <a class="level-top" href="#"> <span>Catalogo</span> </a>
                            <ul class="level0">
                                <li class="level1 first"><a href="<?php echo base_url() ?>"><span>Inicio</span></a></li>
                                <li class="level1 nav-6-2"> <a href="<?php echo base_url("productos/catalogo/?ofrt=1") ?>"> <span>Ofertas</span> </a> </li>
                                <li class="level1 nav-6-3"> <a href="<?php echo base_url("productos/catalogo/?fi={$fi_novedades}") ?>"> <span>Novedades</span> </a> </li>
                            </ul>
                        </li>
                        <li class="level0 nav-7 level-top first parent"> <a class="level-top" href="#"> <span>Información</span> </a>
                            <ul class="level0">
                                <li class="level1 firts"><a href="<?php echo base_url("pedidos/estado") ?>"> <span>ESTADO COMPRA</span> </a> </li>
                                <li class="level1 nav-7-2"><a href="<?php echo base_url("posts/leer/15/sobre-nosotros") ?>"><span>Sobre Nosotros</span></a></li>
                                <li class="level1 nav-7-3"><a href="<?php echo base_url("posts/leer/28/pagos-pse/") ?>"> <span>Pagos PSE</span> </a> </li>
                            </ul>
                        </li>

                        

                        <li class="drop-menu hidden">
                            <?php echo anchor("pedidos/soy_distribuidor", '<span>Soy Distribuidor</span>') ?>
                        </li>
                        
                        

                        
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    <!--navmenu--> 
</div>

<!--End mobile-menu -->