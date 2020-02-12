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
                        <li class="drop-menu">
                            <a href="<?php echo base_url() ?>" class=""><span>Inicio</span></a>
                        </li>
                        <li class="drop-menu">
                            <?php echo anchor("posts/leer/15/sobre-nosotros", '<span>Sobre nosotros</span>') ?>
                        </li>

                        <li class="drop-menu">
                            <?php echo anchor("pedidos/estado", '<span>Estado del pedido</span>') ?>
                        </li>
                        
                        <li class="drop-menu">
                            <?php echo anchor("posts/leer/28/pagos-pse", '<span>Pagos PSE</span>') ?>
                        </li>
                        <li class="drop-menu">
                            <?php echo anchor("pedidos/soy_distribuidor", '<span>Soy Distribuidor</span>') ?>
                        </li>
                        <li class="level0 parent drop-menu active" title="Productos en oferta">
                            <?php echo anchor("productos/catalogo/?ofrt=1", '<span>En Oferta</span>') ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    <!--navmenu--> 
</div>

<!--End mobile-menu -->