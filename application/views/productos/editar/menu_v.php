<?php
        $seccion = $this->uri->segment(4);
        if ( $this->uri->segment(4) == '' ) { $seccion = 'descripcion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['descripcion'] = array(
            'icono' => '<i class="fa fa-info-circle"></i>',
            'texto' => 'Descripción',
            'link' => "productos/editar/{$row->id}/descripcion",
            'atributos' => 'title="Datos de descripción del producto"'
        );
            
        $arr_menus['valores'] = array(
            'icono' => '<i class="fa fa-money"></i>',
            'texto' => 'Valores',
            'link' => "productos/editar/{$row->id}/valores",
            'atributos' => 'title="Valores numéricos del producto"'
        );
            
        $arr_menus['especificaciones'] = array(
            'icono' => '<i class="fa fa-list-ol"></i>',
            'texto' => 'Especificaciones',
            'link' => "productos/editar/{$row->id}/especificaciones",
            'atributos' => 'title="Valores numéricos del producto"'
        );
            
        $arr_menus['imagenes'] = array(
            'icono' => '<i class="fa fa-image"></i>',
            'texto' => "Imágenes ({$imagenes->num_rows()})" ,
            'link' => "productos/editar/{$row->id}/imagenes",
            'atributos' => 'title="Imágenes del producto"'
        );
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('descripcion', 'valores', 'especificaciones', 'imagenes');
        $elementos_rol[1] = array('descripcion', 'valores', 'especificaciones', 'imagenes');
        $elementos_rol[2] = array('descripcion', 'valores', 'especificaciones', 'imagenes');
        $elementos_rol[6] = array('descripcion', 'valores', 'especificaciones', 'imagenes');
        $elementos_rol[7] = array('descripcion', 'valores', 'especificaciones', 'imagenes');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);