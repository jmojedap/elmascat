<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cambiar_contrasena' ) { $seccion = 'active'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['mi_perfil'] = array(
            'icono' => '<i class="fa fa-user"></i>',
            'texto' => 'Información',
            'link' => "usuarios/mi_perfil/",
            'atributos' => 'title="Mi cuenta"'
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
            
        $arr_menus['editarme'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editarme/edit/{$row->id}",
            'atributos' => 'title="Editar mis datos"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[1] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[2] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[6] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[7] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[21] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[22] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        $elementos_rol[23] = array('mi_perfil', 'direcciones', 'contrasena', 'editarme');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);