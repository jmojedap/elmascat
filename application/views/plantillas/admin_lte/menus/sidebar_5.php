<?php
    
//Clases para menú izquierda
    $activas = $this->App_model->menu_actual();
    
    $clases_m0[$activas[0]] = 'active';    //Clase menú
    $clases_m1[$activas[1]] = 'active';    //Clase submenú
    
//Formulario búsquedas
    $att_form = array(
        'class' => 'sidebar-form'
    );
    if ( is_null($destino_busquedas) ) { $destino_busquedas = 'busquedas/productos/'; }
    

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        
        <ul class="sidebar-menu">
            
            <li class="<?= $clases_m0['pedidos'] ?>">
                <a href="<?= base_url() ?>">
                    <i class="fa fa-home"></i> <span>Volver a tienda</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['pedidos'] ?>">
                <a href="<?= base_url() ?>pedidos/mis_pedidos">
                    <i class="fa fa-shopping-cart"></i> <span>Mis pedidos</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['usuarios'] ?>">
                <a href="<?= base_url() ?>usuarios/mi_perfil">
                    <i class="fa fa-user"></i> <span>Mi cuenta</span>
                </a>
            </li>
        </ul>
        
    </section>
    <!-- /.sidebar -->
</aside>