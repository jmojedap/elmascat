<?php 
    $clases_menu['promo'] = 'link_especial';
    
    if ( $this->input->get('ofrt') == 1 ) { $clases_menu['promo'] = 'active'; }
    
    $clases_menu['novedades'] = 'link_yellow ';
    $mktime = strtotime(date('Ymd') . ' -3 months');
    $fi_novedades = date('Y-m-d', $mktime);    //Creados hace menos de tres meses
    if ( strlen($this->input->get('d1')) > 0 ) { $clases_menu['novedades'] = 'active'; }

    //Temporada
    $clases_menu['navidad'] = 'link_green ';
    if ( strlen($this->input->get('tag')) == 677 ) { $clases_menu['navidad'] = 'active'; }

    if ( $this->uri->segment(4) == 'pagos-pse' ) { $clases_menu['pagos_pse'] = 'active'; }
    if ( $this->uri->segment(4) == 'sobre-nosotros' ) { $clases_menu['sobre_nosotros'] = 'active'; }
    if ( $this->uri->segment(2) == 'estado' ) { $clases_menu['pedidos_estado'] = 'active'; }
?>

<ul id="nav" class="hidden-xs">
    <li class="level0 parent drop-menu"><a href="<?= base_url() ?>index.php">inicio</a></li>
    
    <li class="level0 parent drop-menu" title="Productos en oferta">
        <a href="<?= URL_APP . "productos/catalogo/?promo=1" ?>" class="<?= $clases_menu['promo'] ?>">
            Ofertas
        </a>
    </li>
    
    <li class="level0 parent drop-menu" title="Nuevos productos">
        <a href="<?= base_url("productos/catalogo/?d1={$fi_novedades}") ?>" class="<?= $clases_menu['novedades'] ?>">
            Novedades
        </a>
    </li>
    <!-- <li class="level0 parent drop-menu" title="Productos temporada navidad">
        <a href="<?= base_url("productos/catalogo/productos-navidad/?tag=677") ?>" class="<?= $clases_menu['navidad'] ?>">
            <i class="far fa-star"></i>
            Navidad
        </a>
    </li> -->

    <li class="level0 parent drop-menu">
        <a href="#"><span>Minutos de Amor</span></a>
        <ul class="level1">
            <li class="level1">
                <a href="<?= base_url("info/distribuidores_minutos_de_amor") ?>">Distribuidores</a>
            </li>
            <li class="level1">
                <a href="<?= base_url("productos/catalogo/?fab=513") ?>">Impresa</a>
            </li>
            <li class="level1">
                <a href="<?= base_url("catalogo/productos_digitales") ?>">En Línea</a>
            </li>
        </ul>
    </li>
    <li class="level0 parent drop-menu" title="¿Cómo comprar?">
        <a href="<?= base_url("posts/leer/333/como-comprar-en-districatolicas") ?>" title="Paso a paso para comprar en DistriCatolicas.com">
            <span>¿Cómo comprar?</span>
        </a>
    </li>

    <li class="level0 parent drop-menu">
        <a href="#"><span>Información</span></a>
        <ul class="level1">
            <li class="level1 parent"><a title="Instructivo de pagos en línea PSE para distribuidores y mayoristas" href="<?= base_url("posts/leer/28/pagos-pse/") ?>"><span>Pagos PSE</span></a></li>
            <li class="level1 parent"><a title="Sobre DistriCatólicas" href="<?= base_url("posts/leer/15/sobre-nosotros/") ?>"><span>Sobre nosotros</span></a></li>
            <li class="level1 parent"><a title="Estado de mi compra" href="<?= base_url("pedidos/estado") ?>"><span>Estado compra</span></a></li>
        </ul>
    </li>

    <?php if ( $this->session->userdata('logged') ){ ?>
        <li class="level0 parent drop-menu" title="Información de mi cuenta de usuario">
            <a href="<?= base_url("usuarios/books") ?>">
                Mis Libros
            </a>
        </li>
        <li class="level0 parent drop-menu" title="Cerrar sesión de usuario">
            <a href="<?= base_url("app/logout") ?>">
                Cerrar sesión
            </a>
        </li>
    <?php } else { ?>
        <li class="level0 parent drop-menu" title="Iniciar sesión de usuario">
            <a href="<?= base_url("accounts/login/") ?>">
                Iniciar sesión
            </a>
        </li>
        <li class="level0 parent drop-menu" title="Registrarme en Districatolicas.com">
            <a href="<?= base_url("accounts/signup/") ?>">
                Registrarme
            </a>
        </li>
    <?php } ?>
    
</ul>