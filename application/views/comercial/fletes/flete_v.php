<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "fletes/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar ',
            'link' => "fletes/editar/edit/{$row->id}",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'editar');
        $elementos_rol[1] = array('explorar', 'editar');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/menu_v', $data_menu);