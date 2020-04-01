<?php 
    $clases_menu['oferta'] = '';
    
    if ( $this->input->get('ofrt') == 1 ) { $clases_menu['oferta'] = 'active'; }
    
    $clases_menu['novedades'] = '';
    $mktime = strtotime(date('Ymd') . ' -3 months');
    $fi_novedades = date('Ymd', $mktime);    //Creados hace menos de tres meses
    if ( strlen($this->input->get('fi')) > 0 ) { $clases_menu['novedades'] = 'active'; }
?>

<style>
    #nav .link_especial{
        background-color: #fdd922;
        color: #333;
    }
</style>

<ul id="nav" class="hidden-xs">
    <li class="level0 parent drop-menu">
        <a href="<?= base_url() ?>">
            <span>Inicio</span>
        </a>
    </li>
    <li class="level0 parent drop-menu">
        <?= anchor("posts/leer/15/sobre-nosotros", '<span>Sobre nosotros</span>') ?>
    </li>
    
    <li class="level0 parent drop-menu">
        <?= anchor("pedidos/estado", '<span>Estado del pedido</span>') ?>
    </li>
    
    <li class="level0 parent drop-menu active" title="Productos en oferta">
        <?= anchor("productos/catalogo/?ofrt=1", 'Ofertas', 'class="' . $clases_menu['oferta'] . '"') ?>
    </li>
    
    <li class="level0 parent drop-menu active" title="Nuevos productos">
        <?= anchor("productos/catalogo/?fi={$fi_novedades}", 'Novedades', 'class="' . $clases_menu['novedades'] . '"') ?>
    </li>
    
    <li class="level0 parent drop-menu" title="Instructivo de pagos en línea PSE para distribuidores y mayoristas">
        <?= anchor("posts/leer/28/pagos-pse", '<span>Pagos PSE</span>') ?>
    </li>
    
    <li class="level0 parent drop-menu hidden" title="¿Quieres ser distribuidor de Districatólicas?">
        <?= anchor("pedidos/soy_distribuidor/", '<span>Soy distribuidor</span>') ?>
    </li>

    <li class="level0 parent drop-menu" title="¿Quieres ser distribuidor de Districatólicas?">
        <a href="<?php echo base_url("info/distribuidores_disponibles") ?>" class="link_especial" title="Información sobre la Revista Minutos de Amor Abril 2020">
            <span>Abril 2020</span>
        </a>
    </li>
    
</ul>