<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['procesos'] = array(
            'icono' => '',
            'texto' => 'General',
            'link' => 'develop/procesos/',
            'atributos' => ''
        );
        
        $arr_menus['acl_recursos'] = array(
            'icono' => '',
            'texto' => 'ACL Permisos',
            'link' => 'develop/acl_recursos',
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('procesos');
        $elementos_rol[1] = array('procesos');
        
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista
        $this->load->view('comunes/menu_v', $data_menu);