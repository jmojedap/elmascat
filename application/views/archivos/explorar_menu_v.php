<?php
    //$primer_archivo_id = $this->Archivo_model->archivo_id();    
?>

<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['imagenes'] = array(
            'icono' => '<i class="fa fa-picture-o"></i>',
            'texto' => 'Imágenes',
            'link' => "archivos/imagenes/",
            'atributos' => 'title="Explorar imágenes"'
        );
            
        $arr_menus['carpetas'] = array(
            'icono' => '<i class="fa fa-folder-o"></i>',
            'texto' => 'Carpetas',
            'link' => 'archivos/carpetas/' . date('Y') .  '/'.  date('m'),
            'atributos' => 'title="Editar usuario"'
        );
        
        $arr_menus['cargar'] = array(
            'icono' => '<i class="fa fa-upload"></i>',
            'texto' => 'Cargar',
            'link' => "archivos/cargar/",
            'atributos' => 'title="Cargar archivo"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('imagenes', 'cargar', 'carpetas');
        $elementos_rol[1] = array('imagenes', 'cargar');
        $elementos_rol[2] = array('imagenes', 'cargar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);

