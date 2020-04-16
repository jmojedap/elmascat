<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($post_id)
    {
        $row = $this->Pcrn->registro_id('post', $post_id);
        $destino = 'posts/editar/' . $post_id;
        if ( $row->tipo_id == 22 ) { $destino = "posts/lista/{$post_id}"; }
        
        redirect($destino);
                
    }

//CRUD
//---------------------------------------------------------------------------------------------------

    
    function explorar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Post_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "posts/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Post_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['listas'] = $this->db->get_where('item', 'categoria_id = 22');
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Posts';
            $data['subtitulo_pagina'] = $data['cant_resultados'] . ' resultados';
            $data['vista_menu'] = 'posts/explorar_menu_v';
            $data['vista_a'] = 'posts/explorar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * Eliminar un grupo de posts seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Post_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $resultados_total = $this->Busqueda_model->posts($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Posts';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_tickets.xls'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    function nuevo()
    {
        $gc_output = $this->Post_model->crud_basico();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Posts';
            $data['subtitulo_pagina'] = 'Explorar';
            $data['vista_menu'] = 'posts/explorar_menu_v';
            $data['vista_a'] = 'comunes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Editar la información bsica de un post
     * Funciona con grocery crud
     * 
     * @param type $proceso
     * @param type $post_id
     */
    function editar($post_id)
    {   
        $this->load->model('Esp');
        //Datos básicos
            $data = $this->Post_model->basico($post_id);
        
        //Array data espefícicas
            $vista_b = 'posts/editar_v';
            if ( $data['row']->tipo_id == 22 ) { $vista_b = 'posts/listas/editar_v'; }
            $data['vista_b'] = $vista_b;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function actualizar($post_id)
    {
        $this->Post_model->actualizar($post_id);
        redirect("posts/editar/{$post_id}/actualizado");
    }
    
    function ver($post_id)
    {
        
        $data = $this->Post_model->basico($post_id);    
        $data['detalle'] = $this->Post_model->detalle($post_id);
        $data['extras'] = $this->Post_model->extras($post_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Estados
            $data['estados'] = $this->db->get_where('item', 'categoria_id = 7');
        
        //Variables
            $data['post_id'] = $post_id;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Post';
            $data['vista_a'] = 'posts/post_v';
            $data['vista_b'] = 'posts/ver_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function leer($post_id)
    {
        
        $data = $this->Post_model->basico($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
        
        //Solicitar vista
            $data['head_subtitle'] = 'Post';
            $data['view_a'] = 'posts/leer_v';
            $this->load->view(TPL_FRONT, $data);
    }
    
//LISTAS - TIPO 22
//---------------------------------------------------------------------------------------------------
    
    /**
     * Muestra los elementos de un post tipo lista, CRUD de los elementos de la lista
     * 
     * @param type $post_id
     */
    function lista($post_id)
    {
        //$this->output->enable_profiler(TRUE);
            $this->load->model('Esp');
            
        //Datos básicos
            $data = $this->Post_model->basico($post_id);
        
        //Variables
            $tabla_id = $this->Pcrn->si_vacia($data['row']->referente_1_id, '1000');
            $elementos_lista = $this->Post_model->metadatos($post_id, 22);  //22, tipo de dato, elementos de lista
            
        //Cargando variables
            $data['tabla_id'] = $tabla_id;
            $data['tabla'] = $this->Pcrn->campo('sis_tabla', "id = {$tabla_id}", 'nombre_tabla');
            $data['elementos_lista'] = $elementos_lista;
            
        //Array data espefícicas
            $data['vista_a'] = 'posts/listas/lista_v';
            $data['vista_b'] = 'posts/listas/elementos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function reordenar_lista($post_id)
    {
        $str_orden = $this->input->post('str_orden');
        
        parse_str($str_orden);
        $arr_elementos = $elemento;
        
        $cant_elementos = $this->Post_model->reordenar_lista($post_id, $arr_elementos);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($cant_elementos));
    }
    
}