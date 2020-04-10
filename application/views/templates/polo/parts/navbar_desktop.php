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
        background-color: #fdd922;
        color: #333;
    }
</style>

<ul id="nav" class="hidden-xs">
    <li class="level0 parent drop-menu"><a href="<?php echo base_url() ?>">inicio</a></li>
    
    <li class="level0 parent drop-menu active" title="Productos en oferta">
        <a href="<?php echo base_url("productos/catalogo/?ofrt=1") ?>" class="<?php echo $clases_menu['oferta'] ?>">
            Ofertas
        </a>
    </li>
    
    <li class="level0 parent drop-menu active" title="Nuevos productos">
        <a href="<?php echo base_url("productos/catalogo/?fi={$fi_novedades}") ?>" class="<?php echo $clases_menu['novedades'] ?>">
            Novedades
        </a>
    </li>
    
    <li class="level0 parent drop-menu" title="Instructivo de pagos en línea PSE para distribuidores y mayoristas">
        <a href="<?php echo base_url("posts/leer/28/pagos-pse/") ?>" class="<?php echo $clases_menu['pagos_pse'] ?>">
            <span>Pagos PSE</span>
        </a>
    </li>

    <li class="level0 parent drop-menu">
        <a href="<?php echo base_url("posts/leer/15/sobre-nosotros/") ?>" class="<?php echo $clases_menu['sobre_nosotros'] ?>">
            sobre nosotros
        </a>
    </li>
    
    <li class="level0 parent drop-menu hidden" title="¿Quieres ser distribuidor de Districatólicas?">
        <a href="<?php echo base_url("pedidos/soy_distribuidor") ?>" class="<?php echo $clases_menu['soy_distribuidor'] ?>">
            soy distribuidor
        </a>
    </li>

    <li class="level0 parent drop-menu">
        <a href="<?php echo base_url("pedidos/estado") ?>" class="<?php echo $clases_menu['pedidos_estado'] ?>">
            estado compra
        </a>
    </li>

    <li class="level0 parent drop-menu" title="¿Quieres ser distribuidor de Districatólicas?">
        <a href="<?php echo base_url("info/distribuidores_disponibles") ?>" class="link_especial" title="Información sobre la Revista Minutos de Amor Abril 2020">
            <span>Abril 2020</span>
        </a>
    </li>
    
</ul>