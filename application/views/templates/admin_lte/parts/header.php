<header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url() ?>" class="logo">
        <?= img(URL_IMG . 'app/logo_admin.png') ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="min-width: 150px;">
                        <img src="<?= $this->session->userdata('src_img') ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $this->session->userdata('nombre_corto') ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $this->session->userdata('src_img') ?>" class="img-circle" alt="User Image" />
                            <p>
                                <?= $this->session->userdata('nombre_completo') ?>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= anchor("accounts/edit/password", '<i class="fa fa-user"></i> Mi perfil', 'class="btn btn-default btn-flat" title="Ver mi perfil"') ?>
                            </div>
                            <div class="pull-right">
                                <?= anchor("app/logout", '<i class="fa fa-sign-out"></i> Cerrar sesión', 'class="btn btn-default btn-flat" title="Cerrar sesión"') ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>