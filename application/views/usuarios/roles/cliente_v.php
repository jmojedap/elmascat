<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cambiar_contrasena' ) { $seccion = 'contrasena'; }
        if ( $this->uri->segment(2) == 'mi_perfil' ) { $seccion = 'info'; }
        if ( $this->uri->segment(2) == 'mis_pedidos' ) { $seccion = 'pedidos'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => "usuarios/explorar",
            'atributos' => 'title="Explorar usuarios"'
        );
            
        $arr_menus['info'] = array(
            'icono' => '<i class="fa fa-user"></i>',
            'texto' => 'Información',
            'link' => "usuarios/info/{$row->id}",
            'atributos' => 'title="Información general del usuario"'
        );
            
        $arr_menus['pedidos'] = array(
            'icono' => '<i class="fa fa-shopping-cart"></i>',
            'texto' => 'Pedidos',
            'link' => "usuarios/pedidos/{$row->id}",
            'atributos' => 'title="Pedidos del cliente"'
        );
            
        $arr_menus['direcciones'] = array(
            'icono' => '<i class="fa fa-map-marker"></i>',
            'texto' => 'Direcciones',
            'link' => "usuarios/direcciones/{$row->id}",
            'atributos' => 'title="Mis direcciones de entrega"'
        );
            
        $arr_menus['contrasena'] = array(
            'icono' => '<i class="fa fa-lock"></i>',
            'texto' => 'Contraseña',
            'link' => "usuarios/contrasena/",
            'atributos' => 'title="Cambiar contraseña"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editar/edit/{$row->id}",
            'atributos' => 'title="Editar datos del cliente"'
        );
            
        $arr_menus['editarme'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editarme/edit/{$row->id}",
            'atributos' => 'title="Editar datos del cliente"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'info', 'pedidos', 'editar');
        $elementos_rol[1] = array('explorar', 'info', 'pedidos', 'editar');
        $elementos_rol[2] = array('explorar', 'info', 'pedidos', 'editar');
        $elementos_rol[6] = array('info', 'pedidos', 'direcciones');
        $elementos_rol[7] = array('info', 'pedidos', 'direcciones');
        $elementos_rol[21] = array('info', 'pedidos', 'editarme');
        $elementos_rol[22] = array('info', 'pedidos', 'editarme');
        $elementos_rol[23] = array('info', 'pedidos', 'editarme');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
        if ( $row->id == $this->session->userdata('usuario_id') )
        {
            $elementos[] = 'contrasena';
        }
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);
        $this->load->view($vista_b);
        