<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fletes extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Flete_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//LUGARES - TABLE PLACE
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Fletees de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Flete_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "fletes/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Flete_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['listas'] = $this->db->get_where('item', 'categoria_id = 22');
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Fletes';
            $data['subtitulo_pagina'] = $config['total_rows'];
            $data['vista_menu'] = 'comercial/fletes/explorar_menu_v';
            $data['vista_a'] = 'comercial/fletes/explorar_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $resultados_total = $this->Flete_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Fletees';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_fletes'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    function nuevo($origen_id)
    {

        $gc_output = $this->Flete_model->crud_basico($origen_id);
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Flete';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['origen_id'] = $origen_id;
            $data['origenes'] = $this->Flete_model->origenes();
            $data['vista_menu'] = 'comercial/fletes/explorar_menu_v';
            $data['vista_a'] = 'comercial/fletes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function editar()
    {
        $flete_id = $this->uri->segment(4);
        $data = $this->Flete_model->basico($flete_id);
        
        $gc_output = $this->Flete_model->crud_basico(909);

        //Array data espefícicas
            $data['origen_id'] = $data['row']->origen_id;
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_a'] = 'comercial/fletes/gc_v';
            $data['vista_menu'] = 'comercial/fletes/flete_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Flete_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
}