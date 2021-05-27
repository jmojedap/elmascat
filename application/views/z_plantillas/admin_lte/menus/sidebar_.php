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
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= URL_IMG ?>app/user_mid.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?= $this->session->userdata('nombre_completo') ?></p>
            </div>
        </div>
        
        <!-- search form -->
        <?= form_open($destino_busquedas, $att_form) ?>
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        <?= form_close('') ?>
        <!-- /.search form -->
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        
        <ul class="sidebar-menu">
            <li class="header">NAVEGACIÓN</li>
            
            <li class="<?= $clases_m0['productos'] ?>">
                <a href="<?= base_url() ?>productos/explorar">
                    <i class="fa fa-book"></i> <span>Productos</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            
            <li class="<?= $clases_m0['pedidos'] ?>">
                <a href="<?= base_url() ?>pedidos/explorar">
                    <i class="fa fa-shopping-cart"></i> <span>Pedidos</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            
            <li class="treeview <?= $clases_m0['archivos'] ?>">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Archivos</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['archivos-imagenes'] ?>">
                        <?= anchor("archivos/imagenes", '<i class="fa fa-picture-o"></i> Imágenes') ?>
                    </li>
                    <li class="<?= $clases_m1['archivos-cargar'] ?>">
                        <?= anchor("archivos/cargar", '<i class="fa fa-upload"></i> Cargar') ?>
                    </li>
                </ul>
            </li>
            
            <li class="treeview <?= $clases_m0['usuarios'] ?>">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Usuarios</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['usuarios-explorar'] ?>">
                        <?= anchor("usuarios/explorar", '<i class="fa fa-users"></i> Usarios') ?>
                    </li>
                    <li class="<?= $clases_m1['usuarios-nuevo'] ?>">
                        <?= anchor("usuarios/nuevo/add", '<i class="fa fa-plus"></i> Nuevo') ?>
                    </li>
                    <li class="<?= $clases_m1['usuarios-mi_perfil'] ?>">
                        <?= anchor("usuarios/mi_perfil", '<i class="fa fa-user"></i> Mi perfil') ?>
                    </li>
                </ul>
            </li>
            
            <li class="treeview <?= $clases_m0['ajustes'] ?>">
                <a href="#">
                    <i class="fa fa-sliders"></i> <span>Ajustes</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= $clases_m1['ajustes-parametros'] ?>">
                        <?= anchor("datos/sis_option", '<i class="fa fa-gear"></i> Parámetros') ?>
                    </li>
                    <li class="<?= $clases_m1['ajustes-database'] ?>">
                        <?= anchor("develop/tablas/item", '<i class="fa fa-database"></i> Base de datos') ?>
                    </li>
                    <li class="<?= $clases_m1['ajustes-lugares'] ?>">
                        <?= anchor("lugares/explorar/?tp=4", '<i class="fa fa-map-marker"></i> Ciudades y lugares') ?>
                    </li>
                </ul>
            </li>
        </ul>
        
    </section>
    <!-- /.sidebar -->
</aside>