<?php 
    $clases_menu['oferta'] = '';
    
    if ( $this->input->get('ofrt') == 1 ) { $clases_menu['oferta'] = 'active'; }
    
    $clases_menu['novedades'] = '';
    $mktime = strtotime(date('Ymd') . ' -3 months');
    $fi_novedades = date('Ymd', $mktime);    //Creados hace menos de tres meses
    if ( strlen($this->input->get('fi')) > 0 ) { $clases_menu['novedades'] = 'active'; }

    if ( $this->uri->segment(4) == 'pagos-pse' ) { $clases_menu['pagos_pse'] = 'active'; }
    if ( $this->uri->segment(4) == 'sobre-nosotros' ) { $clases_menu['sobre_nosotros'] = 'active'; }
    if ( $this->uri->segment(2) == 'estado' ) { $clases_menu['pedidos_estado'] = 'active'; }
?>

<style>
    #nav .link_especial{
        background-color: #f44336;
        color: #FFF;
    }

    #nav .link_yellow{
        background-color: #fdd922;
        color: #333;
    }
</style>

<ul id="nav" class="hidden-xs">
    <li class="level0 parent drop-menu"><a href="<?php echo base_url() ?>">inicio</a></li>
    
    <li class="level0 parent drop-menu" title="Productos en oferta">
        <a href="<?php echo base_url("productos/catalogo/?ofrt=1") ?>" class="<?php echo $clases_menu['oferta'] ?>">
            Ofertas
        </a>
    </li>
    
    <li class="level0 parent drop-menu" title="Nuevos productos">
        <a href="<?php echo base_url("productos/catalogo/?fi={$fi_novedades}") ?>" class="<?php echo $clases_menu['novedades'] ?>">
            Novedades
        </a>
    </li>
    
    <li class="level0 parent drop-menu hidden" title="¿Quieres ser distribuidor de Districatólicas?">
        <a href="<?php echo base_url("pedidos/soy_distribuidor") ?>" class="<?php echo $clases_menu['soy_distribuidor'] ?>">
            soy distribuidor
        </a>
    </li>

    <li class="level0 parent drop-menu">
        <a href="#"><span>Minutos de Amor</span></a>
        <ul class="level1">
            <li class="level1">
                <a href="<?= base_url("productos/catalogo/?fab=513") ?>">Impresa</a>
            </li>
            <li class="level1">
                <a href="<?= base_url("catalogo/productos_digitales") ?>">En Línea</a>
            </li>
        </ul>
    </li>
    <li class="level0 parent drop-menu" title="¿Cómo comprar?">
        <a href="<?php echo base_url("posts/leer/333/como-comprar-en-districatolicas") ?>" title="Paso a paso para comprar en DistriCatolicas.com">
            <span>¿Cómo comprar?</span>
        </a>
    </li>

    <li class="level0 parent drop-menu">
        <a href="#"><span>Información</span></a>
        <ul class="level1">
            <li class="level1 parent"><a title="Instructivo de pagos en línea PSE para distribuidores y mayoristas" href="<?php echo base_url("posts/leer/28/pagos-pse/") ?>"><span>Pagos PSE</span></a></li>
            <li class="level1 parent"><a title="Sobre DistriCatólicas" href="<?php echo base_url("posts/leer/15/sobre-nosotros/") ?>"><span>Sobre nosotros</span></a></li>
            <li class="level1 parent"><a title="Estado de mi compra" href="<?php echo base_url("pedidos/estado") ?>"><span>Estado compra</span></a></li>
        </ul>
    </li>

    <?php if ( $this->session->userdata('logged') ){ ?>
        <li class="level0 parent drop-menu" title="Información de mi cuenta de usuario">
            <a href="<?php echo base_url("usuarios/books") ?>">
                Mis Libros
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
    
</ul>