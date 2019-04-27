<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'actualizar_datos' ) { $seccion = 'importar'; }
        if ( $this->uri->segment(2) == 'actualizar_datos_e' ) { $seccion = 'importar'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "productos/explorar/",
            'atributos' => 'title="Explorar productos"'
        );
            
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "productos/nuevo/",
            'atributos' => 'title="Agregar un nuevo producto"'
        );
        
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Importar',
            'link' => "productos/actualizar_datos",
            'atributos' => 'title="Importar y actualizar datos de productos"'
        );
        
        $arr_menus['procesos'] = array(
            'icono' => '<i class="fa fa-gear"></i>',
            'texto' => 'Procesos',
            'link' => "productos/procesos/",
            'atributos' => 'title="Procesos de productos"'
        );
        
        $arr_menus['promociones'] = array(
            'icono' => '',
            'texto' => 'Promociones',
            'link' => "productos/promociones/",
            'atributos' => 'title="Promociones de productos"'
        );
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'nuevo', 'procesos', 'promociones', 'importar');
        $elementos_rol[1] = array('explorar', 'nuevo', 'procesos', 'promociones', 'importar');
        $elementos_rol[2] = array('explorar', 'nuevo', 'importar');
        $elementos_rol[6] = array('explorar', 'nuevo');
        $elementos_rol[7] = array('explorar', 'nuevo');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);