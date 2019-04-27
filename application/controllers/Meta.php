<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meta extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        
        $this->load->model('Meta_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($meta_id)
    {
        //Crear registro de visita
        $this->Meta_model->registrar_visita($meta_id);
        $this->info($meta_id);
    }
    
//CRUD
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Meta_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "meta/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Meta_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Tabla meta';
            $data['subtitulo_pagina'] = 'Explorar';
            $data['vista_a'] = 'meta/explorar_v';
            $this->load->view('p_sbadmin2/plantilla_v', $data);
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
            $resultados_total = $this->Meta_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Meta';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_meta.xls'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * AJAX Eliminar un grupo de meta seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->meta('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Meta_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function nuevo(){
        
        //Datos básicos
            $this->load->model('Usuario_model');
            $usuario_id = $this->session->userdata('usuario_id');
        
        //Crud
            $output = $this->Meta_model->crud_nuevo();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Metas';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'meta/nuevo_v';
        
        $output = array_merge($data,(array)$output);
        $this->load->view('p_sbadmin2/plantilla_v', $output);
        
    }
    
    /**
     * Editar la información bsica de un meta
     * Funciona con grocery crud
     * 
     * @param type $proceso
     * @param type $meta_id
     */
    function editar()
    {   
        
        $meta_id = $this->uri->segment(4);
        
        //Datos básicos
            $data = $this->Meta_model->basico($meta_id);
        
        //Render del grocery crud
            $output = $this->Meta_model->crud_basico();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
        
        //Array data espefícicas
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'meta/editar_v';
        
        $output = array_merge($data,(array)$output);
        $this->load->view('p_sbadmin2/plantilla_v', $output);
    }
    
    /**
     * AJAX
     * @param type $tipo_clave
     */
    function guardar($tipo_clave = 'relacionado')
    {
        $registro = $this->input->post();
        $meta_id = $this->Meta_model->guardar($registro, $tipo_clave);
        echo $meta_id;
    }
    
    function ver($meta_id)
    {
        //Datos básicos
            $data = $this->Meta_model->basico($meta_id);
        
        //Solicitar vista
            $data['vista_b'] = 'meta/ver_v';
            $this->load->view('p_sbadmin2/plantilla_v', $data);
    }
    
    /**
     * AJAX, elimina un registro de la tabla meta
     * @param type $meta_id
     */
    function eliminar($meta_id = NULL)
    {
        if ( !is_null($meta_id) ) {
            $arr_where['id'] = $meta_id;
        } else {
            $arr_where = $this->input->post();
        }
        
        $cant_eliminados = $this->Meta_model->eliminar($arr_where);
        
        echo 'Cant eliminados: ' . $cant_eliminados;
        //echo 'ELIMINANDO';
    }
    
}
