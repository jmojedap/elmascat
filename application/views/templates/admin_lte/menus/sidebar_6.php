<?php
    
    //Clases menú
        //Usuarios
            $menus['usuarios/explorar'] = array('usuarios', 'usuarios-explorar');
            $menus['usuarios/mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/info'] = array('usuarios', 'usuarios');
            $menus['usuarios/contrasena'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/editar_mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/nuevo'] = array('usuarios');
            $menus['usuarios/editar'] = array('usuarios');
            $menus['usuarios/solicitudes_rol'] = array('usuarios');
            $menus['usuarios/books'] = array('usuarios');
        
        //Productos
            $menus['productos/explorar'] = array('productos');
            $menus['productos/nuevo'] = array('productos');
            $menus['productos/editar'] = array('productos');
            $menus['productos/ver'] = array('productos');
            $menus['productos/editar_img'] = array('productos');
            $menus['productos/cargar'] = array('productos', '');
            $menus['productos/procesos'] = array('productos', '');
            $menus['productos/categorias'] = array('productos', '');
            $menus['productos/variaciones'] = array('productos', '');
            
        //Pedidos
            $menus['pedidos/explorar'] = array('pedidos');
            $menus['pedidos/ver'] = array('pedidos');
            $menus['pedidos/nuevo'] = array('pedidos');
            $menus['pedidos/pol'] = array('pedidos');
            $menus['pedidos/editar'] = array('pedidos');
    
    //Clases para menú izquierda
        $ubicacion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        $activas = $menus[$ubicacion];

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
                <a href="<?= base_url() ?>pedidos/explorar">
                    <i class="fa fa-shopping-cart"></i> <span>Pedidos</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['productos'] ?>">
                <a href="<?= base_url() ?>productos/explorar">
                    <i class="fa fa-book"></i> <span>Productos</span>
                </a>
            </li>
            <li class="<?= $clases_m0['usuarios'] ?>">
                <a href="<?= base_url() ?>usuarios/explorar">
                    <i class="fa fa-user"></i> <span>Usuarios</span>
                </a>
            </li>
        </ul>
        
    </section>
    <!-- /.sidebar -->
</aside>