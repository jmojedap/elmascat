<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "archivos/imagenes/",
            'atributos' => 'title="Explorar imágene"'
        );
            
        $arr_menus['edit'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "archivos/edit/{$row->id}",
            'atributos' => 'title="Editar registro de imagen"'
        );
        
        $arr_menus['cambiar'] = array(
            'icono' => '<i class="fa fa-file-image-o"></i>',
            'texto' => 'Cambiar',
            'link' => "archivos/cambiar/{$row->id}",
            'atributos' => 'title="Cambiar archivo conservando los datos"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'edit', 'cambiar');
        $elementos_rol[1] = array('explorar', 'edit', 'cambiar');
        $elementos_rol[2] = array('explorar', 'edit', 'cambiar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);
        $this->load->view($vista_b);