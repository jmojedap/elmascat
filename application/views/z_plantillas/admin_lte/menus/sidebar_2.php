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
        $menus['usuarios/direcciones'] = array('usuarios');
        $menus['usuarios/pedidos'] = array('usuarios');
        $menus['usuarios/editarme'] = array('usuarios');

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
        $menus['productos/importar_existencias'] = array('productos', '');
        $menus['productos/importar_existencias_e'] = array('productos', '');

    //Estadísticas
        $menus['estadisticas/resumen_mes'] = array('estadisticas', 'estadisticas-pedidos');
        $menus['estadisticas/resumen_mes'] = array('estadisticas', 'estadisticas-pedidos');
        $menus['pedidos/ventas_departamento'] = array('estadisticas', 'estadisticas-pedidos');
        $menus['pedidos/meta_anual'] = array('estadisticas', 'estadisticas-pedidos');
        $menus['pedidos/productos_top'] = array('estadisticas', 'estadisticas-pedidos');
        $menus['pedidos/efectividad'] = array('estadisticas', 'estadisticas-pedidos');

    //Fletes
        $menus['fletes/explorar'] = array('fletes');
        $menus['fletes/nuevo'] = array('fletes');
        $menus['fletes/editar'] = array('fletes');

    //Pedidos
        $menus['pedidos/explore'] = array('pedidos');
        $menus['pedidos/ver'] = array('pedidos');
        $menus['pedidos/nuevo'] = array('pedidos');
        $menus['pedidos/pol'] = array('pedidos');
        $menus['pedidos/editar'] = array('pedidos');

    //Recursos
        $menus['archivos/imagenes'] = array('recursos', 'recursos-archivos');
        $menus['archivos/cargar'] = array('recursos', 'recursos-archivos');
        $menus['archivos/editar'] = array('recursos', 'recursos-archivos');
        $menus['archivos/cambiar'] = array('recursos', 'recursos-archivos');
        
        $menus['posts/explore'] = array('recursos', 'recursos-posts');
        $menus['posts/editar'] = array('recursos', 'recursos-posts');
        $menus['posts/lista'] = array('recursos', 'recursos-posts');
        
        $menus['eventos/explorar'] = array('recursos', 'recursos-eventos');
    
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
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= URL_IMG ?>app/user_mid.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?= $this->session->userdata('nombre_corto') ?></p>
            </div>
        </div>
        
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
                    <i class="fa fa-users"></i> <span>Usuarios</span>
                </a>
            </li>
            
            <li class="treeview <?= $clases_m0['recursos'] ?>">
                <a href="#">
                    <i class="fa fa-table"></i>
                    <span>Datos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['recursos-archivos'] ?>">
                        <?= anchor('archivos/imagenes', '<i class="fa fa-file"></i> Archivos') ?>
                    </li>
                    <li class="<?= $clases_m1['recursos-posts'] ?>">
                        <?= anchor('posts/explore', '<i class="fa fa-newspaper"></i> Posts') ?>
                    </li>
                    <li class="<?= $clases_m1['recursos-eventos'] ?>">
                        <?= anchor('eventos/explorar', '<i class="fa fa-calendar"></i> Eventos') ?>
                    </li>
                </ul>
            </li>
        </ul>
        
    </section>
    <!-- /.sidebar -->
</aside>