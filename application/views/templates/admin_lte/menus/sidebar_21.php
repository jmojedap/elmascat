<?php
    
//Clases menú
    //Usuarios
        $menus['usuarios/mi_perfil'] = array('usuarios', '');
        $menus['usuarios/direcciones'] = array('usuarios', '');
        $menus['usuarios/contrasena'] = array('usuarios', '');
        $menus['usuarios/editarme'] = array('usuarios', '');
        
    //Pedidos
        $menus['usuarios/mis_pedidos'] = array('pedidos', '');

    //Pedidos
        $menus['usuarios/books'] = array('books', '');
    
//Clases para menú izquierda
    $ubicacion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $activas = $menus[$ubicacion];

    $clases_m0[$activas[0]] = 'active';    //Clase menú
    $clases_m1[$activas[1]] = 'active';    //Clase submenú

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        
        <ul class="sidebar-menu">
            <li class="header">NAVEGACIÓN</li>
            
            <li>
                <a href="<?= base_url() ?>">
                    <i class="fa fa-arrow-left"></i> <span>Volver a tienda</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['pedidos'] ?>">
                <a href="<?= base_url('usuarios/mis_pedidos') ?>">
                    <i class="fa fa-shopping-cart"></i> <span>Mis pedidos</span>
                </a>
            </li>

            <li class="<?= $clases_m0['books'] ?>">
                <a href="<?= base_url('usuarios/books/') ?>">
                    <i class="fa fa-book"></i> <span>Mis libros virtuales</span>
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