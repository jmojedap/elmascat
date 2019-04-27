<?php
    
    $origen_id = $this->Flete_model->origen_id_default();
    if ( ! is_null($this->input->get('cat')) ) { $origen_id = $this->input->get('cat'); }

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
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo ',
            'link' => "fletes/nuevo/{$origen_id}/add",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'nuevo');
        $elementos_rol[1] = array('explorar', 'nuevo');
        $elementos_rol[2] = array('explorar', 'nuevo');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/menu_v', $data_menu);