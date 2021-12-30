<?php
    //Clases menú
        $seccion = $this->uri->segment(2);

        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "usuarios/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['solicitudes_rol'] = array(
            'icono' => '<i class="fa fa-user-plus"></i>',
            'texto' => 'Solicitudes',
            'link' => "usuarios/solicitudes_rol/",
            'atributos' => ''
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "usuarios/nuevo/add",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'nuevo', 'solicitudes_rol');
        $elementos_rol[1] = array('explorar', 'nuevo', 'solicitudes_rol');
        $elementos_rol[2] = array('explorar');
        $elementos_rol[6] = array('explorar');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/menu_v', $data_menu);