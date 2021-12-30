<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['options'] = array(
            'icono' => '<i class="fa fa-gear"></i>',
            'texto' => 'General',
            'link' => "admin/options/",
            'atributos' => ''
        );
        
        $arr_menus['tags'] = array(
            'icono' => '<i class="fa fa-tags"></i>',
            'texto' => 'Etiquetas',
            'link' => "datos/tags/",
            'atributos' => ''
        );
        
        $arr_menus['metadatos'] = array(
            'icono' => '',
            'texto' => 'Metadatos',
            'link' => "datos/metadatos/",
            'atributos' => ''
        );
        
        $arr_menus['fabricantes'] = array(
            'icono' => '',
            'texto' => 'Fabricantes',
            'link' => "datos/fabricantes/",
            'atributos' => ''
        );
        
        $arr_menus['extras'] = array(
            'icono' => '',
            'texto' => 'Extras pedidos',
            'link' => "datos/extras/",
            'atributos' => ''
        );
        
        $arr_menus['estado_pedido'] = array(
            'icono' => '',
            'texto' => 'Estados pedidos',
            'link' => "datos/estado_pedido/",
            'atributos' => ''
        );
        
        $arr_menus['acl'] = array(
            'icono' => '<i class="fa fa-lock"></i>',
            'texto' => 'ACL Permisos',
            'link' => "admin/acl/",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('options', 'acl', 'tags', 'metadatos', 'fabricantes', 'extras', 'estado_pedido');
        $elementos_rol[1] = array('options', 'acl', 'tags', 'metadatos', 'fabricantes', 'extras', 'estado_pedido');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/menu_v', $data_menu);