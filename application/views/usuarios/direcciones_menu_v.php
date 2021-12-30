<?php
        //$seccion = $this->uri->segment(2);
        if ( strlen($this->uri->segment(4)) > 0 ) { $clases['direcciones'] = ''; }
        if ( $this->uri->segment(4) == 'add' ) { $clases['direcciones_add'] = 'active'; }
        if ( $this->uri->segment(4) == 'edit' ) { $clases['direcciones_volver'] = 'active'; }

        //$clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['direcciones'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Guardadas',
            'link' => "usuarios/direcciones/{$row->id}",
            'atributos' => 'title="Listado de direcciones"'
        );
            
        $arr_menus['direcciones_add'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nueva',
            'link' => "usuarios/direcciones/{$row->id}/add",
            'atributos' => 'title="Agregar un nueva dirección"'
        );
            
        $arr_menus['direcciones_volver'] = array(
            'icono' => '<i class="fa fa-arrow-left"></i>',
            'texto' => 'Volver',
            'link' => "usuarios/direcciones/{$row->id}",
            'atributos' => 'title="Agregar un nueva dirección"'
        );
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('direcciones', 'direcciones_add');
        $elementos_rol[1] = array('direcciones', 'direcciones_add');
        $elementos_rol[2] = array('direcciones', 'direcciones_add');
        $elementos_rol[21] = array('direcciones', 'direcciones_add');
        $elementos_rol[22] = array('direcciones', 'direcciones_add');
        $elementos_rol[23] = array('direcciones', 'direcciones_add');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
        if ( $this->uri->segment(4) == 'edit' ) { $elementos[] = 'direcciones_volver'; }
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);