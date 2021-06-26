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
            $menus['usuarios/books'] = array('usuarios');
        
        //Productos
            $menus['productos/explore'] = array('productos');
            $menus['productos/nuevo'] = array('productos');
            $menus['productos/editar'] = array('productos');
            $menus['productos/ver'] = array('productos');
            $menus['productos/editar_img'] = array('productos');
            $menus['productos/cargar'] = array('productos', '');
            $menus['productos/procesos'] = array('productos', '');
            $menus['productos/categorias'] = array('productos', '');
            $menus['productos/variaciones'] = array('productos', '');
            $menus['productos/books'] = array('productos', '');
            $menus['productos/importar_existencias'] = array('productos', '');
            $menus['productos/importar_existencias_e'] = array('productos', '');
            
        //Estadísticas
            $menus['estadisticas/resumen_dia'] = array('estadisticas', 'estadisticas-pedidos');
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
            
        //Datos
            $menus['admin/sis_option'] = array('ajustes', 'ajustes-parametros');
            $menus['admin/acl'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/tags'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/palabras_clave'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/listas'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/metadatos'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/fabricantes'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/extras'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/estado_pedido'] = array('ajustes', 'ajustes-parametros');
            
            $menus['develop/tablas'] = array('ajustes', 'ajustes-database');
            $menus['develop/acl_recursos'] = array('ajustes', 'ajustes-procesos');
            $menus['develop/procesos'] = array('ajustes', 'ajustes-procesos');
            $menus['sincro/panel'] = array('ajustes', 'ajustes-database');
            
            $menus['items/listado'] = array('ajustes', 'ajustes-items');
            $menus['items/explorar'] = array('ajustes', 'ajustes-items');
            $menus['items/nuevo'] = array('ajustes', 'ajustes-items');
            $menus['items/editar'] = array('ajustes', 'ajustes-items');
            
            $menus['lugares/explorar'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/sublugares'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/nuevo'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/editar'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/fletes'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/pedidos'] = array('ajustes', 'ajustes-lugares');

    
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
                <a href="<?= base_url() ?>pedidos/explore">
                    <i class="fa fa-shopping-cart"></i> <span>Pedidos</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['productos'] ?>">
                <a href="<?= base_url() ?>productos/explore">
                    <i class="fa fa-book"></i> <span>Productos</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['fletes'] ?>">
                <a href="<?= base_url() ?>fletes/explorar">
                    <i class="fa fa-truck"></i> <span>Fletes</span>
                </a>
            </li>
            
            <li class="<?= $clases_m0['usuarios'] ?>">
                <a href="<?= base_url() ?>usuarios/explorar">
                    <i class="fa fa-user"></i> <span>Usuarios</span>
                </a>
            </li>
            
            <li class="treeview <?= $clases_m0['estadisticas'] ?>">
                <a href="#">
                    <i class="fa fa-bar-chart"></i> <span>Estadísticas</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['estadisticas-pedidos'] ?>">
                        <?= anchor("estadisticas/resumen_dia", '<i class="fa fa-shopping-cart"></i> Ventas') ?>
                    </li>
                </ul>
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
            
            <li class="treeview <?= $clases_m0['ajustes'] ?>">
                <a href="#">
                    <i class="fa fa-sliders-h"></i> <span>Ajustes</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['ajustes-parametros'] ?>">
                        <?= anchor('admin/options', '<i class="fa fa-sliders-h"></i> Parámetros') ?>
                    </li>
                    <li class="<?= $clases_m1['ajustes-lugares'] ?>">
                        <?= anchor("lugares/explorar/?tp=4", '<i class="fa fa-map-marker"></i> Ciudades y lugares') ?>
                    </li>
                    <li class="<?= $clases_m1['ajustes-items'] ?>">
                        <?= anchor("items/listado/", '<i class="fa fa-bars"></i> Ítems') ?>
                    </li>
                    <li class="<?= $clases_m1['ajustes-database'] ?>">
                        <?= anchor("admin/tablas/meta", '<i class="fa fa-database"></i> Base de datos') ?>
                    </li>
                </ul>
            </li>
        </ul>
        
    </section>
    <!-- /.sidebar -->
</aside>