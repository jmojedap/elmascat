<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'actualizar_datos' ) { $seccion = 'importar'; }
        if ( $this->uri->segment(2) == 'actualizar_datos_e' ) { $seccion = 'importar'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "{$controlador}/explorar/",
            'atributos' => 'title="Explorar ' . $elemento_p . '"'
        );
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar');
        $elementos_rol[1] = array('explorar');
        $elementos_rol[2] = array('explorar');
        $elementos_rol[6] = array('explorar');
        $elementos_rol[7] = array('explorar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);